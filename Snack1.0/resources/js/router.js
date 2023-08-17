import { createRouter, createWebHistory } from 'vue-router'
const BASE_URL = '/'

import Practice1 from './components/practice1.vue'
import Home2 from './components/Home2.vue'


const routes = [
    {
        path: '/practice1',
        name: 'practice1',
        component: Practice1,
    },
    {
        path: '/home2',
        name: 'Home2',
        component: Home2,
    },

]

const router = createRouter({
    history: createWebHistory(BASE_URL),  // set BASE_URL
    routes
})

export default router