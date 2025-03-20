<template>
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Side Panel -->
        <div class="w-1/5 bg-white shadow-lg min-h-screen">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-8">Menu</h2>
                <nav class="space-y-2">
                    <router-link 
                        to="/screen1" 
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200"
                        :class="{ 'bg-gray-100': $route.path === '/screen1' }"
                    >
                        Screen 1
                    </router-link>
                    <router-link 
                        to="/screen2" 
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200"
                        :class="{ 'bg-gray-100': $route.path === '/screen2' }"
                    >
                        Screen 2
                    </router-link>
                    <router-link 
                        to="/screen3" 
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200"
                        :class="{ 'bg-gray-100': $route.path === '/screen3' }"
                    >
                        Screen 3
                    </router-link>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App'
}
</script> 