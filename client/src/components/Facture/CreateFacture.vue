<script setup>
    import { reactive, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import Navbar from '../Navbar/Navbar.vue';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();

    const form = reactive({
        client_id: route.params.client_id,
        num: '',
        date: '',
    });

    const errorMessage = ref('');
    const factures = ref([]);

    // Fetch factures from API
    const fetchFactures = async () => {
        try {
            const { data } = await axios.get('http://127.0.0.1:8000/api/facture');
            factures.value = data;
        } catch (error) {
            console.error("Erreur lors du chargement des produits:", error);
            throw new Error("Impossible de charger les produits.");
        }
    };

    // Use Vue Query for fetching factures
    const { error: queryError } = useQuery({
        queryKey: ['factures'],
        queryFn: fetchFactures,
    });

    // Mutation for adding achat
    const addAchatMutation = useMutation({
        mutationFn: async (achatData) => {
            return await axios.post('http://127.0.0.1:8000/api/facture', achatData);
        },
        onSuccess: () => {
            toast.success('Achat ajouté avec succès');
            router.push(`/facture/show/${form.client_id}`);
        },
        onError: (error) => {
            console.error("Erreur API:", error.response?.data);
            toast.error(error.response?.data?.message || "Une erreur s'est produite.");
        }
    });

    // Handle form submission
    const handleSubmit = async () => {
        try {
            await addAchatMutation.mutateAsync({
                client_id: form.client_id,
                num: form.num,
                date: form.date,
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
                        <label class="block text-gray-700 font-bold mb-2">Num</label>
                        <input type="text" v-model="form.num" placeholder="Num" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                        <div v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Date</label>
                        <input type="date" v-model="form.date" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
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