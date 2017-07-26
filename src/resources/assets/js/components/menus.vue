<template></template>

<script>
import draggable from 'vuedraggable'
Vue.component('MenuChild', require('./_nested.vue'));

export default {
    props: ['PagesRoute', 'DelRoute', 'locale'],
    components: {
        draggable,
    },
    data() {
        return {
            pages: [],
            allPages: [],
            saveList: [],
        }
    },
    created() {
        $.ajaxSetup({
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        this.getPages()
    },
    methods: {
        getPages(){
            $.get(this.PagesRoute, (res) => {
                this.pages = res.pages
                this.allPages = res.allPages
            })
        },
        deleteItem(item){
            $.post(this.DelRoute,{
                page_id: item.id,
            }, (res) => {
                if (res.done) {
                    this.pages.splice(this.pages.indexOf(item), 1)
                }
            })
        },

        // operations
        getTitle(title){
            let locale = this.locale
            let v = Object.keys(title).indexOf(locale)
            return title.hasOwnProperty(locale) ? Object.values(title)[v] : Object.values(title)[0]
        },
        checkAdded(evt){
            if (evt.added) {
                evt.added.element.updated_at = null
            }
        },
        undoItem(item){
            this.pages.splice(this.pages.indexOf(item),1)
            this.allPages.unshift(item)
        },
    },
    watch: {
        pages(val) {
            this.saveList = []

            val.map((item) => {
                return this.saveList.push({
                    id: item.id,
                    order: this.pages.indexOf(item) + 1
                })
            })
        }
    },
}
</script>
