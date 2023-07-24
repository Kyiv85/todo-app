<template>
    <v-container>
      <h2>Registration</h2>
      <v-form @submit.prevent="register">
        <v-text-field v-model="name" label="Name"></v-text-field>
        <v-text-field v-model="email" label="Email"></v-text-field>
        <v-text-field v-model="password" type="password" label="Password"></v-text-field>
        <v-text-field v-model="confirmPassword" type="password" label="Confirm Password"></v-text-field>
        <v-btn type="submit" color="primary">Register</v-btn>
      </v-form>
    </v-container>
  </template>
  
  <script>
  import apiClient from '../api';
  
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
      };
    },
    methods: {
      register() {
        const userData = {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword,
        };
  
        apiClient.post('/register', userData)
          .then(response => {
            // Handle successful registration response
            console.log(response.data);
          })
          .catch(error => {
            // Handle registration error
            console.error(error.response.data);
          });
      },
    },
  };
  </script>
  