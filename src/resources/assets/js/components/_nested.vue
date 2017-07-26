<template>
    <ul>
        <li v-for="item in childs" :key="item.id" class="item">
            <div class="notification menu-item">
                <span class="icon is-small"><i class="fa fa-caret-right"></i></span>
                <span v-html="getTitle(item.title)"></span>
            </div>

            <menu-child v-if="item.children.length > 0" :childs="item.children" :locale="locale"></menu-child>
        </li>
    </ul>
</template>

<style scoped>
    ul {
        margin: 0 !important;
        border-left: none !important;
    }
</style>

<script>
import draggable from 'vuedraggable'

export default {
    props: ['childs','locale'],
    components: {
        draggable,
    },
    data() {
        return {
        }
    },
    methods: {
        getTitle(title){
            let locale = this.locale
            let v = Object.keys(title).indexOf(locale)
            return title.hasOwnProperty(locale) ? Object.values(title)[v] : Object.values(title)[0]
        },
    },
}
</script>
