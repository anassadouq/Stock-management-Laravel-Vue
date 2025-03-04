<script setup>
    import { ref, computed } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';
    import jsPDF from 'jspdf';

    const queryFactures = useQueryClient();
    const searchFilter = ref('');

    // Fetch factures
    const { data: facture, error } = useQuery({
        queryKey: ['facture'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/facture');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (facture.value || []).filter(item =>
            item.client.nom.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.num.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.date.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete facture mutation
    const deleteFacture = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/facture/${id}`);
        },
        onSuccess: () => {
            queryFactures.invalidateQueries({ queryKey: ['facture'] });
            toast.success('facture deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting facture', error);
            toast.error('facture was not deleted');
        },
    });

    // Confirm and delete
    const confirmDeletingFacture = (id) => {
        if (window.confirm('Are you sure you want to delete this facture?')) {
            deleteFacture.mutate(id);
        }
    };

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('Liste des factures', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
        pdf.text('Client', 10, y);
        pdf.text('Numero', 70, y);
        pdf.text('Date', 130, y);
        y += 10;
        
        filteredItems.value.forEach(f => {
            pdf.text(f.client.nom, 10, y);
            pdf.text(f.num, 70, y);
            pdf.text(f.date, 130, y);
            y += 10;
        });
        
        pdf.save('facture.pdf');
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
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Num</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems" :key="facture.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ item.client.nom }}</td>
                        <td class="px-6 py-4">
                            <RouterLink :to="`/achat/show/${item.id}`" class="text-blue-700">{{ item.num }}</RouterLink>
                        </td>
                        <td class="px-6 py-4">{{ item.date }}</td>        
                        <td class="px-6 py-4">
                            <button @click="confirmDeletingFacture(item.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>