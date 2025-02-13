<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import { useRoute, useRouter } from 'vue-router';

    const achats = ref([]);
    const errorMessage = ref('');
    const route = useRoute();
    const router = useRouter();
    import { useToast } from 'vue-toastification';

    const client_id = route.params.client_id;
    const toast = useToast();

    const fetchAchat = async () => {
        try {
            const response = await axios.get(`http://127.0.0.1:8000/api/achat/show/${client_id}`);
            achats.value = response.data.achats;
        } catch (error) {
            console.error('Erreur lors du chargement des achats', error);
            errorMessage.value = 'Impossible de charger les achats.';
        }
    };

    const deleteAchat = async (id) => {
        try {
            const confirmDelete = window.confirm('Are you sure you want to delete this achat?');
            if (confirmDelete) {
                await axios.delete(`http://127.0.0.1:8000/api/achat/${id}`);
                fetchAchat();
                toast.success('achat deleted successfully');
            }
        } catch (error) {
            console.error('Error deleting achat', error);
            toast.error('achat was not deleted');

        }
    };

    onMounted(fetchAchat);
</script>

<template>
    <RouterLink :to="`/achat/show/${client_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div>
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