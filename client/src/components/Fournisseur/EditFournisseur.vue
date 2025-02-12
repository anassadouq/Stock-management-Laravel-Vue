<script setup>
import { reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const fournisseurId = route.params.id;

const form = reactive({
    nom: '',
    adresse: '',
    tel: '',
});

const handleUpdate = async () => {
    const updateFournisseur = {
        nom: form.nom,
        adresse: form.adresse,
        tel: form.tel,
    };

    try {
        const response = await axios.put(`http://127.0.0.1:8000/api/fournisseur/${fournisseurId}`, updateFournisseur);
        toast.success('Fournisseur updated successfully');
        router.push(`/fournisseur`);
    } catch (error) {
        console.error('Error updating fournisseur', error);
        toast.error('Fournisseur was not updated');
    }
};

onMounted(async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/fournisseur/${fournisseurId}`);
        const fournisseur = response.data.fournisseur;

        // Populate the form with the fetched fournisseur data
        form.nom = fournisseur.nom;
        form.adresse = fournisseur.adresse;
        form.tel = fournisseur.tel;
    } catch (error) {
        console.error('Error fetching fournisseur', error);
        toast.error('Failed to fetch fournisseur details.');
    }
});
</script>

<template>
    <section>
        <div>
            <div>
                <form @submit.prevent="handleUpdate">
                    <div>
                        <label for="nom">Nom</label>
                        <!-- Use only v-model to bind the value -->
                        <input type="text" v-model="form.nom" placeholder="Nom" />
                    </div>
                    <div class="mb-4">
                        <label for="adresse">Adresse</label>
                        <input type="text" v-model="form.adresse" placeholder="Adresse" />
                    </div>
                    <div class="mb-4">
                        <label for="tel">Tel</label>
                        <input type="text" v-model="form.tel" placeholder="Tel" />
                    </div>

                    <div>
                        <button type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>