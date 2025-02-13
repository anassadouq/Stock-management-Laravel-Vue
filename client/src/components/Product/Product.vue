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
    <RouterLink to="/product/create">Add</RouterLink><br><br>
    
    <section>
        <div>
            <SearchForm @search="handleSearch"/>
            <h2>Liste des produits</h2>
            <table width="100%" style="text-align: center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Fournisseur</th>
                        <th>Designation</th>
                        <th>Qte</th>
                        <th>PU</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredProducts" :key="product.id">
                        <td>{{ product.code }}</td>
                        <td>{{ product.fournisseur?.nom || 'N/A' }}</td>
                        <td>{{ product.designation }}</td>
                        <td :class="{'low-stock': product.qte <= 10}">{{ product.qte }}</td>
                        <td>{{ product.pu }}</td>
                        <td>
                            <RouterLink :to="`/product/edit/${product.id}`">Edit</RouterLink>
                            <button @click="deleteProduct(product.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<style>
    .low-stock {
        background-color: red;
        color: white;
    }
</style>