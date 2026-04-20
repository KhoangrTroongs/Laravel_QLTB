<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import axios from '@/api/axios';
import { 
  User, 
  Mail, 
  Phone, 
  MapPin, 
  Shield, 
  Camera, 
  Check, 
  Loader2,
  Lock
} from 'lucide-vue-next';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const avatarFile = ref(null);
const avatarPreview = ref(authStore.user?.avatar || null);
const loading = ref(false);

const form = ref({
  name: authStore.user?.name || '',
  email: authStore.user?.email || '',
  phone: authStore.user?.phone || '',
  address: authStore.user?.address || '',
});

const handleAvatarChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    avatarFile.value = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const handleUpdateProfile = async () => {
  loading.value = true;
  const formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('email', form.value.email);
  formData.append('phone', form.value.phone);
  formData.append('address', form.value.address);
  formData.append('_method', 'PUT');
  
  if (avatarFile.value) {
    formData.append('avatar', avatarFile.value);
  }

  try {
    const response = await axios.post('/profile', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    authStore.user = response.data.user;
    localStorage.setItem('user', JSON.stringify(authStore.user));
    Swal.fire('Thành công', 'Hồ sơ đã được cập nhật.', 'success');
  } catch (error) {
    Swal.fire('Lỗi', error.response?.data?.message || 'Cập nhật thất bại.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="max-w-4xl mx-auto space-y-10 py-10">
    <div class="flex flex-col items-center text-center space-y-4">
      <div class="relative group">
        <div class="h-40 w-40 rounded-[3rem] bg-gray-100 border-4 border-white shadow-2xl overflow-hidden relative">
          <img v-if="avatarPreview" :src="avatarPreview" class="h-full w-full object-cover" />
          <div v-else class="h-full w-full bg-primary/10 flex items-center justify-center">
            <User class="h-16 w-16 text-primary" />
          </div>
        </div>
        <label class="absolute bottom-2 right-2 h-12 w-12 bg-primary hover:bg-primary/90 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-lg transition-all border-4 border-white transform hover:scale-110">
          <Camera class="h-5 w-5" />
          <input type="file" @change="handleAvatarChange" class="absolute inset-0 opacity-0 cursor-pointer" />
        </label>
      </div>
      <div>
        <h1 class="text-3xl font-black text-gray-900 italic tracking-tight uppercase">{{ authStore.user?.name }}</h1>
        <p class="text-gray-400 font-bold text-xs uppercase tracking-[0.3em] mt-1">{{ authStore.user?.roles?.[0]?.name || 'Staff' }}</p>
      </div>
    </div>

    <div class="bg-white rounded-[3.5rem] p-12 border border-gray-100 shadow-sm relative overflow-hidden">
      <!-- Abstract Background -->
      <div class="absolute -bottom-20 -left-20 h-64 w-64 bg-primary/5 rounded-full blur-3xl"></div>
      
      <div class="relative">
        <h2 class="text-xl font-black text-gray-900 italic uppercase tracking-widest mb-10 flex text-center justify-center gap-3">
          <Shield class="h-6 w-6 text-primary" /> Thông tin cá nhân
        </h2>

        <form @submit.prevent="handleUpdateProfile" class="space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Họ và tên</label>
              <div class="relative">
                <User class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                <input v-model="form.name" required type="text"
                  class="w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" />
              </div>
            </div>

            <div class="space-y-3">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Email</label>
              <div class="relative opacity-60">
                <Mail class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                <input v-model="form.email" disabled type="email"
                  class="w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl outline-none font-bold text-gray-400 cursor-not-allowed" />
              </div>
            </div>

            <div class="space-y-3">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Số điện thoại</label>
              <div class="relative">
                <Phone class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                <input v-model="form.phone" type="text"
                  class="w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" />
              </div>
            </div>

            <div class="space-y-3">
              <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1 italic">Địa chỉ</label>
              <div class="relative">
                <MapPin class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                <input v-model="form.address" type="text"
                  class="w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all font-bold text-gray-900" />
              </div>
            </div>
          </div>

          <div class="pt-8 flex justify-center">
            <button type="submit" :disabled="loading"
              class="px-16 py-5 bg-gray-900 hover:bg-black text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-gray-900/20 transition-all flex items-center gap-3">
              <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />
              <span v-else>Cập nhật hồ sơ</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Password Change Section -->
    <div class="bg-white rounded-[3.5rem] p-12 border border-gray-100 shadow-sm text-center">
       <router-link :to="{ name: 'settings' }" class="group flex flex-col items-center">
          <div class="h-14 w-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:text-primary group-hover:bg-primary/10 transition-all mb-4">
            <Lock class="h-6 w-6" />
          </div>
          <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest italic group-hover:text-primary transition-all">Thay đổi mật khẩu bảo mật</h3>
          <p class="text-xs text-gray-400 font-medium mt-1">Nên cập nhật mật khẩu định kỳ 3 tháng/lần.</p>
       </router-link>
    </div>
  </div>
</template>
