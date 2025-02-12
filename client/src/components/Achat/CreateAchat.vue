<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();

    const form = reactive({
        client_id: route.params.client_id,  // Récupère le client_id depuis l'URL
        product_id: '',
        qte: '',
    });

    const products = ref([]);
    const errorMessage = ref('');

    // Fonction pour récupérer la liste des produits
    const fetchProducts = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            products.value = response.data;
        } catch (error) {
            console.error('Erreur lors du chargement des produits', error);
        }
    };

    const handleSubmit = async () => {
        const newAchat = {
            client_id: form.client_id, // client_id récupéré depuis l'URL
            product_id: form.product_id,
            qte: form.qte,
        };

        try {
            await axios.post('http://127.0.0.1:8000/api/achat', newAchat);
            toast.success('Achat ajouté avec succès');
            router.push(`/achat/show/${form.client_id}`);
        } catch (error) {
            console.error('Erreur lors de l\'ajout de l\'achat', error);
            errorMessage.value = 'Impossible d\'ajouter l\'achat. Vérifiez les informations.';
            toast.error('L\'achat n\'a pas été ajouté');
        }
    };

    // Charger les produits dès que le composant est monté
    onMounted(fetchProducts);
</script>



<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-24">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un achat</h2>

                    <!-- Sélection du produit -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Produit</label>
                        <select v-model="form.product_id"
                                name="product_id"
                                class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2">
                            <option value="" disabled>Choisissez un produit</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">
                                {{ product.designation }} - {{ product.pu }} DH
                            </option>
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" 
                               v-model="form.qte" 
                               name="qte" 
                               placeholder="Quantité" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div v-if="errorMessage" class="text-red-500 text-sm mt-1 mb-4">{{ errorMessage }}</div>

                    <div>
                        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>