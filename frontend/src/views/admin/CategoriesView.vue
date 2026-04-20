<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const categories = ref([]);
const loading = ref(true);

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/admin/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const handleDelete = async (id) => {
  const result = await Swal.fire({
    title: 'Xóa danh mục?',
    text: "Mọi thiết bị thuộc nhóm này cũng sẽ bị ảnh hưởng.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0d6efd',
    confirmButtonText: 'Đồng ý'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/admin/categories/${id}`);
      Swal.fire('Thành công', 'Đã xóa danh mục.', 'success');
      fetchData();
    } catch (e) {}
  }
};
</script>

<template>
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">Danh Mục Thiết Bị</h2>
    <button @click="$router.push('/admin/categories/create')" class="inline-flex items-center justify-center gap-2.5 rounded-lg bg-brand-500 py-2.5 px-6 text-center font-medium text-white hover:bg-brand-600 transition-colors">
       <i class="bi bi-folder-plus text-lg"></i> Thêm mới
    </button>
  </div>

  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-dark">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/[0.03]">
            <th class="px-5 py-3 text-left w-16 sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">ID</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tên danh mục / Nhóm</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Mô tả chi tiết</p>
            </th>
            <th class="px-5 py-3 text-center sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">S.Lượng</p>
            </th>
            <th class="px-5 py-3 text-left sm:px-6">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Metadata</p>
            </th>
            <th class="px-5 py-3 text-right sm:px-6 w-24">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="loading"><td colspan="6" class="text-center py-10 text-gray-500">Đang tải...</td></tr>
          <tr v-else v-for="cat in categories" :key="cat.id" class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-xs font-mono dark:text-gray-400">#{{ cat.id }}</p>
             </td>
             <td class="px-5 py-4 sm:px-6 flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                   <i class="bi bi-collection-fill"></i>
                </div>
                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90 uppercase">{{ cat.name }}</span>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400 truncate max-w-xs">{{ cat.description || 'N/A' }}</p>
             </td>
             <td class="px-5 py-4 sm:px-6 text-center">
                <span class="rounded-full bg-brand-50 px-2 py-0.5 text-brand-700 text-theme-xs font-medium dark:bg-brand-500/15 dark:text-brand-400">
                   {{ cat.equipment_count || 0 }}
                </span>
             </td>
             <td class="px-5 py-4 sm:px-6">
                <div class="flex flex-wrap gap-1">
                   <span v-for="f in cat.spec_fields" :key="f" class="rounded whitespace-nowrap bg-gray-100 border border-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                      {{ f }}
                   </span>
                </div>
             </td>
             <td class="px-5 py-4 sm:px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                   <button @click="$router.push(`/admin/categories/edit/${cat.id}`)" class="text-gray-400 hover:text-brand-500 transition-colors">
                      <i class="bi bi-pencil-square text-lg"></i>
                   </button>
                   <button @click="handleDelete(cat.id)" class="text-gray-400 hover:text-error-500 transition-colors">
                      <i class="bi bi-trash text-lg"></i>
                   </button>
                </div>
             </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
