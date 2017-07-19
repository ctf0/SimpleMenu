<template></template>

<script>
    export default {
        props: ['SelectFirst'],
        data() {
            return {
                title: '',
                body: '',
                desc: '',
                prefix: '',
                url: '',
                hideOther: false
            };
        },
        mounted() {
            this.title = this.SelectFirst
            this.body = this.SelectFirst
            this.desc = this.SelectFirst
            this.prefix = this.SelectFirst
            this.url = this.SelectFirst
        },
        methods: {
            showTitle(code) {
                return this.title == code;
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
            }
        }
    }
</script>
