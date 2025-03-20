<template>
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800">{{ message }}</h1>
    </div>
</template>

<script>
export default {
    name: 'Screen1',
    data() {
        return {
            message: 'Loading...'
        }
    },
    async created() {
        try {
            const response = await fetch('/api/screen1');
            const data = await response.json();
            this.message = data.message;
        } catch (error) {
            console.error('Error fetching data:', error);
            this.message = 'Error loading data';
        }
    }
}
</script> 