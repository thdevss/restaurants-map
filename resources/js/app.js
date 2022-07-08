import './bootstrap';
import {createApp} from 'vue'
import BootstrapVue3 from 'bootstrap-vue-3'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css'

import App from './Pages/Main.vue'
import VueGoogleMaps from '@fawmi/vue-google-maps'
import vue3StarRatings from "vue3-star-ratings";

const app = createApp(App)
app.use(BootstrapVue3)
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyAArEA3V7UUnB4bsQYpI9B8pVd7wytVGek'
    },
})
app.component("vue3-star-ratings", vue3StarRatings);

app.mount("#app")