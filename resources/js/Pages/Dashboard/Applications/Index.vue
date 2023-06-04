<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue'
defineProps(['applications'])
</script>
<template>
  <AdminLayout title="Mis API Keys">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Mis API Keys
      </h2>
    </template>
    <div
      class="p-4 bg-white lg:border border-gray-200 lg:rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-lg">API Keys</h3>
        <a href="{{ route('api-tokens.create') }}"
          class="bg-blue-500 hover:bg-blue-600 text-white font-bold rounded focus:outline-none focus:shadow-outline dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-gray-200 dark:focus:bg-blue-700 dark:focus:text-gray-200 text-xs sm:text-base py-1 px-3 sm:px-4 sm:py-2">
          Crear API Key
        </a>
      </div>
      <div v-for="application in applications" :key="application.id"
        class="flex justify-between items-center py-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm mb-4 px-4">
        <h4 class="flex flex-col gap-y-2">
          {{ application.name }}
          <span class="text-xs text-gray-500 dark:text-gray-400">
            Último uso: {{ application.token.last_used_at ? application.token.last_used_at : 'Nunca usado' }}
          </span>
        </h4>
        <div class="flex gap-x-2">
          <button
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold rounded focus:outline-none focus:shadow-outline dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-gray-200 dark:focus:bg-blue-700 dark:focus:text-gray-200 text-xs sm:text-base py-1 px-3 sm:px-4 sm:py-2"
            >
            Regenerar
          </button>
          <button
            class="bg-red-500 hover:bg-red-600 text-white font-bold rounded focus:outline-none focus:shadow-outline dark:bg-red-600 dark:hover:bg-red-700 dark:text-gray-200 dark:focus:bg-red-700 dark:focus:text-gray-200 text-xs sm:text-base py-1 px-3 sm:px-4 sm:py-2"
            >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>