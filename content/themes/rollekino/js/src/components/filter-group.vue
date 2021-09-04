<template>
  <div class="filter-group" v-bind:class="name">
    <h2 v-if="title">{{title}}</h2>
    <div v-if="type === 'checkbox'" class="checkbox-group">
      <checkbox v-for="filter in filters" v-bind:key="filter.id" :checked="filter.selected" v-on:update:selected="updateSelected($event, filter.id)" :name="name" :filter="filter" />
    </div>
    <select v-if="type === 'select'" class="select-custom" :id="name" :name="name" v-on:change="updateSelected(true, $event.target.value)" placeholder="Valitse">
      <option value="" selected>Valitse</option>
      <option v-for="filter in filters" v-bind:key="filter.id" :value="filter.id">{{filter.name}}</option>
    </select>
  </div>
</template>
<script>
export default {
  props: ['filters', 'name', 'title', 'type'],
  methods: {
    updateSelected: function (value, updatedFilter) {
      this.$emit('updateSelected', {id: parseInt(updatedFilter, 10), value: value, filter: this.name});
    },
  }

}
</script>
