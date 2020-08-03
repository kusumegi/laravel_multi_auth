<template :ref="daialogue">
    <div>
        <button @click="open">toggle button</button>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                    {{ title }}
                </v-card-title>
                <v-card-text>
                    {{ message }}
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="close">
                        {{ btnLabel }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    data: () => ({
        dialog: false,
        title: "",
        message: "",
        btnLabel: "",
    }),
    methods: {
        open() {
            this.dialog = true;
        },
        close() {
            this.dialog = false;
        },
    },
};
</script>
