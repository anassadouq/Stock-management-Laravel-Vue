<script setup>
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();

    const magasin_id = route.params.magasin_id;

    const fetchProducts = async () => {
        const { data } = await axios.get(`http://127.0.0.1:8000/api/product/show/${magasin_id}`);
        return data.products;
    };

    const { data: products, error } = useQuery({
        queryKey: ['products', magasin_id],
        queryFn: fetchProducts
    });

    const deleteProductMutaion = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/product/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries(['products', magasin_id]);
            toast.success('product deleted successfully');
        },
        onError: () => {
            toast.error('product was not deleted');
        }
    });

    const confirmAndDeleteProduct = (id) => {
        if (window.confirm('Are you sure you want to delete this product?')) {
            deleteProductMutaion.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink :to="`/product/show/${magasin_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div v-if="error">Impossible de charger les products.</div>
        <div v-else>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Fournisseur</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Stock Min</th>
                        <th scope="col" class="px-6 py-3">Min sortie</th>
                        <th scope="col" class="px-6 py-3">Qte</th>
                        <th scope="col" class="px-6 py-3">PU</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ product.code }}</td>
                        <td class="px-6 py-4">{{ product.fournisseur?.nom || 'N/A' }}</td>
                        <td class="px-6 py-4">{{ product.designation }}</td>
                        <td class="px-6 py-4">{{ product.stock_min }}</td>
                        <td class="px-6 py-4">{{ product.min_sortie }}</td>

                        <td class="px-6 py-4 flex items-center space-x-2" :class="{'text-red-600': product.qte <= product.stock_min}">
                            <i v-if="product.qte <= product.stock_min" class="pi pi-exclamation-triangle text-red-600 text-xl"></i>
                            <b>{{ product.qte }}</b>
                        </td>
                        <td class="px-6 py-4">{{ product.pu }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/product/show/${magasin_id}/edit/${product.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>
                            <button @click="confirmAndDeleteProduct(product.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>