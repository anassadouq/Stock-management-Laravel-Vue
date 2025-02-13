<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';

    const products = ref([]);
    const errorMessage = ref('');
    const toast = useToast();

    const fetchProducts = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            products.value = response.data;
        } catch (error) {
            console.error('Erreur lors du chargement des products', error);
            errorMessage.value = 'Impossible de charger les products.';
        }
    };

    const deleteProduct = async (id) => {
        try {
            const confirmDelete = window.confirm('Are you sure you want to delete this product?');
            if (confirmDelete) {
                await axios.delete(`http://127.0.0.1:8000/api/product/${id}`);
                fetchProducts();
                toast.success('Product deleted successfully');
            }
        } catch (error) {
            console.error('Error deleting product', error);
            toast.error('Product was not deleted');
        }
    };

    onMounted(fetchProducts);
</script>

<template>
    <RouterLink to="/product/create">Add</RouterLink>
    <section>
        <div>
            <h2>Liste des products</h2>
            <table width="100%" style="text-align: center">
                <thead>
                    <tr>
                        <th>Fournisseur</th>
                        <th>Code</th>
                        <th>Designation</th>
                        <th>Qte</th>
                        <th>PU</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td>{{ product.fournisseur.nom }}</td>
                        <td>{{ product.code }}</td>
                        <td>{{ product.designation }}</td>
                        <td>{{ product.qte }}</td>
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