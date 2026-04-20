<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const users = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortBy = ref('id');
const sortOrder = ref('desc');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/admin/users', {
      params: {
        search: searchQuery.value,
        sort_by: sortBy.value,
        sort_order: sortOrder.value,
        page: currentPage.value
      }
    });
    users.value = response.data.data;
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

watch([sortBy, sortOrder], () => {
  currentPage.value = 1;
  fetchData();
});

const toggleSort = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = column;
    sortOrder.value = 'asc';
  }
};

const handleDelete = async (id) => {
  const result = await Swal.fire({
    title: 'Xóa nhân viên?',
    text: "Tài khoản sẽ được tạm khóa.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Đồng ý'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/admin/users/${id}`);
      Swal.fire('Thành công', 'Đã xóa tài khoản.', 'success');
      fetchData();
    } catch (e) {}
  }
};
</script>

<template>
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Quản Trị Nhân Viên</h2>
    <div class="flex items-center gap-3 w-full sm:w-auto">
      <div class="relative w-full sm:w-64">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
          <i class="bi bi-search"></i>
        </span>
        <input v-model="searchQuery" type="text" placeholder="Tên, Email hoặc mã NV..." class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 pl-10 pr-4 outline-none focus:border-brand-500 dark:text-white/90 text-sm">
      </div>
      <button @click="$router.push('/admin/users/create')" class="inline-flex items-center justify-center gap-2.5 rounded-lg bg-brand-500 py-2 px-4 text-center font-medium text-white hover:bg-brand-600 transition-colors shrink-0">
         <i class="bi bi-person-plus-fill"></i> Thêm Mới
      </button>
    </div>
  </div>

  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-dark mb-6">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.03]">
            <th class="px-5 py-3 text-left w-16 sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">ID</p>
            </th>
            <th @click="toggleSort('name')" class="px-5 py-3 text-left sm:px-6 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-colors">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400 flex items-center gap-1">Nhân viên <i class="bi bi-arrow-down-up text-[10px]"></i></p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Liên lạc / Email</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Vai trò</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Ngày gia nhập</p>
            </th>
            <th class="px-5 py-3 text-right sm:px-6 w-24">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="loading"><td colspan="6" class="text-center py-10 text-gray-500">Đang tải...</td></tr>
          <tr v-else v-for="user in users" :key="user.id" class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-xs font-mono dark:text-gray-400">#{{ user.id }}</p>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                   <div class="w-10 h-10 overflow-hidden rounded-full ring-2 ring-gray-100 dark:ring-gray-800 shrink-0">
                      <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover">
                      <div v-else class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                         <i class="bi bi-person-fill text-gray-400"></i>
                      </div>
                   </div>
                   <div>
                      <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ user.name }}</p>
                      <p class="block text-brand-500 text-theme-xs uppercase font-medium">{{ user.employee_id }}</p>
                   </div>
                </div>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400 flex items-center gap-2">
                   <i class="bi bi-envelope-at text-gray-400"></i> {{ user.email }}
                </p>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <div class="flex flex-wrap gap-1">
                   <span v-for="role in user.roles" :key="role.id" class="rounded whitespace-nowrap bg-info-50 px-2 py-0.5 text-theme-xs font-medium text-info-700 dark:bg-info-500/15 dark:text-info-400 uppercase">
                      {{ role.name }}
                   </span>
                </div>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400 italic">{{ user.created_at }}</p>
             </td>
             <td class="px-5 py-4 sm:px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                   <button @click="$router.push(`/admin/users/edit/${user.id}`)" class="text-gray-400 hover:text-brand-500 transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                   <button v-if="user.id !== 1" @click="handleDelete(user.id)" class="text-gray-400 hover:text-error-500 transition-colors"><i class="bi bi-trash text-lg"></i></button>
                </div>
             </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="py-4 px-4 sm:px-6 flex justify-between items-center border-t border-gray-200 dark:border-gray-700">
       <p class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Trang {{ currentPage }} / {{ totalPages }}</p>
       <div class="flex gap-2 text-sm font-medium">
          <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"><i class="bi bi-chevron-left"></i></button>
          <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"><i class="bi bi-chevron-right"></i></button>
       </div>
    </div>
  </div>
</template>
