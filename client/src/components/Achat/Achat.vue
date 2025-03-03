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

    const facture_id = route.params.facture_id;

    // Fetch Achats from API
    const fetchAchats = async () => {
        try {
            const { data } = await axios.get(`http://127.0.0.1:8000/api/achat/show/${facture_id}`);
            return data.achat;  // FIXED: Correct API response key
        } catch (error) {
            console.error("Erreur lors du chargement des achats:", error);
            throw new Error("Impossible de charger les achats.");
        }
    };

    // Fetch Achats with Vue Query
    const { data: achat, error, isLoading } = useQuery({
        queryKey: ['achat', facture_id],
        queryFn: fetchAchats,
    });

    // Mutation for Deleting Achat
    const deleteAchatMutation = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/achat/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries(['achat', facture_id]);
            toast.success('Achat supprimé avec succès');
        },
        onError: () => {
            toast.error("L'achat n'a pas été supprimé");
        }
    });

    // Delete Achat Function
    const deleteAchat = (id) => {
        if (window.confirm('Êtes-vous sûr de vouloir supprimer cet achat ?')) {
            deleteAchatMutation.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink :to="`/achat/show/${facture_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Produit</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Quantité</th>
                        <th scope="col" class="px-6 py-3">PU</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in achat" :key="item.id" class="bg-white border-b">
                        <td class="px-6 py-4">{{ item.product?.code }}</td>
                        <td class="px-6 py-4">{{ item.product?.designation }}</td>
                        <td class="px-6 py-4">{{ item.qte }}</td>
                        <td class="px-6 py-4">{{ item.pu }}</td>
                        <td class="px-6 py-4">{{ item.qte * item.pu }} DH</td>

                        <td class="px-6 py-4">
                            <button @click="deleteAchat(item.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>