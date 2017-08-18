export default {
    props: ['locale', 'delChild'],
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
            let v = Object.keys(title).indexOf(locale)

            return title.hasOwnProperty(locale) ? Object.values(title)[v] : Object.values(title)[0].concat(` "${Object.keys(title)[0]}"`)
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
        hasChilds(item) {
            return item.nests && item.nests.length > 0
        }
    }
}
