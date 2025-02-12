<script setup>
import { reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const clientId = route.params.id;

const form = reactive({
    nom: '',
    adresse: '',
    tel: '',
});

const handleUpdate = async () => {
    const updateclient = {
        nom: form.nom,
        adresse: form.adresse,
        tel: form.tel,
    };

    try {
        const response = await axios.put(`http://127.0.0.1:8000/api/client/${clientId}`, updateclient);
        toast.success('client updated successfully');
        router.push(`/client`);
    } catch (error) {
        console.error('Error updating client', error);
        toast.error('client was not updated');
    }
};

onMounted(async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/client/${clientId}`);
        const client = response.data.client;

        // Populate the form with the fetched client data
        form.nom = client.nom;
        form.adresse = client.adresse;
        form.tel = client.tel;
    } catch (error) {
        console.error('Error fetching client', error);
        toast.error('Failed to fetch client details.');
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