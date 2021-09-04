<template>
  <div class="search-wrapper" role="search">
    <button type="button" class="search-toggle search-trigger" v-on:click="toggle()">
      <span class="search-label" :class="toggled ? 'hidden' : ''">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="search-icon">
          <g fill="none" fill-rule="evenodd" stroke="#393939" stroke-width="2">
            <circle aria-hidden="true" cx="10.5" cy="10.5" r="9.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18l5.401 5.401"/>
          </g>
        </svg><span class="screen-reader-text">Hae</span>
      </span>
      <span class="close-label" :class="! toggled ? 'hidden' : ''">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="close-icon">
        <path d="M13.46 12L19 17.54V19h-1.46L12 13.46 6.46 19H5v-1.46L10.54 12 5 6.46V5h1.46L12 10.54 17.54 5H19v1.46L13.46 12z"></path>
        </svg><span class="screen-reader-text">Sulje</span>
      </span>
    </button>

    <section class="search-panel" v-if="toggled">

      <div class="search-field search-form">
        <div class="container">
          <label for="search-field"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="search-icon"><g fill="none" fill-rule="evenodd" stroke="#393939" stroke-width="2"><circle aria-hidden="true" cx="10.5" cy="10.5" r="9.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M18 18l5.401 5.401"/></g></svg><span class="screen-reader-text">Hae:</span></label>
          <input placeholder="Hae" type="text" name="search-field" id="search-field" class="search-input" ref="search" v-model="searchQuery" v-on:input="search()"/>
        </div>
      </div>

      <div class="result-container" :class="searchStatus">
        <div class="container">
        <div class="load-animation">
          <svg>
            <use xlink:href="#load-animation" href="#load-animation"></use>
          </svg>
        </div>

        <div class="instructions" v-if="!hasResults && searchQuery.length < 3">
          <p>{{localization.instructions}}</p>
        </div>

        <div class="results" v-if="Object.keys(results).length">
          <template v-for="(resultGroup, key) in results">
            <div class="result-group"  :class="key" :key="key" v-if="resultGroup.count">
              <h2>{{resultGroup.title}} ({{resultGroup.count}})</h2>
              <ul>
                <li v-for="item in resultGroup.items" :key="item.id">
                  <a :href="item.link">
                    <img :src="item.thumbnail_url" v-if="item.thumbnail_url" />
                    <span class="book-details">
                      <span v-if="item.name">{{item.name}}</span>
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </template>
        </div>

        <div class="no-results" v-if="!hasResults && searchQuery.length > 4">
          <p>{{localization.noResults}} <strong>{{searchQuery}}</strong></p>
        </div>

        </div>
      </div>
    </section>

  </div>
</template>

<script>
import {api} from '../inc/api';
export default {
  data: function () {

    return {
      toggled: false,
      searchQuery: '',
      results: {},
      timeout: false,
      localization: window.atena_searchLocalization,
      hasResults: false,
      searchStatus: 'visible',
    }
  },
  mounted() {
    // Add listener for closing the toggled search with esc key
    window.addEventListener('keyup', event => {
      if (! this.toggled) {
        return;
      }
      let isEscape = false;
      if ("key" in event) {
        isEscape = (event.key === "Escape" || event.key === "Esc");
      } else {
        isEscape = (event.keyCode === 27);
      }
      if (! isEscape) {
        return;
      }
      this.toggled = false;
    });
  },

  methods: {
    search() {
      if ( this.searchQuery.length < 3 ) {
        return;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
      }

      this.timeout = setTimeout(() => {
        this.fetchResults();
        this.searchStatus = 'loading';
      }, 800);
    },
    fetchResults() {

      api
      .get(`/wp-json/rollekino/v1/search/?s=${this.searchQuery}`)
      .then(response => {
        this.results = response.data;

        this.resultsLoaded();

        console.log(response.data);
      })
      .catch(error => {
        console.log(error);
      });
    },
    resultsLoaded() {
      // Let it animate
      this.searchStatus = 'loaded';
      // Check if we have any results and update status
      for (const key in this.results) {
        if (this.results.hasOwnProperty(key) && this.results[key].hasOwnProperty('items')) {
          this.hasResults = this.results[key].items.length ? true : this.hasResults;
        }
      }
      setTimeout(() => this.searchStatus = 'visible', 1000);
    },
    toggle() {
      this.toggled = ! this.toggled;

      // Focus when search is opened
      if (this.toggled) {
        this.$nextTick(() => {
          this.$refs.search ? this.$refs.search.focus() : '';
        });
      }
    }
  },
}
</script>
