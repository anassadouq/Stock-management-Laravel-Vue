<script setup>
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();
    const userRole = ref(null);


    const client_id = route.params.client_id;

    onMounted(() => {
        userRole.value = localStorage.getItem('role');
    });

    // Fetch factures from API
    const fetchFactures = async () => {
        try {
            const { data } = await axios.get(`http://127.0.0.1:8000/api/facture/show/${client_id}`);
            return data.facture;  // FIXED: Correct API response key
        } catch (error) {
            console.error("Erreur lors du chargement des factures:", error);
            throw new Error("Impossible de charger les factures.");
        }
    };

    // Fetch factures with Vue Query
    const { data: facture, error, isLoading } = useQuery({
        queryKey: ['facture', client_id],
        queryFn: fetchFactures,
    });

    // Mutation for Deleting facture
    const deleteFactureMutation = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/facture/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries(['facture', client_id]);
            toast.success('facture supprimé avec succès');
        },
        onError: () => {
            toast.error("L'facture n'a pas été supprimé");
        }
    });

    // Delete facture Function
    const deleteFacture = (id) => {
        if (window.confirm('Êtes-vous sûr de vouloir supprimer cet facture ?')) {
            deleteFactureMutation.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink v-if="userRole === 'admin' || userRole === 'super_admin'" :to="`/facture/show/${client_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Numero</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in facture" :key="item.id" class="bg-white border-b">
                        <td class="px-6 py-4">{{ item.num }}</td>
                        <td class="px-6 py-4">{{ item.date }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/achat/show/${item.id}`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-eye"></i>
                            </RouterLink>

                            <RouterLink v-if="userRole === 'admin' || userRole === 'super_admin'" :to="`/facture/show/${client_id}/edit/${item.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>

                            <button v-if="userRole === 'admin' || userRole === 'super_admin'" @click="deleteFacture(item.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>