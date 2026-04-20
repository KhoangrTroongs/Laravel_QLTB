<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/api/axios';
import { useRouter } from 'vue-router';
import { 
  UserPlus, 
  Search, 
  Monitor, 
  User as UserIcon, 
  Calendar, 
  CheckCircle2, 
  ArrowRight,
  ChevronLeft,
  Filter,
  PackageCheck
} from 'lucide-vue-next';
import Swal from 'sweetalert2';

const router = useRouter();
const users = ref([]);
const equipment = ref([]);
const loading = ref(false);

const searchQueryUser = ref('');
const searchQueryEq = ref('');

const selectedUser = ref(null);
const selectedEq = ref(null);
const form = ref({
  hantra: '',
  description: ''
});

const searchUsers = async () => {
    if (!searchQueryUser.value) return;
    try {
        const res = await axios.get('/admin/users', { params: { search: searchQueryUser.value } });
        users.value = res.data.data;
    } catch (e) {}
};

const searchEquipment = async () => {
    try {
        const res = await axios.get('/admin/equipment/available', { params: { search: searchQueryEq.value } });
        equipment.value = res.data.data;
    } catch (e) {}
};

onMounted(() => {
    searchEquipment();
});

watch(searchQueryUser, (val) => {
    if (val.length > 2) searchUsers();
});

watch(searchQueryEq, (val) => {
    searchEquipment();
});

const handleSubmit = async () => {
    if (!selectedUser.value || !selectedEq.value) {
        Swal.fire('Thiếu thông tin', 'Vui lòng chọn nhân viên và thiết bị.', 'warning');
        return;
    }

    try {
        await axios.post('/admin/borrows', {
            user_id: selectedUser.value.id,
            equipment_id: selectedEq.value.id,
            hantra: form.value.hantra,
            description: form.value.description
        });
        Swal.fire('Thành công', 'Đã tạo phiếu mượn thiết bị.', 'success');
        router.push('/admin/borrows');
    } catch (error) {
        Swal.fire('Lỗi', error.response?.data?.message || 'Có lỗi xảy ra.', 'error');
    }
};
</script>

