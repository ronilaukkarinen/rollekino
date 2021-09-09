/* eslint-disable import/no-extraneous-dependencies, no-unused-vars, no-shadow, import/no-extraneous-dependencies, max-len */
/*
 * The movies archive
 */
import Swup from 'swup';
import SwupScriptsPlugin from '@swup/scripts-plugin';
import SwupBodyClassPlugin from '@swup/body-class-plugin';

import Vue from 'vue';

// Import views
import Movies from './views/movies.vue';

// Import components
import Checkbox from './components/checkbox.vue';
import FilterGroup from './components/filter-group.vue';
import Sidebar from './components/sidebar.vue';
import Pagination from './components/pagination.vue';

// Initiate Swup transitions
const swup = new Swup({
  plugins: [
    new SwupScriptsPlugin({
      head: true,
      body: true,
    }),
    new SwupBodyClassPlugin(),
  ],
});

/**
 * Movies archive
 *
 * @param {Element} elem Target element
 */
const initMoviesArchive = (elem) => {
  Vue.component('movies', Movies);
  Vue.component('checkbox', Checkbox);
  Vue.component('filter-group', FilterGroup);
  Vue.component('sidebar', Sidebar);
  Vue.component('pagination', Pagination);

  return new Vue({
    render: (h) => h(Movies),
  }).$mount(elem);
};

const target = document.getElementById('movies-listing');

if (target) {
  initMoviesArchive(target);
}

// Swup starts
swup.on('contentReplaced', () => {
  /**
 * Movies archive
 *
 * @param {Element} elem Target element
 */
  const initMoviesArchive = (elem) => {
    Vue.component('movies', Movies);
    Vue.component('checkbox', Checkbox);
    Vue.component('filter-group', FilterGroup);
    Vue.component('sidebar', Sidebar);
    Vue.component('pagination', Pagination);

    return new Vue({
      render: (h) => h(Movies),
    }).$mount(elem);
  };

  const target = document.getElementById('movies-listing');

  if (target) {
    initMoviesArchive(target);
  }
});
// Swup ends
