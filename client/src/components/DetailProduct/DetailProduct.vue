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

    const product_id = route.params.product_id;

    const fetchDetailProduct = async () => {
        const { data } = await axios.get(`http://127.0.0.1:8000/api/detail_product/show/${product_id}`);
        return data.detail_products ?? [];  // Pluralized the key to match controller response
    };

    const { data: detail_product, error } = useQuery({
        queryKey: ['detail_product', product_id],
        queryFn: fetchDetailProduct,
        onError: () => {
            toast.error('Failed to load detail products');
        }
    });

    const deleteDetailMutation = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/detail_product/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries(['detail_product', product_id]);
            toast.success('Detail product deleted successfully');
        },
        onError: () => {
            toast.error('Failed to delete detail product');
        }
    });

    const confirmAndDeleteDetail = (id) => {
        if (window.confirm('Are you sure you want to delete this detail_product?')) {
            deleteDetailMutation.mutate(id);
        }
    };
</script>

<template>
    <Navbar /><br>

    <RouterLink :to="`/detail_product/show/${product_id}/create`"
        class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <section>
        <div>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Supplier</th>
                        <th scope="col" class="px-6 py-3">Quantity</th>
                        <th scope="col" class="px-6 py-3">UP</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in detail_product || []" :key="item.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ item.fournisseur?.nom || 'N/A' }}</td>
                        <td class="px-6 py-4">{{ item.qte }}</td>
                        <td class="px-6 py-4">{{ item.pu }}</td>
                        <td class="px-6 py-4">{{ item.qte * item.pu}} DH</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/detail_product/show/${product_id}/edit/${item.id}`"
                                class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>

                            <button @click="confirmAndDeleteDetail(item.id)"
                                class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>