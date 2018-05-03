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
require('vue-multi-ref')
import 'vue-awesome/icons/search'
import 'vue-awesome/icons/times'
import 'vue-awesome/icons/pencil'
Vue.component('icon', require('vue-awesome/components/Icon'))

// table sort
window.ListJS = require('list.js')

// axios
window.axios = require('axios')
axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
}
axios.interceptors.response.use(
    (response) => response,
    (error) => Promise.reject(error.response)
)

/*                Component                */
Vue.component('SmPage', require('./page.vue'))
Vue.component('SmMenu', require('./menu.vue'))
Vue.component('SmIndex', require('./index.vue'))
Vue.component('MyNotification', require('vue-notif'))