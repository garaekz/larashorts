<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

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
            <button @click="creatingShort = true"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Create Short Url
            </button>
            {{ urls }}
        </div>
    </AdminLayout>
    <DialogModal :show="creatingShort" @close="closeModal">
        <template #title>
            Create Short Url
        </template>

        <template #content>
            <form @submit.prevent="handleSubmit">
                <div class="mt-4">
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Url
                    </label>
                    <div class="mt-1">
                        <input type="text" name="url" id="url"
                            class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                            v-model="form.url">
                        <InputError class="mt-2" :message="form.errors.url" />
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create
                    </button>
                </div>
            </form>
        </template>
    </DialogModal>
</template>
