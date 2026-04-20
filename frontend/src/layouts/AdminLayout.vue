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
const isDropdownOpen = ref(false);
const isNotificationOpen = ref(false);

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

const navigation = [
  { name: 'Dashboard', href: '/admin', icon: 'bi-grid-1x2-fill', roles: ['admin', 'editor'] },
  { name: 'Duyệt mượn', href: '/admin/borrows/queue', icon: 'bi-calendar-check-fill', roles: ['admin', 'editor'] },
  { name: 'Kho thiết bị', href: '/admin/equipment', icon: 'bi-laptop-fill', roles: ['admin', 'editor'] },
  { name: 'Lịch sử thiết bị', href: '/admin/borrows', icon: 'bi-clock-history', roles: ['admin', 'editor'] },
  { name: 'Danh mục', href: '/admin/categories', icon: 'bi-tags-fill', roles: ['admin', 'editor'] },
  { name: 'Báo cáo', href: '/admin/reports', icon: 'bi-bar-chart-fill', roles: ['admin', 'editor'] },
  { name: 'Cài đặt nhân sự', href: '/admin/users', icon: 'bi-people-fill', roles: ['admin'] },
  { name: 'Thùng rác', href: '/admin/trash', icon: 'bi-trash3-fill', roles: ['admin', 'editor'] },
];

const filteredNav = navigation.filter(item => {
  return !item.roles || authStore.user?.roles?.some(r => item.roles.includes(r.name));
});

onMounted(() => {
  if (authStore.isAuthenticated) {
    notificationStore.fetchUnreadCount();
  }
});
</script>

<template>
  <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-boxdark-2 text-slate-800">
    <!-- Sidebar -->
    <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="absolute left-0 top-0 z-50 flex h-screen w-72 flex-col overflow-y-hidden bg-[#1C2434] duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0">
      <!-- Sidebar Header -->
      <div class="flex items-center justify-center gap-2 px-6 py-6 border-b border-strokedark">
        <router-link to="/admin" class="text-2xl font-bold text-white flex items-center gap-3">
          <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center shadow-lg shadow-primary/30">
            <i class="bi bi-shield-check text-white text-xl"></i>
          </div>
          TailAdmin
        </router-link>
        <button @click="isSidebarOpen = false" class="block lg:hidden text-bodydark p-2 hover:text-white">
          <i class="bi bi-x-lg text-xl"></i>
        </button>
      </div>

      <!-- Menu -->
      <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
          <h3 class="mb-4 ml-4 text-sm font-semibold text-bodydark2">MAIN MENU</h3>
          <ul class="mb-6 flex flex-col gap-1.5">
            <!-- Back to user site -->
             <li>
                <router-link to="/" class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4 mb-4 bg-[#2A3648] border border-strokedark">
                   <i class="bi bi-box-arrow-left text-lg"></i>
                   Chuyển sang trang User
                </router-link>
             </li>
             <hr class="border-strokedark mb-4" />
             <!-- Admin Menu Items -->
            <li v-for="item in filteredNav" :key="item.href">
              <router-link :to="item.href"
                 class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2.5 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-[#333A48] hover:text-white"
                 :class="{ 'bg-[#333A48] text-white': route.path === item.href || route.path.startsWith(item.href + '/') }"
              >
                <i :class="item.icon" class="text-lg opacity-80 group-hover:opacity-100"></i>
                {{ item.name }}
              </router-link>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Content Area -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden pt-18 lg:pt-0">
      
      <!-- Header -->
      <header class="sticky top-0 z-40 flex w-full bg-white shadow-sm dark:bg-boxdark dark:drop-shadow-none xl:static lg:mb-0">
        <div class="flex flex-grow items-center justify-between px-4 py-4 md:px-6 2xl:px-11">
          <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <!-- Hamburger Toggle BTN -->
            <button @click="isSidebarOpen = !isSidebarOpen" class="z-50 block rounded-sm border border-stroke bg-white p-1.5 shadow-sm dark:border-strokedark dark:bg-boxdark lg:hidden">
              <i class="bi bi-list text-2xl px-1"></i>
            </button>
            <span class="text-xl font-bold font-sans text-slate-800">Admin</span>
          </div>

          <div class="hidden sm:block">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ route.meta.title || 'Dashboard' }}</h1>
          </div>

          <div class="flex items-center gap-3 space-x-4">
             <!-- Notification Button -->
             <div class="relative">
                <button @click="isNotificationOpen = !isNotificationOpen" class="relative flex h-10 w-10 items-center justify-center rounded-full border border-stroke bg-gray-100 hover:text-primary dark:border-strokedark dark:bg-meta-4">
                  <i class="bi bi-bell text-lg"></i>
                  <span v-if="notificationStore.unreadCount > 0" class="absolute -top-0.5 -right-0.5 z-1 h-3 w-3 rounded-full bg-[#DC3545] border-2 border-white"></span>
                </button>
             </div>

             <!-- User Dropdown -->
             <div class="relative">
               <button @click="isDropdownOpen = !isDropdownOpen" class="flex items-center gap-4 focus:outline-none">
                 <span class="hidden text-right lg:block">
                   <span class="block text-sm font-medium text-black dark:text-white">{{ authStore.user?.name }}</span>
                   <span class="block text-xs text-slate-500">{{ authStore.user?.roles?.[0]?.name }}</span>
                 </span>
                 <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="h-11 w-11 rounded-full object-cover shadow-sm border border-slate-200" alt="User">
                 <div v-else class="h-11 w-11 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 border border-slate-300">
                    <i class="bi bi-person-fill text-xl"></i>
                 </div>
               </button>
               
               <!-- Dropdown Menu -->
               <div v-show="isDropdownOpen" @click.away="isDropdownOpen = false" class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-md dark:border-strokedark dark:bg-boxdark w-48">
                 <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-4 dark:border-strokedark">
                   <li><router-link to="/profile" class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary"><i class="bi bi-person"></i> Thông tin</router-link></li>
                   <li><router-link to="/settings" class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary"><i class="bi bi-gear"></i> Cài đặt</router-link></li>
                 </ul>
                 <button @click="handleLogout" class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-[#DC3545]">
                   <i class="bi bi-box-arrow-right text-[#DC3545]"></i> Thoát
                 </button>
               </div>
             </div>
          </div>
        </div>
      </header>
      
      <!-- Main Content -->
      <main class="w-full h-full bg-slate-100">
         <!-- Dynamic Title for Mobile -->
         <div class="block sm:hidden px-4 md:px-6 2xl:px-11 pt-6 pb-2">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ route.meta.title || 'Dashboard' }}</h1>
         </div>
         
         <div class="mx-auto p-4 md:p-6 2xl:p-10">
           <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
                 <component :is="Component" />
              </transition>
           </router-view>
         </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
