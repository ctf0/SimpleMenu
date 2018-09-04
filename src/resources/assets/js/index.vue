<script>
import Search from './mixins/search'

export default {
    name: 'index-comp',
    mixins: [Search],
    props: ['count'],
    data() {
        return {
            itemsCount: this.count,
            ids: [],
            sorter: null,
            searchColName: 'data-sort-name',
            sortableList: [
                'data-sort-name',
                'data-sort-email',
                'data-sort-middlewares',
                'data-sort-route',
                'data-sort-url',
                'data-sort-template',
                {name: 'data-sort-permissions', attr: 'data-permissions'},
                {name: 'data-sort-roles', attr: 'data-roles'},
                {name: 'data-sort-menus', attr: 'data-menus'},
                {name: 'data-sort-locals', attr: 'data-locals'},
                {name: 'data-sort-ops', attr: 'data-ops'}
            ]
        }
    },
    mounted() {
        this.sorter = new ListJS('table', {
            valueNames: this.sortableList
        })
    },
    methods: {
        selectAll() {
            // clear
            if (this.ids.length) {
                return this.ids = []
            }

            // add
            this.$refs['sm-ids'].filter((e) => {
                document.getElementById(e.id) ? this.ids.push(e.value) : false
            })
        },
        clickOnCkBox(id) {
            document.querySelector(`#${id}`).click()
        },
        updateCounter(val) {
            return this.itemsCount = val
        },

        DelItem(event, name) {

            let id = event.target.dataset.id

            axios({
                method: 'DELETE',
                url: event.target.action
            }).then(({data}) => {
                if (data.done) {
                    EventHub.fire('showNotif', {
                        title: 'Success',
                        body: `"${name}" was removed`,
                        type: 'success',
                        duration: 2,
                        icon: false
                    })

                    // remove item
                    document.getElementById(`${id}`).remove()
                    this.sorter.remove(this.searchColName, name)

                    // for sidebar menu
                    let item = document.querySelector(`li[data-id="${id}"]`)
                    if (item) {
                        item.remove()
                    }

                    this.itemsCount = --this.itemsCount
                }

            }).catch((err) => {
                console.error(err)
            })
        }
    },
    render() {}
}
</script>
