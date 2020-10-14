require('./bootstrap');

window.Vue = require('vue');

Vue.component('post', require('./components/Post.vue').default);

import { store } from './store';
const app = new Vue({
    el: '#vue-app',
    store: store
});