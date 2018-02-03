<script>
export default {
    name: 'page-comp',
    props: ['SelectFirst'],
    data() {
        return {
            toggleControl: true,
            toggleCover: true,
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

        this.initTMC()
        this.initAce()
        this.initChoice()
    },
    methods: {
        // editors
        initTMC() {
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
        initAce() {
            let item = document.getElementById('ace-editor')

            if (item) {
                ace.require('ace/ext/language_tools')
                let editor = ace.edit('ace-editor')
                item.style.lineHeight = '2'
                editor.setOptions({
                    enableBasicAutocompletion:true,
                    enableLiveAutocompletion:true,
                    enableSnippets:true
                })
                editor.renderer.setOptions({
                    animatedScroll:true,
                    showInvisibles:true,
                    showPrintMargin:false,
                    fontSize: 14,
                    theme: 'ace/theme/monokai'
                })
                editor.session.setOptions({
                    mode: 'ace/mode/php'
                })

                editor.getSession().on('change', () => {
                    this.$refs.controllerFile.value = editor.getSession().getValue()
                })
            }
        },
        initChoice() {
            new Choices('.select2', {
                duplicateItems: false,
                removeItemButton: true,
                paste: false,
                placeholderValue: 'Select an option'
            })
        },

        // toggle
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
        },
        updateValue(event, item) {
            this.$refs[item].focus()
            document.execCommand('selectAll')
            document.execCommand('insertText', false, event.target.dataset.value)
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
