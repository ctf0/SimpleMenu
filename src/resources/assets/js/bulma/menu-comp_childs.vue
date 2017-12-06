<template>
    <draggable :list="childs"
               :class="{dragArea: isDragging}"
               :options="{group:'pages', ghostClass: 'ghost'}"
               :element="'ul'"
               @change="updateList"
               @start="dragStart"
               @end="dragEnd">

        <li v-for="item in childs" :key="item.id">
            <!-- main -->
            <div class="notification is-dark menu-item" :class="classObj(item)">
                <span>{{ getTitle(item.title) }}</span>

                <!-- ops -->
                <button type="button"
                        v-if="checkFrom(item)"
                        class="delete"
                        @click="undoItem(item)"
                        title="undo"
                        v-tippy/>
                <button type="button"
                        v-else
                        class="delete"
                        @click.prevent="deleteChild(item)"
                        title="remove child"
                        v-tippy/>
            </div>

            <!-- childs -->
            <menu-child :locale="locale"
                        :class="{dragArea: isDragging}"
                        :pages="pages"
                        :all-pages="allPages"
                        :del-child="delChild"
                        :childs="item.nests"/>
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
import menu from './mixins/menu'

export default {
    components: {draggable},
    name: 'menu-child',
    mixins: [menu],
    props: ['pages', 'allPages', 'childs'],
    methods: {
        deleteChild(item) {
            axios.post(this.delChild, {
                child_id: item.id
            }).then(({data}) => {
                if (data.done) {
                    this.childs.splice(this.childs.indexOf(item), 1)
                    this.showNotif(`"${this.getTitle(item.title)}" was removed`)
                    EventHub.fire('updateAllPages')
                    EventHub.fire('updatePagesHierarchy')
                }
            }).catch((err) => {
                console.error(err)
            })
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

        // styling
        updateList(e) {
            // update visual when item is moved
            if (e.moved || e.added) {
                let el = e.moved || e.added
                el.element.created_at = null
            }

            // update saveList on nest movement
            if (e.moved || e.removed || e.added && e.added.element.from == 'allPages') {
                EventHub.fire('updatePagesHierarchy')
            }
        }
    }
}
</script>
