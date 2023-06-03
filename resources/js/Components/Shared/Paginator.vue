<script setup lang="ts">
import { computed, toRefs } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalItems: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    required: true,
  },
  links: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['page-change'])

const { currentPage, totalItems, perPage } = toRefs(props)

const totalPages = computed(() => {
  return Math.ceil(totalItems.value / perPage.value)
})

const hasPreviousPage = computed(() => {
  return currentPage.value > 1
})

const hasNextPage = computed(() => {
  return currentPage.value < totalPages.value
})

const visiblePages = computed(() => {
  const pages: (number|string)[] = [];
  let total = totalPages.value;

  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1);
  } else {
    let current = currentPage.value;
    let range = current <= 4 ? [2, 5] : current >= total - 3 ? [total - 4, total - 1] : [current - 1, current + 1];

    pages.push(1);
    if (range[0] > 2) pages.push('...');
    pages.push(...Array.from({ length: range[1] - range[0] + 1 }, (_, i) => i + range[0]));
    if (range[1] < total - 1) pages.push('...');
    pages.push(total);
  }

  return pages;
});



const getStartingIndex = () => {
  return (currentPage.value - 1) * perPage.value + 1
}

const getEndingIndex = () => {
  const lastItemIndex = currentPage.value * perPage.value
  return Math.min(lastItemIndex, totalItems.value)
}

const goToPage = (page: number) => {
  if (page < 1 || page > totalPages.value) {
    return
  }

  emit('page-change', page)
}
</script>

<template>
  <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
    aria-label="Table navigation">
    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
      Mostrando
      <span class="font-semibold text-gray-900 dark:text-white">{{ getStartingIndex() }}-{{ getEndingIndex() }}</span>
      de
      <span class="font-semibold text-gray-900 dark:text-white">{{ totalItems }}</span>
    </span>
    <ul class="inline-flex items-stretch -space-x-px">
      <li>
        <button v-if="hasPreviousPage" @click.prevent="goToPage(currentPage - 1)"
          class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <span class="sr-only">Previous</span>
          <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
              clip-rule="evenodd" />
          </svg>
        </button>
        <span v-else
          class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400">
          <span class="sr-only">Previous</span>
          <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
              clip-rule="evenodd" />
          </svg>
        </span>
      </li>
      <li v-for="pageNumber in visiblePages" :key="pageNumber">
        <button v-if="currentPage !== pageNumber && pageNumber !== '...'" @click.prevent="goToPage(Number(pageNumber))"
          class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-white">
          {{ pageNumber }}
        </button>
        <span v-else
          class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-gray-600 bg-gray-100 border border-gray-300 dark:border-gray-700 dark:bg-gray-700/40 dark:text-white">
          {{ pageNumber }}
        </span>
      </li>
      <li>
        <button v-if="hasNextPage" @click.prevent="goToPage(currentPage + 1)"
          class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <span class="sr-only">Next</span>
          <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
        </button>
        <span v-else
          class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-400">
          <span class="sr-only">Next</span>
          <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
        </span>
      </li>
    </ul>
  </nav>
</template>
