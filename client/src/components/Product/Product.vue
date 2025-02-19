<script setup>
    import { ref, computed } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';

    const queryClient = useQueryClient();
    const searchFilter = ref('');

    // Fetch products
    const { data: products, error } = useQuery({
        queryKey: ['products'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (products.value || []).filter(item => 
            item.code.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.designation.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.qte.toString().includes(searchFilter.value.toLowerCase()) ||
            item.pu.toString().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete product mutation
    const deleteProduct = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/product/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['products'] });
            toast.success('product deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting product', error);
            toast.error('product was not deleted');
        },
    });

    // Confirm and delete
    const confirmAndDeleteProduct = (id) => {
        if (window.confirm('Are you sure you want to delete this product?')) {
            deleteProduct.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>
    <section>
        <div>
            <RouterLink to="/product/create" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
                <i class="pi pi-plus-circle"></i>
            </RouterLink><br><br>

            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Fournisseur</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Qte</th>
                        <th scope="col" class="px-6 py-3">PU</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredItems" :key="product.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ product.code }}</td>
                        <td class="px-6 py-4">{{ product.fournisseur?.nom || 'N/A' }}</td>
                        <td class="px-6 py-4">{{ product.designation }}</td>
                        <td class="px-6 py-4 flex items-center space-x-2" :class="{'text-red-600': product.qte <= 10}">
                            <i v-if="product.qte <= 10" class="pi pi-exclamation-triangle text-red-600 text-xl"></i>
                            <b>{{ product.qte }}</b>
                        </td>
                        <td class="px-6 py-4">{{ product.pu }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/product/edit/${product.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>
                            <button @click="confirmAndDeleteProduct(product.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>