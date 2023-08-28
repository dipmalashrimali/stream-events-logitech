// axiosInstance.js
import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost:8000',
    headers: {
        'Content-Type': 'application/json'
    }
});

// Add an interceptor to set the Authorization header
instance.interceptors.request.use(config => {
    const userToken = localStorage.getItem('token');
    if (userToken) {
        config.headers.Authorization = `Bearer ${userToken}`;
    }
    return config;
});

export default instance;
