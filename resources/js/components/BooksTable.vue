<template>
  <div class="container mx-auto p-4">
    <div class="mb-4">
      <input
        v-model="searchQuery"
        @input="handleSearch"
        @keyup.enter="handleSearch"
        type="text"
        placeholder="Search books..."
        class="w-full p-2 border rounded"
      />
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border rounded">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books" :key="book.id" class="border-t">
            <td class="px-4 py-2">{{ book.id }}</td>
            <td class="px-4 py-2">{{ book.title }}</td>
            <td class="px-4 py-2">{{ book.status }}</td>
            <td class="px-4 py-2">{{ book.notes }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-center">
      <nav class="flex items-center space-x-2">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="currentPage = page"
          :class="[
            'px-3 py-1 rounded',
            currentPage === page
              ? 'bg-blue-500 text-white'
              : 'bg-gray-200 hover:bg-gray-300'
          ]"
        >
          {{ page }}
        </button>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const books = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const searchTimeout = ref(null);

const fetchBooks = async () => {
  try {
    console.log('Fetching books...');
    const response = await axios.get(`/api/books`, {
      params: {
        page: currentPage.value,
        search: searchQuery.value
      }
    });
    console.log('Books response:', response.data);
    books.value = response.data.data;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.error('Error fetching books:', error);
  }
};

const handleSearch = () => {
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value);
  }
  
  searchTimeout.value = setTimeout(() => {
    currentPage.value = 1;
    fetchBooks();
  }, 300);
};

watch(currentPage, () => {
  fetchBooks();
});

onMounted(() => {
  fetchBooks();
});
</script> 