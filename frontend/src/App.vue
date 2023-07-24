<template>
  <div>
    <v-app>
      <v-main>
        <v-container>
          <router-link to="/login">Login</router-link>
          <router-link to="/register">Register</router-link>
          <router-link to="/todos">Todo List</router-link>
          <router-view></router-view>
        </v-container>
      </v-main>
    </v-app>
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>
