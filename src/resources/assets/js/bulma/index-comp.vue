<script>
export default {
    name: 'index-comp',
    props: ['count'],
    data() {
        return {
            itemsCount: this.count
        }
    },
    methods: {
        DelItem(event, name) {

            let that = this
            let id = event.target.dataset.id

            $.ajax({
                url: event.target.action,
                type: 'DELETE',
                success(res) {
                    if (res.done) {
                        EventHub.fire('showNotif', {
                            title: 'Success',
                            body: `"${name}" was removed`,
                            type: 'success',
                            duration: 1,
                            icon: false
                        })

                        $(`#${id}`).remove()

                        // for sidebar menu
                        if ($(`li[data-id="${id}"]`)) {
                            $(`li[data-id="${id}"]`).remove()
                        }

                        that.itemsCount = --that.itemsCount
                    }
                }
            })
        }
    },
    render () {}
}
</script>
