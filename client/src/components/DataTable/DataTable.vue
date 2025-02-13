<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import SearchForm from './SearchForm.vue';

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

onMounted(fetchFournisseurs);
</script>

<template>
    <div>
        <SearchForm @search="handleSearch" />
        <table width="100%" style="text-align: center">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fournisseur in filteredItems" :key="fournisseur.id">
                    <td>{{ fournisseur.nom }}</td>
                    <td>{{ fournisseur.adresse }}</td>
                    <td>{{ fournisseur.tel }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>