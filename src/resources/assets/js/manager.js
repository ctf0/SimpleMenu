/*                Packages                */
window.Vue = require('vue')
window.EventHub = require('vuemit')
Vue.use(require('vue-tippy'), {
    arrow: true,
    touchHold: true,
    inertia: true,
    performance: true,
    flipDuration: 0,
    popperOptions: {
        modifiers: {
            preventOverflow: {
                enabled: false
            },
            hide: {
                enabled: false
            }
        }
    }
})

// icons
import 'vue-awesome/icons/search'
import 'vue-awesome/icons/times'
Vue.component('icon', require('vue-awesome/components/Icon'))

// table sort
window.ListJS = require('list.js')

// axios
window.axios = require('axios')
axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
}
axios.interceptors.response.use((response) => {
    return response
}, (error) => {
    return Promise.reject(error.response)
})

/*                Component                */
Vue.component('SmPage', require('./' + process.env.MIX_SM_FRAMEWORK + '/page-comp.vue'))
Vue.component('SmMenu', require('./' + process.env.MIX_SM_FRAMEWORK + '/menu-comp.vue'))
Vue.component('SmIndex', require('./' + process.env.MIX_SM_FRAMEWORK + '/index-comp.vue'))
Vue.component('MyNotification', require('vue-notif'))
