<template>
  <div class="input-wrapper input-group">
    <input class="checkbox" type="checkbox" :id="filter.id" :name="name" :checked="filter.selected" v-on:change="$emit('update:selected', $event.target.checked)" />
    <label :for="filter.id"><span v-html="filter.name"></span></label>
  </div>
</template>

<script>
module.exports = {
  props: ['name', 'filter'],
  data: function() {
    return {
    };
  }
}
</script>
