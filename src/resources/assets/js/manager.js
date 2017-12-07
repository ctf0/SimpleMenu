window.Vue = require('vue')
window.EventHub = require('vuemit')
Vue.use(require('vue-tippy'), {
    flipDuration: 0,
    arrow: true,
    touchHold: true,
    performance: true,
    popperOptions: {
        modifiers: {
            preventOverflow: {
                enabled: false
            }
        }
    }
})

// axios
window.axios = require('axios')
axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
}

Vue.component('SmPage', require('./' + process.env.MIX_SM_FRAMEWORK + '/page-comp.vue'))
Vue.component('SmMenu', require('./' + process.env.MIX_SM_FRAMEWORK + '/menu-comp.vue'))
Vue.component('SmIndex', require('./' + process.env.MIX_SM_FRAMEWORK + '/index-comp.vue'))
Vue.component('MyNotification', require('vue-notif'))
