<template></template>

<script>
import draggable from 'vuedraggable'
import MenuChild from './menu-comp_childs.vue'

export default {
    components: {draggable, MenuChild},
    props: ['getMenuPages', 'delPage', 'delChild', 'locale'],
    data() {
        return {
            pages: [],
            allPages: [],
            saveList: [],
            isDragging: false
        }
    },
    created() {
        this.getPages()
        this.eventsListeners()
    },
    methods: {
        getPages() {
            $.get(this.getMenuPages, (res) => {
                this.pages = res.pages
                this.allPages = res.allPages
            })
        },
        deletePage(item) {
            $.post(this.delPage, {
                page_id: item.id
            }, (res) => {
                if (res.done) {
                    this.pages.splice(this.pages.indexOf(item), 1)
                    this.pushBackToList(item)

                    EventHub.fire('showNotif', {
                        title: 'Success',
                        body: `"${this.getTitle(item.title)}" was removed`,
                        type: 'success',
                        duration: 1,
                        icon: false
                    })
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
            return item.from == 'allPages' || item.updated_at == null ? true : false
        },
        undoItem(item) {
            this.pages.splice(this.pages.indexOf(item), 1)
            this.pushBackToList(item)
        },
        pushBackToList(item) {
            item.from = 'allPages'
            this.allPages.unshift(item)
        },
        eventsListeners() {
            EventHub.listen('updateAllPages', () => {
                $.get(this.getMenuPages, (res) => {
                    this.allPages = res.allPages.filter((x) => this.pages.indexOf(x) < 0 )
                })
            })

            EventHub.listen('childDragStart', () => {
                this.isDragging = true
            })

            EventHub.listen('childDragEnd', () => {
                this.isDragging = false
            })
        },

        // styling
        updateList(e) {
            // catch moving from the childs list
            if (e.added && !e.added.element.from) {
                e.added.element.updated_at = null
            }

            // update visual when item is moved
            if (e.moved) {
                e.moved.element.created_at = null
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
            EventHub.fire('parentDragStart')
        },
        dragEnd() {
            this.isDragging = false
            EventHub.fire('parentDragEnd')
        },
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        },
        loop(item) {
            let childs = []

            item.nests.forEach((e) => {
                if (this.hasChilds(e)) {
                    childs.push({
                        id: e.id,
                        parent_id: item.id,
                        children: this.loop(e)
                    })
                } else {
                    childs.push({
                        id: e.id,
                        parent_id: item.id,
                        children: null
                    })
                }
            })

            return childs
        },

        // list hierarchy for db
        updatePages(val) {
            this.saveList = []

            val.map((item) => {
                if (this.hasChilds(item)) {
                    let childs = []

                    item.nests.forEach((e) => {
                        if (this.hasChilds(e)) {
                            childs.push({
                                id: e.id,
                                parent_id: item.id,
                                children: this.loop(e)
                            })
                        } else {
                            childs.push({
                                id: e.id,
                                parent_id: item.id,
                                children: null
                            })
                        }
                    })

                    return this.saveList.push({
                        id: item.id,
                        children: childs,
                        order: val.indexOf(item) + 1
                    })
                } else {
                    return this.saveList.push({
                        id: item.id,
                        children: null,
                        order: val.indexOf(item) + 1
                    })
                }
            })
        }
    },
    watch: {
        pages(val) {
            this.updatePages(val)

            EventHub.listen('updatePagesHierarchy', () => {
                this.updatePages(val)
            })
        }
    }
}
</script>
