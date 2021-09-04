<template>
  <div class="container container-movie-archive-grid">
    <div class="movie-archive-grid">
      <button class="sidebar-toggle" :class="sidebarToggled ? 'toggled' : ''" v-on:click="sidebarToggled = !sidebarToggled">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 9" v-if="! sidebarToggled">
          <path fill="none" fill-rule="evenodd" stroke="#393939" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1.5 1.5h11m-11 3h11m-11 3h11"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" v-else>
          <path fill="none" fill-rule="evenodd" stroke="#393939" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l20 20m0-20L1 21"/>
        </svg>

        <span v-if="! sidebarToggled">Suodata tuotteita</span>
        <span v-else>Sulje</span>
      </button>
      <sidebar v-on:updateSelected="updateSelected($event)" :filterGroups="filterGroups" :class="sidebarToggled ? 'toggled' : ''"></sidebar>
      <div class="main">
        <div class="movies" :class="movieListStatus">
          <div class="top-bar">
            <div class="selected-filters" v-if="filterList.length">
              <ul>
                <li v-for="filter in filterList" v-bind:key="filter.name">
                  <button class="button" type="button" v-on:click="updateSelected({id: filter.id, value: false, filter: filter.groupName})">
                    <span class="screen-reader-text">{{localization.filters.remove}}</span>
                    <span class="icon">✕</span> <span class="button-label" v-html="filter.name"></span>
                  </button>
                </li>
              </ul>
            </div>
            <div class="orderby">
              <select name="orderBy" v-model="orderBy" v-on:change="updateMovies(1)">
                <option v-for="item in orderByOptions" :key="item" :value="item">
                  {{localization.orderBy[item]}}
                </option>
              </select>
            </div>
          </div>
          <div class="load-animation">
            <div class="loader">
                <div class="loader__filmstrip">
                </div>
                <p class="loader__text">
                    ladataan
                </p>
            </div>
          </div>
          <div class="cols">
            <div class="col col-movie-listing-single" v-for="movie in movies" :key="movie.id" v-html="movie.meta.rendered_listing">
            </div>
          </div><!-- .movie-list -->

          <div class="movie-navigation" v-if="totalPages >= 2">

            <pagination :currentPage="currentPage" :totalPages="totalPages" v-on:changePage="updateMovies($event)" />

          </div><!-- .movie-navigation -->
        </div><!-- .movies -->

      </div><!-- .main -->
    </div><!-- .movie-archive-grid -->
  </div><!-- .container -->
</template>

<script>
import { api, parseFilters } from '../inc/api';
import MoveTo from 'moveto';
import LazyLoad from "vanilla-lazyload";

