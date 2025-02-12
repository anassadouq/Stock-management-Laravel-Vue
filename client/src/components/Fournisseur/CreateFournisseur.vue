<script setup>
import { reactive, ref } from 'vue';
import router from '@/router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();

const form = reactive({
    nom: '',
    adresse: '',
    tel: '',
});

const errorMessage = ref('');

const handleSubmit = async () => {
    const newFournisseur = {
        nom: form.nom,
        adresse: form.adresse,
        tel: form.tel,
    };

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/fournisseur', newFournisseur);
        toast.success('Fournisseur added successfully');
        router.push('/fournisseur');
    } catch (error) {
        console.error('Erreur lors de l\'ajout du fournisseur', error);
        errorMessage.value = 'Impossible d\'ajouter le fournisseur. Veuillez vérifier les informations.';
        toast.error('Fournisseur was not added');
    }
};
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-24">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un Fournisseur</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nom</label>
                        <input type="text" 
                               v-model="form.nom" 
                               name="nom" 
                               placeholder="Nom" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Adresse</label>
                        <input type="text" 
                               v-model="form.adresse" 
                               name="adresse" 
                               placeholder="Adresse" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Téléphone</label>
                        <input type="text" 
                               v-model="form.tel" 
                               name="tel" 
                               placeholder="Téléphone" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div v-if="errorMessage" class="text-red-500 text-sm mt-1 mb-4">{{ errorMessage }}</div>

                    <div>
                        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>