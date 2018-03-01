<script>
import Search from './mixins/search'

export default {
    name: 'index-comp',
    props: ['count'],
    mixins: [Search],
    data() {
        return {
            itemsCount: this.count,
            ids: [],
            sorter: null,
            searchColName: 'data-sort-name',
            sortableList: [
                'data-sort-name',
                'data-sort-email',
                'data-sort-roles',
                'data-sort-permissions',
                'data-sort-route',
                'data-sort-url',
                'data-sort-menus',
                'data-sort-locals',
                'data-sort-template',
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
    }
}
</script>
