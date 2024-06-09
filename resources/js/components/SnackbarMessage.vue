<template>
    <v-snackbar v-model="snackbarVisible" :color="color" :timeout="timeout" top>
        {{ message }}
        <v-btn text @click="closeSnackbar">Close</v-btn>
    </v-snackbar>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                required: true
            },
            color: {
                type: String,
                default: 'success'
            },
            timeout: {
                type: Number,
                default: 4000
            }
        },
        data() {
            return {
                snackbarVisible: true
            };
        },
        methods: {
            closeSnackbar() {
                this.snackbarVisible = false;
            }
        }
    };
</script>
