<script setup>
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import { ref, computed } from 'vue';
    import SearchForm from '../DataTable/SearchForm.vue';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';
    import jsPDF from 'jspdf';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const queryClient = useQueryClient();
    const searchFilter = ref('');

    const magasin_id = route.params.magasin_id;

    const fetchProducts = async () => {
        const { data } = await axios.get(`http://127.0.0.1:8000/api/product/show/${magasin_id}`);
        return data.products;
    };

    const { data: products, error } = useQuery({
        queryKey: ['products', magasin_id],
        queryFn: fetchProducts
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (products.value || []).filter(item =>
            item.code.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.designation.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.stock_min.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.min_sortie.toLowerCase().includes(searchFilter.value.toLowerCase())
    );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

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

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('Liste des produits', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
            
        // Entêtes du tableau
        pdf.text('Produit', 10, y);
        pdf.text('Designation', 50, y);
        pdf.text('Stock min', 120, y);
        pdf.text('Min sortie', 140, y);
        pdf.text('Total QTE', 160, y);

        y += 10;


        // Remplissage du tableau avec les données
        filteredItems.value.forEach(f => {
            pdf.text(f.code, 10, y);
            pdf.text(f.designation, 50, y);
            pdf.text(f.stock_min.toString(), 120, y);
            pdf.text(f.min_sortie.toString(), 140, y);
            pdf.text(f.detail_products_sum_qte.toString(), 160, y);
            y += 10;
        });

        pdf.save('Product.pdf');
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink :to="`/product/show/${magasin_id}/create`" class="text-white bg-blue-500 hover:bg-blue-600 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <button class="text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg text-sm px-5 py-2.5 mx-1" @click="downloadPDF">
        <i class="pi pi-download"></i>
    </button><br><br>

    <section>
        <div v-if="error">Impossible de charger les products.</div>
        <div v-else>
            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Minimum Stock</th>
                        <th scope="col" class="px-6 py-3">Minimum Output</th>
                        <th scope="col" class="px-6 py-3">Total Stock</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredItems" :key="product.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ product.code }}</td>
                        <td class="px-6 py-4">{{ product.designation }}</td>
                        <td class="px-6 py-4">{{ product.stock_min }}</td>
                        <td class="px-6 py-4">{{ product.min_sortie }}</td>
                        <td class="px-6 py-4 flex items-center space-x-2" :class="{'text-red-600': product.detail_products_sum_qte <= product.stock_min}">
                            <i v-if="product.detail_products_sum_qte <= product.stock_min" class="pi pi-exclamation-triangle text-red-600 text-xl"></i>
                            &nbsp;{{ product.detail_products_sum_qte }}
                        </td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/detail_product/show/${product.id}`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-eye"></i>
                            </RouterLink>

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