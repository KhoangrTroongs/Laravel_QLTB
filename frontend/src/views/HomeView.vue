<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const categories = ref([]);
const equipment = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const selectedCategory = ref('all');
const authStore = useAuthStore();

const fetchData = async () => {
  loading.value = true;
  try {
    const params = {
      search: searchQuery.value,
      category: selectedCategory.value === 'all' ? '' : selectedCategory.value
    };
    const [catRes, eqRes] = await Promise.all([
      axios.get('/categories'),
      axios.get('/equipment/available', { params })
    ]);
    categories.value = catRes.data;
    equipment.value = eqRes.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

watch([selectedCategory], fetchData);
let timeout;
watch(searchQuery, () => {
  clearTimeout(timeout);
  timeout = setTimeout(fetchData, 500);
});

const handleBorrow = async (item) => {
  if (!authStore.isAuthenticated) {
     Swal.fire({
        title: 'Yêu cầu đăng nhập',
        text: 'Vui lòng đăng nhập để mượn thiết bị.',
        icon: 'warning',
        confirmButtonColor: '#3C50E0'
     });
     return;
  }

  const { value: days } = await Swal.fire({
    title: 'Đăng ký mượn máy',
    html: `Bạn đang chọn: <b class="text-primary">${item.name}</b><br><small class="text-slate-500">Chọn số ngày mượn dự kiến (tối đa 30 ngày)</small>`,
    input: 'number',
    inputValue: 3,
    inputAttributes: { min: 1, max: 30, step: 1 },
    showCancelButton: true,
    confirmButtonText: 'Gửi yêu cầu',
    confirmButtonColor: '#3C50E0',
    cancelButtonText: 'Hủy',
    inputValidator: (value) => {
      if (!value || value < 1) return 'Số ngày không hợp lệ!';
      if (value > 30) return 'Tối đa 30 ngày!';
    }
  });

  if (days) {
    try {
      await axios.post(`/equipment/${item.id}/borrow`, {
        days: parseInt(days)
      });
      Swal.fire({ icon: 'success', title: 'Đã gửi yêu cầu', text: 'Vui lòng chờ quản trị viên phê duyệt.', timer: 2000, showConfirmButton: false });
      fetchData();
    } catch (error) {
      Swal.fire('Lỗi', error.response?.data?.message || 'Không thể thực hiện yêu cầu', 'error');
    }
  }
};
</script>

<template>
  <div class="w-full">
    <!-- Hero Section using Tailwind -->
    <div class="bg-white border flex flex-col items-center justify-center border-slate-200 rounded-2xl shadow-sm text-center mb-8 px-6 py-12 lg:py-16 relative overflow-hidden">
       <!-- Abstract background element -->
       <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-primary/5 blur-3xl"></div>
       <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-secondary/10 blur-3xl"></div>
       
       <div class="relative z-10 w-full max-w-3xl">
          <h1 class="text-3xl md:text-5xl font-extrabold text-slate-800 tracking-tight mb-4">
             Hệ thống <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-indigo-600">Đăng Ký Thiết Bị</span>
          </h1>
          <p class="text-base md:text-lg text-slate-500 mb-8 max-w-2xl mx-auto leading-relaxed">
             Dễ dàng tìm kiếm, tra cứu cấu hình và yêu cầu cấp phát các trang thiết bị CNTT phục vụ cho công việc dự án một cách nhanh chóng.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
             <router-link to="/my-borrows" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-semibold rounded-lg text-white bg-primary hover:bg-primary-dark transition-colors shadow-sm">
                <i class="bi bi-clock-history mr-2"></i> Lịch sử Mượn 
             </router-link>
             <a href="#browse" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-slate-300 text-base font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                Khám phá kho thiết bị
             </a>
          </div>
       </div>
    </div>

    <!-- Filters Section -->
    <div id="browse" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
       <!-- Search -->
       <div class="relative w-full md:w-96">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
             <i class="bi bi-search text-slate-400"></i>
          </div>
          <input v-model="searchQuery" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm" placeholder="Tìm tên máy, mã số, cấu hình...">
       </div>
       
       <!-- Categories pills -->
       <div class="w-full md:w-auto overflow-x-auto no-scrollbar pb-1">
          <div class="flex items-center gap-2">
             <button @click="selectedCategory = 'all'" 
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                :class="selectedCategory === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                Tất cả thiết bị
             </button>
             <button v-for="cat in categories" :key="cat.id" 
                @click="selectedCategory = cat.id"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                :class="selectedCategory === cat.id ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                {{ cat.name }}
             </button>
          </div>
       </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-20">
       <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin mb-4"></div>
       <p class="text-slate-500 font-medium animate-pulse">Đang nạp dữ liệu thiết bị...</p>
    </div>

    <!-- Equipment Grid -->
    <div v-else-if="equipment.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
       <div v-for="item in equipment" :key="item.id" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
          <!-- Card Header (Category & Status) -->
          <div class="px-5 pt-5 pb-3 flex justify-between items-center bg-white border-b border-slate-100">
             <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold leading-5 bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                {{ item.category?.name || 'Vật tư' }}
             </span>
             <span class="inline-flex items-center text-primary bg-primary/10 w-8 h-8 rounded-full justify-center">
                <i class="bi bi-laptop"></i>
             </span>
          </div>

          <!-- Main Info -->
          <div class="p-5 flex-grow">
             <h3 class="text-lg font-bold text-slate-900 group-hover:text-primary transition-colors line-clamp-1 mb-1">{{ item.name }}</h3>
             <p class="text-sm font-mono text-slate-500 mb-4">{{ item.model || 'N/A' }}</p>

             <!-- Specs -->
             <div class="bg-slate-50 rounded-lg p-3 space-y-2 mb-4 border border-slate-100">
                <div v-for="(v, k) in Object.entries(item.specs || {}).slice(0, 3).reduce((a, [k,v]) => ({...a, [k]:v}), {})" :key="k" class="flex justify-between items-center text-sm border-b border-slate-200 last:border-0 pb-1 last:pb-0">
                   <span class="text-slate-500 pr-2 truncate shrink-0 max-w-[40%]">{{ k }}:</span>
                   <span class="text-slate-800 font-medium truncate text-right">{{ v }}</span>
                </div>
                <div v-if="!item.specs || Object.keys(item.specs).length === 0" class="text-sm text-slate-400 italic text-center py-2">
                   Không có thông tin chi tiết
                </div>
             </div>
          </div>

          <!-- Card Footer (Action) -->
          <div class="px-5 pb-5 pt-0 mt-auto">
             <button @click="handleBorrow(item)" class="w-full flex items-center justify-center gap-2 bg-slate-50 hover:bg-primary border border-slate-200 text-primary hover:text-white hover:border-primary px-4 py-2.5 rounded-lg font-bold transition-all duration-200">
                <i class="bi bi-plus-circle"></i> Đăng ký mượn ngay
             </button>
          </div>
       </div>
    </div>

    <!-- Empty State -->
    <div v-else class="bg-white rounded-xl border border-slate-200 border-dashed py-24 flex flex-col items-center justify-center text-center">
       <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
          <i class="bi bi-inbox text-4xl text-slate-400"></i>
       </div>
       <h3 class="text-xl font-bold text-slate-800 mb-2">Không tìm thấy thiết bị</h3>
       <p class="text-slate-500 max-w-md">Rất tiếc không có thiết bị nào khớp với tiêu chí tìm kiếm của bạn. Hãy thử thay đổi từ khóa hoặc bộ lọc danh mục.</p>
       <button @click="searchQuery = ''; selectedCategory = 'all'" class="mt-6 text-primary font-medium hover:underline">
          Xóa bộ lọc
       </button>
    </div>
  </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>
