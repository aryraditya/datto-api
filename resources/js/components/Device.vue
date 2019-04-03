<template>
    <div>
        <slot :device="device" :loading="loading"></slot>
    </div>
</template>

<script>
    export default {
        name: "Device",
        props: ['sn', 'url'],
        data() {
            return {
                device: {},
                loading: false,
            }
        },
        mounted() {
            this.loading = true;
            axios.get(this.url).then(res => {
                this.device = res.data;
            }).then(() => this.loading = false);
        },
        updated() {
            $('[data-toggle="popover"]').popover();
        }
    }
</script>

<style scoped>

</style>