<script setup>
    import { ref, reactive, watchEffect } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const productId = route.params.id;

    const form = reactive({
        fournisseur_id: null,
        code: '',
        designation: '',
        qte: '',
        pu: '',
    });

    // Fetch fournisseurs list
    const { data: fournisseurs } = useQuery({
        queryKey: ['fournisseurs'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            return response.data;
        },
        onError: () => toast.error('Error loading fournisseurs list'),
    });

    // Fetch product details
    const { data: productData } = useQuery({
        queryKey: ['product', productId],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/product/${productId}`);
            return response.data.product;
        },
        onError: () => toast.error('Failed to fetch product details'),
    });

    watchEffect(() => {
        if (productData.value && fournisseurs.value) {
            form.code = productData.value.code;
            form.designation = productData.value.designation;
            form.qte = productData.value.qte;
            form.pu = productData.value.pu;
            form.stock_min = productData.value.stock_min;
            form.min_sortie = productData.value.min_sortie;

            
            // Match the fournisseur object from the list using its ID
            form.fournisseur_id = fournisseurs.value.find(f => f.id === productData.value.fournisseur_id) || null;
        }
    });

    // Mutation to update product
    const updateProductMutation = useMutation({
        mutationFn: async (updatedProduct) => {
            await axios.put(`http://127.0.0.1:8000/api/product/${productId}`, updatedProduct);
        },
        onSuccess: () => {
            toast.success('Product updated successfully');
            queryClient.invalidateQueries(['product', productId]);
            router.push('/product');
        },
        onError: () => toast.error('Product was not updated'),
    });

    const handleUpdate = () => {
        updateProductMutation.mutate({
            fournisseur_id: form.fournisseur_id ? form.fournisseur_id.id : null,
            code: form.code,
            designation: form.designation,
            qte: form.qte,
            pu: form.pu,
            stock_min: form.stock_min,
            min_sortie: form.min_sortie,
        });
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Editer le produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Fournisseur</label>
                        <vue-multiselect
                            v-model="form.fournisseur_id"
                            :options="fournisseurs || []"
                            :searchable="true"
                            track-by="id"
                            label="nom"
                            placeholder="Choisissez un fournisseur"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" v-model="form.code" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Designation</label>
                        <input type="text" v-model="form.designation" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                        <input type="number" v-model="form.qte" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">PU</label>
                        <input type="number" step="0.01" v-model="form.pu" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">stock_min</label>
                        <input type="number" step="0.01" v-model="form.stock_min" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">min_sortie</label>
                        <input type="number" step="0.01" v-model="form.min_sortie" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full">
                            <i class="pi pi-pencil"></i> Editer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>