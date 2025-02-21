<script setup>
    import { reactive, watch } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const id = route.params.id;

    const form = reactive({
        nom: '',
    });

    // Fetch magasin data using TanStack Query
    const { data, error } = useQuery({
        queryKey: ['magasin', id],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/magasin/${id}`);
            return response.data.magasin;
        },
        onError: () => {
            toast.error('Failed to fetch magasin details.');
        },
    });

    // Update form when data is fetched
    watch(data, (newData) => {
        if (newData && newData.nom) {
            form.nom = newData.nom;
        }
    });

    // Mutation to update magasin
    const mutation = useMutation({
        mutationFn: async () => {
            const updateMagasin = {
            nom: form.nom,
            };
            await axios.put(`http://127.0.0.1:8000/api/magasin/${id}`, updateMagasin);
        },
        onSuccess: () => {
            toast.success('magasin updated successfully');
            router.push('/magasin');
        },
        onError: (err) => {
            toast.error(err?.response?.data?.message || 'magasin was not updated');
        },
    });

    const handleUpdate = () => {
        mutation.mutate();
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">

                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer le magasin</h2>

                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
                        <input type="text" v-model="form.nom" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"><i class="pi pi-pencil"></i> Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>