<template>
  <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-500">
    <div class="flex items-center gap-4">
       <button @click="$router.back()" class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
          <ChevronLeft class="h-5 w-5 text-slate-400" />
       </button>
       <div>
          <h2 class="text-2xl font-black text-slate-900 tracking-tight italic uppercase">Khởi tạo phiếu mượn mới</h2>
          <p class="text-xs text-slate-400 font-bold mt-1">Cấp phát thiết bị cho nhân viên một cách trực tiếp.</p>
       </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <!-- Step 1: Select User -->
       <div class="space-y-4">
          <div :class="['modern-card p-6 border-2 transition-all', selectedUser ? 'border-emerald-500 bg-emerald-50/10' : 'border-transparent']">
             <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 italic flex items-center gap-2">
                <UserIcon class="h-4 w-4" /> BƯỚC 1: CHỌN NHÂN VIÊN
             </h3>
             
             <div class="relative group mb-4">
                <Search class="absolute left-4 top-3.5 h-4 w-4 text-slate-300 group-focus-within:text-primary transition-all" />
                <input v-model="searchQueryUser" type="text" placeholder="Tìm tên hoặc email nhân sự..." class="modern-input pl-12 py-3.5" />
             </div>

             <div class="space-y-2 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                <div v-for="user in users" :key="user.id" 
                  @click="selectedUser = user"
                  :class="['p-4 rounded-2xl border cursor-pointer transition-all flex items-center justify-between group', 
                    selectedUser?.id === user.id ? 'bg-white border-emerald-500 shadow-lg shadow-emerald-100' : 'bg-slate-50 border-slate-100 hover:border-slate-300']">
                   <div class="flex items-center gap-3">
                      <div class="h-10 w-10 bg-slate-900 rounded-xl flex items-center justify-center text-white text-xs font-black">
                         {{ user.name.charAt(0) }}
                      </div>
                      <div>
                         <p class="text-xs font-black text-slate-900 italic">{{ user.name }}</p>
                         <p class="text-[10px] text-slate-400 uppercase font-bold">{{ user.employee_id }}</p>
                      </div>
                   </div>
                   <CheckCircle2 v-if="selectedUser?.id === user.id" class="h-5 w-5 text-emerald-500 animate-in zoom-in" />
                   <ArrowRight v-else class="h-4 w-4 text-slate-300 opacity-0 group-hover:opacity-100 transition-all" />
                </div>
             </div>
          </div>
       </div>

       <!-- Step 2: Select Equipment -->
       <div class="space-y-4">
          <div :class="['modern-card p-6 border-2 transition-all', selectedEq ? 'border-indigo-500 bg-indigo-50/10' : 'border-transparent']">
             <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 italic flex items-center gap-2">
                <Monitor class="h-4 w-4" /> BƯỚC 2: CHỌN THIẾT BỊ SẴN CÓ
             </h3>

             <div class="relative group mb-4">
                <Search class="absolute left-4 top-3.5 h-4 w-4 text-slate-300 group-focus-within:text-primary transition-all" />
                <input v-model="searchQueryEq" type="text" placeholder="Tìm theo tên máy hoặc model..." class="modern-input pl-12 py-3.5" />
             </div>

             <div class="space-y-2 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                <div v-for="eq in equipment" :key="eq.id" 
                  @click="selectedEq = eq"
                  :class="['p-4 rounded-2xl border cursor-pointer transition-all flex items-center justify-between group', 
                    selectedEq?.id === eq.id ? 'bg-white border-indigo-500 shadow-lg shadow-indigo-100' : 'bg-slate-50 border-slate-100 hover:border-slate-300']">
                   <div class="flex items-center gap-3">
                      <div class="h-10 w-10 bg-indigo-50 text-primary rounded-xl flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-100 transition-colors">
                         <Monitor class="h-5 w-5" />
                      </div>
                      <div>
                         <p class="text-xs font-black text-slate-900 italic">{{ eq.name }}</p>
                         <p class="text-[10px] text-slate-400 uppercase font-bold">{{ eq.model }}</p>
                      </div>
                   </div>
                   <CheckCircle2 v-if="selectedEq?.id === eq.id" class="h-5 w-5 text-indigo-500 animate-in zoom-in" />
                   <ArrowRight v-else class="h-4 w-4 text-slate-300 opacity-0 group-hover:opacity-100 transition-all" />
                </div>
             </div>
          </div>
       </div>
    </div>

    <!-- Final Details -->
    <div class="modern-card p-8 bg-slate-900 text-white overflow-hidden relative">
       <div class="absolute -right-10 -bottom-10 opacity-10">
          <PackageCheck class="h-64 w-64" />
       </div>

       <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="space-y-4">
             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic flex items-center gap-2">
                <Calendar class="h-4 w-4" /> THỜI HẠN TRẢ MÁY
             </label>
             <input v-model="form.hantra" type="date" class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm text-white focus:ring-2 ring-primary outline-none" />
          </div>

          <div class="md:col-span-1 space-y-4">
             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic flex items-center gap-2">
                <Filter class="h-4 w-4" /> GHI CHÚ BÀN GIAO
             </label>
             <input v-model="form.description" type="text" placeholder="Tình trạng lúc giao, phụ kiện kèm theo..." class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm text-white focus:ring-2 ring-primary outline-none" />
          </div>

          <div class="flex items-end">
             <button @click="handleSubmit" 
               :disabled="!selectedUser || !selectedEq"
               class="w-full bg-primary hover:bg-primary-dark disabled:opacity-20 disabled:cursor-not-allowed text-white py-3.5 rounded-2xl font-black uppercase tracking-widest italic text-xs transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-3">
                <Zap v-if="!loading" class="h-4 w-4" />
                <RefreshCw v-else class="h-4 w-4 animate-spin" />
                HOÀN TẤT & PHÁT MÁY
             </button>
          </div>
       </div>
    </div>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
</style>
