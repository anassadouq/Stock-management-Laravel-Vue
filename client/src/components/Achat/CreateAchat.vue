<script setup>
    import { reactive, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import Navbar from '../Navbar/Navbar.vue';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();

    const form = reactive({
        client_id: route.params.client_id,
        product_id: null,
        qte: '',
    });

    const fetchProducts = async () => {
        const { data } = await axios.get('http://127.0.0.1:8000/api/product');
        return data;
    };

    const { data: products, error } = useQuery({
        queryKey: ['products'],
        queryFn: fetchProducts,
    });

    const addAchatMutation = useMutation({
        mutationFn: async (newProduct) => {
            await axios.post('http://127.0.0.1:8000/api/achat', newProduct);
        },
        onSuccess: () => {
            toast.success('Achat ajouté avec succès');
            router.push(`/achat/show/${form.client_id}`);
        },
        onError: () => {
            toast.error("L'achat n'a pas été ajouté");
        }
    });

    const handleSubmit = () => {
        const selectedProductId = form.product_id ? form.product_id.id : null;
        addAchatMutation.mutate({
            client_id: form.client_id,
            product_id: selectedProductId,
            qte: form.qte,
        });
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleSubmit">
                    <h2 class="text-3xl text-center font-semibold mb-6">Ajouter un achat</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Produit</label>
                        <vue-multiselect
                            v-model="form.product_id" 
                            :options="products" 
                            :searchable="true"
                            track-by="id"
                            label="designation"
                            placeholder="Choisissez un produit"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div v-if="error" class="text-red-500 text-sm mt-1 mb-4">Impossible de charger les produits.</div>

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

<style src="vue-multiselect/dist/vue-multiselect.css"></style>