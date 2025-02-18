<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import router from '@/router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect'; // Import the multi-select component

    const toast = useToast();

    const form = reactive({
        fournisseur_id: null, // This will store a single selected fournisseur object
        code: '',
        designation: '',
        qte: '',
        pu: '',
    });

    const fournisseurs = ref([]); // Store fournisseurs
    const errorMessage = ref('');

    // Fetch fournisseurs from API
    const fetchFournisseurs = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            fournisseurs.value = response.data; // Assuming API returns an array of fournisseurs
        } catch (error) {
            console.error('Erreur lors du chargement des fournisseurs', error);
        }
    };

    // Fetch fournisseurs when component is mounted
    onMounted(fetchFournisseurs);

    // Handle form submission
    const handleSubmit = async () => {
        // Extract the ID from the selected fournisseur
        const selectedFournisseurId = form.fournisseur_id ? form.fournisseur_id.id : null;

        const newProduct = {
            fournisseur_id: selectedFournisseurId, // Send only the ID
            code: form.code,
            designation: form.designation,
            qte: form.qte,
            pu: form.pu,
        };

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/product', newProduct);
            toast.success('Product added successfully');
            router.push('/product');
        } catch (error) {
            console.error('Erreur lors de l\'ajout du product', error);
            errorMessage.value = 'Impossible d\'ajouter le product. Veuillez vérifier les informations.';
            toast.error('Product was not added');
        }
    };
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Fournisseur</label>
                        <vue-multiselect
                            v-model="form.fournisseur_id" 
                            :options="fournisseurs" 
                            :searchable="true"
                            track-by="id"
                            label="nom"
                            placeholder="Choisissez un fournisseur"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" v-model="form.code" placeholder="Code" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Désignation</label>
                        <input type="text" v-model="form.designation" placeholder="Désignation" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Prix Unitaire</label>
                        <input type="number" v-model="form.pu" placeholder="Prix Unitaire" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
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