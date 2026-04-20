<script setup>
import { ref } from 'vue';
import axios from '@/api/axios';
import { Lock, Loader2, ArrowLeft, ShieldAlert } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const router = useRouter();
const loading = ref(false);
const form = ref({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const handleUpdatePassword = async () => {
  loading.value = true;
  try {
    await axios.put('/profile/password', form.value);
    Swal.fire('Thành công', 'Mật khẩu đã được đổi.', 'success');
    router.push({ name: 'profile' });
  } catch (error) {
    Swal.fire('Lỗi', error.response?.data?.message || 'Cập nhật thất bại.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="max-w-2xl mx-auto space-y-6 py-10">
    <button @click="router.back()" class="flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-bold uppercase text-[10px] tracking-widest italic px-4">
      <ArrowLeft class="h-4 w-4" /> Quay lại hồ sơ
    </button>

    <div class="bg-white rounded-[3.5rem] p-12 border border-gray-100 shadow-sm relative overflow-hidden">
      <div class="relative">
        <div class="flex flex-col items-center mb-10">
          <div class="h-16 w-16 bg-primary/10 rounded-[1.5rem] flex items-center justify-center text-primary mb-4 rotate-3">
            <Lock class="h-8 w-8" />
          </div>
          <h2 class="text-2xl font-black text-gray-900 italic uppercase tracking-tight">Đổi mật khẩu</h2>
          <p class="text-xs text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Nâng cao bảo mật tài khoản</p>
        </div>

        <form @submit.prevent="handleUpdatePassword" class="space-y-6">
          <div class="space-y-2">
            <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Mật khẩu hiện tại</label>
            <input v-model="form.current_password" required type="password"
              class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" 
              placeholder="••••••••" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Mật khẩu mới</label>
              <input v-model="form.password" required type="password"
                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" 
                placeholder="••••••••" />
            </div>
            <div class="space-y-2">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Xác nhận lại</label>
              <input v-model="form.password_confirmation" required type="password"
                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" 
                placeholder="••••••••" />
            </div>
          </div>

          <div class="pt-6">
             <div class="bg-gray-50 rounded-2xl p-4 flex gap-3 items-center mb-8">
                <ShieldAlert class="h-5 w-5 text-gray-400 flex-shrink-0" />
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight italic">Yêu cầu ít nhất 8 ký tự, bao gồm cả chữ và số để đảm bảo an toàn.</p>
             </div>

            <button type="submit" :disabled="loading"
              class="w-full py-5 bg-primary hover:bg-primary/90 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-primary/20 transition-all flex items-center justify-center gap-3">
              <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />
              <span>XÁC NHẬN ĐỔI MẬT KHẨU</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
