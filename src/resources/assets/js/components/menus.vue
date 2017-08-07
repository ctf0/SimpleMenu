<template></template>

<script>
import draggable from 'vuedraggable'
import MenuChild from './menu_child.vue'

export default {
    props: ['getMenuPages', 'delPage', 'delChild', 'locale'],
    components: {draggable, MenuChild},
    data() {
        return {
            pages: [],
            allPages: [],
            saveList: []
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

        EventHub.listen('updateAllPages', () => {
            $.get(this.getMenuPages, (res) => {
                this.allPages = res.allPages.filter((x) => this.pages.indexOf(x) < 0 )
            })
        })
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
                        duration: 3,
                        icon: false
                    })
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
            return item.from == 'allPages' || item.updated_at == null ? true : false
        },
        undoItem(item) {
            this.pages.splice(this.pages.indexOf(item), 1)
            this.pushBackToList(item)
        },
        pushBackToList(item) {
            this.allPages.unshift(item)
        },
        cancelAdd(item) {
            return item.from == 'pages' ? this.pages.unshift(item) : this.allPages.unshift(item)
        },
        checkAdded(e) {
            // catch moving from the childs list
            if (e.added && !e.added.element.from) {
                e.added.element.updated_at = null
            }
        },

        // nests
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        },
        loop(item) {
            let childs = []

            item.nests.forEach((e) => {
                if (this.hasChilds(e)) {
                    // avoid self nesting
                    if (e.id == item.id) {
                        item.nests.splice(item.nests.indexOf(e), 1)
                        this.cancelAdd(item)
                    } else {
                        childs.push({
                            id: e.id,
                            parent_id: item.id,
                            children: this.loop(e)
                        })
                    }
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

        updatePages(val) {
            this.saveList = []

            val.map((item) => {
                if (this.hasChilds(item)) {
                    let childs = []

                    item.nests.forEach((e) => {
                        if (this.hasChilds(e)) {
                            // avoid self nesting
                            if (e.id == item.id) {
                                item.nests.splice(item.nests.indexOf(e), 1)
                                this.cancelAdd(item)
                            } else {
                                childs.push({
                                    id: e.id,
                                    parent_id: item.id,
                                    children: this.loop(e)
                                })
                            }
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
