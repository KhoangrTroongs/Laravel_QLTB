<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const items = ref([]);
const loading = ref(true);
const activeTab = ref('equipment');
const searchQuery = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/admin/trash/${activeTab.value}`, {
      params: {
        search: searchQuery.value,
        page: currentPage.value
      }
    });
    items.value = response.data.data;
    totalPages.value = response.data.meta.last_page;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const handleRestore = async (id) => {
  try {
    await axios.post(`/admin/trash/${activeTab.value}/${id}/restore`);
    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Đã khôi phục', showConfirmButton: false, timer: 1500 });
    fetchData();
  } catch (error) {
    Swal.fire('Lỗi', 'Không thể khôi phục.', 'error');
  }
};

const handlePermanentDelete = async (id) => {
  const res = await Swal.fire({
    title: 'Xóa vĩnh viễn?',
    text: "Hành động này không thể hoàn tác!",
    icon: 'error',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Đồng ý'
  });

  if (res.isConfirmed) {
    try {
      await axios.delete(`/admin/trash/${activeTab.value}/${id}/force`);
      Swal.fire('Đã xóa', 'Dữ liệu đã bị xóa vĩnh viễn.', 'success');
      fetchData();
    } catch (e) {}
  }
};

watch([activeTab, searchQuery], () => {
  currentPage.value = 1;
  fetchData();
});
</script>

<template>
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Thùng Rác</h2>
    
    <div class="flex items-center gap-3 w-full sm:w-auto">
      <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg p-1 shadow-sm dark:bg-gray-800 dark:border-gray-700">
         <button @click="activeTab = 'equipment'" class="rounded-md px-4 py-1.5 text-sm font-medium transition-colors" :class="activeTab === 'equipment' ? 'bg-error-500 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'">Thiết bị</button>
         <button @click="activeTab = 'users'" class="rounded-md px-4 py-1.5 text-sm font-medium transition-colors" :class="activeTab === 'users' ? 'bg-error-500 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'">Nhân viên</button>
      </div>
      <div class="relative w-full sm:w-64">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
          <i class="bi bi-search"></i>
        </span>
        <input v-model="searchQuery" type="text" placeholder="Tìm trong thùng rác..." class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-2 pl-10 pr-4 outline-none focus:border-brand-500 dark:text-white/90 text-sm shadow-sm">
      </div>
    </div>
  </div>

  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-dark mb-6">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.03]">
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tên / Mô tả</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">{{ activeTab === 'equipment' ? 'Model / Mã máy' : 'Email / Định danh' }}</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Ngày xóa tạm</p>
            </th>
            <th class="px-5 py-3 text-right sm:px-6 w-32">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="loading"><td colspan="4" class="text-center py-10 text-gray-500">Đang tải...</td></tr>
          <tr v-else v-for="item in items" :key="item.id" class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
             <td class="px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                   <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 shrink-0">
                      <i :class="['text-error-500', activeTab === 'equipment' ? 'bi-laptop' : 'bi-person-x']"></i>
                   </div>
                   <span class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ item.name }}</span>
                </div>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ activeTab === 'equipment' ? item.model : item.email }}</p>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400 italic flex items-center gap-1">
                   <i class="bi bi-clock-history"></i> {{ item.deleted_at }}
                </p>
             </td>
             <td class="px-5 py-4 sm:px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                   <button @click="handleRestore(item.id)" class="px-3 py-1 rounded border border-success-500 text-success-500 hover:bg-success-50 dark:hover:bg-success-500/10 text-xs font-semibold uppercase tracking-wider transition-colors">
                      Khôi phục
                   </button>
                   <button @click="handlePermanentDelete(item.id)" class="px-2 py-1 rounded border border-error-500 text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors">
                      <i class="bi bi-trash3-fill"></i>
                   </button>
                </div>
             </td>
          </tr>
          <tr v-if="!loading && items.length === 0">
             <td colspan="4" class="text-center py-10 font-medium text-gray-500 text-sm uppercase tracking-widest">Thùng rác trống</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="py-4 px-4 sm:px-6 flex justify-between items-center border-t border-gray-200 dark:border-gray-700">
       <span class="text-gray-500 text-xs italic font-bold uppercase tracking-widest dark:text-gray-400">TRASH CONSOLE</span>
       <div class="flex gap-2 text-sm font-medium">
          <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"><i class="bi bi-chevron-left"></i></button>
          <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"><i class="bi bi-chevron-right"></i></button>
       </div>
    </div>
  </div>
</template>
