<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router'; // Import useRoute and useRouter
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect';

    const toast = useToast();
    const route = useRoute(); // Get the current route
    const router = useRouter(); // Get the router instance

    const form = reactive({
        client_id: route.params.client_id, // Get client_id from the route params
        product_id: null, // This will store a single selected product object
        qte: '',
    });

    const products = ref([]); // Store products
    const errorMessage = ref('');

    // Fetch products from API
    const fetchProducts = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            products.value = response.data; // Assuming API returns an array of products
        } catch (error) {
            console.error('Erreur lors du chargement des produits', error);
        }
    };

    // Fetch products when component is mounted
    onMounted(fetchProducts);

    // Handle form submission
    const handleSubmit = async () => {
        // Extract the ID from the selected product
        const selectedProductId = form.product_id ? form.product_id.id : null;

        const newProduct = {
            client_id: form.client_id, // Include client_id in the request
            product_id: selectedProductId, // Send only the ID
            qte: form.qte,
        };

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/achat', newProduct);
            toast.success('Achat ajouté avec succès');
            router.push(`/achat/show/${form.client_id}`); // Navigate to the correct client_id
        } catch (error) {
            console.error('Erreur lors de l\'ajout de l\'achat', error);
            errorMessage.value = 'Impossible d\'ajouter l\'achat. Veuillez vérifier les informations.';
            toast.error('L\'achat n\'a pas été ajouté');
        }
    };
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un achat</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Produit</label>
                        <vue-multiselect
                            v-model="form.product_id" 
                            :options="products" 
                            :searchable="true"
                            track-by="id"
                            label="designation"
                            placeholder="Choisissez un produit"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div v-if="errorMessage" class="text-red-500 text-sm mt-1 mb-4">{{ errorMessage }}</div>

                    <div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>