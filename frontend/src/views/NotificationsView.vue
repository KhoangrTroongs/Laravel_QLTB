<script setup>
import { onMounted } from 'vue';
import { useNotificationStore } from '@/stores/notification';
import { 
  Bell, 
  Check, 
  CheckCircle2, 
  Clock, 
  RefreshCw, 
  Loader2,
  Inbox
} from 'lucide-vue-next';

const notificationStore = useNotificationStore();

onMounted(() => {
  notificationStore.fetchNotifications();
});
</script>

<template>
  <div class="max-w-4xl mx-auto space-y-8">
    <div class="flex justify-between items-end">
      <div>
        <h1 class="text-3xl font-extrabold text-gray-900 italic uppercase tracking-tight">Trung tâm thông báo</h1>
        <p class="text-gray-500 mt-1 font-medium italic underline decoration-blue-200">Luôn cập nhập tình trạng các yêu cầu và phản hồi từ hệ thống.</p>
      </div>
      <div class="flex gap-3">
        <button @click="notificationStore.markAllAsRead" 
          class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-100 rounded-xl text-xs font-black text-gray-500 hover:text-primary transition-all shadow-sm uppercase tracking-widest italic">
          <Check class="h-4 w-4" /> Đọc tất cả
        </button>
        <button @click="notificationStore.fetchNotifications" class="p-2.5 bg-white border border-gray-100 rounded-xl text-gray-400 hover:text-primary transition-all shadow-sm">
          <RefreshCw class="h-5 w-5" />
        </button>
      </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden min-h-[60vh] flex flex-col">
      <div v-if="notificationStore.notifications.length > 0" class="divide-y divide-gray-50">
        <div v-for="notif in notificationStore.notifications" :key="notif.id"
          @click="notificationStore.markAsRead(notif.id)"
          :class="['p-8 hover:bg-gray-50 transition-all cursor-pointer flex items-start gap-6 border-l-[6px]', 
            notif.is_read ? 'border-transparent' : 'border-primary bg-primary/5']">
          
          <div :class="['h-14 w-14 rounded-2xl flex items-center justify-center flex-shrink-0', 
            notif.is_read ? 'bg-gray-100 text-gray-400' : 'bg-primary/10 text-primary']">
            <Bell class="h-7 w-7" />
          </div>

          <div class="flex-1 space-y-2">
            <div class="flex justify-between items-start">
              <h3 :class="['text-lg tracking-tight italic', notif.is_read ? 'text-gray-600 font-bold' : 'text-gray-900 font-black']">
                {{ notif.data.message || 'Thông báo hệ thống' }}
              </h3>
              <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5 whitespace-nowrap">
                <Clock class="h-3 w-3" /> {{ notif.created_at }}
              </span>
            </div>
            <p v-if="notif.data.description" class="text-sm text-gray-500 font-medium leading-relaxed italic">
              "{{ notif.data.description }}"
            </p>
            <div class="pt-2 flex items-center gap-4">
               <span v-if="!notif.is_read" class="text-[9px] font-black text-primary bg-white px-2.5 py-1 rounded-md uppercase tracking-[0.2em] shadow-sm border border-primary/10 italic">
                  Tin mới
               </span>
               <router-link v-if="notif.data.action_url" :to="notif.data.action_url" 
                 class="text-[10px] font-black text-gray-400 hover:text-primary transition-all uppercase tracking-widest underline italic">
                 Chi tiết thao tác
               </router-link>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="flex-1 flex flex-col items-center justify-center p-20">
        <div class="h-24 w-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 text-gray-200">
           <Inbox class="h-12 w-12" />
        </div>
        <h3 class="text-xl font-black text-gray-900 italic tracking-tight uppercase">Hộp thư trống</h3>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Bạn đã xử lý hết mọi tin nhắn</p>
      </div>
    </div>
  </div>
</template>
