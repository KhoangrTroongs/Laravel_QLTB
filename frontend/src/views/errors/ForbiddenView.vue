<script setup>
import { ShieldAlert, ArrowLeft } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center space-y-8">
      <div class="h-40 w-40 bg-red-100 rounded-[3rem] flex items-center justify-center text-red-500 mx-auto transform -rotate-6">
        <ShieldAlert class="h-20 w-20" />
      </div>
      
      <div class="space-y-4">
        <h1 class="text-6xl font-black text-gray-900 tracking-tighter italic">403</h1>
        <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-widest italic">Truy cập bị chặn</h2>
        <p class="text-gray-500 font-medium italic">Bạn không có đủ quyền hạn để truy cập vào khu vực này. Vui lòng liên hệ quản trị viên nếu có sự nhầm lẫn.</p>
      </div>

      <button @click="router.push('/')" 
        class="inline-flex items-center gap-3 px-8 py-4 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-red-500/20 transition-all">
        <ArrowLeft class="h-4 w-4" /> Quay lại an toàn
      </button>
    </div>
  </div>
</template>
