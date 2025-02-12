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
    <RouterLink :to="`/achat/show/${client_id}/create`">Add</RouterLink>
    <section>
        <div>
            <h2>Liste des achats</h2>
            <table width="100%" style="text-align: center">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="achat in achats" :key="achat.id">
                        <td>{{ achat.product.designation }}</td>
                        <td>{{ achat.product.pu + ' DH' }}</td>
                        <td>{{ achat.qte }}</td>
                        <td>{{ achat.qte * achat.product.pu + ' DH' }}</td>
                        <td>
                            <RouterLink :to="`/achat/show/${achat.id}/edit?client_id=${achat.client_id}`">Edit</RouterLink>
                            <button @click="deleteAchat(achat.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>