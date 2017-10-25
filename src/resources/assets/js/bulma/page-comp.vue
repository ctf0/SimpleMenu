<script>
export default {
    props: ['SelectFirst'],
    data() {
        return {
            toggleControl: true,
            toggleContent: true,
            toggleAccess: true,
            toggleGuards: true,
            title: '',
            body: '',
            desc: '',
            prefix: '',
            meta: '',
            url: ''
        }
    },
    mounted() {
        this.title = this.SelectFirst
        this.meta = this.SelectFirst
        this.body = this.SelectFirst
        this.desc = this.SelectFirst
        this.prefix = this.SelectFirst
        this.url = this.SelectFirst

        tinymce.overrideDefaults({
            menubar: false,
            branding: false,
            browser_spellcheck: true,
            contextmenu: false,
            height : '120',
            plugins: 'lists link image fullscreen media table preview autoresize',
            toolbar: 'undo redo | link unlink | media image | styleselect removeformat | outdent indent | numlist bullist table | preview fullscreen'
        })
    },
    methods: {
        showTitle(code) {
            return this.title == code
        },
        showMeta(code) {
            return this.meta == code
        },
        showBody(code) {
            return this.body == code
        },
        showDesc(code) {
            return this.desc == code
        },
        showPrefix(code) {
            return this.prefix == code
        },
        showUrl(code) {
            return this.url == code
        },
        toggleTinyMce(input, newVal, oldVal) {
            tinymce.init({
                selector: `#${input}-${newVal}`
            })

            if (oldVal) {
                tinymce.remove(`#${input}-${oldVal}`)
                this.$nextTick(() => {
                    $(`#${input}-${newVal}`).hide().closest('div').find('.mce-tinymce').fadeToggle('fast')
                })
            }
        }
    },
    watch: {
        body(newVal, oldVal) {
            this.toggleTinyMce('body', newVal, oldVal)
        },
        desc(newVal, oldVal) {
            this.toggleTinyMce('desc', newVal, oldVal)
        }
    },
    render () {}
}
</script>
