<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from '@/api/axios';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, Filler } from 'chart.js';
import { Bar, Line } from 'vue-chartjs';
import Swal from 'sweetalert2';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, Filler);

const stats = ref(null);
const recentActivities = ref([]);
const pendingRequests = ref([]);
const loading = ref(true);

const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, activityRes, queueRes] = await Promise.all([
      axios.get('/admin/dashboard/stats').catch(() => ({ data: {} })),
      axios.get('/admin/borrows?limit=5').catch(() => ({ data: { data: [] } })), // Mocking recent activities via borrows
      axios.get('/admin/borrows/queue').catch(() => ({ data: { data: [] } }))
    ]);
    stats.value = statsRes.data || { total_equipment: 0, borrowing_count: 0, pending_count: 0, overdue_count: 0 };
    recentActivities.value = activityRes.data.data.slice(0, 5); // Take top 5 recent borrows/returns as activities
    pendingRequests.value = queueRes.data.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const handleQuickAction = async (id, action) => {
  try {
    await axios.post(`/admin/borrows/${id}/${action}`);
    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Đã xử lý thành công', showConfirmButton: false, timer: 1500 });
    fetchData();
  } catch (error) {
    Swal.fire('Lỗi', 'Thao tác không thành công', 'error');
  }
};

const barData = computed(() => ({
  labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
  datasets: [{
    label: 'Lượt mượn',
    backgroundColor: '#3C50E0',
    borderRadius: 4,
    data: [12, 19, 3, 5, 2, 3, 15],
  }]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false }
  },
  scales: {
    x: { grid: { display: false } },
    y: { grid: { color: '#E2E8F0', drawBorder: false } }
  }
};

const getStatusColor = (statusText) => {
  if (statusText?.includes('mượn')) return 'text-primary bg-primary/10';
  if (statusText?.includes('chờ')) return 'text-warning bg-warning/10';
  if (statusText?.includes('trả')) return 'text-success bg-success/10';
  return 'text-danger bg-danger/10';
};
</script>

