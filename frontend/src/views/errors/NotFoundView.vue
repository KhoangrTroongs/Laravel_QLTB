<script setup>
import { ArrowLeft, Ghost } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center space-y-8">
      <div class="relative inline-block">
        <div class="h-40 w-40 bg-primary/10 rounded-[3rem] flex items-center justify-center text-primary animate-bounce">
          <Ghost class="h-20 w-20" />
        </div>
        <div class="absolute -top-4 -right-4 h-12 w-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-2xl font-black text-gray-900 border border-gray-100">
          ?
        </div>
      </div>
      
      <div class="space-y-4">
        <h1 class="text-6xl font-black text-gray-900 tracking-tighter italic">404</h1>
        <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-widest italic">Trang không tồn tại</h2>
        <p class="text-gray-500 font-medium italic">Có vẻ như địa chỉ bạn đang truy cập đã bị thay đổi hoặc không còn tồn tại trên hệ thống.</p>
      </div>

      <button @click="router.push('/')" 
        class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 hover:bg-black text-white rounded-2xl font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-gray-900/20 transition-all">
        <ArrowLeft class="h-4 w-4" /> Quay lại trang chủ
      </button>
    </div>
  </div>
</template>
