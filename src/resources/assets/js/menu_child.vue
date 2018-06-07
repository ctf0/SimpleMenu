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
            <div :class="classObj(item)" class="notification is-dark menu-item">
                <p>
                    {{ getTitle(item.title) }}
                    <a :href="goTo(item.id)" class="is-inline-block is-paddingless">
                        <span class="icon is-medium"><icon name="pencil"/></span>
                    </a>
                </p>

                <!-- ops -->
                <button v-tippy
                        v-if="checkFrom(item)"
                        :title="trans('undo')"
                        type="button"
                        class="delete"
                        @click="undoItem(item)"/>
                <button v-tippy
                        v-else
                        :title="trans('remove_child')"
                        type="button"
                        class="delete"
                        @click.prevent="deleteChild(item)"/>
            </div>

            <!-- childs -->
            <menu-child :locale="locale"
                        :class="{dragArea: isDragging}"
                        :pages="pages"
                        :all-pages="allPages"
                        :del-child="delChild"
                        :edit-page="editPage"
                        :translation="translation"
                        :childs="item.nests"/>
        </li>
    </draggable>
</template>

<style scoped>
    ul {
        margin-right: 0 !important;
        /*border-left: none !important;*/
    }

    .icon.is-medium {
        height: unset;
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
