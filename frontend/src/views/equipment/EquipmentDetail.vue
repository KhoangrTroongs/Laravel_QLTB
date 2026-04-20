<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from '@/api/axios';
import { 
  ArrowLeft, 
  Monitor, 
  Calendar, 
  FileText, 
  Info, 
  CheckCircle2, 
  ShieldCheck, 
  Loader2,
  Clock
} from 'lucide-vue-next';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const item = ref(null);
const loading = ref(true);
const submitting = ref(false);

const borrowForm = ref({
  hantra: '',
  description: ''
});

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/equipment/${route.params.id}`);
    item.value = response.data;
  } catch (error) {
    console.error('Fetch error:', error);
    Swal.fire('Lỗi', 'Không tìm thấy thiết bị này.', 'error');
    router.push({ name: 'home' });
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const handleBorrow = async () => {
  submitting.value = true;
  try {
    await axios.post(`/equipment/${item.value.id}/borrow`, borrowForm.value);
    Swal.fire({
      title: 'Thành công!',
      text: 'Yêu cầu mượn thiết bị của bạn đã được gửi và đang chờ duyệt.',
      icon: 'success',
      confirmButtonText: 'Đóng',
      confirmButtonColor: '#3b82f6',
    });
    router.push({ name: 'my-borrows' });
  } catch (error) {
    Swal.fire('Lỗi', error.response?.data?.message || 'Có lỗi xảy ra khi gửi yêu cầu.', 'error');
  } finally {
    submitting.value = false;
  }
};
</script>

<template>
  <div class="space-y-6">
    <button @click="router.back()" class="flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium">
      <ArrowLeft class="h-4 w-4" /> Quay lại
    </button>

    <div v-if="loading" class="flex flex-col items-center justify-center py-20 gap-4">
      <Loader2 class="h-10 w-10 text-primary animate-spin" />
      <p class="text-gray-500 font-medium">Đang tải thông tin thiết bị...</p>
    </div>

    <div v-else-if="item" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Image and Info -->
      <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
          <div class="aspect-video relative bg-gray-50 flex items-center justify-center">
            <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
            <Monitor v-else class="h-32 w-32 text-gray-200" />
          </div>
          <div class="p-8">
            <div class="flex flex-wrap gap-2 mb-4">
              <span class="bg-primary/10 text-primary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                {{ item.category?.name || 'Phổ thông' }}
              </span>
              <span :class="[
                'text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest',
                item.available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'
              ]">
                {{ item.available ? 'Sẵn sàng' : 'Không khả dụng' }}
              </span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ item.name }}</h1>
            <p class="text-gray-500 font-medium mt-1">{{ item.model }}</p>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center gap-2">
                  <Info class="h-4 w-4 text-primary" /> Thông số kỹ thuật
                </h3>
                <ul class="space-y-2">
                  <li v-for="(val, key) in item.spec" :key="key" class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span class="text-gray-500 font-medium capitalize">{{ key.replace(/_/g, ' ') }}</span>
                    <span class="text-gray-900 font-bold">{{ val }}</span>
                  </li>
                  <li v-if="!item.spec || Object.keys(item.spec).length === 0" class="text-sm text-gray-400 italic">
                    Chưa cập nhật thông số...
                  </li>
                </ul>
              </div>
              
              <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center gap-2">
                  <FileText class="h-4 w-4 text-primary" /> Mô tả thiết bị
                </h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                  {{ item.description || 'Chưa có mô tả chi tiết cho thiết bị này.' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Borrow Form -->
      <div class="space-y-6">
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 sticky top-24">
          <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <ClipboardList class="h-5 w-5 text-primary" /> Mượn thiết bị
          </h2>

          <div v-if="item.is_borrowed" class="bg-orange-50 border border-orange-100 rounded-2xl p-4 mb-6">
            <div class="flex gap-3">
              <Clock class="h-5 w-5 text-orange-500 flex-shrink-0" />
              <div>
                <h4 class="text-sm font-bold text-orange-900">Thiết bị đang được mượn</h4>
                <p class="text-xs text-orange-700 mt-0.5">Hiện tại thiết bị này đang có nhân viên khác sử dụng. Bạn vẫn có thể gửi yêu cầu mượn, yêu cầu sẽ được xử lý khi thiết bị được trả.</p>
              </div>
            </div>
          </div>

          <form @submit.prevent="handleBorrow" class="space-y-5">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Hạn trả dự kiến</label>
              <div class="relative">
                <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                <input v-model="borrowForm.hantra" type="date" required
                  class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold" />
              </div>
              <p class="text-[10px] text-gray-400 mt-2 italic px-1">Gợi ý: Mặc định là 14 ngày kể từ khi duyệt.</p>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Ghi chú / Lý do mượn</label>
              <textarea v-model="borrowForm.description" rows="3"
                class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm"
                placeholder="Nêu rõ mục đích sử dụng máy..."></textarea>
            </div>

            <button type="submit" :disabled="submitting || !item.available"
              class="w-full bg-primary hover:bg-primary/90 text-white py-4 rounded-2xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary/20 disabled:grayscale disabled:opacity-50">
              <Loader2 v-if="submitting" class="animate-spin h-5 w-5 text-white" />
              <span v-else>Xác nhận mượn máy</span>
            </button>
            
            <div class="flex items-start gap-3 mt-4 px-2">
              <ShieldCheck class="h-4 w-4 text-gray-400 mt-1 flex-shrink-0" />
              <p class="text-[10px] text-gray-400">Bằng việc xác nhận, bạn đồng ý tuân thủ quy định sử dụng thiết bị của trung tâm và chịu trách nhiệm bảo quản máy.</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
