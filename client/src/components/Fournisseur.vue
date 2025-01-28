<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const fournisseurs = ref([]);
const errorMessage = ref('');
const route = useRoute();
const router = useRouter();

const fournisseurId = route.params.id;

const fetchFournisseurs = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
        fournisseurs.value = response.data;
    } catch (error) {
        console.error('Erreur lors du chargement des fournisseurs', error);
        errorMessage.value = 'Impossible de charger les fournisseurs.';
    }
};

const deleteFournisseur = async (id) => {
    try {
        const confirmDelete = window.confirm('Are you sure you want to delete this fournisseur?');
        if (confirmDelete) {
            await axios.delete(`http://127.0.0.1:8000/api/fournisseur/${id}`);
            fetchFournisseurs();
        }
    } catch (error) {
        console.error('Error deleting Fournisseur', error);
    }
};

onMounted(fetchFournisseurs);
</script>

<template>
    <RouterLink to="/fournisseur/add">Add</RouterLink>
    <section>
        <div>
            <h2>Liste des Fournisseurs</h2>
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
                    <tr v-for="fournisseur in fournisseurs" :key="fournisseur.id">
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
    </section>
</template>