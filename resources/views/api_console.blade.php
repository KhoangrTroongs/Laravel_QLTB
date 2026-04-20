<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Console | Equipment Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-4xl w-full space-y-8 animate-in fade-in zoom-in duration-500">
        <div class="text-center">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-indigo-500 rounded-2xl shadow-xl shadow-indigo-500/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight mb-2 uppercase italic">Backend API <span class="text-indigo-400">Console</span></h1>
            <p class="text-slate-400 font-medium">Trung tâm giám sát, gỡ lỗi và quản lý dữ liệu hệ thống.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Monitor Box -->
            <a href="/telescope" class="group glass p-8 rounded-3xl hover:bg-slate-800 transition-all border-b-4 border-indigo-500">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold italic uppercase">Laravel <span class="text-indigo-400">Telescope</span></h3>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">Theo dõi thời gian thực mọi API request, Database query và track lỗi code chi tiết.</p>
                <div class="mt-6 flex items-center gap-2 text-indigo-400 font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                    Mở Dashboard ngay <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </div>
            </a>

            <!-- UI Link Box -->
            <a href="http://localhost:5173" class="group glass p-8 rounded-3xl hover:bg-slate-800 transition-all border-b-4 border-emerald-500">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold italic uppercase">Frontend <span class="text-emerald-400">UI</span></h3>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">Truy cập giao diện người dùng hiện đại tại cổng 5173 để kiểm tra trải nghiệm.</p>
                <div class="mt-6 flex items-center gap-2 text-emerald-400 font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                    Quay lại giao diện <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </div>
            </a>
        </div>

        <!-- Quick Status -->
        <div class="p-6 bg-slate-800/30 rounded-3xl border border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse shadow-lg shadow-emerald-500/50"></div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">API Status: Online</span>
                </div>
                <div class="h-4 w-px bg-slate-700"></div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    Environment: Dev
                </div>
            </div>
            <div class="text-[10px] bg-indigo-500 text-white px-2 py-1 rounded-lg font-black uppercase italic shadow-lg shadow-indigo-500/20">Laravel 12</div>
        </div>
    </div>
</body>
</html>
