/*
 * The movies archive
 */
import Vue from 'vue';

// Import views
import Movies from './views/movies.vue';

// Import components
import Checkbox from './components/checkbox.vue';
import FilterGroup from './components/filter-group.vue';
import Sidebar from './components/sidebar.vue';
import Pagination from './components/pagination.vue';

/**
 * Book archive
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
