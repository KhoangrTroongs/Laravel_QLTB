import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

// Layouts
import UserLayout from '@/layouts/UserLayout.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/auth/LoginView.vue'),
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/auth/RegisterView.vue'),
      meta: { guest: true }
    },
    {
      path: '/forbidden',
      name: 'forbidden',
      component: () => import('@/views/errors/ForbiddenView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/errors/NotFoundView.vue')
    },
    // USER ROUTES (Frontend)
    {
      path: '/',
      component: UserLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: () => import('@/views/HomeView.vue')
        },
        {
          path: 'equipment/:id',
          name: 'equipment-detail',
          component: () => import('@/views/equipment/EquipmentDetail.vue')
        },
        {
          path: 'my-borrows',
          name: 'my-borrows',
          component: () => import('@/views/equipment/MyBorrows.vue'),
          meta: { auth: true }
        },
        {
          path: 'notifications',
          name: 'notifications',
          component: () => import('@/views/NotificationsView.vue'),
          meta: { auth: true }
        },
        {
          path: 'profile',
          name: 'profile',
          component: () => import('@/views/profile/ProfileView.vue'),
          meta: { auth: true }
        },
        {
          path: 'settings',
          name: 'settings',
          component: () => import('@/views/profile/SettingsView.vue'),
          meta: { auth: true }
        }
      ]
    },
    // ADMIN ROUTES (Backend)
    {
      path: '/admin',
      component: AdminLayout,
      meta: { auth: true, roles: ['admin', 'editor'] },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: () => import('@/views/admin/DashboardView.vue'),
          meta: { title: 'Tổng quan hệ thống' }
        },
        {
          path: 'categories',
          name: 'admin-categories',
          component: () => import('@/views/admin/CategoriesView.vue'),
          meta: { title: 'Danh mục thiết bị' }
        },
        {
          path: 'equipment',
          name: 'admin-equipment',
          component: () => import('@/views/admin/EquipmentView.vue'),
          meta: { title: 'Kho thiết bị' }
        },
        {
          path: 'borrows/queue',
          name: 'admin-queue',
          component: () => import('@/views/admin/BorrowQueueView.vue'),
          meta: { title: 'Duyệt mượn máy' }
        },
        {
          path: 'borrows/create',
          name: 'admin-borrows-create',
          component: () => import('@/views/admin/CreateBorrowView.vue'),
          meta: { title: 'Tạo phiếu mượn mới' }
        },
        {
          path: 'borrows',
          name: 'admin-borrows',
          component: () => import('@/views/admin/BorrowHistoryView.vue'),
          meta: { title: 'Lịch sử luân chuyển' }
        },
        {
          path: 'users',
          name: 'admin-users',
          component: () => import('@/views/admin/UsersView.vue'),
          meta: { title: 'Quản lý nhân viên', roles: ['admin'] } // Restricted to admin
        },
        {
          path: 'reports',
          name: 'admin-reports',
          component: () => import('@/views/admin/ReportsView.vue'),
          meta: { title: 'Báo cáo & Thống kê' }
        },
        {
          path: 'trash',
          name: 'admin-trash',
          component: () => import('@/views/admin/TrashView.vue'),
          meta: { title: 'Thùng rác' }
        }
      ]
    }
  ]
});

router.beforeEach(async (to, from, next) => {
  NProgress.start();
  const authStore = useAuthStore();
  
  if (to.meta.auth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'home' });
  } else if (to.meta.roles && !authStore.user?.roles?.some(r => to.meta.roles.includes(r.name))) {
    // Also check parent meta roles if child doesn't have it
    const matchedRoles = to.matched.flatMap(record => record.meta.roles || []);
    if (matchedRoles.length > 0 && !authStore.user?.roles?.some(r => matchedRoles.includes(r.name))) {
       next({ name: 'forbidden' });
    } else {
       next();
    }
  } else {
    next();
  }
});

router.afterEach(() => {
  NProgress.done();
});

export default router;
