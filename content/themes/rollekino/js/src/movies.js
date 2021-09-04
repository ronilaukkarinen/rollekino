/*
 * The movies archive
 */
import Vue from 'vue';

// Import views
import Movies from './views/movies.vue';
import Search from './views/search.vue';

// Import components
import Checkbox from './components/checkbox.vue';
import FilterGroup from './components/filter-group.vue';
import Sidebar from './components/sidebar.vue';
import Pagination from './components/pagination.vue';

/**
 * Site search
 *
 * @param {Element} elem Target element
 */
const search = (elem) => {
  Vue.component('search', Search);

  // eslint-disable-next-line no-shadow
  const search = new Vue({
    render: (h) => h(Search),
  }).$mount(elem);

  return search;
};

export default { search };

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
