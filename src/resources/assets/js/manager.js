window.Vue = require('vue')
window.EventHub = require('vuemit')
Vue.use(require('vue-tippy'), {
    arrow: true,
    touchHold: true,
    inertia: true,
    performance: true
})
window.addEventListener('scroll', function () {
    const poppers = document.querySelectorAll('.tippy-popper')

    for (const popper of poppers) {
        const tooltip = popper._reference._tippy

        if (tooltip.state.visible) {
            tooltip.popperInstance.disableEventListeners()
            tooltip.hide()
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
