<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const records = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const selectedStatus = ref('');
const sortBy = ref('created_at');
const sortOrder = ref('desc');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/admin/borrows', {
      params: {
        search: searchQuery.value,
        status: selectedStatus.value,
        sort_by: sortBy.value,
        sort_order: sortOrder.value,
        page: currentPage.value
      }
    });
    records.value = response.data.data;
    totalPages.value = response.data.meta.last_page;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

let timeout;
watch(searchQuery, () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    currentPage.value = 1;
    fetchData();
  }, 500);
});

watch([selectedStatus, sortBy, sortOrder], () => {
  currentPage.value = 1;
  fetchData();
});

const getTimelineIconAndColor = (status) => {
  switch (String(status)) {
    case '1': return { icon: 'bi-hourglass-split', color: 'bg-warning text-white shadow-warning/50' };
    case '2': return { icon: 'bi-arrow-left-right', color: 'bg-primary text-white shadow-primary/50' };
    case '3': return { icon: 'bi-check-lg', color: 'bg-success text-white shadow-success/50' };
    case '4': return { icon: 'bi-x-lg', color: 'bg-danger text-white shadow-danger/50' };
    default: return { icon: 'bi-circle', color: 'bg-slate-400 text-white' };
  }
};

const handleConfirmReturn = async (id) => {
    const res = await Swal.fire({
        title: 'Xác nhận thu hồi?',
        text: 'Cập nhật tình trạng thiết bị đã được thu hồi.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3C50E0',
        confirmButtonText: 'Đã nhận máy',
        cancelButtonText: 'Hủy'
    });

    if (res.isConfirmed) {
        try {
            await axios.post(`/admin/borrows/${id}/return`);
            Swal.fire('Thành công', 'Đã cập nhật tình trạng trả máy.', 'success');
            fetchData();
        } catch (e) {}
    }
};
</script>

<template>
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Lịch Sử Mượn Trả</h2>
  </div>

  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-dark mb-6">
    <!-- Toolbar -->
    <div class="border-b border-gray-200 dark:border-gray-700 py-4 px-4 sm:px-6 flex flex-col sm:flex-row justify-between gap-4">
       <!-- Search -->
       <div class="relative w-full sm:w-1/3">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
             <i class="bi bi-search"></i>
          </span>
          <input v-model="searchQuery" type="text" placeholder="Tìm theo tên NV hoặc thiết bị..." class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 pl-10 pr-4 outline-none focus:border-brand-500 dark:text-white/90 text-sm">
       </div>
       
       <!-- Status Select -->
       <div class="w-full sm:w-auto">
          <select v-model="selectedStatus" class="w-full sm:w-64 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 px-3 outline-none focus:border-brand-500 text-sm appearance-none dark:text-gray-400">
             <option value="">Tất cả trạng thái</option>
             <option value="1">Yêu cầu chờ duyệt</option>
             <option value="2">Đang sử dụng</option>
             <option value="3">Đã hoàn trả</option>
             <option value="4">Bị từ chối</option>
          </select>
       </div>
    </div>

    <!-- Table -->
    <div class="max-w-full overflow-x-auto custom-scrollbar">
       <table class="min-w-full">
          <thead>
             <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.03]">
                <th class="px-5 py-3 text-left w-16 sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thiết bị</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Người mượn</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thời gian mượn</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Ngày trả báo cáo</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6 w-32">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tình trạng</p>
                </th>
                <th class="px-5 py-3 text-right sm:px-6 w-32">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
                </th>
             </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
             <tr v-if="loading"><td colspan="6" class="text-center py-10 text-gray-500">Đang tải...</td></tr>
             <tr v-else v-for="rec in records" :key="rec.id" class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4 sm:px-6">
                   <div class="flex flex-col">
                      <span class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ rec.equipment?.name }}</span>
                      <span class="text-gray-500 text-xs italic dark:text-gray-400">{{ rec.equipment?.model }}</span>
                   </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                   <div class="flex items-center gap-2 text-theme-sm font-medium text-gray-800 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center text-brand-500 dark:bg-brand-500/10"><i class="bi bi-person-fill"></i></div>
                      {{ rec.user?.name }}
                   </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                   <div class="flex flex-col text-theme-sm text-gray-600 dark:text-gray-400">
                      <span><i class="bi bi-box-arrow-right mr-1"></i> {{ rec.ngaymuon }}</span>
                      <span :class="new Date(rec.hantra) < new Date() && !rec.ngaytra ? 'text-error-500 font-medium' : ''"><i class="bi bi-flag mr-1"></i> Hạn: {{ rec.hantra || 'N/A' }}</span>
                   </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                   <div class="text-theme-sm text-gray-600 dark:text-gray-400">
                      <span v-if="rec.ngaytra" class="text-success-500 font-medium"><i class="bi bi-check-circle mr-1"></i> {{ rec.ngaytra }}</span>
                      <span v-else class="italic">Chưa trả</span>
                   </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                   <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium" :class="[
                       rec.status == 1 ? 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500' : '',
                       rec.status == 2 ? 'bg-info-50 text-info-700 dark:bg-info-500/15 dark:text-info-500' : '',
                       rec.status == 3 ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500' : '',
                       rec.status == 4 ? 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500' : ''
                   ]">
                      {{ rec.status_label }}
                   </span>
                </td>
                <td class="px-5 py-4 sm:px-6 text-right">
                   <button v-if="rec.status == 2" @click="handleConfirmReturn(rec.id)" class="px-3 py-1 rounded bg-brand-500 text-white hover:bg-brand-600 transition-colors text-xs font-medium uppercase shadow-sm">
                      <i class="bi bi-box-arrow-left"></i> Nhận trả
                   </button>
                   <span v-else class="text-gray-400 text-theme-xs italic">Hoàn tất</span>
                </td>
             </tr>
             <tr v-if="!loading && records.length === 0">
                <td colspan="6" class="text-center py-10 font-medium text-gray-500">Không tìm thấy lịch sử</td>
             </tr>
          </tbody>
       </table>
    </div>
    
    <!-- Pagination -->
    <div class="py-4 px-4 sm:px-6 flex justify-between items-center border-t border-gray-200 dark:border-gray-700">
       <p class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Trang {{ currentPage }} / {{ totalPages }}</p>
       <div class="flex gap-2 text-sm font-medium">
          <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">Trước</button>
          <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">Sau</button>
       </div>
    </div>
  </div>
</template>
