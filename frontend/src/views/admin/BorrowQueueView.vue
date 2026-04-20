<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/api/axios';
import Swal from 'sweetalert2';

const queue = ref([]);
const loading = ref(true);
const config = ref({
   autoApprove: false,
   requireApproval: true
});

const fetchData = async () => {
  loading.value = true;
  try {
    const queueRes = await axios.get('/admin/borrows/queue');
    queue.value = queueRes.data.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const handleAction = async (id, action) => {
  try {
    await axios.post(`/admin/borrows/${id}/${action}`);
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: action === 'approve' ? 'Đã duyệt phiếu' : 'Đã từ chối',
      showConfirmButton: false,
      timer: 1500
    });
    fetchData();
  } catch (error) {
    Swal.fire('Lỗi', 'Thao tác không thành công', 'error');
  }
};

const handleBulkApprove = async () => {
    if(queue.value.length === 0) return;
    
    const result = await Swal.fire({
        title: 'Duyệt hàng loạt?',
        text: `Bạn chuẩn bị duyệt ${queue.value.length} yêu cầu đang chờ.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3C50E0',
        confirmButtonText: 'Đồng ý duyệt tất cả'
    });
    
    if(result.isConfirmed) {
        Swal.fire('Thông báo', 'Tính năng đang được nâng cấp.', 'info');
    }
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-black dark:text-white">Duyệt mượn máy</h2>
      
      <nav>
        <ol class="flex items-center gap-2">
          <li><router-link class="font-medium hover:text-primary" to="/admin">Dashboard /</router-link></li>
          <li class="font-medium text-primary">Queue</li>
        </ol>
      </nav>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6 2xl:gap-7.5">
       <!-- Config Panel -->
       <div class="col-span-1 rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default sm:px-7.5 xl:pb-6">
          <h4 class="mb-6 text-xl font-bold text-black dark:text-white">Cấu hình xét duyệt</h4>
          
          <div class="flex flex-col gap-5.5 p-6.5">
             <div>
                <label class="mb-3 block text-sm font-medium text-black dark:text-white">Chế độ tự động</label>
                <div class="flex items-center gap-2">
                   <input type="checkbox" v-model="config.autoApprove" class="w-4 h-4 rounded border-stroke text-primary focus:ring-primary">
                   <span class="text-sm">Tự động duyệt mọi yêu cầu</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Khuyến cáo: Chỉ nên bật trong thời gian bảo trì.</p>
             </div>
             
             <hr class="border-stroke">
             
             <button @click="handleBulkApprove" :disabled="queue.length === 0" class="flex w-full justify-center rounded bg-primary p-3 font-medium text-white hover:bg-opacity-90 disabled:opacity-50">
               Duyệt tất cả {{ queue.length }} phiếu
             </button>
          </div>
       </div>

       <!-- Queue List -->
       <div class="col-span-1 md:col-span-2 rounded-sm border border-stroke bg-white shadow-default">
          <div class="border-b border-stroke py-4 px-6.5">
             <h3 class="font-semibold text-black dark:text-white flex items-center gap-2">
                <i class="bi bi-inboxes text-primary"></i> Danh sách chờ ({{ queue.length }})
             </h3>
          </div>
          
          <div class="p-6.5">
             <div v-if="loading" class="flex justify-center py-10"><div class="spinner"></div></div>
             
             <div v-else-if="queue.length > 0" class="flex flex-col gap-4">
                <div v-for="req in queue" :key="req.id" class="rounded-lg border border-stroke bg-slate-50 p-4 transition hover:shadow-md">
                   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                      
                      <!-- User & Eq Info -->
                      <div class="flex items-start gap-4">
                         <div class="h-12 w-12 rounded-full overflow-hidden bg-white border border-stroke flex items-center justify-center shadow-sm shrink-0">
                            <img v-if="req.user?.avatar" :src="req.user.avatar" class="w-full h-full object-cover">
                            <i v-else class="bi bi-person-fill text-2xl text-slate-400"></i>
                         </div>
                         <div>
                            <h5 class="font-bold text-black text-lg">{{ req.user?.name }}</h5>
                            <p class="text-xs text-slate-500 mb-1">Thời gian tạo: {{ req.created_at }}</p>
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-primary/10 px-2.5 py-1 text-sm font-semibold text-primary">
                               <i class="bi bi-laptop"></i> {{ req.equipment?.name }}
                            </span>
                            <span class="text-xs ml-2 text-slate-500 italic">{{ req.equipment?.model }}</span>
                         </div>
                      </div>
                      
                      <!-- Actions -->
                      <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                         <button @click="handleAction(req.id, 'reject')" class="flex-1 sm:flex-none justify-center rounded border border-danger p-2 px-6 font-medium text-danger hover:bg-danger hover:text-white transition">
                            <i class="bi bi-x-lg mr-1"></i> Từ chối
                         </button>
                         <button @click="handleAction(req.id, 'approve')" class="flex-1 sm:flex-none justify-center rounded border border-primary bg-primary p-2 px-6 font-medium text-white hover:bg-opacity-90 transition shadow-sm">
                            <i class="bi bi-check-lg mr-1"></i> Phê duyệt
                         </button>
                      </div>
                   </div>
                   
                   <!-- Extra details -->
                   <div class="mt-4 border-t border-stroke pt-3 text-sm text-slate-600 flex gap-6">
                      <p><i class="bi bi-calendar-event opacity-50 mr-1"></i> Số ngày mượn: <span class="font-bold">Dự kiến 3 ngày</span></p>
                      <p><i class="bi bi-diagram-3 opacity-50 mr-1"></i> Báo cáo bộ phận: <span class="font-bold">Nhân sự</span></p>
                   </div>
                </div>
             </div>
             
             <!-- Empty state -->
             <div v-else class="flex flex-col items-center justify-center py-16 text-center">
                <div class="h-20 w-20 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                   <i class="bi bi-check2-circle text-4xl text-primary"></i>
                </div>
                <h4 class="text-lg font-bold text-black">Hàng chờ trống</h4>
                <p class="text-slate-500 text-sm mt-1">Tuyệt vời, tất cả các yêu cầu đã được xử lý.</p>
             </div>
          </div>
       </div>
    </div>
  </div>
</template>

<style scoped>
.shadow-default { box-shadow: 0px 8px 13px -3px rgba(0, 0, 0, 0.07); }
</style>
