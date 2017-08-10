<template>
    <draggable :list="childs"
        :options="{draggable:'.item', group:'pages', ghostClass: 'ghost'}"
        :element="'ul'"
        @change="checkAdded">
        <li v-for="item in childs" :key="item.id" class="item">
            <!-- main -->
            <div class="notification is-dark menu-item" :class="classObj(item)">
                <span class="icon is-small"><i class="fa fa-caret-right"></i></span>
                <span v-html="getTitle(item.title)"></span>

                <!-- ops -->
                <button type="button" v-if="checkFrom(item)" class="delete" @click="undoItem(item)" title="undo"></button>
                <button type="button" v-else class="delete" @click.prevent="deleteChild(item)" title="remove child"></button>
            </div>

            <!-- childs -->
            <template v-if="hasChilds(item)">
                <menu-child :pages="pages" :all-pages="allPages" :locale="locale" :del-child="delChild" :childs="item.nests"></menu-child>
            </template>
        </li>
    </draggable>
</template>

<style scoped>
    ul {
        margin-right: 0 !important;
        /*border-left: none !important;*/
    }
</style>

<script>
import draggable from 'vuedraggable'

export default {
    name: 'menu-child',
    props: ['pages', 'allPages', 'locale', 'delChild', 'childs'],
    components: {draggable},
    methods: {
        deleteChild(item) {
            $.post(this.delChild, {
                child_id: item.id
            }, (res) => {
                if (res.done) {
                    this.childs.splice(this.childs.indexOf(item), 1)

                    EventHub.fire('showNotif', {
                        title: 'Success',
                        body: `"${this.getTitle(item.title)}" was removed`,
                        type: 'success',
                        duration: 3,
                        icon: false
                    })

                    EventHub.fire('updateAllPages')
                    EventHub.fire('updatePagesHierarchy')
                }
            })
        },
        getTitle(title) {
            let locale = this.locale
            let v = Object.keys(title).indexOf(locale)
            return title.hasOwnProperty(locale) ? Object.values(title)[v] : Object.values(title)[0]
        },

        // operations
        checkFrom(item) {
            return item.from ? true : false
        },
        undoItem(item) {
            this.childs.splice(this.childs.indexOf(item), 1)
            this.pushBackToList(item)
        },
        pushBackToList(item) {
            return item.from == 'pages' ? this.pages.unshift(item) : this.allPages.unshift(item)
        },
        classObj(item){
            if (this.checkFrom(item)) {
                return 'is-warning'
            }
        },

        // nests
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        },
        checkAdded(e) {
            // update saveList on nest movement
            if (e.moved || e.removed || e.added && e.added.element.from == 'allPages') {
                EventHub.fire('updatePagesHierarchy')
            }
        }
    }
}
</script>
