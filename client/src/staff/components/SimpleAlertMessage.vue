<template :ref="alert">
    <v-alert
        dense
        :type="type"
        dismissible
        transition="scale-transition"
        v-show="message != null"
    >
        {{ message }}
    </v-alert>
</template>
<script>
export default {
    props: {
        type: {
            type: String,
        },
        message: {
            type: String,
        },
    },
};
</script>
