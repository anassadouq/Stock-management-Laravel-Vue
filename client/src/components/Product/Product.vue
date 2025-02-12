<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const products = ref([]);
const errorMessage = ref('');
const route = useRoute();
const router = useRouter();
const toast = useToast();

const fournisseurId = route.params.fournisseur_id;

// Fetch products for a specific fournisseur
const fetchProducts = async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/product/show/${fournisseurId}`);
        products.value = response.data.product;
    } catch (error) {
        console.error('Erreur lors du chargement des produits', error);
        errorMessage.value = 'Impossible de charger les produits.';
    }
};

onMounted(fetchProducts);

const deleteProduct = async (productId) => {
    try {
        const confirmDelete = window.confirm('Are you sure you want to delete this product?');
        if (confirmDelete) {
            await axios.delete(`http://127.0.0.1:8000/api/product/${productId}`);
            toast.success('Product Deleted Successfully');
            // Remove the deleted product from the list without reloading
            products.value = products.value.filter(product => product.id !== productId);
        }
    } catch (error) {
        console.error('Error deleting product', error);
        toast.error('Product not deleted');
    }
};
</script>

<template>
    <RouterLink :to="`/product/show/${fournisseurId}/create`">Create</RouterLink>
    <section>
        <div>
            <h2>Liste des produits</h2>
            <table width="100%" style="text-align: center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Designation</th>
                        <th>Qte</th>
                        <th>Pu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td>{{ product.code }}</td>
                        <td>{{ product.designation }}</td>
                        <td>{{ product.qte }}</td>
                        <td>{{ product.pu }}</td>
                        <td>
                            <RouterLink :to="`/product/show/${product.id}/edit`">Edit</RouterLink>
                            <button  
                                @click="deleteProduct(product.id)" 
                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline mt-4 block"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>