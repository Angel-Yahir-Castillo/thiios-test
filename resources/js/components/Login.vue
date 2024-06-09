<template>
    <v-container class="fill-height">
        <v-row justify="center" align="center">
            <v-col lg="6" sm="8">
                <v-card>
                    <v-card-title class="justify-center">Login</v-card-title>
                    <v-card-text>
                        <v-form ref="loginForm" @submit.prevent="login" >
                            <div v-if="errorMessage" class="error-message" style="color: red; font-size: 16px;">{{ errorMessage }}</div>
                            <v-text-field
                                label="Email" prepend-icon="mdi-email" variant="outlined"
                                v-model="email" required :rules="emailRules"
                                :error-messages="emailErrors">
                            </v-text-field>
                            <v-text-field
                                label="Password" prepend-icon="mdi-lock" variant="outlined"
                                v-model="password" type="password" required :rules="passwordRules"
                                :error-messages="passwordErrors"
                                hint="Enter your password to access this website">
                            </v-text-field>
                            <v-btn type="submit" color="primary">Login</v-btn>
                        </v-form>
                        <div style="margin-top: 20px; text-align: center;">
                            <span>If you don't have an account yet, <router-link to="/register">register here</router-link></span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <SnackbarMessage
            v-if="showSnackbar"
            :message="snackbarMessage"
            color="success"
            :timeout="4000"
        />

    </v-container>
</template>

<script>
import axios from 'axios';
import SnackbarMessage from "@/components/SnackbarMessage.vue";

export default {
    components: {
        SnackbarMessage
    },
    data() {
        return {
            email: '',
            password: '',
            emailRules: [
                v => !!v || 'Email is required',
                v => /.+@.+\..+/.test(v) || 'Email must be valid'
            ],
            passwordRules: [
                v => !!v || 'Password is required'
            ],
            passwordErrors: [],
            emailErrors: [],
            errorMessage: '',
            showSnackbar: false,
            snackbarMessage: ""
        };
    },
    mounted() {
        this.showSessionInfo();
    },
    methods: {
        async login() {
            if (this.$refs.loginForm.validate()) {
                try {
                    const response = await axios.post('/api/auth/login', {
                        email: this.email,
                        password: this.password
                    });
                    localStorage.setItem('access_token', response.data.data.access_token);
                    localStorage.setItem('session-info', 'Login Successfully');
                    this.$router.push('/dashboard');
                } catch (error) {
                    if (error.response) {
                        this.emailErrors = [];
                        this.passwordErrors = [];
                        if (error.response.status === 401) {
                            this.errorMessage = 'Invalid credentials. Please try again.';
                        } else if (error.response.status === 422) {
                            const errors = error.response.data.errors;
                            for (const [key, value] of Object.entries(errors)) {
                                if (key === 'email') {
                                    this.emailErrors.push(value[0]);
                                } else if (key === 'password') {
                                    this.passwordErrors.push(value[0]);
                                }
                            }
                        } else {
                            console.log('An error occurred. Please try again later.');
                        }
                    } else {
                        console.error(error);
                    }
                }
            }
        },
        showSuccessSnackbar(message) {
            this.snackbarMessage = message;
            this.showSnackbar = true;
        },
        showSessionInfo(){
            setTimeout(() => {
                if(localStorage.getItem('session-info')){
                    this.showSuccessSnackbar(localStorage.getItem('session-info'))
                    localStorage.removeItem('session-info');
                }
            }, 500);
        }
    }
};
</script>
