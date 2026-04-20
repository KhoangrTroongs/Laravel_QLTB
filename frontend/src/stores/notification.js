import { defineStore } from 'pinia';
import axios from '@/api/axios';
import createEchoInstance from '@/utils/echo';
import { useAuthStore } from './auth';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
    unreadCount: 0,
    echo: null,
  }),

  actions: {
    async fetchNotifications() {
      try {
        const response = await axios.get('/notifications');
        this.notifications = response.data.data;
      } catch (error) {
        console.error('Fetch notifications error:', error);
      }
    },

    async fetchUnreadCount() {
      try {
        const response = await axios.get('/notifications/count');
        this.unreadCount = response.data.unread_count;
      } catch (error) {
        console.error('Fetch unread count error:', error);
      }
    },

    async markAsRead(id) {
      try {
        await axios.post(`/notifications/${id}/mark-read`);
        const index = this.notifications.findIndex(n => n.id === id);
        if (index !== -1) {
          this.notifications[index].is_read = true;
          this.unreadCount = Math.max(0, this.unreadCount - 1);
        }
      } catch (error) {
        console.error('Mark as read error:', error);
      }
    },

    async markAllAsRead() {
      try {
        await axios.post('/notifications/mark-all-read');
        this.notifications.forEach(n => n.is_read = true);
        this.unreadCount = 0;
      } catch (error) {
        console.error('Mark all read error:', error);
      }
    },

    initializeEcho() {
      const authStore = useAuthStore();
      if (!authStore.token || !authStore.user) return;

      if (this.echo) {
        this.echo.disconnect();
      }

      this.echo = createEchoInstance(authStore.token);

      // Private channel for user notifications
      this.echo.private(`App.Models.User.${authStore.user.id}`)
        .notification((notification) => {
          this.notifications.unshift({
            id: notification.id,
            type: notification.type,
            data: notification,
            created_at: 'Vừa xong',
            is_read: false
          });
          this.unreadCount++;
          
          // Optional: Play sound or show toast
          this.showToast(notification);
        });
    },

    showToast(notification) {
      // Logic to show a toast message
      console.log('New notification:', notification);
    }
  }
});
