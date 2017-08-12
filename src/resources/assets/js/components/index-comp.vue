<template></template>

<script>
    export default {
        props: ['count'],
        data() {
            return {
                itemsCount: this.count
            };
        },
        methods: {
            DelItem(event, name) {

                let that = this;

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

                            $(`#${event.target.dataset.id}`).remove();

                            // for sidebar menu
                            if ($(`li[data-id="${event.target.dataset.id}"]`)) {
                                $(`li[data-id="${event.target.dataset.id}"]`).remove();
                            }

                            that.itemsCount = --that.itemsCount;
                        }
                    }
                })
            }
        },
    }
</script>
