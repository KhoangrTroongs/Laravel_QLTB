<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useNotificationStore } from '@/stores/notification';
import { Menu, Bell, User, LogOut, Settings, HelpCircle } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const notificationStore = useNotificationStore();
const router = useRouter();

const isUserMenuOpen = ref(false);
const isNotificationOpen = ref(false);

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'login' });
};
</script>

<template>
  <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-30">
    <div class="flex items-center gap-4">
      <button @click="$emit('toggle-sidebar')" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
        <Menu class="h-5 w-5 text-gray-500" />
      </button>
      <div class="hidden md:block">
        <h1 class="text-sm font-medium text-gray-500">QUẢN LÝ THIẾT BỊ</h1>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <!-- Notifications -->
      <div class="relative">
        <button @click="isNotificationOpen = !isNotificationOpen" 
          class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
          <Bell class="h-5 w-5 text-gray-500" />
          <span v-if="notificationStore.unreadCount > 0" 
            class="absolute top-1.5 right-1.5 h-4 w-4 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
            {{ notificationStore.unreadCount }}
          </span>
        </button>

        <!-- Notification Dropdown -->
        <div v-if="isNotificationOpen" @click.away="isNotificationOpen = false"
          class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden ring-1 ring-black ring-opacity-5">
          <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-semibold text-gray-900">Thông báo</h3>
            <button @click="notificationStore.markAllAsRead" class="text-xs text-primary font-medium hover:underline">
              Đánh dấu tất cả
            </button>
          </div>
          <div class="max-h-96 overflow-y-auto">
            <div v-if="notificationStore.notifications.length === 0" class="p-8 text-center text-gray-400">
              Không có thông báo mới
            </div>
            <div v-for="notif in notificationStore.notifications" :key="notif.id"
              class="p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer"
              :class="{'bg-primary/5': !notif.is_read}">
              <div class="flex gap-3">
                <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                  <Bell class="h-5 w-5 text-primary" />
                </div>
                <div>
                  <p class="text-sm text-gray-900 leading-snug">{{ notif.data.message || 'Có thông báo mới' }}</p>
                  <p class="text-xs text-gray-500 mt-1">{{ notif.created_at }}</p>
                </div>
              </div>
            </div>
          </div>
          <router-link :to="{ name: 'notifications' }" class="block p-3 text-center text-sm font-medium text-primary hover:bg-gray-50 border-t border-gray-100">
            Xem tất cả
          </router-link>
        </div>
      </div>

      <!-- User Profile -->
      <div class="relative">
        <button @click="isUserMenuOpen = !isUserMenuOpen" 
          class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-100 transition-colors">
          <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-200 border border-gray-300">
            <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" alt="Avatar" class="h-full w-full object-cover" />
            <User v-else class="h-full w-full p-1.5 text-gray-400" />
          </div>
          <span class="hidden sm:block text-sm font-medium text-gray-700">{{ authStore.user?.name || 'Cán bộ' }}</span>
        </button>

        <!-- User Dropdown -->
        <div v-if="isUserMenuOpen" 
          class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden ring-1 ring-black ring-opacity-5 py-2">
          <div class="px-4 py-2 border-b border-gray-100 mb-2">
            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tài khoản</p>
            <p class="text-sm font-medium text-gray-900 truncate">{{ authStore.user?.email }}</p>
          </div>
          
          <router-link to="/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <User class="h-4 w-4 text-gray-400" /> Hồ sơ cá nhân
          </router-link>
          <router-link to="/settings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <Settings class="h-4 w-4 text-gray-400" /> Cài đặt
          </router-link>
          <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <HelpCircle class="h-4 w-4 text-gray-400" /> Trợ giúp
          </a>
          
          <div class="border-t border-gray-100 mt-2 pt-2">
            <button @click="handleLogout" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
              <LogOut class="h-4 w-4" /> Đăng xuất
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
