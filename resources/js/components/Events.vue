<template>
    <div>
        <Header/>
        <div class="d-flex flex-column justify-content-center mt-lg-5">
            <div class="bd-example">
                <h3 class="text-center">Events</h3>
                <div class="d-flex text-center text-primary justify-content-center" v-if="eventsData.length > 0">
                    <ul class="list-group bg-white w-50 text-center justify-content-center" ref="scrollComponent">
                        <li
                            :class="item.status ? 'bg-light' : 'bg-white'"
                            class="list-group-item d-flex justify-content-between align-items-start"
                            v-for="item in eventsData"
                            :key="item.id"
                        >
                            <div class="ms-2 me-auto">
                                <div :class="item.status ? 'text-muted' : ''">{{ item.event }}
                                    <p v-if="item.type ==3" class="text-primary">"Thank you for being awesome"
                                    </p>
                                </div>
                            </div>
                            <button
                                :class="item.status ? 'btn-secondary' : 'btn-primary'"
                                class="btn btn-sm"
                                @click="toggleReadStatus(item)"
                            >
                                Mark {{ item.status ? 'Unread' : 'Read' }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="d-flex text-center text-primary justify-content-center" v-else>
                    <div class="d-flex justify-content-center alert alert-info fw-bold">No events are found.</div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import Header from './Header.vue';
import {ref,onMounted} from 'vue';
import axiosInstance from '../store/axiosInstance';

const eventsData = ref([]);
const isLoading = ref(false);
const lastPage = ref();
const currentPage = ref(1);
let page = 1;

// Fetch event records on scroll
const loadMoreEvents = async () => {
    isLoading.value = true;
    try {
        const response = await axiosInstance.get(`/api/events?page=${page}`);
        eventsData.value = eventsData.value.concat(response.data.data);
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
        page++;
    } catch (error) {
        console.error('Error loading events:', error);
    } finally {
        isLoading.value = false;
    }
};

// Load initial data
onMounted(() => {
    setTimeout(()=>{
        loadMoreEvents(); // Initial load
    },1000);

});
window.addEventListener('scroll', () => {

    if (
        window.innerHeight + window.scrollY >= document.body.offsetHeight - 100 &&
        !isLoading.value && lastPage.value != currentPage.value
    ) {
        loadMoreEvents();
    }
});
// Mark read/unread event
const toggleReadStatus = async (item) => {
    await axiosInstance.put(`/api/events/${item.id}`, {status: !item.status, type: item.type});
    item.status = !item.status;
};

</script>


