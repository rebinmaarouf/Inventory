import './bootstrap';
import { createApp } from 'vue'; // Import Vue 3
import App from './components/App.vue'; // Main component
import router from './router'; // Import the router instance
import axios from 'axios';

import 'bootstrap/dist/css/bootstrap.min.css';  // Bootstrap CSS

import '@fortawesome/fontawesome-free/css/all.min.css';

import Notification from './components/Helpers/Notification.vue';

// Configure the default base URL for your API
axios.defaults.baseURL = 'http://localhost:8000'; // Replace with your Laravel API URL
// Set default headers for axios
axios.defaults.headers.post['Content-Type'] = 'application/json';

const app = createApp(App);

// Register the Notification component globally
app.component('Notification', Notification); 

// Use router and mount the app to the DOM
app.use(router).mount('#app');

export default axios;