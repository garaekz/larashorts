<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, router } from '@inertiajs/vue3';
import DataTable from '../../../Components/Shared/DataTable.vue';

const props = defineProps({
  urls: {},
  errors: {},
});

const creatingShort = ref(false);

const form = useForm({
  url: null,
});

const closeModal = () => {
  creatingShort.value = false;
  form.reset();
};

const handleSubmit = () => {
  form.post(route('urls.store'), {
    onSuccess: () => {
      closeModal();
    },
  });
};

const columns = [
  {
    key: 'code',
    label: 'Código',
  },
  {
    key: 'original_url',
    label: 'URL Original',
  },
  {
    key: 'visits',
    label: 'Visitas',
  },
  {
    key: 'is_active',
    label: 'Estado',
  },
  {
    key: 'expires_at',
    label: 'Fecha de expiración',
  },
]

const goToPage = (page) => {
  router.reload({ data: page });
};
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
      <DataTable
        :data="urls" 
        :columns="columns"
        @table:page-change="goToPage"
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
      </DataTable>
    </div>
  </AdminLayout>
  <DialogModal :show="creatingShort" @close="closeModal">
    <template #title>
      Acortar URL
    </template>

    <template #content>
      <form @submit.prevent="handleSubmit">
        <div class="mt-4">
          <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            Url Original
          </label>
          <div class="mt-1">
            <input type="text" name="url" id="url"
              class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
              v-model="form.url">
            <InputError class="mt-2" :message="form.errors.url" />
          </div>
        </div>

        <div class="mt-4 flex space-x-4 justify-end">
          <button type="button"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-500 bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            @click="closeModal">
            Cancelar
          </button>
          <button type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Guardar
          </button>
        </div>
      </form>
    </template>
  </DialogModal>
</template>