<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useNotificationStore } from '@/stores/notification';

const authStore = useAuthStore();
const notificationStore = useNotificationStore();

import ThemeProvider from '@/components/layout/ThemeProvider.vue';
import SidebarProvider from '@/components/layout/SidebarProvider.vue';

onMounted(async () => {
  if (authStore.isAuthenticated) {
    await authStore.fetchMe();
    notificationStore.initializeEcho();
    notificationStore.fetchUnreadCount();
  }
});
</script>

<template>
  <ThemeProvider>
    <SidebarProvider>
      <router-view />
    </SidebarProvider>
  </ThemeProvider>
</template>

<style>
/* Global styles in main.css */
</style>
