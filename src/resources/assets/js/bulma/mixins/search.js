export default {
    data() {
        return {
            searchFor: null
        }
    },
    methods: {
        resetSearch() {
            this.searchFor = null
        }
    },
    watch: {
        searchFor(val) {
            let res = this.sorter.search(val, this.searchColName)
            this.updateCounter(res.length)
        }
    }
}
