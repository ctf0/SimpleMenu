$.ajaxSetup({
    cache: false,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

Vue.component('PageComp', require('./components/page-comp.vue'))
Vue.component('MenuComp', require('./components/menu-comp.vue'))
Vue.component('IndexComp', require('./components/index-comp.vue'))
