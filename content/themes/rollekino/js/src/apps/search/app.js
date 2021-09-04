/*
 * @Author: Niku Hietanen
 * @Date: 2020-04-09 12:36:38
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-06-11 13:48:34
 */

import Vue from 'vue';

// Import views
import Search from './views/search.vue';

/**
 * Site search
 */
const initSearch = (elem) => {
  Vue.component('search', Search);

  return new Vue({
    render: (h) => h(Search),
  }).$mount(elem);
};

export default initSearch;
