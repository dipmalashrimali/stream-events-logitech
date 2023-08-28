import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Events from "../components/Events.vue";

const routes=[

    {
        path: '/login',
        name: 'loginUrl',
        component: Login,
        meta: {
            middleware: "guest",
            title: `Facebook Login`
        },
    },
    {
        path:'/events',
        name:'events',
        component: Events,
        meta: {
            title: 'Events'
        }
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});



export default router;
