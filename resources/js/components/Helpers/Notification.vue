<template>
    <!-- The component does not render anything to the DOM -->
    <div></div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    methods: {
        showNotification({
            icon = "info", // Default icon
            title = "", // Title of the notification
            text = "", // Message of the notification
            timer = 3000, // Duration of the notification in milliseconds
        }) {
            Swal.fire({
                icon: icon, // Icon type (success, error, warning, info)
                title: title,
                text: text,
                timer: timer,
                showConfirmButton: false, // Hide the confirm button
            });
        },

        // Specific methods for each notification type
        notifySuccess() {
            this.showNotification({
                icon: "success",
                title: "Success!",
                text: "The operation was successful.",
                timer: 3000,
            });
        },
        notifyError() {
            this.showNotification({
                icon: "error",
                title: "Error!",
                text: "There was an error performing the operation.",
                timer: 3000,
            });
        },
        notifyWarning() {
            this.showNotification({
                icon: "warning",
                title: "Warning!",
                text: "Please be cautious with your next action.",
                timer: 3000,
            });
        },
        notifyInfo() {
            this.showNotification({
                icon: "info",
                title: "Information",
                text: "Here's some information for you.",
                timer: 3000,
            });
        },
    },
};
</script>
