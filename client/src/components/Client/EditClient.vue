<script setup>
    import { reactive, watch } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const id = route.params.id;

    const form = reactive({
        nom: '',
        adresse: '',
        tel: '',
    });

    // Fetch client data using TanStack Query
    const { data, error } = useQuery({
        queryKey: ['client', id],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/client/${id}`);
            return response.data.client;
        },
        onError: () => {
            toast.error('Failed to fetch client details.');
        },
    });

    // Update form when data is fetched
    watch(data, (newData) => {
        if (newData && newData.nom) {
            form.nom = newData.nom;
            form.adresse = newData.adresse;
            form.tel = newData.tel;
        }
    });

    // Mutation to update client
    const mutation = useMutation({
        mutationFn: async () => {
            const updateClient = {
            nom: form.nom,
            adresse: form.adresse,
            tel: form.tel,
            };
            await axios.put(`http://127.0.0.1:8000/api/client/${id}`, updateClient);
        },
        onSuccess: () => {
            toast.success('client updated successfully');
            router.push('/client');
        },
        onError: (err) => {
            toast.error(err?.response?.data?.message || 'client was not updated');
        },
    });

    const handleUpdate = () => {
        mutation.mutate();
    };
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">

                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer le client</h2>

                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
                        <input type="text" v-model="form.nom" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>
                    <div class="mb-4">
                        <label for="adresse" class="block text-gray-700 font-bold mb-2">Adresse</label>
                        <input type="text" v-model="form.adresse" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>
                    <div class="mb-4">
                        <label for="tel" class="block text-gray-700 font-bold mb-2">Tel</label>
                        <input type="text" v-model="form.tel" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"><i class="pi pi-pencil"></i> Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>