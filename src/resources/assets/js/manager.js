window.Vue = require('vue')
window.EventHub = require('vuemit')
window.axios = require('axios')
axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
}

Vue.component('PageComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/page-comp.vue'))
Vue.component('MenuComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/menu-comp.vue'))
Vue.component('IndexComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/index-comp.vue'))
Vue.component('MyNotification', require('vue-notif'))
