<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import SearchForm from '../DataTable/SearchForm.vue';

const products = ref([]);
const errorMessage = ref('');
const toast = useToast();
const searchFilter = ref(''); // Define search filter

const fetchProducts = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/product');
        products.value = response.data;
    } catch (error) {
        console.error('Erreur lors du chargement des produits', error);
        errorMessage.value = 'Impossible de charger les produits.';
    }
};

const deleteProduct = async (id) => {
    try {
        if (window.confirm('Are you sure you want to delete this product?')) {
            await axios.delete(`http://127.0.0.1:8000/api/product/${id}`);
            fetchProducts();
            toast.success('Product deleted successfully');
        }
    } catch (error) {
        console.error('Error deleting product', error);
        toast.error('Product was not deleted');
    }
};

// Computed property for filtering the items
const filteredProducts = computed(() => {
    if (searchFilter.value) {
        return products.value.filter(product => {
            const searchText = searchFilter.value.toLowerCase();
            return (
                product.code.toLowerCase().includes(searchText) ||
                (product.fournisseur?.nom || '').toLowerCase().includes(searchText) || // Check fournisseur.nom
                product.designation.toLowerCase().includes(searchText) ||
                product.qte.toString().toLowerCase().includes(searchText) ||
                product.pu.toString().toLowerCase().includes(searchText)
            );
        });
    }
    return products.value;
});

// Handle search from child component
const handleSearch = (search) => {
    searchFilter.value = search;
};

onMounted(fetchProducts);
</script>

<template>
    
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
                    <tr v-for="product in filteredProducts" :key="product.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
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
                            <button @click="deleteProduct(product.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>