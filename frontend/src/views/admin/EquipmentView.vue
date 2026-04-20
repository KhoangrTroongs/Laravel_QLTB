<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const equipment = ref([]);
const categories = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const sortBy = ref('id');
const sortOrder = ref('desc');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchData = async () => {
  loading.value = true;
  try {
    const [eqRes, catRes] = await Promise.all([
      axios.get('/admin/equipment', {
        params: {
          search: searchQuery.value,
          category_id: selectedCategory.value,
          status: selectedStatus.value,
          sort_by: sortBy.value,
          sort_order: sortOrder.value,
          page: currentPage.value
        }
      }),
      axios.get('/admin/categories')
    ]);
    equipment.value = eqRes.data.data;
    totalPages.value = eqRes.data.meta.last_page;
    categories.value = catRes.data;
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

watch([selectedCategory, selectedStatus, sortBy, sortOrder], () => {
  currentPage.value = 1;
  fetchData();
});

const getStatusBadge = (status) => {
  switch (status) {
    case 1: return 'bg-success text-white';
    case 0: return 'bg-primary text-white';
    case 2: return 'bg-danger text-white';
    default: return 'bg-slate-500 text-white';
  }
};

const getStatusText = (status) => {
  switch (status) {
    case 1: return 'SẴN SÀNG';
    case 0: return 'ĐANG MƯỢN';
    case 2: return 'BẢO TRÌ';
    default: return 'N/A';
  }
};

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
    title: 'Xác nhận xóa?',
    text: "Thiết bị sẽ được đưa vào thùng rác.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DC3545',
    confirmButtonText: 'Đồng ý'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/admin/equipment/${id}`);
      Swal.fire('Thành công', 'Đã chuyển vào thùng rác.', 'success');
      fetchData();
    } catch (e) {}
  }
};
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Kho Thiết Bị</h2>
      <button @click="$router.push('/admin/equipment/create')" class="inline-flex items-center justify-center gap-2.5 rounded-lg bg-brand-500 py-2.5 px-6 text-center font-medium text-white hover:bg-brand-600 transition-colors shadow-sm">
         <i class="bi bi-plus-lg"></i> Nhập thiết bị mới
      </button>
    </div>

    <!-- Main Container -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-dark mb-6">
       <!-- Toolbar -->
       <div class="border-b border-gray-200 dark:border-gray-700 py-4 px-4 sm:px-6 flex flex-col sm:flex-row justify-between gap-4">
          <!-- Search -->
          <div class="relative w-full sm:w-1/3">
             <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="bi bi-search"></i>
             </span>
             <input v-model="searchQuery" type="text" placeholder="Tìm tên, model..." class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 pl-10 pr-4 outline-none focus:border-brand-500 dark:text-white/90 text-sm">
          </div>
          
          <!-- Filters -->
          <div class="flex gap-3 w-full sm:w-auto">
             <select v-model="selectedCategory" class="w-full sm:w-40 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 px-3 outline-none focus:border-brand-500 text-sm appearance-none dark:text-gray-400">
                <option value="">Tất cả danh mục</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
             </select>
             
             <select v-model="selectedStatus" class="w-full sm:w-40 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent py-2 px-3 outline-none focus:border-brand-500 text-sm appearance-none dark:text-gray-400">
                <option value="">Mọi tình trạng</option>
                <option value="1">Sẵn sàng</option>
                <option value="0">Đang mượn</option>
                <option value="2">Bảo trì</option>
             </select>
          </div>
       </div>

       <!-- Table -->
       <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="min-w-full">
             <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.03]">
                   <th class="px-5 py-3 text-left w-16 sm:px-6">
                     <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">ID</p>
                   </th>
                   <th class="px-5 py-3 text-left sm:px-6">
                     <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tên Máy / Model</p>
                   </th>
                   <th class="px-5 py-3 text-left hidden md:table-cell sm:px-6">
                     <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thông số chính</p>
                   </th>
                   <th class="px-5 py-3 text-left w-32 sm:px-6">
                     <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Trạng thái</p>
                   </th>
                   <th class="px-5 py-3 text-right w-24 sm:px-6">
                     <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
                   </th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-if="loading"><td colspan="5" class="text-center py-10 text-gray-500">Đang tải...</td></tr>
                <tr v-else v-for="item in equipment" :key="item.id" class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                   <td class="px-5 py-4 sm:px-6">
                     <p class="text-gray-500 text-theme-xs font-mono dark:text-gray-400">#{{ item.id }}</p>
                   </td>
                   <td class="px-5 py-4 sm:px-6">
                      <div class="flex items-center gap-3">
                         <div class="flex items-center justify-center w-10 h-10 shrink-0 rounded-lg bg-gray-100 dark:bg-gray-800 flex justify-center items-center">
                            <i class="bi bi-pc-display text-gray-500 dark:text-gray-400"></i>
                         </div>
                         <div>
                            <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ item.name }}</p>
                            <p class="block text-gray-500 text-theme-xs dark:text-gray-400">{{ item.category?.name }} <span class="italic font-mono ml-1">[{{ item.model }}]</span></p>
                         </div>
                      </div>
                   </td>
                   <td class="px-5 py-4 sm:px-6 hidden md:table-cell">
                      <div class="flex flex-wrap gap-1 max-w-xs">
                         <span v-for="(v, k) in Object.entries(item.spec || {}).slice(0,3).reduce((acc, [k,v]) => ({...acc, [k]:v}), {})" :key="k" 
                               class="rounded whitespace-nowrap bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            {{ k }}: {{ v }}
                         </span>
                         <span v-if="Object.keys(item.spec || {}).length > 3" class="text-theme-xs text-gray-400">...</span>
                      </div>
                   </td>
                   <td class="px-5 py-4 sm:px-6">
                      <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium" :class="[
                          item.status === 1 ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500' : '',
                          item.status === 0 ? 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500' : '',
                          item.status === 2 ? 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500' : ''
                      ]">
                         {{ getStatusText(item.status) }}
                      </span>
                   </td>
                   <td class="px-5 py-4 sm:px-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                         <button @click="$router.push(`/admin/equipment/edit/${item.id}`)" class="text-gray-400 hover:text-brand-500 transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                         <button @click="handleDelete(item.id)" class="text-gray-400 hover:text-error-500 transition-colors"><i class="bi bi-trash text-lg"></i></button>
                      </div>
                   </td>
                </tr>
                <tr v-if="!loading && equipment.length === 0">
                   <td colspan="5" class="text-center py-10 font-medium text-gray-500">Không tìm thấy thiết bị</td>
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
  </div>
</template>
