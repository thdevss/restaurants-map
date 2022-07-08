<template>
    <AppLayout title="Index">
        <b-row>
            <b-col cols="4">
                <b-form @submit="onSubmit">

                    <b-input-group class="mb-3">
                        <b-form-input
                            id="input-1"
                            type="text"
                            placeholder="Please enter your location."
                            required
                            ></b-form-input>
                        <b-input-group-append>
                            <b-button type="submit" size="sm" class="btn-search" text="Search" variant="primary">Search</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form>
                

                <RestaurantsList :restaurants="restaurants" />
            </b-col>
            <b-col cols="8">
                <img src="https://raw.githubusercontent.com/shvam0000/Google-Maps-Clone/main/Mockup%20images/ss-1.PNG" style="width: 100%;" />
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
import AppLayout from '@/Layouts/AppLayout.vue';
import RestaurantsList from '@/Components/RestaurantsList.vue';
 
export default {
    components: {
        AppLayout, RestaurantsList
    },
    data() {
        return {
            restaurants: []
        }
    },
    mounted() {
        this.getall()
    },
    methods: {
        getall: function () {
            for (let i = 0; i < 10; i++) {
                this.restaurants.push({
                    id: i,
                    name: 'New 1',
                    lat: 'xxx',
                    lng: 'yyyy',
                    description: 'rrrrrrr',
                    link: 'https://github.com/thdevss'
                })
            }
        }
    }
}
</script>