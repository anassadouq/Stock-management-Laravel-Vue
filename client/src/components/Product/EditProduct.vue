<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const productId = route.params.id;

    const form = reactive({
        fournisseur_id: '',
        code: '',
        designation: '',
        qte: '',
        pu: '',
    });

    const fournisseurs = ref([]); // Store fournisseurs list

    const handleUpdate = async () => {
        try {
            await axios.put(`http://127.0.0.1:8000/api/product/${productId}`, form);
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

            form.fournisseur_id = product.fournisseur_id;
            form.code = product.code;
            form.designation = product.designation;
            form.qte = product.qte;
            form.pu = product.pu;

            // Fetch fournisseurs list
            const fournisseursResponse = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            if (Array.isArray(fournisseursResponse.data)) {
                fournisseurs.value = fournisseursResponse.data; // Ensure it's an array
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
                        <select v-model="form.fournisseur_id" class="border-gray-300 rounded-md shadow-sm focus:ring w-full px-4 py-2">
                            <option value="" disabled selected>Choisissez un fournisseur</option>
                            <option v-for="fournisseur in fournisseurs" :key="fournisseur.id" :value="fournisseur.id">
                                {{ fournisseur.nom }}
                            </option>
                        </select>
                    </div>

                    <div>
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

                    <div class="block text-gray-700 font-bold mb-2">
                        <label for="pu">Price</label>
                        <input type="number" step="0.01" v-model="form.pu" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"><i class="pi pi-pencil"></i> Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>