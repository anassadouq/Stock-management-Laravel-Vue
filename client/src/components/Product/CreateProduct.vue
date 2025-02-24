<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import router from '@/router';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import Navbar from '../Navbar/Navbar.vue';

const toast = useToast();
const route = useRoute();
const magasin_id = ref(route.params.magasin_id); // Récupération de magasin_id

const form = reactive({
    code: '',
    designation: '',
    stock_min: '',
    min_sortie: '',
});

const fournisseurs = ref([]);
const errorMessage = ref('');

const fetchFournisseurs = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
        fournisseurs.value = response.data;
    } catch (error) {
        console.error('Erreur lors du chargement des fournisseurs', error);
    }
};

onMounted(fetchFournisseurs);

const handleSubmit = async () => {

    const newProduct = {
        magasin_id: magasin_id.value,
        code: form.code,
        designation: form.designation,
        stock_min: form.stock_min,
        min_sortie: form.min_sortie,
    };

    try {
        await axios.post('http://127.0.0.1:8000/api/product', newProduct);
        toast.success('Produit ajouté avec succès');
        router.push(`/product/show/${magasin_id.value}`);
    } catch (error) {
        console.error('Erreur lors de l\'ajout du produit', error);
        errorMessage.value = 'Impossible d\'ajouter le produit. Veuillez vérifier les informations.';
        toast.error('Le produit n\'a pas été ajouté');
    }
};

</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" v-model="form.code" placeholder="Code"
                            class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2" />
                    </div>

                    <!-- Désignation -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Désignation</label>
                        <input type="text" v-model="form.designation" placeholder="Désignation"
                            class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2" />
                    </div>

                    <!-- Stock Min -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Stock min</label>
                        <input type="number" v-model="form.stock_min" placeholder="Stock min"
                            class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2" />
                    </div>

                    <!-- Min sortie -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Min sortie</label>
                        <input type="number" v-model="form.min_sortie" placeholder="Min sortie"
                            class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2" />
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