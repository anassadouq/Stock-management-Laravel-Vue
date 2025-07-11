<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';
    import jsPDF from 'jspdf';

    const queryProducts = useQueryClient();
    const searchFilter = ref('');
    const userRole = ref(null);

    onMounted(() => {
        userRole.value = localStorage.getItem('role');
    });

    // Fetch products
    const { data: products, error } = useQuery({
        queryKey: ['products'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (products.value || []).filter(item =>
            item.magasin.nom.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.code.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.designation.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.stock_min.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.min_sortie.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.product.detail_products_sum_qte.toLowerCase().includes(searchFilter.value.toLowerCase()) 
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete product mutation
    const deleteProduct = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/product/${id}`);
        },
        onSuccess: () => {
            queryProducts.invalidateQueries({ queryKey: ['products'] });
            toast.success('product deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting product', error);
            toast.error('product was not deleted');
        },
    });

    // Confirm and delete
    const confirmDeletetingProduct = (id) => {
        if (window.confirm('Are you sure you want to delete this product?')) {
            deleteProduct.mutate(id);
        }
    };

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('Product List', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
        
        pdf.text('Magasin', 10, y);
        pdf.text('Code', 40, y);
        pdf.text('Designation', 70, y);
        pdf.text('Stock min', 120, y);
        pdf.text('Min sortie', 150, y);
        pdf.text('Stock Total', 180, y);

        y += 10;
        
        filteredItems.value.forEach(f => {
            pdf.text(f.magasin.nom, 10, y);
            pdf.text(f.code, 40, y);
            pdf.text(f.designation, 70, y);
            pdf.text(f.stock_min.toString(), 120, y);
            pdf.text(f.min_sortie.toString(), 150, y);
            pdf.text(f.detail_products_sum_qte.toString(), 180, y);
            
            y += 10;
        });

        pdf.save('product.pdf');
    };
</script>


<template>
    <Navbar/><br>
    <section>
        <div>
            <div class="pdf">
                <button class="text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg text-sm px-5 py-2.5 mx-1" @click="downloadPDF">
                    <i class="pi pi-download"></i>
                </button>
            </div><br>

            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Magasin</th>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Stock minimum</th>
                        <th scope="col" class="px-6 py-3">Minimum sortie</th>
                        <th scope="col" class="px-6 py-3">Stock total</th>
                        <th scope="col" class="px-6 py-3" v-if="userRole === 'admin' || userRole === 'super_admin'">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredItems" :key="product.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">
                            <RouterLink :to="`/product/show/${product.magasin.id}`" class="text-blue-700">{{ product.magasin.nom }}</RouterLink>
                        </td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/product/show/${product.magasin.id}`" class="text-blue-700">{{ product.code }}</RouterLink>
                        </td>
                        <td class="px-6 py-4">{{ product.designation }}</td>
                        <td class="px-6 py-4">{{ product.stock_min }}</td>
                        <td class="px-6 py-4">{{ product.min_sortie }}</td>
                        <td class="px-6 py-4 flex items-center space-x-2" :class="{'text-red-600': product.detail_products_sum_qte <= product.stock_min}">
                            <i v-if="product.detail_products_sum_qte <= product.stock_min" class="pi pi-exclamation-triangle text-red-600 text-xl"></i>
                            &nbsp;{{ product.detail_products_sum_qte }}
                        </td>
                        <td class="px-6 py-4">
                            <button v-if="userRole === 'admin' || userRole === 'super_admin'" @click="confirmDeletetingProduct(product.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>