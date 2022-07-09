<template>
  <template v-if="restaurants.length > 0">
    <b-list-group>
      <b-list-group-item  button v-for="restaurant in restaurants" :key="restaurant.id" @click="getRestaurantData(restaurant)">
        <div class="d-flex justify-content-between align-items-center">
          {{ restaurant.name }}
          <b-badge variant="success" pill v-if="restaurant.opening_hours">Open Now</b-badge>

        </div>
        <small>{{ restaurant.formatted_address }}</small>
        
      </b-list-group-item>

    </b-list-group>
  
  </template>
  <template v-else>
    <b-alert show variant="warning">
      Search not found,<br />
      <small>Please try agian with other keyword.</small>
    </b-alert>
  </template>
  
</template>

<style scoped>


.list-group {
  position: relative;
  /* height: 450px; */
  height: 70vh;
  overflow: scroll;
}
</style>
<script>

export default({
  components: {
    
  },
  props: {
      restaurants: Array,
  },
  emits: ["getRestaurant"],
  methods: {
    getRestaurantData(restaurant) {
      this.$emit("getRestaurant", restaurant);
      // this.$root.$emit('getRestaurant', restaurant)
    }

  }
});
</script>
