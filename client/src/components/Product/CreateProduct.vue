<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const route = useRoute();
const router = useRouter();

const form = reactive({
    fournisseur_id: '', // Will be set from the URL
    code: '',
    designation: '',
    qte: '',
    pu: ''
});

const errorMessage = ref('');

// Get fournisseur_id from the URL when the component is mounted
onMounted(() => {
    form.fournisseur_id = route.params.fournisseur_id || ''; 
});

const handleSubmit = async () => {
    const newProduct = {
        fournisseur_id: form.fournisseur_id, // Still included in the request
        code: form.code,
        designation: form.designation,
        qte: form.qte,
        pu: form.pu,
    };

    try {
        await axios.post('http://127.0.0.1:8000/api/product', newProduct);
        toast.success('Product added successfully');
        router.push('/fournisseur');
    } catch (error) {
        console.error('Erreur lors de l\'ajout du product', error);
        errorMessage.value = 'Impossible d\'ajouter le product. Veuillez vérifier les informations.';
        toast.error('Product was not added');
    }
};
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-24">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un product</h2>

                    <!-- Hidden input field for fournisseur_id -->
                    <input type="hidden" v-model="form.fournisseur_id" name="fournisseur_id" />

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" 
                               v-model="form.code" 
                               name="code" 
                               placeholder="Code" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Designation</label>
                        <input type="text" 
                               v-model="form.designation" 
                               name="designation" 
                               placeholder="Designation" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Qte</label>
                        <input type="text" 
                               v-model="form.qte" 
                               name="qte" 
                               placeholder="Qte" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">PU</label>
                        <input type="text" 
                               v-model="form.pu" 
                               name="pu" 
                               placeholder="PU" 
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
