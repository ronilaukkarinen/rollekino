<template>
  <div class="search-wrapper">

      <form id="search-form" role="search" method="get" class="search-form" action="<?php echo esc_url( get_home_url() ); ?>">
				<label for="search-field" class="search-form-label">Hae elokuvaa</label>
				<input id="search-field" type="search" class="search-field search-input search-form-field" ref="search" v-model="searchQuery" v-on:input="search()" value="" name="s" autocomplete="off">
				<button type="submit" class="search-submit" aria-label="Hae">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27"><g stroke="#fff" stroke-width="1.5" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><circle cx="11.813" cy="11.813" r="11.25"></circle><path d="M26.438 26.438l-6.671-6.671"></path></g></svg>
        </button>
			</form>

      <div class="result-container" :class="searchStatus">
        <div class="load-animation">
          <span class="circle"></span>
          <span class="circle"></span>
          <span class="circle"></span>
        </div>
        <div class="container">
          <div class="instructions" v-if="!resultCount && searchQuery.length < 3">
            <p>{{localization.instructions}}</p>
          </div>

          <div class="results" v-if="Object.keys(results).length">
            <template v-for="(resultGroup, key) in results">
              <div class="result-group"  :class="key" :key="key" v-if="resultGroup.count">
                <h2>{{resultGroup.title}} <span>({{resultGroup.count}})</span></h2>
                <ul>
                  <li v-for="item in resultGroup.items" :key="item.id">
                    <div v-html="item.html"></div>
                  </li>
                </ul>
              </div>
            </template>
          </div>

          <div class="no-results" v-if="resultCount === 0">
            <p>{{localization.noResults}} <strong>{{searchQuery}}</strong></p>
          </div>

        </div>
      </div>

  </div>
</template>

<script>
import {api} from '../inc/api';
export default {
  data: function () {

    return {
      searchQuery: '',
      results: {},
      timeout: false,
      localization: window.rollekino_searchLocalization,
      resultCount: -1,
      searchStatus: 'visible',
    }
  },
  methods: {
    search() {
      if ( this.searchQuery.length < 3 ) {
        return;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
      }

      // this.timeout = setTimeout(() => {
        this.fetchResults();
        this.searchStatus = 'loading';
      // }, 800);
    },
    fetchResults() {

      api
      .get( rollekino_searchLocalization.apiUrl + `rollekino/v1/search/?s=${this.searchQuery}`)
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
      let searchResults = [];
      // Check if we have any results and update status
      for (const key in this.results) {
        if (this.results.hasOwnProperty(key) && this.results[key].hasOwnProperty('items')) {
          searchResults = [
            ...searchResults,
            ...this.results[key].items.map(item => item.id)
          ];
        }
      }

      this.resultCount = searchResults.length;

      setTimeout(() => this.searchStatus = 'visible', 1000);
    },
  },
}
</script>
