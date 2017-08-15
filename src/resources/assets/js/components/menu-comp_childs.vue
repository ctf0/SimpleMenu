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
                <span class="icon is-small"><i class="fa" :class="arrowObj(item)"></i></span>
                <span>{{ getTitle(item.title) }}</span>

                <!-- ops -->
                <button type="button" v-if="checkFrom(item)" class="delete" @click="undoItem(item)" title="undo"></button>
                <button type="button" v-else class="delete" @click.prevent="deleteChild(item)" title="remove child"></button>
            </div>

            <!-- childs -->
            <menu-child :locale="locale"
                :class="{dragArea: isDragging}"
                :pages="pages"
                :all-pages="allPages"
                :del-child="delChild"
                :childs="item.nests">
            </menu-child>
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
    components: {draggable},
    name: 'menu-child',
    props: ['pages', 'allPages', 'locale', 'delChild', 'childs'],
    data() {
        return {
            isDragging: false
        }
    },
    created() {
        this.eventsListeners()
    },
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

            return title.hasOwnProperty(locale) ? Object.values(title)[v] : Object.values(title)[0].concat(` "${Object.keys(title)[0]}"`)
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
        eventsListeners() {
            EventHub.listen('parentDragStart', () => {
                this.isDragging = true
            })

            EventHub.listen('parentDragEnd', () => {
                this.isDragging = false
            })
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
        },
        classObj(item) {
            if (this.checkFrom(item)) {
                return 'is-warning'
            }
            if (item.created_at == null) {
                return 'is-danger'
            }
        },
        arrowObj(item) {
            if (this.hasChilds(item)) {
                return 'fa-caret-down'
            }
            return 'fa-caret-right'
        },

        // nests
        dragStart() {
            this.isDragging = true
            EventHub.fire('childDragStart')
        },
        dragEnd() {
            this.isDragging = false
            EventHub.fire('childDragEnd')
        },
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        }
    }
}
</script>
