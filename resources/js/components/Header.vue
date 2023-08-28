<template>
    <div class="d-flex flex-column justify-content-center mt-lg-5">
        <div class="text-center">
            <img src="https://www.cdnlogo.com/logos/l/23/laravel.svg" width="100" class="img-fluid img-responsive center-block d-inline-flex" alt="" />
            <h1 class="h1 mb-3 font-weight-normal">Stream Events</h1>
            <h4 class="text-primary font-weight-bold">Welcome {{store.state.auth.user.name}}</h4>
            <div class="d-flex justify-content-center mt-lg-5">
                <router-link :to="{name: 'events'}" class="btn btn-primary btn-lg mx-5">Events</router-link>
                <button @click="store.dispatch('auth/logout')" class="btn btn-danger btn-lg">Logout</button>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column justify-content-center mt-lg-5">
        <div class="bd-example">
            <div class="d-flex text-center text-primary justify-content-center" v-if="dashboardSummary.totalRevenue != undefined">
                <div class="card bg-white" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Total Revenue</h5>
                        <h6 class="card-subtitle mb-2 text-danger fw-bold my-4">{{dashboardSummary.totalRevenue}}</h6>
                    </div>
                </div>
                <div class="card mx-5 bg-white" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Total Followers</h5>
                        <h6 class="card-subtitle mb-2 text-success my-4 fw-bold">{{dashboardSummary.totalFollowers}}</h6>
                    </div>
                </div>
                <div class="card bg-white" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Top 3 Items</h5>
                        <p class="card-text" v-for="item in dashboardSummary.top3Items">{{item.item_name}} - <span class="text-danger">{{item.total_sales}} USD</span></p>
                    </div>
                </div>
            </div>
            <div class="d-flex text-center text-primary justify-content-center" v-else>
                <div class="d-flex justify-content-center alert alert-info fw-bold">Data are loading please wait....</div>
            </div>

        </div>
    </div>
</template>
<script setup>
import {ref, onMounted, onBeforeMount} from "vue";
import axiosInstance from '../store/axiosInstance';
import { useStore } from 'vuex';

const store = useStore();
const dashboardSummary = ref({});

// Event summary data for dashboard and event pages
const eventSummary = async () => {
    const response = await axiosInstance.get(`/api/eventsSummary`);
    dashboardSummary.value = response.data.data;
};

//Logout method
const logout = () =>{
    store.dispatch("auth/logout");
}
// The onBeforeMount function is called before the component is mounted
onBeforeMount(async() => {
    const data = await  axios.get('facebook/getToken');
    localStorage.setItem('token',data.data.token);
    await store.dispatch("auth/login");
    await eventSummary();
});

onMounted(async() => {


})

</script>

