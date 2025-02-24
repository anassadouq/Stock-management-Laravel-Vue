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
        product_id: route.params.product_id, // Ici vous récupérez 'product_id' de l'URL
        fournisseur_id: null,
        qte: '',
        pu: '',
    });


    const errorMessage = ref('');
    const fournisseurs = ref([]);

    const fetchFournisseurs = async () => {
        try {
            const { data } = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            fournisseurs.value = data;
        } catch (error) {
            console.error("Erreur lors du chargement des fournisseurs:", error);
        }
    };

    const { error: queryError } = useQuery({
        queryKey: ['fournisseurs'],
        queryFn: fetchFournisseurs,
    });

    const addDetailProductMutation = useMutation({
        mutationFn: async (newDetailProduct) => {
            await axios.post('http://127.0.0.1:8000/api/detail_product', newDetailProduct);
        },
        onSuccess: () => {
            toast.success('Achat ajouté avec succès');
            router.push(`/detailProduct/show/${form.product_id}`); // Utilisation de product_id ici aussi
        },

        onError: () => {
            toast.error("Qté non suffisante");
        }
    });

    const handleSubmit = () => {
        const selectedDetailItems = form.fournisseur_id ? form.fournisseur_id.id : null;
        addDetailProductMutation.mutate({
            product_id: form.product_id, // Inclure product_id ici si nécessaire
            fournisseur_id: selectedDetailItems,
            qte: form.qte,
            pu: form.pu,
        });
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
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                        <div v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">PU</label>
                        <input type="number" v-model="form.pu" placeholder="PU" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                        <div v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</div>
                    </div>

                    <div v-if="error" class="text-red-500 text-sm mt-1 mb-4">Impossible de charger les produits.</div>

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