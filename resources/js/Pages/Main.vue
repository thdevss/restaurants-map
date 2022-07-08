<template>
    <AppLayout title="Index">
        <b-row>
            <b-col cols="4">
                <b-form @submit="getall">

                    <b-input-group class="mb-3">
                        <b-form-input
                            id="input-1"
                            type="text"
                            placeholder="Please enter your location."
                            required
                            v-model="location"
                        ></b-form-input>
                        <b-input-group-append>
                            <b-button type="submit" size="sm" class="btn-search" text="Search" variant="primary">Search</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form>
                
                <b-overlay :show="listLoading" rounded="sm">
                    <RestaurantsList :restaurants="restaurants" @getRestaurant="onGetRestaurant" />
                </b-overlay>
            </b-col>
            <b-col cols="8">
                <b-overlay :show="dataLoading" rounded="sm">
                    <template v-if="selectedRestaurant.name">
                        <b-row>
                            <!-- <b-col cols="6">
                                <b-overlay :show="imageLoading" rounded="sm">
                                    <img class="img-responsive" v-bind:src="selectedRestaurant.image" />
                                </b-overlay>
                            </b-col> -->
                            <b-col cols="12">
                                <p>Name: {{ selectedRestaurant.name }}</p>
                                <p>Address: {{ selectedRestaurant.formatted_address }}</p>
                                <p>
                                    Rating: {{ selectedRestaurant.rating }} / 5
                                </p>
                            </b-col>
                            <b-col cols="12" class="py-4">
                                <GMapMap
                                    :center="selectedRestaurant.geometry.location"
                                    :zoom="16"
                                    map-type-id="terrain"
                                    style="width: 100%; height: 22rem"
                                >
                                    <GMapCluster :zoomOnClick="true">
                                    <GMapMarker
                                        :position="selectedRestaurant.geometry.location"
                                        :clickable="true"
                                        :draggable="true"
                                        @click="center = selectedRestaurant.geometry.location"
                                    />
                                    </GMapCluster>
                                </GMapMap>
                            </b-col>
                        </b-row>
                    </template>
                    <template v-else>
                        please select a restaurant...
                    </template>
                    
                </b-overlay>
            </b-col>

        </b-row>
    </AppLayout>
</template>

<style scoped>
.btn-search {
    border-radius: 0 0.2rem 0.2rem 0;
}
</style>
<script>
import { HTTP } from '@/http.js';

import AppLayout from '@/Layouts/AppLayout.vue';
import RestaurantsList from '@/Components/RestaurantsList.vue';
import vue3starRatings from "vue3-star-ratings";
 
export default {
    components: {
        AppLayout, RestaurantsList, vue3starRatings
    },
    data() {
        return {
            listLoading: false,
            dataLoading: false,
            imageLoading: false,
            location: 'Bang Sue',
            restaurants: [],
            selectedRestaurant: {}
        }
    },
    mounted() {
        this.getall()

    },
    methods: {
        async getall() {
            this.listLoading = true;
            this.dataLoading = true;

            await HTTP.get(`/restaurant?keyword=${this.location}`).then(res => {
                this.listLoading = false;
                this.dataLoading = false;

                if(!res.data.status) {
                    alert(res.data.message)
                } else {
                    this.restaurants = res.data.data;
                    this.selectedRestaurant = {}
                }
                
            })
        },
        async onGetRestaurant(restaurant) {
            // get image before showing
            this.dataLoading = true;
            this.selectedRestaurant = restaurant;
            // if(this.selectedRestaurant.photos) {
            //     this.getRestaurantImage(this.selectedRestaurant.photos[0].photo_reference);
            // }
            this.dataLoading = false;
        },
        async getRestaurantImage(photo_reference) {
            this.imageLoading = true;

            await HTTP.get(`/restaurant/image/${this.selectedRestaurant.photos[0].photo_reference}`).then(res => {
                this.imageLoading = false;

                if(!res.data.status) {
                    alert(res.data.message)
                } else {
                    this.selectedRestaurant.image = `data:${res.data.mime};base64,${res.data.data}`
                }
                
            })
        }
    }
}
</script>