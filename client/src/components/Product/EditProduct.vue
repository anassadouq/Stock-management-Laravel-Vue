<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect'; // Import the multi-select component

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const productId = route.params.id;

    const form = reactive({
        fournisseur_id: null, // Store the selected fournisseur object
        code: '',
        designation: '',
        qte: '',
        pu: '',
    });

    const fournisseurs = ref([]); // Store fournisseurs list

    const handleUpdate = async () => {
        try {
            // Extract the ID from the selected fournisseur object
            const fournisseurId = form.fournisseur_id ? form.fournisseur_id.id : null;

            const updatedProduct = {
                fournisseur_id: fournisseurId, // Send only the ID
                code: form.code,
                designation: form.designation,
                qte: form.qte,
                pu: form.pu,
            };

            await axios.put(`http://127.0.0.1:8000/api/product/${productId}`, updatedProduct);
            toast.success('Product updated successfully');
            router.push(`/product`);
        } catch (error) {
            console.error('Error updating product', error);
            toast.error('Product was not updated');
        }
    };

    onMounted(async () => {
        try {
            // Fetch the product details
            const response = await axios.get(`http://127.0.0.1:8000/api/product/${productId}`);
            const product = response.data.product;

            // Fetch fournisseurs list
            const fournisseursResponse = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            if (Array.isArray(fournisseursResponse.data)) {
                fournisseurs.value = fournisseursResponse.data; // Ensure it's an array

                // Find the selected fournisseur object based on the product's fournisseur_id
                const selectedFournisseur = fournisseurs.value.find(
                    (f) => f.id === product.fournisseur_id
                );

                // Populate the form
                form.fournisseur_id = selectedFournisseur || null; // Set the selected fournisseur object
                form.code = product.code;
                form.designation = product.designation;
                form.qte = product.qte;
                form.pu = product.pu;
            } else {
                console.error('Unexpected fournisseurs response:', fournisseursResponse.data);
                toast.error('Error loading fournisseurs list');
            }
        } catch (error) {
            console.error('Error fetching product or fournisseurs', error);
            toast.error('Failed to fetch data.');
        }
    });
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer le produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Fournisseur</label>
                        <vue-multiselect
                            v-model="form.fournisseur_id"
                            :options="fournisseurs"
                            :searchable="true"
                            track-by="id"
                            label="nom"
                            placeholder="Choisissez un fournisseur"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" v-model="form.code" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Designation</label>
                        <input type="text" v-model="form.designation" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                        <input type="number" v-model="form.qte" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Price</label>
                        <input type="number" step="0.01" v-model="form.pu" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline">
                            <i class="pi pi-pencil"></i> Editer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>