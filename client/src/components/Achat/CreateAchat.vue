<script setup>
    import { reactive, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import Navbar from '../Navbar/Navbar.vue';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();

    const form = reactive({
        client_id: route.params.client_id,
        product_id: null,
        qte: '',
    });

    const errorMessage = ref('');
    const products = ref([]);

    // Fetch products from API
    const fetchProducts = async () => {
        try {
            const { data } = await axios.get('http://127.0.0.1:8000/api/product');
            products.value = data;
        } catch (error) {
            console.error("Erreur lors du chargement des produits:", error);
            throw new Error("Impossible de charger les produits.");
        }
    };

    // Use Vue Query for fetching products
    const { error: queryError } = useQuery({
        queryKey: ['products'],
        queryFn: fetchProducts,
    });

    // Mutation for adding achat
    const addAchatMutation = useMutation({
        mutationFn: async (achatData) => {
            return await axios.post('http://127.0.0.1:8000/api/achat', achatData);
        },
        onSuccess: () => {
            toast.success('Achat ajouté avec succès');
            router.push(`/achat/show/${form.client_id}`);
        },
        onError: (error) => {
            console.error("Erreur API:", error.response?.data);
            toast.error(error.response?.data?.message || "Une erreur s'est produite.");
        }
    });

    // Handle form submission
    const handleSubmit = async () => {
        if (!form.product_id || !form.product_id.id) {
            errorMessage.value = "Veuillez sélectionner un produit.";
            return;
        }

        if (!form.qte || form.qte <= 0) {
            errorMessage.value = "Veuillez entrer une quantité valide.";
            return;
        }

        const selectedProduct = products.value.find(p => p.id === form.product_id.id);
        if (selectedProduct && selectedProduct.detail_products_sum_qte < form.qte) {
            errorMessage.value = "Stock insuffisant pour cet achat.";
            return;
        }

        errorMessage.value = '';

        try {
            await addAchatMutation.mutateAsync({
                client_id: form.client_id,
                product_id: form.product_id.id,
                qte: form.qte,
            });
        } catch (error) {
            console.error("Erreur lors de l'achat:", error.response?.data);
            toast.error(error.response?.data?.message || "Erreur inconnue");
        }
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
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
                        <div v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</div>
                    </div>

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