<script setup lang="ts">
// =====================================================
// FILE: src/views/auth/LoginView.vue
// Login screen with 2FA support
// =====================================================

import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const email = ref('')
const password = ref('')
const twoFactorCode = ref('')
const showPassword = ref(false)
const requires2FA = ref(false)
const localError = ref('')

const isValid = computed(() =>
  email.value.includes('@') && password.value.length >= 6
)

async function handleLogin() {
  localError.value = ''
  const result = await auth.login(email.value, password.value, twoFactorCode.value || undefined)

  if (result.requires2FA) {
    requires2FA.value = true
    return
  }

  if (result.success) {
    const redirect = (route.query.redirect as string) || '/dashboard'
    router.push(redirect)
  } else {
    localError.value = result.error || 'Login failed'
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand to-brand-dark px-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white tracking-wide">WinCase</h1>
        <p class="text-white/60 mt-1">Immigration Bureau CRM</p>
      </div>

      <!-- Login card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
          {{ requires2FA ? 'Two-Factor Authentication' : 'Sign In' }}
        </h2>

        <!-- Error message -->
        <div v-if="localError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
          {{ localError }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email -->
          <div v-if="!requires2FA">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              v-model="email"
              type="email"
              required
              placeholder="manager@wincase.pro"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition"
            />
          </div>

          <!-- Password -->
          <div v-if="!requires2FA">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                minlength="6"
                placeholder="••••••••"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition pr-12"
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm"
                @click="showPassword = !showPassword"
              >
                {{ showPassword ? 'Hide' : 'Show' }}
              </button>
            </div>
          </div>

          <!-- 2FA Code -->
          <div v-if="requires2FA">
            <label class="block text-sm font-medium text-gray-700 mb-1">Authentication Code</label>
            <input
              v-model="twoFactorCode"
              type="text"
              maxlength="6"
              pattern="[0-9]{6}"
              required
              placeholder="000000"
              autofocus
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition text-center text-2xl tracking-[0.5em] font-mono"
            />
            <p class="text-xs text-gray-400 mt-2">Enter the 6-digit code from Google Authenticator</p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="(!requires2FA && !isValid) || auth.loading"
            class="w-full py-2.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-light transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="auth.loading" class="inline-flex items-center gap-2">
              <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round"/></svg>
              Signing in...
            </span>
            <span v-else>{{ requires2FA ? 'Verify' : 'Sign In' }}</span>
          </button>

          <!-- Back from 2FA -->
          <button
            v-if="requires2FA"
            type="button"
            class="w-full py-2 text-sm text-gray-500 hover:text-gray-700"
            @click="requires2FA = false; twoFactorCode = ''"
          >
            ← Back to login
          </button>
        </form>
      </div>

      <p class="text-center text-white/40 text-xs mt-6">© 2026 WinCase — Biuro Imigracyjne</p>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
LoginView.vue — экран авторизации.
2 режима: email+password, 2FA (6-значный код Google Authenticator).
Валидация: email contains @, password min 6.
Redirect: после логина → query.redirect или /dashboard.
Loading spinner, error message display.
Gradient background brand→brand-dark (#1F3864).
Файл: src/views/auth/LoginView.vue
-->
