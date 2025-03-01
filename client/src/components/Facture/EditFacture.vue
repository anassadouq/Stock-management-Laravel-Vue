<script setup>
    import { ref, reactive } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const client_id = ref(route.params.client_id);
    const factureId = ref(route.params.id);

    const form = reactive({
        client_id: client_id.value,
        num: '',
        date: '',
    });

    // Fetch facture details
    const { data: factureData } = useQuery({
        queryKey: ['facture', factureId.value],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/facture/${factureId.value}`);
            return response.data.facture;
        },
        onSuccess: (editFacture) => {
            if (editFacture) {
                Object.assign(form, {
                    client_id: editFacture.client_id || client_id.value,
                    num: editFacture.num || '',
                    date: editFacture.date || '',
                });
            }
        },
        onError: () => toast.error('Failed to fetch facture details'),
    });

    // Mutation to update facture
    const updateFactureMutation = useMutation({
        mutationFn: async (updateFacture) => {
            await axios.put(`http://127.0.0.1:8000/api/facture/${factureId.value}`, updateFacture);
        },
        onSuccess: () => {
            toast.success('facture updated successfully');
            queryClient.invalidateQueries(['facture', factureId.value]);
            router.push(`/facture/show/${client_id.value}`);
        },
        onError: () => toast.error('facture was not updated'),
    });

    // Handle Update
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