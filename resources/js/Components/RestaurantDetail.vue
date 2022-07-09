<template>
    <template v-if="restaurant.name"> 
        <b-row>
            <b-col cols="12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2 text-muted float-end" v-if="!$isMobile()">
                            <!-- for responsive design in desktop, float right -->
                            <vue3-star-ratings starSize="16" :key="restaurant.place_id" v-model="restaurant.rating" :disableClick="true" :showControl="false" step="0.1" />
                        </div>

                        <h5 class="card-title">{{ restaurant.name }}</h5>

                        <div class="mt-2 mb-2 text-muted text-center" v-if="$isMobile()">
                            <!-- for responsive design in mobile, center -->
                            <vue3-star-ratings starSize="16" :key="restaurant.place_id" v-model="restaurant.rating" :disableClick="true" :showControl="false" step="0.1" />
                        </div>
                        <!-- <h6 class="card-subtitle mt-2 mb-2 text-muted">Rating: {{ restaurant.rating }} / 5</h6> -->
                        



                        <p class="card-text">{{ restaurant.formatted_address }}</p>
                        <a :href="`https://www.google.com/search?q=${restaurant.name}`" target="_blank" class="card-link">More detail</a>
                        <a :href="`https://www.google.com/maps/search/?api=1&query=${restaurant.geometry.location.lat}%2C${restaurant.geometry.location.lng}&query_place_id=${restaurant.place_id}`" target="_blank" class="card-link">Open in Google Maps</a>
                    </div>
                </div>

            </b-col>
            <b-col cols="12" class="py-4">
                <GMapMap
                    :center="restaurant.geometry.location"
                    :zoom="16"
                    map-type-id="terrain"
                    style="width: 100%; height: 20rem"
                >
                    <GMapCluster :zoomOnClick="true">
                    <GMapMarker
                        :position="restaurant.geometry.location"
                        :clickable="true"
                        :draggable="true"
                        @click="center = restaurant.geometry.location"
                    />
                    </GMapCluster>
                </GMapMap>
            </b-col>
        </b-row>
    </template>
</template>
<style scoped>
.vue3-star-ratings__wrapper {
    margin: 0 !important;
    padding: 0 !important;
}
</style>
<script>
import vue3starRatings from "vue3-star-ratings";

export default {
    components: {
        vue3starRatings
    },
    props: {
        restaurant: Object,
    },
};
</script>
