<template>
  <div class="search-wrapper" role="search">
    <section class="search-panel">

      <div class="search-field search-form">
        <div class="container">
          <h2>{{localization.blockTitle}}</h2>
          <input :placeholder="localization.inputLabel" type="text" name="search-field" id="search-field" class="search-input" ref="search" v-model="searchQuery" v-on:input="search()"/>
        </div>
      </div>

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
    </section>

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

      this.timeout = setTimeout(() => {
        this.fetchResults();
        this.searchStatus = 'loading';
      }, 800);
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
