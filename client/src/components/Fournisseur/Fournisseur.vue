<script setup>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';

    const fournisseurs = ref([]);
    const searchFilter = ref('');

    const fetchFournisseurs = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            fournisseurs.value = response.data;
        } catch (error) {
            console.error('Erreur lors du chargement des fournisseurs', error);
        }
    };

    // Computed property for filtering the items
    const filteredItems = computed(() => {
        if (searchFilter.value) {
            return fournisseurs.value.filter(item => 
                item.nom.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
                item.adresse.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
                item.tel.toLowerCase().includes(searchFilter.value.toLowerCase())
            );
        }
        return fournisseurs.value;
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    
    const deleteFournisseur = async (id) => {
        try {
            const confirmDelete = window.confirm('Are you sure you want to delete this fournisseur?');
            if (confirmDelete) {
                await axios.delete(`http://127.0.0.1:8000/api/fournisseur/${id}`);
                fetchFournisseurs();
                toast.success('Fournisseur deleted successfully');
            }
        } catch (error) {
            console.error('Error deleting Fournisseur', error);
            toast.error('Fournisseur was not deleted');

        }
    };

    onMounted(fetchFournisseurs);
</script>

<template>
    <div>
        <RouterLink to="/fournisseur/create">Add</RouterLink><br><br>

        <SearchForm @search="handleSearch" />
        <table width="100%" style="text-align: center">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fournisseur in filteredItems" :key="fournisseur.id">
                    <td>{{ fournisseur.nom }}</td>
                    <td>{{ fournisseur.adresse }}</td>
                    <td>{{ fournisseur.tel }}</td>
                    <td>
                        <RouterLink :to="`/fournisseur/edit/${fournisseur.id}`">Edit</RouterLink>
                        <button @click="deleteFournisseur(fournisseur.id)" >Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>