<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';

    const toast = useToast();

    const route = useRoute();
    const router = useRouter();

    const id = route.params.id;
    const client_id = route.query.client_id || null; // Récupérer client_id depuis l'URL

    const form = reactive({
        client_id: client_id,  // Ajouter client_id dans form dès l'initialisation
        product_id: '',
        qte: '',
    });

    const products = ref([]);
    const errorMessage = ref('');

    // Récupérer les produits pour la sélection
    const fetchProducts = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            products.value = response.data;
        } catch (error) {
            console.error('Erreur lors du chargement des produits', error);
        }
    };

    // Récupérer les détails de l'achat existant
    const fetchAchat = async () => {
        try {
            const response = await axios.get(`http://127.0.0.1:8000/api/achat/${id}`);
            const achat = response.data;

            form.client_id = achat.client_id || form.client_id;
            form.product_id = achat.product_id;
            form.qte = achat.qte;
            
            console.log("Fetched Achat:", form); // Debugging
        } catch (error) {
            console.error("Erreur lors du chargement de l'achat", error);
            errorMessage.value = "Impossible de charger les informations de l'achat.";
        }
    };

    // Envoyer les données mises à jour
    const handleUpdate = async () => {
        console.log("Form before update:", form); // Vérifier `client_id`
        
        if (!form.client_id) {
            console.error("client_id is missing!", form);
            toast.error("Erreur: client_id est manquant !");
            return;
        }

        try {
            console.log("Updating Achat with Data:", form);
            await axios.put(`http://127.0.0.1:8000/api/achat/${id}`, form);
            toast.success("Achat mis à jour avec succès");
            router.push(`/achat/show/${form.client_id}`);
        } catch (error) {
            console.error("Erreur lors de la mise à jour de l'achat", error);
            errorMessage.value = "Impossible de mettre à jour l'achat.";
            toast.error("La mise à jour a échoué");
        }
    };

    // Charger les données
    onMounted(() => {
        fetchAchat();
        fetchProducts();
    });
</script>


<template>
    <section class="bg-blue-50">
        <div class="container m-auto max-w-2xl py-24">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Modifier l'achat</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Produit</label>
                        <select v-model="form.product_id" name="product_id" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 w-full px-4 py-2">
                            <option value="" disabled>Choisissez un produit</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">
                                {{ product.designation }} - {{ product.pu }} DH
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" name="qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>