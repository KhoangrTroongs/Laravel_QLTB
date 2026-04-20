<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const isMobileMenuOpen = ref(false);

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800">
    <!-- Navbar -->
    <header class="bg-white sticky top-0 z-50 border-b border-slate-200/60 shadow-sm">
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16">
             <!-- Logo -->
             <div class="flex items-center">
                <router-link to="/" class="flex items-center gap-2">
                   <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center shadow-md">
                      <i class="bi bi-laptop-fill"></i>
                   </div>
                   <span class="font-bold text-xl tracking-tight text-slate-800">DeviceManager</span>
                </router-link>
             </div>

             <!-- Desktop Nav -->
             <nav class="hidden md:flex space-x-8">
                <router-link to="/" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                  :class="route.path === '/' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'">
                   Trang chủ
                </router-link>
                <router-link v-if="authStore.isAuthenticated" to="/my-borrows" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
                  :class="route.path === '/my-borrows' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'">
                   Phiếu của tôi
                </router-link>
             </nav>

             <!-- User Actions -->
             <div class="hidden md:flex items-center gap-4">
                <template v-if="authStore.isAuthenticated">
                   <div v-if="authStore.user?.roles?.some(r => ['admin', 'editor'].includes(r.name))">
                       <router-link to="/admin" class="text-sm font-medium text-slate-600 hover:text-primary transition-colors flex items-center gap-1 bg-slate-100 px-3 py-1.5 rounded-md border border-slate-200">
                          <i class="bi bi-speedometer2 text-primary"></i> Quản trị
                       </router-link>
                   </div>
                   <div class="h-8 w-[1px] bg-slate-200"></div>
                   <div class="flex items-center gap-2">
                      <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-8 h-8 rounded-full border border-slate-200 object-cover" />
                      <span v-else class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center"><i class="bi bi-person text-slate-400"></i></span>
                      <span class="text-sm font-medium text-slate-700">{{ authStore.user?.name }}</span>
                   </div>
                   <button @click="handleLogout" class="text-slate-400 hover:text-red-500 transition-colors ml-2" title="Đăng xuất">
                      <i class="bi bi-box-arrow-right text-lg"></i>
                   </button>
                </template>
                <template v-else>
                   <router-link to="/login" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Đăng nhập</router-link>
                   <router-link to="/register" class="text-sm font-medium text-white bg-primary hover:bg-primary-dark px-4 py-2 rounded-lg shadow-sm transition-colors">Đăng ký</router-link>
                </template>
             </div>

             <!-- Mobile menu button -->
             <div class="flex items-center md:hidden">
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-slate-500 hover:text-slate-900 p-2">
                   <i class="bi text-2xl" :class="isMobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
             </div>
          </div>
       </div>

       <!-- Mobile Nav -->
       <div v-show="isMobileMenuOpen" class="md:hidden border-t border-slate-200 bg-white">
          <div class="pt-2 pb-3 space-y-1">
             <router-link to="/" class="block px-4 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-primary">Trang chủ</router-link>
             <router-link v-if="authStore.isAuthenticated" to="/my-borrows" class="block px-4 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-primary">Phiếu của tôi</router-link>
             <router-link v-if="authStore.isAuthenticated && authStore.user?.roles?.some(r => ['admin', 'editor'].includes(r.name))" to="/admin" class="block px-4 py-2 text-base font-medium text-primary bg-slate-50">Vào trang Quản trị</router-link>
          </div>
          <div v-if="authStore.isAuthenticated" class="pt-4 pb-3 border-t border-slate-200">
             <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                   <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-10 h-10 rounded-full object-cover">
                   <i v-else class="bi bi-person-circle text-3xl text-slate-400"></i>
                </div>
                <div class="ml-3">
                   <div class="text-base font-medium text-slate-800">{{ authStore.user?.name }}</div>
                   <div class="text-sm font-medium text-slate-500">{{ authStore.user?.email }}</div>
                </div>
             </div>
             <div class="mt-3 space-y-1">
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:bg-slate-50">Đăng xuất</button>
             </div>
          </div>
          <div v-else class="pt-4 pb-3 border-t border-slate-200 px-4 flex flex-col gap-2">
             <router-link to="/login" class="btn btn-outline-primary w-full text-center">Đăng nhập</router-link>
             <router-link to="/register" class="btn btn-primary w-full text-center">Đăng ký</router-link>
          </div>
       </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center">
       <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
             <component :is="Component" class="w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-8" />
          </transition>
       </router-view>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
       <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="flex items-center gap-2">
             <i class="bi bi-laptop text-slate-400"></i>
             <span class="text-sm text-slate-500 tracking-wide">&copy; {{ new Date().getFullYear() }} DeviceManager. All rights reserved.</span>
          </div>
          <div class="flex space-x-6">
             <a href="#" class="text-slate-400 hover:text-slate-500"><i class="bi bi-github"></i></a>
             <a href="#" class="text-slate-400 hover:text-slate-500"><i class="bi bi-twitter"></i></a>
          </div>
       </div>
    </footer>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
