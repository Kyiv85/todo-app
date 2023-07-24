<template>
    <v-container>
      <h2>Todo List</h2>
      <v-form @submit.prevent="createTodo">
        <v-text-field v-model="title" label="Title"></v-text-field>
        <v-text-field v-model="description" label="Description"></v-text-field>
        <v-btn type="submit" color="primary">Create Todo</v-btn>
      </v-form>
  
      <v-list>
        <v-list-item-group v-if="todos.length > 0">
          <v-list-item v-for="todo in todos" :key="todo.id">
            <v-list-item-content>
              <v-list-item-title>{{ todo.title }}</v-list-item-title>
              <v-list-item-subtitle>{{ todo.description }}</v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-btn @click="updateTodo(todo.id)" color="primary">Update</v-btn>
              <v-btn @click="deleteTodo(todo.id)" color="error">Delete</v-btn>
            </v-list-item-action>
          </v-list-item>
        </v-list-item-group>
        <p v-else>No todos found.</p>
      </v-list>
    </v-container>
  </template>
  
  <script>
  import apiClient from '../api';
  
  export default {
    data() {
      return {
        title: '',
        description: '',
        todos: [],
      };
    },
    created() {
      this.fetchTodos();
    },
    methods: {
      fetchTodos() {
        apiClient.get('/todos')
          .then(response => {
            this.todos = response.data.todos;
          })
          .catch(error => {
            console.error(error.response.data);
          });
      },
      createTodo() {
        const todoData = {
          title: this.title,
          description: this.description,
        };
  
        apiClient.post('/todos', todoData)
          .then(response => {
            // Handle successful todo creation response
            console.log(response.data);
            this.fetchTodos(); // Refresh the todo list
          })
          .catch(error => {
            // Handle todo creation error
            console.error(error.response.data);
          });
      },
      updateTodo(todoId) {
        const todoToUpdate = this.todos.find(todo => todo.id === todoId);
        if (!todoToUpdate) {
            console.error(`Todo with ID ${todoId} not found.`);
            return;
        }

        const updatedTodoData = {
            title: todoToUpdate.title,
            description: todoToUpdate.description,
            completed: !todoToUpdate.completed, // Toggle the completed status
        };

        apiClient.put(`/todos/${todoId}`, updatedTodoData)
            .then(response => {
            // Handle successful todo update response
            console.log(response.data);
            this.fetchTodos(); // Refresh the todo list
            })
            .catch(error => {
            // Handle todo update error
            console.error(error.response.data);
            });
        },
        deleteTodo(todoId) {
            const todoToDelete = this.todos.find(todo => todo.id === todoId);
            if (!todoToDelete) {
                console.error(`Todo with ID ${todoId} not found.`);
                return;
            }

            apiClient.delete(`/todos/${todoId}`)
                .then(response => {
                // Handle successful todo deletion response
                console.log(response.data);
                this.fetchTodos(); // Refresh the todo list
                })
                .catch(error => {
                // Handle todo deletion error
                console.error(error.response.data);
                });
        },
    },
  };
  </script>
  