<template>
    <div>
        <!-- Buttons to trigger the different notifications -->
        <button @click="notifySuccess">Notify Success</button>
        <button @click="notifyError">Notify Error</button>
        <button @click="notifyWarning">Notify Warning</button>
        <button @click="notifyInfo">Notify Info</button>
    </div>
</template>

<script>
export default {
    methods: {
        notifySuccess() {
            this.$refs.notification.notifySuccess(); // Trigger Success notification
        },
        notifyError() {
            this.$refs.notification.notifyError(); // Trigger Error notification
        },
        notifyWarning() {
            this.$refs.notification.notifyWarning(); // Trigger Warning notification
        },
        notifyInfo() {
            this.$refs.notification.notifyInfo(); // Trigger Info notification
        },
    },
};
</script>
