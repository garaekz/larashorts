<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue'
defineProps(['applications'])
</script>
<template>
  <AdminLayout title="Url List">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
      </h2>
    </template>

    <div
      class="p-4 bg-white lg:border border-gray-200 lg:rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
      {{ applications }}
      <!-- <DataTable
        :data="urls" 
        :columns="columns"
        createLabel="Acortar nueva URL"
        @table:search="onSearch"
        @table:page-change="onPageChange"
        @table:creating="creatingShort = true"
        >
        <template #is_active="{ item }">
          <span
            :class="{ 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': !item.is_active, 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': item.is_active }"
            class="text-xs uppercase font-bold px-2 py-0.5 rounded">
            {{ item.is_active ? 'Activa' : 'Inactiva' }}
          </span>
        </template>
        <template #expires_at="{ item }">
          {{ item.expires_at ?? 'No expira' }}
        </template>
      </DataTable> -->
    </div>
  </AdminLayout>
</template>