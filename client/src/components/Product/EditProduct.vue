<script setup>
    import { reactive, watch, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const magasin_id = route.params.magasin_id;
    const productId = route.params.id;

    const form = reactive({
        magasin_id: magasin_id,
        code: '',
        designation: '',
        stock_min: '',
        min_sortie: ''
    });

    // Fetch product details
    const { data: magasinData } = useQuery({
        queryKey: ['product', productId],
        queryFn: async () => {
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/product/${magasin_id}`);
                return response.data.magasin || {}; // Handle empty response gracefully
            } catch (error) {
                console.error("API error:", error);
                toast.error(error?.response?.data?.message || 'Erreur lors de la récupération du produit');
                return {}; // Returning an empty object on error
            }
        },
    });

    watch(magasinData, (newData) => {
        if (newData && newData.products && newData.products.length > 0) {
            const product = newData.products.find(p => p.id === parseInt(productId));
            if (product) {
                form.code = product.code || '';
                form.designation = product.designation || '';
                form.stock_min = product.stock_min || '';
                form.min_sortie = product.min_sortie || '';
            }
        }
    });

    const updateProductMutation = useMutation({
        mutationFn: async (updatedProduct) => {
            await axios.put(`http://127.0.0.1:8000/api/product/${productId}`, updatedProduct);
        },
        onSuccess: () => {
            toast.success('Produit mis à jour avec succès');
            queryClient.invalidateQueries(['product', productId]);
            router.push(`/product/show/${magasin_id}`);
        },
        onError: () => toast.error('Échec de la mise à jour du produit'),
    });

    // Handle update
    const handleUpdate = () => {
        updateProductMutation.mutate({
            magasin_id: form.magasin_id,
            code: form.code,
            designation: form.designation,
            stock_min: form.stock_min,
            min_sortie: form.min_sortie
        });
    };
</script>


<template>
    <Navbar />
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-5">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Éditer le produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Code</label>
                        <input type="text" v-model="form.code" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Désignation</label>
                        <input type="text" v-model="form.designation" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Stock Minimum</label>
                        <input type="number" step="0.01" v-model="form.stock_min" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Minimum Sortie</label>
                        <input type="number" step="0.01" v-model="form.min_sortie" class="border-gray-300 rounded-md shadow-sm w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full">
                            <i class="pi pi-pencil"></i> Éditer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>