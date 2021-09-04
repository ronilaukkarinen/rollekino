<template>
  <nav class="pagination">
    <ul class="nav-links">
      <li class="prev" v-if="currentPage !== 1">
        <button type="button" class="page-numbers prev" v-on:click="changePage(currentPage - 1)">
          {{texts.pagination.previousPage}}
        </button>
      </li>
      <li v-for="page in pages" :key="page">
        <span class="sep" v-if="( currentPage < totalPages && page === totalPages )">...</span>
        <button type="button" class="page-numbers" :class="currentPage === page ? 'current' : ''" v-on:click="changePage(page)">
          <span class="screen-reader-text">{{texts.pagination.selectPage}}</span>
          {{page}}
        </button>
        <span class="sep" v-if="( currentPage !== 1 && page === 1 )">...</span>
      </li>
      <li class="next" v-if="currentPage < totalPages">
        <button type="button" class="page-numbers next" v-on:click="changePage(currentPage + 1)">
          {{texts.pagination.nextPage}}
        </button>
      </li>
    </ul>
  </nav>
</template>
<script>
export default {
  props: ['totalPages', 'currentPage'],
  data: function() {
    return {
      texts: window.rollekino_productLocalization,
      pages: [],
    }
  },
  created() {
    this.pages = this.parsePages(this.totalPages, this.currentPage);
  },
  watch: {
    currentPage: function () {
      this.pages = this.parsePages(this.totalPages, this.currentPage);
    },
    totalPages: function () {
      this.pages = this.parsePages(this.totalPages, this.currentPage);
    }
  },
  methods: {
    changePage(page) {
      this.$emit('changePage', page);
    },
    parsePages(totalPages, currentPage) {
      let pageArray = [...Array(totalPages).keys()].map(x => ++x);

      if (pageArray.length > 6) {
        // Create array from page amount
        const startLimiter = currentPage < 3 ? 1 : currentPage - 2;
        const endLimiter = currentPage < 3 ? 6 : currentPage + 2;
        const pages = pageArray.slice(startLimiter - 1, endLimiter);

        // Add first page if not otherwise there
        if ( pages[0] != '1' ) {
          pages.unshift(1);
        }

        // Add first page if not otherwise there
        if ( pages[ pages.length - 1 ] != totalPages ) {
          pages.push( totalPages );
        }

        return pages;
      }
      return pageArray;
    }
  }
}
</script>
