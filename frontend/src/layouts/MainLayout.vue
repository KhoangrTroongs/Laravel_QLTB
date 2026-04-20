<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useNotificationStore } from '@/stores/notification';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const notificationStore = useNotificationStore();
const isSidebarOpen = ref(true);

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

const navigation = [
  { name: 'Trang chủ', href: '/', icon: 'bi-house-door', roles: ['user', 'editor', 'admin'] },
  { name: 'Thiết bị của tôi', href: '/my-borrows', icon: 'bi-clock-history', roles: ['user', 'editor', 'admin'] },
  { type: 'header', name: 'Hệ thống Quản lý', roles: ['admin', 'editor'] },
  { name: 'Tổng quan', href: '/admin', icon: 'bi-speedometer2', roles: ['admin', 'editor'] },
  { name: 'Danh mục', href: '/admin/categories', icon: 'bi-grid-fill', roles: ['admin', 'editor'] },
  { name: 'Kho thiết bị', href: '/admin/equipment', icon: 'bi-laptop', roles: ['admin', 'editor'] },
  { name: 'Duyệt mượn', href: '/admin/borrows/queue', icon: 'bi-calendar-check', roles: ['admin', 'editor'] },
  { name: 'Lịch sử mượn', href: '/admin/borrows', icon: 'bi-clipboard-data', roles: ['admin', 'editor'] },
  { name: 'Báo cáo', href: '/admin/reports', icon: 'bi-bar-chart-line', roles: ['admin', 'editor'] },
  { name: 'Nhân viên', href: '/admin/users', icon: 'bi-people', roles: ['admin'] },
  { name: 'Thùng rác', href: '/admin/trash', icon: 'bi-trash3', roles: ['admin', 'editor'] },
];

const filteredNav = navigation.filter(item => {
  if (item.type === 'header') {
    return authStore.user?.roles?.some(r => item.roles.includes(r.name));
  }
  return !item.roles || authStore.user?.roles?.some(r => item.roles.includes(r.name));
});

onMounted(() => {
  notificationStore.fetchUnreadCount();
});
</script>

<template>
  <div class="app-wrapper" :class="{ 'sidebar-closed': !isSidebarOpen }">
    <!-- Sidebar -->
    <aside class="app-sidebar shadow">
      <!-- Brand Logo -->
      <router-link to="/" class="sidebar-brand no-underline border-bottom border-secondary">
        <i class="bi bi-laptop-fill text-primary me-2"></i>
        <span>DEVICE MANAGER</span>
      </router-link>

      <!-- Sidebar Content -->
      <div class="nav-sidebar scrollbar-thin overflow-y-auto">
        <nav>
          <ul class="nav flex-column">
            <!-- User Profile Summary in Sidebar -->
            <li class="px-3 py-4 border-bottom border-secondary mb-3">
               <div class="d-flex align-items-center gap-3">
                  <div class="h-10 w-10 rounded-circle bg-secondary border border-light d-flex align-items-center justify-content-center overflow-hidden">
                     <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-100 h-100 object-fit-cover" />
                     <i v-else class="bi bi-person-fill text-white fs-5"></i>
                  </div>
                  <div class="overflow-hidden">
                     <p class="mb-0 text-white fw-bold x-small truncate">{{ authStore.user?.name }}</p>
                     <small class="text-secondary x-small d-block italic">{{ authStore.user?.roles?.[0]?.name }}</small>
                  </div>
               </div>
            </li>

            <li v-for="(item, idx) in filteredNav" :key="idx" class="nav-item">
              <div v-if="item.type === 'header'" class="nav-header">
                {{ item.name }}
              </div>
              <router-link v-else :to="item.href" class="nav-link mx-2 rounded" :class="{ 'active': route.path === item.href }">
                <i :class="['bi', item.icon]"></i>
                <span>{{ item.name }}</span>
              </router-link>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="app-main">
      <!-- Navbar -->
      <header class="app-header shadow-sm bg-white sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100">
          <ul class="navbar-nav list-unstyled d-flex align-items-center m-0">
             <li class="nav-item">
                <a @click="isSidebarOpen = !isSidebarOpen" class="nav-link px-2 cursor-pointer text-dark">
                   <i class="bi bi-list fs-4"></i>
                </a>
             </li>
             <li class="nav-item d-none d-md-block">
                <router-link to="/" class="nav-link px-3 text-dark fw-bold">Trang chủ</router-link>
             </li>
          </ul>

          <ul class="navbar-nav list-unstyled d-flex align-items-center m-0 gap-3">
             <li class="nav-item dropdown">
                <a class="nav-link p-2 text-dark position-relative">
                   <i class="bi bi-bell-fill"></i>
                   <span v-if="notificationStore.unreadCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;">
                      {{ notificationStore.unreadCount }}
                   </span>
                </a>
             </li>
             <li class="nav-item">
                <a @click="handleLogout" class="btn btn-sm btn-outline-danger px-3 fw-bold">
                   <i class="bi bi-box-arrow-right me-1"></i> THOÁT
                </a>
             </li>
          </ul>
        </div>
      </header>

      <!-- Page Header -->
      <div class="app-content-header py-3 bg-white border-bottom shadow-sm">
         <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0 fw-bold text-dark">{{ route.meta.title || 'Dashboard' }}</h1>
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 py-0 bg-transparent x-small fw-bold">
                  <li class="breadcrumb-item"><router-link to="/" class="text-primary">Home</router-link></li>
                  <li class="breadcrumb-item active">{{ route.meta.title }}</li>
               </ol>
            </nav>
         </div>
      </div>

      <!-- Page Content -->
      <div class="app-content p-4">
         <router-view v-slot="{ Component }">
            <component :is="Component" />
         </router-view>
      </div>
    </main>
  </div>
</template>

<style scoped>
.cursor-pointer { cursor: pointer; }
.no-underline { text-decoration: none; }
.x-small { font-size: 0.75rem; }
.transition-all { transition: all 0.3s ease-in-out; }
.h-10 { height: 2.5rem; }
.w-10 { width: 2.5rem; }
</style>
