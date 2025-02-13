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
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer un Fournisseur</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nom</label>
                        <input type="text" v-model="form.nom" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Adresse</label>
                        <input type="text" v-model="form.adresse" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Tel</label>
                        <input type="text" v-model="form.tel" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"><i class="pi pi-pencil"></i> Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>