export default {
  data: function() {
    const movieFilters = Object.values(window.rollekino_movieFilters).map((filterGroup) => ({
      ...filterGroup,
      filters: parseFilters(filterGroup.filters),
    }));

    return {
      sidebarToggled: false,
      orderByOptions: ['date', 'title', 'meta_value_num'],
      orderBy: 'date',
      filterList: [],
      movies: window.rollekino_defaultMovies ? window.rollekino_defaultMovies : [],
      totalMovies: window.rollekino_movieCount ? window.rollekino_movieCount : 0,
      totalPages: window.rollekino_moviePages ? parseInt( window.rollekino_moviePages ) : 0,
      perPage: 24,
      currentPage: 1,
      movieListStatus: 'visible',
      localization: window.rollekino_movieLocalization,
      filterGroups: movieFilters,
      lazyLoad: new LazyLoad(),

    };
  },
  mounted() {
    // Try to load query and last results from session storage
    this.loadSession();
    this.lazyLoad.update();
  },
  updated() {
    // After your content has changed...
    this.lazyLoad.update();
  },
  methods: {
    // Find filter by group name and filter id
    findFilter(name, id) {
      const filterGroups = Object.keys(this.filterGroups);
      const filterGroupKey = Object.values(this.filterGroups).findIndex(filter => filter.name === name);

      if (filterGroupKey === -1) {
        return false;
      }
      return {
        group: filterGroups[filterGroupKey],
        index: this.filterGroups[filterGroups[filterGroupKey]].filters.findIndex(filter => filter.id === id),
      };
    },
    // Update selected movies in filter groups
    updateSelected(event) {
      // Find the filter that was toggled
      const currentFilter = this.findFilter(event.filter, event.id);

      // Update filter value according to event
      this.filterGroups[currentFilter.group].filters[currentFilter.index].selected = event.value;

      // Update filters and movies
      this.updateFilterList();
      this.loadPage(1);
    },
    // Update the selected filters list
    updateFilterList() {
      this.filterList = Object.values(this.filterGroups).map(filterGroup => {
        const selectedFilters = filterGroup.filters.filter(filter => filter.selected);
        selectedFilters.forEach(filter => filter.groupName = filterGroup.name);
        return [...selectedFilters];
      }).flat();
    },
    // Debounce page loading
    loadPage(page) {
      if (this.timeout) {
        clearTimeout(this.timeout);
      }
      // this.timeout = setTimeout(() => {
        this.updateMovies(page);
      // }, 1250);
    },
    updateMovies(page) {
      this.currentPage = parseInt(page);

      this.movieListStatus = 'loading';

      const move = new MoveTo({
        tolerance: 200,
      });
      const target = document.querySelector('.load-animation');
      move.move(target);

      // Group filters into simple key => values array
      const groups = {}
      this.filterList.forEach(({id, groupName}) => {
        (groups[groupName] = (groups[groupName] || [])).push(id);
      });

      // Construct query string
      let queryString = `?per_page=${this.perPage}&page=${page}&orderby=${this.orderBy}`;

      for (let index = 0; index < Object.keys(groups).length; index++) {
        const groupValues = Object.values(groups)[index];
        const groupKey   = Object.keys(groups)[index];
        queryString = queryString.concat(`&${groupKey}=${groupValues.join(',')}`);
      }

      // Get movies
      api
      .get(`/wp-json/wp/v2/movie/${queryString}`)
      .then(response => {
        // Save movies
        this.movies = response.data;

        // Get query info from headers
        this.totalMovies = parseInt(response.headers['x-wp-total'], 10);
        this.totalPages = parseInt(response.headers['x-wp-totalpages'], 10);

        this.movieListLoaded();
        this.saveSession({
          query: response.data,
          queryString: queryString,
          filters: this.filterGroups,
          perPage: this.perPage,
          currentPage: this.currentPage,
          totalPages: this.totalPages,
          totalMovies: this.totalMovies,
        });

      })
      .catch(error => {
        console.log(error);
      });
    },
    movieListLoaded() {
      // Let it animate
      this.movieListStatus = 'loaded';
      setTimeout(() => {
        this.movieListStatus= 'visible';
      }, 500);
    },
    // Save query to history state and url
    saveSession(session) {
      const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}${session.queryString}`;
      history.pushState(session, '', newUrl);
    },
    // Load query from history state storage or URL parameters
    loadSession() {
      if (history.state) {
        // Load session from history

        this.movies = 'query' in history.state ? history.state.query : [];
        this.filterGroups = 'filters' in history.state ? history.state.filters : this.filterGroups;

        this.orderBy = 'orderBy' in history.state ? history.state.orderBy : this.orderBy;
        this.currentPage = 'currentPage' in history.state ? parseInt(history.state.currentPage) : this.currentPage;
        this.perPage = 'perPage' in history.state ? parseInt(history.state.perPage) : this.perPage;
        this.totalMovies = 'totalMovies' in history.state ? parseInt(history.state.totalMovies) : 0;
        this.totalPages = 'totalPages' in history.state ? parseInt(history.state.totalPages) : 0;

        this.updateFilterList();


      } else if (window.location.search) {
        // Load session from URL
        const params = this.getUrlParameters(window.location.search);

        for (const key in params) {
          // Check if filtergroup with this name exists
          if (! this.findFilter(key, 0)) {
            continue;
          }

          const filters = params[key].split(',').map(param => parseInt(param));

          filters.forEach(filter => {
            const currentFilter = this.findFilter(key, filter);

            if (currentFilter && currentFilter.index !== -1) {
              this.filterGroups[currentFilter.group].filters[currentFilter.index].selected = true;
            }
          });
        }

        this.orderBy = 'orderby' in params ? params.orderby : this.orderBy;
        this.currentPage = 'page' in params ? parseInt(params.page) : this.currentPage;
        this.perPage = 'per_page' in params ? parseInt(params.per_page) : this.perPage;

        // Update filters and movies
        this.updateFilterList();
        this.updateMovies(this.currentPage);
      }

    },
    getUrlParameters(url) {
      const query = url.substr(1);
      const result = {};
      query.split("&").forEach(function(part) {
        var item = part.split("=");
        result[item[0]] = decodeURIComponent(item[1]);
      });
      return result;
    },
    prepareFilterGroups(filterGroups) {
      return
    }
  }
}
</script>
