<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/api/axios';
import { 
  Monitor, 
  Calendar, 
  Clock, 
  CheckCircle2, 
  XCircle, 
  AlertCircle, 
  ChevronRight,
  Loader2,
  RefreshCw
} from 'lucide-vue-next';

const borrows = ref([]);
const loading = ref(true);

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/my-borrows');
    borrows.value = response.data.data;
  } catch (error) {
    console.error('Fetch error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const getStatusClass = (status) => {
  switch (status) {
    case 0: return 'bg-blue-50 text-blue-600 border-blue-100'; // Chờ duyệt
    case 1: return 'bg-green-50 text-green-600 border-green-100'; // Đang mượn
    case 2: return 'bg-red-50 text-red-600 border-red-100'; // Từ chối
    case 3: return 'bg-gray-100 text-gray-500 border-gray-200'; // Đã trả
    default: return 'bg-gray-50 text-gray-400';
  }
};

const getStatusIcon = (status) => {
  switch (status) {
    case 0: return Clock;
    case 1: return RefreshCw;
    case 2: return XCircle;
    case 3: return CheckCircle2;
    default: return AlertCircle;
  }
};
</script>

<template>
  <div class="space-y-6">
    <div class="flex justify-between items-end">
      <div>
        <h1 class="text-3xl font-extrabold text-gray-900">Thiết bị của tôi</h1>
        <p class="text-gray-500 mt-1 font-medium">Theo dõi các yêu cầu mượn và lịch sử sử dụng thiết bị.</p>
      </div>
      <button @click="fetchData" class="p-2 text-gray-400 hover:text-primary transition-colors">
        <RefreshCw :class="['h-5 w-5', loading ? 'animate-spin' : '']" />
      </button>
    </div>

    <div v-if="loading" class="flex flex-col items-center justify-center py-20 gap-4">
      <Loader2 class="h-10 w-10 text-primary animate-spin" />
      <p class="text-gray-500 font-medium">Đang tải lịch sử mượn máy...</p>
    </div>

    <div v-else-if="borrows.length > 0" class="grid grid-cols-1 gap-4">
      <div v-for="record in borrows" :key="record.id" 
        class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row items-start md:items-center gap-6">
        
        <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-gray-100">
          <img v-if="record.equipment.image" :src="record.equipment.image" class="h-full w-full object-cover rounded-2xl" />
          <Monitor v-else class="h-8 w-8 text-gray-300" />
        </div>

        <div class="flex-1 space-y-1">
          <div class="flex items-center gap-2">
            <h3 class="font-bold text-gray-900 text-lg">{{ record.equipment.name }}</h3>
            <span :class="['px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border', getStatusClass(record.status)]">
              {{ record.status_label }}
            </span>
          </div>
          <p class="text-sm text-gray-500 font-medium">{{ record.equipment.model }}</p>
          <div class="flex flex-wrap gap-x-6 gap-y-2 mt-2">
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
              <Calendar class="h-3.5 w-3.5" />
              <span>Ngày mượn: <b class="text-gray-600">{{ record.ngaymuon }}</b></span>
            </div>
            <div v-if="record.hantra" class="flex items-center gap-1.5 text-xs text-gray-400">
              <Clock class="h-3.5 w-3.5" />
              <span>Hạn trả: <b :class="[record.is_overdue ? 'text-red-500' : 'text-gray-600']">{{ record.hantra }}</b></span>
            </div>
            <div v-if="record.ngaytra" class="flex items-center gap-1.5 text-xs text-gray-400">
              <CheckCircle2 class="h-3.5 w-3.5" />
              <span>Ngày trả: <b class="text-gray-600">{{ record.ngaytra }}</b></span>
            </div>
          </div>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
          <router-link :to="{ name: 'equipment-detail', params: { id: record.equipment.id } }"
            class="flex-1 md:flex-none px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl text-xs font-bold transition-all border border-gray-100 text-center uppercase tracking-widest">
            Xem máy
          </router-link>
          <button v-if="record.status === 0" 
            class="flex-1 md:flex-none px-4 py-2 text-red-500 hover:bg-red-50 rounded-xl text-xs font-bold transition-all border border-red-100 uppercase tracking-widest">
            Hủy yêu cầu
          </button>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
      <div class="mx-auto h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
        <History class="h-10 w-10 text-gray-300" />
      </div>
      <h3 class="text-lg font-bold text-gray-900">Bạn chưa mượn thiết bị nào</h3>
      <p class="text-gray-500 mt-1 max-w-sm mx-auto px-4">Hãy quay lại trang chủ để khám phá các thiết bị sẵn có.</p>
      <router-link to="/" class="mt-6 inline-block bg-primary text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-primary/20 hover:bg-primary/90">
        Mượn máy ngay
      </router-link>
    </div>
  </div>
</template>