<template>
  <div>
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
    </div>

    <div v-else-if="stats">
      <!-- Top Cards -->
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
        
        <!-- Card 1 -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i class="bi bi-laptop text-primary text-xl"></i>
          </div>
          <div class="mt-4 flex items-end justify-between">
            <div>
              <h4 class="text-title-md font-bold text-black dark:text-white">{{ stats.total_equipment }}</h4>
              <span class="text-sm font-medium text-slate-500">Tổng thiết bị</span>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i class="bi bi-arrow-left-right text-primary text-xl"></i>
          </div>
          <div class="mt-4 flex items-end justify-between">
            <div>
              <h4 class="text-title-md font-bold text-black dark:text-white">{{ stats.borrowing_count }}</h4>
              <span class="text-sm font-medium text-slate-500">Đang lưu hành</span>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark relative overflow-hidden">
          <div class="absolute right-0 top-0 h-full w-2 bg-warning"></div>
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-warning/20">
            <i class="bi bi-clock-history text-warning text-xl"></i>
          </div>
          <div class="mt-4 flex items-end justify-between">
             <div>
               <h4 class="text-title-md font-bold text-black dark:text-white">{{ stats.pending_count }}</h4>
               <span class="text-sm font-medium text-slate-500">Chờ phê duyệt</span>
             </div>
             <router-link to="/admin/borrows/queue" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                Chi tiết <i class="bi bi-arrow-right"></i>
             </router-link>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-danger/20">
            <i class="bi bi-exclamation-triangle-fill text-danger text-xl"></i>
          </div>
          <div class="mt-4 flex items-end justify-between">
            <div>
              <h4 class="text-title-md font-bold text-black dark:text-white">{{ stats.overdue_count }}</h4>
              <span class="text-sm font-medium text-slate-500">Cảnh báo quá hạn</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Charts & Lists -->
      <div class="mt-4 grid grid-cols-1 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5 xl:grid-cols-3">
         
         <!-- Chart -->
         <div class="xl:col-span-2 rounded-sm border border-stroke bg-white px-5 pt-7 pb-5 shadow-default sm:px-7.5">
            <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
               <div>
                  <h4 class="text-xl font-bold text-black">Xu hướng mượn trả thiết bị</h4>
                  <p class="text-sm font-medium text-slate-500">Dữ liệu theo tuần</p>
               </div>
            </div>
            <div class="mt-6 h-[300px] w-full">
               <Bar :data="barData" :options="chartOptions" />
            </div>
         </div>

         <!-- Quick Approval List -->
         <div class="xl:col-span-1 rounded-sm border border-stroke bg-white py-6 px-7.5 shadow-default">
            <h4 class="mb-6 px-2 text-xl font-bold text-black">Chờ duyệt gấp</h4>
            <div v-if="pendingRequests.length > 0" class="flex flex-col gap-4">
               <div v-for="req in pendingRequests.slice(0, 4)" :key="req.id" class="flex items-center gap-4 rounded-md border border-stroke p-3 bg-slate-50">
                  <div class="flex-grow">
                     <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold text-black tracking-tight text-sm">{{ req.user.name }}</span>
                        <span class="text-xs text-slate-500">{{ req.created_at }}</span>
                     </div>
                     <p class="text-sm font-medium text-primary">{{ req.equipment.name }}</p>
                     
                     <div class="mt-3 flex gap-2 w-full">
                        <button @click="handleQuickAction(req.id, 'reject')" class="flex-1 rounded border border-danger py-1 px-2 text-center font-medium text-danger hover:bg-danger hover:text-white transition text-xs">
                           Từ chối
                        </button>
                        <button @click="handleQuickAction(req.id, 'approve')" class="flex-1 rounded bg-primary py-1 px-2 text-center font-medium text-white hover:bg-opacity-90 transition text-xs">
                           Duyệt
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div v-else class="py-10 text-center text-slate-500 text-sm">
               Hoan hô! Tất cả yêu cầu đã được xử lý.
            </div>
         </div>

      </div>

      <!-- Recent Log Table -->
      <div class="mt-4 md:mt-6 2xl:mt-7.5">
         <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2 shadow-default sm:px-7.5 xl:pb-1">
            <h4 class="mb-6 text-xl font-bold text-black">Luồng hoạt động mới nhất</h4>
            
            <div class="max-w-full overflow-x-auto">
               <table class="w-full table-auto">
                  <thead>
                     <tr class="bg-gray-2 text-left dark:bg-meta-4 border-b border-stroke bg-slate-50">
                        <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white xl:pl-0">Nhân viên</th>
                        <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white">Thiết bị mượn</th>
                        <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Trạng thái</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-right xl:pr-0">Thời gian ghi nhận</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(act, idx) in recentActivities" :key="act.id" :class="idx === recentActivities.length - 1 ? '' : 'border-b border-stroke'">
                        <td class="py-4 px-4 xl:pl-0">
                           <div class="flex items-center gap-3">
                              <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600">
                                 <i class="bi bi-person"></i>
                              </div>
                              <p class="font-medium text-black dark:text-white">{{ act.user.name }}</p>
                           </div>
                        </td>
                        <td class="py-4 px-4">
                           <p class="text-sm font-medium font-sans text-primary">{{ act.equipment.name }}</p>
                        </td>
                        <td class="py-4 px-4 flex items-center h-full">
                           <p class="inline-flex rounded-full bg-opacity-10 py-1 px-3 text-sm font-medium border" :class="getStatusColor(act.status_label)">
                              {{ act.status_label }}
                           </p>
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-0">
                           <p class="text-sm font-medium text-slate-500">{{ act.created_at }}</p>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            
            <div class="py-4 text-center border-t border-stroke mt-4">
               <router-link to="/admin/borrows" class="text-primary hover:text-primary-dark font-medium text-sm transition-colors">Xem toàn bộ lịch sử</router-link>
            </div>
         </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.shadow-default {
  box-shadow: 0px 8px 13px -3px rgba(0, 0, 0, 0.07);
}
.px-7\.5 {
  padding-left: 1.875rem;
  padding-right: 1.875rem;
}
.text-title-md {
  font-size: 1.5rem;
  line-height: 2rem;
}
</style>
