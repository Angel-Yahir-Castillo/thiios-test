<template>
    <v-container class="fill-height">
        <v-row justify="center" align="center">
            <v-col lg="6" sm="8">
                <v-card>
                    <v-card-title class="justify-center">Register</v-card-title>
                    <v-card-text>
                        <v-form ref="registerForm" @submit.prevent="register">
                            <v-text-field
                                label="Name" prepend-icon="mdi-account" variant="outlined"
                                v-model="name" required :rules="nameRules"
                                :error-messages="nameErrors">
                            </v-text-field>
                            <v-text-field
                                label="Email" prepend-icon="mdi-email" variant="outlined"
                                v-model="email" required :rules="emailRules"
                                :error-messages="emailErrors">
                            </v-text-field>
                            <v-text-field
                                label="Password" prepend-icon="mdi-lock" variant="outlined"
                                v-model="password" type="password" required :rules="passwordRules"
                                :error-messages="passwordErrors">
                            </v-text-field>
                            <v-text-field
                                label="Confirm Password" prepend-icon="mdi-lock" variant="outlined"
                                v-model="confirmPassword" type="password" required :rules="confirmPasswordRules">
                            </v-text-field>
                            <v-btn type="submit" color="primary">Submit</v-btn>
                        </v-form>
                        <div style="margin-top: 20px; text-align: center;">
                            <span>Are you already registered?, <router-link to="/login">login here</router-link></span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                name: '',
                email: '',
                password: '',
                confirmPassword: '',
                nameRules: [
                    v => !!v || 'Name is required',
                    v => v.length >= 2 || 'Name must be at least 2 characters'
                ],
                emailRules: [
                    v => !!v || 'Email is required',
                    v => /.+@.+\..+/.test(v) || 'Email must be valid'
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                    v => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/.test(v) || 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character'
                ],
                confirmPasswordRules: [
                    v => !!v || 'Confirm Password is required',
                    v => v === this.password || 'Passwords do not match'
                ],
                nameErrors: [],
                passwordErrors: [],
                emailErrors: [],
            };
        },
        mounted() {
        },
        methods: {
            async register() {
                if (this.$refs.registerForm.validate()) {
                    try {
                        const response = await axios.post('/api/auth/register', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.confirmPassword
                        });
                        localStorage.setItem('session-info', 'Registered Successfully, now yo can login');
                        this.$router.push('/login');
                    } catch (error) {
                        if (error.response) {
                            if (error.response.status === 422) {
                                const errors = error.response.data.errors;
                                this.showErrorMessages(errors);
                            } else {
                                console.log('An error occurred. Please try again later.');
                            }
                        } else {
                            console.error(error);
                        }
                    }
                }
            },
            showErrorMessages(errors){
                this.nameErrors = [];
                this.emailErrors = [];
                this.passwordErrors = [];
                for (const [key, value] of Object.entries(errors)) {
                    if (key === 'email') {
                        this.emailErrors.push(value[0]);
                    } else if (key === 'password') {
                        this.passwordErrors.push(value[0]);
                    } else if (key === 'name') {
                        this.nameErrors.push(value[0]);
                    }
                }
            }
        },
    };
</script>



