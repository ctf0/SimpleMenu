<template></template>

<script>
import Sortable from 'vue-sortable'
Vue.use(Sortable)

export default {
    props: ['DelRoute', 'PagesRoute'],
    data() {
        return {
            list: [],
            saveList: []
        };
    },
    created() {
        $.ajaxSetup({
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        this.getPages();
    },
    methods: {
        onUpdate(event) {
            this.list.splice(event.newIndex, 0, (this.list.splice(event.oldIndex, 1)[0]))
        },
        getPages(){
            $.get(this.PagesRoute, (res) => {
                this.list = res.data;
            });
        },
        deleteItem(item){
            $.post(this.DelRoute,{
                page_id: item.id,
            }, (res) => {
                if (res.done) {
                    this.list.splice(this.list.indexOf(item), 1)
                }
            });
        }
    },
    watch: {
        list(val) {
            this.saveList = []

            val.map((item) => {
                return this.saveList.push({
                    id: item.id,
                    order: this.list.indexOf(item) + 1
                })
            })
        }
    },
}
</script>
