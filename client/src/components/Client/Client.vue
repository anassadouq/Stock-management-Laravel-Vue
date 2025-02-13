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
    
    <section>
        <div>
            <RouterLink to="/client/create" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
                <i class="pi pi-plus-circle"></i>
            </RouterLink><br><br>

            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Adresse</th>
                        <th scope="col" class="px-6 py-3">Téléphone</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in filteredClients" :key="client.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ client.nom }}</td>
                        <td class="px-6 py-4">{{ client.adresse}}</td>
                        <td class="px-6 py-4">{{ client.tel }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/achat/show/${client.id}`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-eye"></i>
                            </RouterLink>
                            <RouterLink :to="`/client/edit/${client.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>
                            <button @click="deleteClient(client.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>