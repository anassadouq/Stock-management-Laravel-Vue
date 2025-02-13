<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import SearchForm from '../DataTable/SearchForm.vue';

const clients = ref([]);
const errorMessage = ref('');
const toast = useToast();
const searchFilter = ref(''); // Define search filter

const fetchClients = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/client');
        clients.value = response.data;
    } catch (error) {
        console.error('Erreur lors du chargement des produits', error);
        errorMessage.value = 'Impossible de charger les produits.';
    }
};

const deleteClient = async (id) => {
    try {
        if (window.confirm('Are you sure you want to delete this client?')) {
            await axios.delete(`http://127.0.0.1:8000/api/client/${id}`);
            fetchClients();
            toast.success('client deleted successfully');
        }
    } catch (error) {
        console.error('Error deleting client', error);
        toast.error('client was not deleted');
    }
};

// Computed property for filtering the items
const filteredClients = computed(() => {
    if (searchFilter.value) {
        return clients.value.filter(client => {
            const searchText = searchFilter.value.toLowerCase();
            return (
                client.nom.toLowerCase().includes(searchText) ||
                client.adresse.toLowerCase().includes(searchText) ||
                client.tel.toString().toLowerCase().includes(searchText)
            );
        });
    }
    return clients.value;
});

// Handle search from child component
const handleSearch = (search) => {
    searchFilter.value = search;
};

onMounted(fetchClients);
</script>

<template>
    <RouterLink to="/client/create">Add</RouterLink><br><br>
    
    <section>
        <div>
            <SearchForm @search="handleSearch"/>
            <h2>Liste des produits</h2>
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
                    <tr v-for="client in filteredClients" :key="client.id">
                        <td>{{ client.nom }}</td>
                        <td>{{ client.adresse}}</td>
                        <td>{{ client.tel }}</td>
                        <td>
                            <RouterLink :to="`/achat/show/${client.id}`">Show</RouterLink>
                            <RouterLink :to="`/client/edit/${client.id}`">Edit</RouterLink>
                            <button @click="deleteClient(client.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>