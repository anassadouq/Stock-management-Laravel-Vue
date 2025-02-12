<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import { useRoute, useRouter } from 'vue-router';

    const clients = ref([]);
    const errorMessage = ref('');
    const route = useRoute();
    const router = useRouter();
    import { useToast } from 'vue-toastification';

    const toast = useToast();

    const fetchClient = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/client');
            clients.value = response.data;
        } catch (error) {
            console.error('Erreur lors du chargement des clients', error);
            errorMessage.value = 'Impossible de charger les clients.';

        }
    };

    const deleteClient = async (id) => {
        try {
            const confirmDelete = window.confirm('Are you sure you want to delete this client?');
            if (confirmDelete) {
                await axios.delete(`http://127.0.0.1:8000/api/client/${id}`);
                fetchClient();
                toast.success('client deleted successfully');
            }
        } catch (error) {
            console.error('Error deleting client', error);
            toast.error('client was not deleted');

        }
    };

    onMounted(fetchClient);
</script>

<template>
    <RouterLink to="/client/create">Add</RouterLink>
    <section>
        <div>
            <h2>Liste des clients</h2>
            <table width="100%" style="text-align: center">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in clients" :key="client.id">
                        <td>{{ client.nom }}</td>
                        <td>{{ client.adresse }}</td>
                        <td>{{ client.tel }}</td>
                        <td>
                            <RouterLink :to="`/client/edit/${client.id}`">Edit</RouterLink>
                            <button @click="deleteClient(client.id)" >Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>