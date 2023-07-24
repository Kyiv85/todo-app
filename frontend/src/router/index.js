import { createRouter, createWebHistory } from 'vue-router';

import Login from '@/views/LoginView.vue';
import Register from '@/views/RegisterView.vue';
import TodoList from '@/views/TodoList.vue';

const routes = [
  {
    path: '/login',
    component: Login,
  },
  {
    path: '/register',
    component: Register,
  },
  {
    path: '/todos',
    component: TodoList,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
