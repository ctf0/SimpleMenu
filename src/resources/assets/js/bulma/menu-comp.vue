<script>
import draggable from 'vuedraggable'
import MenuChild from './menu-comp_childs.vue'
import menu from './mixins/menu'

export default {
    components: {draggable, MenuChild},
    name: 'menu-comp',
    mixins: [menu],
    props: ['getMenuPages', 'delPage'],
    data() {
        return {
            pages: [],
            allPages: [],
            saveList: []
        }
    },
    created() {
        this.getPages()
    },
    mounted() {
        this.$tippy.forceUpdateHtml()
    },
    methods: {
        getPages() {
            axios.get(this.getMenuPages)
                .then(({data}) => {
                    this.pages = data.pages
                    this.allPages = data.allPages
                }).catch((err) => {
                    console.error(err)
                })
        },
        deletePage(item) {
            axios.post(this.delPage, {
                page_id: item.id
            }).then(({data}) => {
                if (data.done) {
                    this.pages.splice(this.pages.indexOf(item), 1)
                    this.pushBackToList(item)
                    this.showNotif(`"${this.getTitle(item.title)}" was removed`)
                }
            }).catch((err) => {
                console.error(err)
            })
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
                axios.get(this.getMenuPages)
                    .then(({data}) => {
                        this.allPages = data.allPages.filter((x) => this.pages.indexOf(x) < 0 )
                    }).catch((err) => {
                        console.error(err)
                    })
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

        // nests
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
    },
    render () {}
}
</script>
