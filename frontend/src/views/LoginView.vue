<template>
  <v-form @submit.prevent="login">
    <v-text-field v-model="email" label="Email"></v-text-field>
    <v-text-field v-model="password" type="password" label="Password"></v-text-field>
    <v-btn type="submit" color="primary">Login</v-btn>
  </v-form>
</template>

<script>
import apiClient from '../api';

export default {
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    login() {
        const userData = {
        email: this.email,
        password: this.password
        };
        
        apiClient.post('/login', userData)
        .then(response => {
            // Handle successful login response
            console.log(response.data);
        })
        .catch(error => {
            // Handle login error
            console.error(error.response.data);
        });
    }
  }
};
</script>
