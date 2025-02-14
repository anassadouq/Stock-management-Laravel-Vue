<script setup>
    import { reactive, ref } from 'vue';
    import { useMutation } from '@tanstack/vue-query';
    import router from '@/router';
    import axios from 'axios';

    const form = reactive({
        nom: '',
        adresse: '',
        tel: '',
    });

    const errorMessage = ref('');

    const mutation = useMutation({
        mutationFn: async (newFournisseur) => {  // mutationFn fonction qui envoie une requête POST pour ajouter une nouvelle personne via axios.post
            return await axios.post('http://127.0.0.1:8000/api/fournisseur', newFournisseur);
        },
        onSuccess: () => {
            router.push('/fournisseur');
        },
        onError: (error) => {
            console.error('Erreur lors de l\'ajout du person', error);
            errorMessage.value = "Impossible d'ajouter le person. Veuillez vérifier les informations.";
        },
    });

    const handleSubmit = () => {
        mutation.mutate({ ...form }); // ...form  passer une copie des données du formulaire
    };
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un Fournisseur</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nom</label>
                        <input type="text" v-model="form.nom" name="nom" placeholder="Nom" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Adresse</label>
                        <input type="text" v-model="form.adresse" name="adresse" placeholder="Adresse" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Téléphone</label>
                        <input type="text" v-model="form.tel" name="tel" placeholder="Téléphone" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div v-if="errorMessage" class="text-red-500 text-sm mt-1 mb-4">{{ errorMessage }}</div>

                    <div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>