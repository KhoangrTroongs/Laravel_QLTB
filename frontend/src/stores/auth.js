import { defineStore } from 'pinia';
import axios from '@/api/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('auth_token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.roles?.some(r => r.name === 'admin'),
    isEditor: (state) => state.user?.roles?.some(r => r.name === 'editor'),
  },

  actions: {
    async login(credentials) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.post('/login', credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        
        localStorage.setItem('auth_token', this.token);
        localStorage.setItem('user', JSON.stringify(this.user));
        
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Đăng nhập thất bại.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async register(userData) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.post('/register', userData);
        this.token = response.data.token;
        this.user = response.data.user;
        
        localStorage.setItem('auth_token', this.token);
        localStorage.setItem('user', JSON.stringify(this.user));
        
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Đăng ký thất bại.';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post('/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
      }
    },

    async fetchMe() {
      if (!this.token) return;
      try {
        const response = await axios.get('/me');
        this.user = response.data;
        localStorage.setItem('user', JSON.stringify(this.user));
      } catch (error) {
        this.logout();
      }
    }
  }
});
