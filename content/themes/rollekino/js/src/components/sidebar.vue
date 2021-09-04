<template>
  <div class="filters">

    <filter-group v-for="filterGroup in filterGroups" v-bind:key="filterGroup.name" :filters="filterGroup.filters" :type="filterGroup.type" :name="filterGroup.name" :title="filterGroup.title" v-on:updateSelected="updateSelected($event)" />

  </div>
</template>

<script>

export default {
  props: ['filterGroups'],
  methods: {
    updateSelected(event) {
      this.$emit('updateSelected', event);
    }
  }
}

</script>
