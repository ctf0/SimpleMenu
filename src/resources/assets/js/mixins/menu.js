export default {
    props: ['locale', 'delChild', 'editPage', 'translation'],
    data() {
        return {
            isDragging: false
        }
    },
    created() {
        this.eventsListeners()
    },
    methods: {
        getTitle(title) {
            let locale = this.locale

            return title.hasOwnProperty(locale)
                ? title[locale]
                : Object.values(title)[0].concat(` "${Object.keys(title)[0]}"`)
        },
        goTo(id) {
            return this.editPage.replace(0, id)
        },
        trans(key) {
            return this.translation[key]
        },

        // ops
        eventsListeners() {
            EventHub.listen('DragStart', () => {
                this.isDragging = true
            })

            EventHub.listen('DragEnd', () => {
                this.isDragging = false
            })
        },
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        },

        // style
        classObj(item) {
            if (this.checkFrom(item)) {
                return 'is-warning'
            }
            if (item.created_at == null) {
                return 'is-danger'
            }
        },

        // notif
        showNotif(msg) {
            EventHub.fire('showNotif', {
                title: 'Success',
                body: msg,
                type: 'success',
                duration: 2,
                icon: false
            })
        },

        // nests
        dragStart() {
            this.isDragging = true
            EventHub.fire('DragStart')
        },
        dragEnd() {
            this.isDragging = false
            EventHub.fire('DragEnd')
        }

    }
}
