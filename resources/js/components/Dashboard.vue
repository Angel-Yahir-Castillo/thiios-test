<template>
    <v-container>

        <v-row justify="space-between" align="center" class="mb-4">
            <v-col>
                <div>Hi {{ currentUser.name }}</div>
                <div>{{ currentUser.email }}</div>
            </v-col>
            <v-col>
                <v-btn color="primary" @click="openRegisterDialog">Register a new User</v-btn>
                <v-btn color="error" @click="logout">Logout</v-btn>
            </v-col>
        </v-row>

        <v-card>
            <v-card-title>Users</v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="users"
                    :items-per-page="5"
                    class="elevation-1"
                >
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip text="Tooltip">
                            <template v-slot:activator="{ props }">
                                <v-icon small @click="editUser(item)" v-bind="props" style="color: blue;">mdi-pencil</v-icon>
                            </template>
                            <span>Edit</span>
                        </v-tooltip>

                        <v-tooltip text="Tooltip">
                            <template v-slot:activator="{ props }">
                                <v-icon small @click="deleteUser(item)" v-bind="props" style="color: red;">mdi-delete</v-icon>
                            </template>
                            <span>Delete</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <v-dialog v-model="registerDialog" max-width="600">
            <v-card>
                <v-card-title>Register a new User</v-card-title>
                <v-card-text>
                    <v-form ref="registerForm">
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
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="registerUser">Submit</v-btn>
                    <v-btn @click="closeRegisterDialog">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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

    axios.interceptors.request.use(
        (config) => {
            const token = localStorage.getItem('access_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    export default {
        components: {
            SnackbarMessage
        },
        data() {
            return {
                users: [],
                currentUser: {},
                headers: [
                    { title: 'ID', key: 'id' },
                    { title: 'Name', key: 'name' },
                    { title: 'Email', key: 'email' },
                    { title: 'Created At', key: 'created_at' },
                    { title: 'Actions', key: 'actions', sortable: false },
                ],
                registerDialog: false,
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
                showSnackbar: false,
                snackbarMessage: ""
            };
        },
        mounted() {
            this.fetchCurrentUser();
            this.fetchUsers();
            this.showSessionInfo();
        },
        methods: {
            async fetchCurrentUser() {
                try {
                    const response = await axios.post('/api/auth/me');
                    this.currentUser = response.data.data.user;
                } catch (error) {
                    console.error('Error fetching current user:', error.response.data.message);
                }
            },
            async fetchUsers() {
                try {
                    const response = await axios.get('/api/users/');
                    this.users = response.data.data.users;
                } catch (error) {
                    console.error('Error fetching users:', error.response.data.message);
                }
            },
            async registerUser() {
                if (this.$refs.registerForm.validate()) {
                    try {
                        const response = await axios.post('/api/users', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.confirmPassword
                        });
                        this.fetchUsers();
                        this.closeRegisterDialog();
                        this.showSuccessSnackbar('User registered successfully');
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
            editUser(user) {
                // Implementar lógica para editar usuario
                console.log('Editar usuario:', user);
            },
            async deleteUser(user) {
                try {
                    const response = await axios.delete('/api/users/'+ user['id']);
                    this.fetchUsers();
                    this.showSuccessSnackbar(response.data.message);
                } catch (error) {
                    console.error('Error:', error.response.data.message);
                }
            },
            async logout() {
                try {
                    await axios.post('/api/auth/logout');
                    localStorage.removeItem('access_token');
                    localStorage.setItem('session-info', 'Logged out Successfully');
                    this.$router.push('/login');
                } catch (error) {
                    console.error('Error fetching current user:', error.response.data.message);
                }
            },
            openRegisterDialog() {
                this.registerDialog = true;
            },
            closeRegisterDialog() {
                this.registerDialog = false;
            },
            showSuccessSnackbar(message) {
                this.snackbarMessage = message;
                this.showSnackbar = true;
                setTimeout(() => {
                    this.showSnackbar = false;
                }, 4000);
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
            },
            showSessionInfo(){
                setTimeout(() => {
                    if(localStorage.getItem('session-info')){
                        this.showSuccessSnackbar(localStorage.getItem('session-info'))
                        localStorage.removeItem('session-info');
                    }
                }, 500);
            }
        },
    };
</script>



