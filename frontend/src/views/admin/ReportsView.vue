<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from '@/api/axios';

const data = ref(null);
const loading = ref(true);
const selectedYear = ref(new Date().getFullYear());
const years = [2024, 2025, 2026];

const searchQuery = ref('');

const fetchData = async () => {
  loading.value = true;
  try {
    const params = { year: selectedYear.value };
    const response = await axios.get('/admin/reports', { params });
    data.value = response.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
    fetchData();
});

watch(selectedYear, fetchData);

const filteredUsers = computed(() => {
   if (!data.value || !data.value.user_stats) return [];
   let userStats = data.value.user_stats;
   if (searchQuery.value) {
      const q = searchQuery.value.toLowerCase();
      userStats = userStats.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
   }
   return userStats;
});

const handlePrint = () => window.print();
</script>

<template>
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between no-print">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Báo Cáo Thống Kê</h2>
  </div>

  <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark mb-6 no-print flex flex-col sm:flex-row gap-4 justify-between items-center">
      <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
         <div class="relative w-full sm:w-64">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
               <i class="bi bi-search"></i>
            </span>
            <input v-model="searchQuery" type="text" placeholder="Tìm kiếm nhân viên..." class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 py-2 pl-10 pr-4 outline-none focus:border-brand-500 dark:text-white/90 text-sm">
         </div>
         <div class="relative w-full sm:w-48">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
               <i class="bi bi-calendar"></i>
            </span>
            <select v-model="selectedYear" class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 py-2 pl-10 pr-4 outline-none focus:border-brand-500 text-sm appearance-none dark:text-gray-400">
               <option v-for="y in years" :key="y" :value="y">Năm {{ y }}</option>
            </select>
         </div>
      </div>
      
      <button @click="handlePrint" class="inline-flex py-2 px-4 rounded-lg bg-brand-500 hover:bg-brand-600 text-white font-medium transition-colors items-center gap-2 text-sm justify-center w-full sm:w-auto shrink-0 shadow-sm">
         <i class="bi bi-printer"></i> In Báo Cáo
      </button>
  </div>

  <!-- Spinner -->
  <div v-if="loading" class="flex justify-center items-center py-20 text-gray-400">
     <span>Đang tải số liệu báo cáo...</span>
  </div>

  <!-- User Stats Cards -->
  <div v-else-if="filteredUsers.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
      <div v-for="user in filteredUsers" :key="user.id" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-dark hover:shadow-md transition-shadow">
         <div class="flex items-center gap-4 border-b border-gray-100 pb-4 dark:border-gray-700">
            <div class="flex items-center justify-center w-12 h-12 rounded-full overflow-hidden border border-gray-200 bg-brand-50 text-brand-500 dark:border-gray-700 dark:bg-gray-800">
               <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover">
               <i v-else class="bi bi-person-fill text-xl"></i>
            </div>
            <div>
               <h4 class="font-bold text-gray-800 text-theme-md dark:text-white/90 line-clamp-1 truncate" :title="user.name">{{ user.name }}</h4>
               <span class="text-gray-500 text-xs italic dark:text-gray-400 line-clamp-1 truncate" :title="user.email">{{ user.email }}</span>
            </div>
         </div>
         <div class="mt-4 flex flex-col gap-3">
            <div class="flex justify-between items-center text-sm">
               <span class="text-gray-500 dark:text-gray-400 font-medium">Tổng lượt mượn</span>
               <span class="font-bold text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">{{ user.total_borrows }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
               <span class="text-brand-500 dark:text-brand-400 font-medium">Đang mượn (đang dùng)</span>
               <span class="font-bold text-brand-500 bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 rounded">{{ user.active_borrows }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
               <span class="text-success-500 dark:text-success-400 font-medium">Đã trả</span>
               <span class="font-bold text-success-500 bg-success-50 dark:bg-success-500/10 px-2 py-0.5 rounded">{{ user.returned_borrows }}</span>
            </div>
            <div v-if="user.overdue_borrows > 0" class="flex justify-between items-center text-sm border-t border-gray-100 pt-2 mt-1 dark:border-gray-700">
               <span class="text-error-500 dark:text-error-400 font-medium font-bold"><i class="bi bi-exclamation-triangle mr-1"></i> Trễ hạn</span>
               <span class="font-bold text-error-500 bg-error-50 dark:bg-error-500/10 px-2 py-0.5 rounded shadow-sm border border-error-100 dark:border-error-500/20">{{ user.overdue_borrows }}</span>
            </div>
         </div>
      </div>
  </div>

  <div v-else class="text-center py-20 bg-white rounded-xl border border-gray-200 dark:bg-gray-dark dark:border-gray-800 shadow-sm text-gray-500 font-medium">
     Không có dữ liệu báo cáo cho năm {{ selectedYear }}.
  </div>
</template>

<style scoped>
@media print {
  .no-print { display: none !important; }
}
</style>
