import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createApp } from "vue/dist/vue.esm-bundler";
import AppComponent from "./components/App.vue";
import router from "./router/index";
import './bootstrap';
import '../sass/app.scss'
import store from './store/index';
const app = createApp({
    components:{
        AppComponent,
    }
});
app.use(router);
app.use(store);
app.mount("#app");

