$.ajaxSetup({
    cache: false,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

window.Vue = require('vue')

Vue.component('PageComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/page-comp.vue'))
Vue.component('MenuComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/menu-comp.vue'))
Vue.component('IndexComp', require('./' + process.env.MIX_SM_FRAMEWORK + '/index-comp.vue'))
Vue.component('MyNotification', require('./' + process.env.MIX_SM_FRAMEWORK + '/notifs.vue'))
