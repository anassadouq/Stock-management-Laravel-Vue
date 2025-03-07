<script setup>
    import { reactive, watchEffect } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const client_id = route.params.client_id;
    const factureId = parseInt(route.params.id);

    const form = reactive({
        client_id: client_id,
        num: '',
        date: '',
    });

    // Fetch all factures for the client
    const { data: clientFactureData } = useQuery({
        queryKey: ['facture', client_id],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/facture/${client_id}`);
            return response.data;
        },
    });

    // Watch the data and update the form
    watchEffect(() => {
        if (clientFactureData.value && clientFactureData.value.facture) {
            const facture = clientFactureData.value.facture.find(f => f.id === factureId);
            if (facture) {
                form.num = facture.num || '';
                form.date = facture.date || '';
            }
        }
    });

    // Mutation to update facture
    const updateFactureMutation = useMutation({
        mutationFn: async (updatedFacture) => {
            await axios.put(`http://127.0.0.1:8000/api/facture/${factureId}`, updatedFacture);
        },
        onSuccess: () => {
            toast.success('Facture mise à jour avec succès');
            queryClient.invalidateQueries(['facture', client_id]); // Invalider la requête pour mettre à jour la liste
            setTimeout(() => {
                router.push(`/facture/show/${client_id}`);
            }, 500);
        },
        onError: () => toast.error('Échec de la mise à jour de la facture'),
    });

    const handleUpdate = () => {
        updateFactureMutation.mutate({
            client_id: form.client_id,
            num: form.num,
            date: form.date,
        });
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer le produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Num</label>
                        <input type="text" v-model="form.num" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Date</label>
                        <input type="date" v-model="form.date" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full">
                            <i class="pi pi-pencil"></i> Editer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>