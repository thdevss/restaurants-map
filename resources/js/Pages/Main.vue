<template>
    <AppLayout title="Index">
        <b-row>
            <b-col lg="4" class="mb-4">
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
            <b-col lg="8" v-if="!this.$isMobile()">
                <b-overlay :show="dataLoading" rounded="sm" class="mt-0">
                    <RestaurantData :key="desktop" :restaurant="selectedRestaurant" />
                </b-overlay>
            </b-col>

        </b-row>

        <b-modal id="restaurantPopup" v-model="isShowMobileModal" hide-footer>
            
            <div class="d-block text-center">
                <b-overlay :show="dataLoading" rounded="sm">
                    <RestaurantData :key="mobile" :restaurant="selectedRestaurant" />
                </b-overlay>
            </div>
        </b-modal>
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
import RestaurantData from '@/Components/RestaurantData.vue';

import vue3starRatings from "vue3-star-ratings";
 
export default {
    components: {
        AppLayout, RestaurantsList, vue3starRatings, RestaurantData
    },
    data() {
        return {
            listLoading: false,
            dataLoading: false,
            imageLoading: false,
            isShowMobileModal: false,
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
            if(this.$isMobile()) {
                // check; if mobile, open modal / else, open form
                this.isShowMobileModal = true
            }

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