<template>
  
  <b-list-group>
    <b-list-group-item  v-for="restaurant in restaurants" :key="restaurant.id"  href="#">
      <div class="d-flex justify-content-between align-items-center">
        {{ restaurant.name }}
        <b-badge variant="primary" pill>{{ restaurant.id }}</b-badge>

      </div>
      <small>{{ restaurant.description }}</small>
    </b-list-group-item>

  </b-list-group>
</template>

<style scoped>


.list-group {
  position: relative;
  /* height: 450px; */
  overflow: scroll;
}
</style>
<script>
export default({
    props: {
        restaurants: Array,
    },
});
</script>
