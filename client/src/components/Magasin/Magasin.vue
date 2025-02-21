<script setup>
    import { ref, computed } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';
    import jsPDF from 'jspdf';

    const querymagasin = useQueryClient();
    const searchFilter = ref('');

    // Fetch magasins
    const { data: magasins, error } = useQuery({
        queryKey: ['magasins'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/magasin');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (magasins.value || []).filter(item => 
            item.nom.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete magasin mutation
    const deleteMagasin = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/magasin/${id}`);
        },
        onSuccess: () => {
            querymagasin.invalidateQueries({ queryKey: ['magasins'] });
            toast.success('magasin deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting magasin', error);
            toast.error('magasin was not deleted');
        },
    });

    // Confirm and delete
    const confirmAnddeleteMagasin = (id) => {
        if (window.confirm('Are you sure you want to delete this magasin?')) {
            deleteMagasin.mutate(id);
        }
    };

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('magasin List', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
        pdf.text('Nom', 10, y);
        y += 10;
        
        filteredItems.value.forEach(f => {
            pdf.text(f.nom, 10, y);
            y += 10;
        });
        
        pdf.save('magasin.pdf');
    };
</script>


<template>
    <Navbar/><br>
    <section>
        <div>
            <RouterLink to="/magasin/create" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
                <i class="pi pi-plus-circle"></i>
            </RouterLink><br><br>

            <div class="pdf">
                <button class="text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg text-sm px-5 py-2.5 mx-1" @click="downloadPDF">
                    <i class="pi pi-download"></i>
                </button>
            </div><br>

            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="magasin in filteredItems" :key="magasin.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ magasin.nom }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/product/show/${magasin.id}`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-eye"></i>
                            </RouterLink>
                            <RouterLink :to="`/magasin/edit/${magasin.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>
                            <button @click="confirmAnddeleteMagasin(magasin.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>