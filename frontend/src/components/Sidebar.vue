<script setup>
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { 
  LayoutDashboard, 
  Monitor, 
  Users, 
  History, 
  ClipboardList, 
  Trash2, 
  Settings,
  ShieldCheck,
  ChevronRight,
  Package
} from 'lucide-vue-next';
import { useRoute } from 'vue-router';

const props = defineProps({
  isOpen: Boolean
});

const authStore = useAuthStore();
const route = useRoute();

const menuItems = computed(() => {
  const items = [
    { name: 'Trang chủ', icon: Package, route: 'home', public: true },
    { name: 'Thiết bị của tôi', icon: Monitor, route: 'my-borrows', public: true },
  ];

  if (authStore.isAdmin || authStore.isEditor) {
    items.push(
      { type: 'header', name: 'QUẢN TRỊ' },
      { name: 'Tổng quan', icon: LayoutDashboard, route: 'dashboard' },
      { name: 'Danh mục', icon: ClipboardList, route: 'admin-categories' },
      { name: 'Thiết bị', icon: Monitor, route: 'admin-equipment' },
      { name: 'Yêu cầu mượn', icon: ShieldCheck, route: 'admin-queue' },
      { name: 'Lịch sử mượn', icon: History, route: 'admin-borrows' },
      { name: 'Nhân viên', icon: Users, route: 'admin-users' },
      { name: 'Thùng rác', icon: Trash2, route: 'admin-trash' },
    );
  }

  return items;
});

const isActive = (itemRoute) => {
  return route.name === itemRoute;
};
</script>

<template>
  <aside :class="[
    'bg-gray-900 text-white h-screen transition-all duration-300 ease-in-out flex flex-col z-40',
    isOpen ? 'w-64' : 'w-20'
  ]">
    <!-- Brand -->
    <div class="h-16 flex items-center px-6 border-b border-gray-800 flex-shrink-0">
      <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-primary/20">
        <Monitor class="h-5 w-5 text-white" />
      </div>
      <span v-if="isOpen" class="ml-3 font-bold text-lg tracking-tight truncate">QL THIẾT BỊ</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 scrollbar-thin scrollbar-thumb-gray-800">
      <template v-for="(item, index) in menuItems" :key="index">
        <div v-if="item.type === 'header' && isOpen" class="px-6 py-4">
          <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">{{ item.name }}</p>
        </div>
        
        <router-link v-else-if="!item.type" :to="{ name: item.route }"
          :class="[
            'flex items-center px-6 py-3 transition-all duration-200 group relative',
            isActive(item.route) 
              ? 'bg-primary/10 text-primary border-r-4 border-primary' 
              : 'text-gray-400 hover:text-white hover:bg-gray-800/50'
          ]">
          <div :class="['flex-shrink-0', isActive(item.route) ? 'text-primary' : 'text-gray-500 group-hover:text-gray-300']">
            <component :is="item.icon" class="h-5 w-5" />
          </div>
          
          <span v-if="isOpen" class="ml-4 font-medium text-sm transition-opacity duration-300">{{ item.name }}</span>
          
          <!-- Tooltip for collapsed mode -->
          <div v-if="!isOpen" class="absolute left-full ml-4 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap shadow-xl">
            {{ item.name }}
          </div>
        </router-link>
      </template>
    </nav>

    <!-- Footer Info -->
    <div v-if="isOpen" class="p-4 bg-gray-800/20 mt-auto border-t border-gray-800">
      <div class="flex items-center gap-3">
        <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center">
          <ShieldCheck class="h-4 w-4 text-primary" />
        </div>
        <div>
          <p class="text-xs font-bold text-white uppercase italic">{{ authStore.isAdmin ? 'Administrator' : 'Staff' }}</p>
          <p class="text-[10px] text-gray-500">v2.0.0 Stable</p>
        </div>
      </div>
    </div>
  </aside>
</template>

<style scoped>
/* Optional: custom scrollbar styling */
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: transparent;
}
aside:hover .scrollbar-thin::-webkit-scrollbar-thumb {
  background: #374151;
}
</style>
