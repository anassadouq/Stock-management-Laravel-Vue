<script setup>
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const client_id = route.params.client_id;

    const fetchAchats = async () => {
        const { data } = await axios.get(`http://127.0.0.1:8000/api/achat/show/${client_id}`);
        return data.achats;
    };

    const { data: achats, error } = useQuery({
        queryKey: ['achats', client_id],
        queryFn: fetchAchats
    });

    const deleteAchatMutation = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/achat/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries(['achats', client_id]);
            toast.success('Achat deleted successfully');
        },
        onError: () => {
            toast.error('Achat was not deleted');
        }
    });

    const deleteAchat = (id) => {
        if (window.confirm('Are you sure you want to delete this achat?')) {
            deleteAchatMutation.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink :to="`/achat/show/${client_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div v-if="error">Impossible de charger les achats.</div>
        <div v-else>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Produit</th>
                        <th scope="col" class="px-6 py-3">Prix Unitaire</th>
                        <th scope="col" class="px-6 py-3">Quantité</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="achat in achats" :key="achat.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ achat.product.designation }}</td>
                        {{achat.client_id.nom}}
                        <td class="px-6 py-4">{{ achat.product.pu + ' DH' }}</td>
                        <td class="px-6 py-4">{{ achat.qte }}</td>
                        <td class="px-6 py-4">{{ achat.qte * achat.product.pu + ' DH' }}</td>
                        <td class="px-6 py-4">
                            <button @click="deleteAchat(achat.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>