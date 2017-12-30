<script>
export default {
    name: 'index-comp',
    props: ['count'],
    data() {
        return {
            itemsCount: this.count,
            ids: []
        }
    },
    mounted() {
        new ListJS('table', {
            valueNames: [
                'data-sort-name',
                'data-sort-email',
                'data-sort-roles',
                'data-sort-permissions',
                'data-sort-title',
                'data-sort-route',
                'data-sort-url',
                'data-sort-menus',
                'data-sort-locals',
                'data-sort-template'
            ]
        })
    },
    methods: {
        selectAll() {
            // clear
            if (this.ids.length) {
                return this.ids = []
            }

            // add
            this.$refs['sm-ids'].map((e) => {
                this.ids.push(e.value)
            })
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
    render () {}
}
</script>
