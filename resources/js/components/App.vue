<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-lg">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo/Brand -->
                    <div class="flex-shrink-0">
                        <span class="text-xl font-bold text-gray-800">Acc</span>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-8">
                        <router-link 
                            to="/" 
                            class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path === '/' 
                                    ? 'text-blue-600 border-b-2 border-blue-600' 
                                    : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                            ]"
                        >
                            Dashboard
                        </router-link>
                        <router-link 
                            to="/customers" 
                            class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path.startsWith('/customers')
                                    ? 'text-blue-600 border-b-2 border-blue-600' 
                                    : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                            ]"
                        >
                            Customers
                        </router-link>
                        <router-link 
                            to="/products" 
                            class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path.startsWith('/products')
                                    ? 'text-blue-600 border-b-2 border-blue-600' 
                                    : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                            ]"
                        >
                            Products
                        </router-link>
                        <router-link 
                            to="/invoices" 
                            class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200"
                            :class="[
                                $route.path.startsWith('/invoices')
                                    ? 'text-blue-600 border-b-2 border-blue-600' 
                                    : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'
                            ]"
                        >
                            Invoices
                        </router-link>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center">
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 mr-8 hidden lg:block">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                New Invoice
                            </button>
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                Add Customer
                            </button>
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                Add Product
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App'
}
</script> 