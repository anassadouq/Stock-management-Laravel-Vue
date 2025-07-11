<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';
    import jsPDF from 'jspdf';

    const queryAchats = useQueryClient();
    const searchFilter = ref('');
    const userRole = ref(null);

    onMounted(() => {
        userRole.value = localStorage.getItem('role');
    });

    // Fetch achats
    const { data: achat, error } = useQuery({
        queryKey: ['achat'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/achat');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (achat.value || []).filter(item =>
            item.facture.client.nom.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.product.code.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.product.designation.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.qte.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete achat mutation
    const deleteAchat = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/achat/${id}`);
        },
        onSuccess: () => {
            queryAchats.invalidateQueries({ queryKey: ['achat'] });
            toast.success('achat deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting achat', error);
            toast.error('achat was not deleted');
        },
    });

    // Confirm and delete
    const confirmDeletingAchat = (id) => {
        if (window.confirm('Are you sure you want to delete this achat?')) {
            deleteAchat.mutate(id);
        }
    };

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('Liste des achats', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
        pdf.text('Client', 10, y);
        pdf.text('Product Code', 70, y);
        pdf.text('Designation', 100, y);
        pdf.text('Qte', 150, y);
        pdf.text('PU', 170, y);
        pdf.text('PT', 190, y);

        y += 10;
        
        filteredItems.value.forEach(f => {
            pdf.text(f.facture.client.nom, 10, y);
            pdf.text(f.product.code, 70, y);
            pdf.text(f.product.designation, 100, y);
            pdf.text(f.qte, 150, y);
            pdf.text(f.pu.toString(), 170, y);
            pdf.text((f.pu * f.qte).toString() + " DH", 190, y);
            y += 10;
        });
        
        pdf.save('achat.pdf');
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
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Produit</th>
                        <th scope="col" class="px-6 py-3">Qte</th>
                        <th scope="col" class="px-6 py-3">PU</th>
                        <th scope="col" class="px-6 py-3">PT</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems" :key="achat.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ item.facture.client.nom }}</td>
                        <td class="px-6 py-4">{{ item.product.code }}</td>
                        <td class="px-6 py-4">{{ item.product.designation }}</td>
                        <td class="px-6 py-4">{{ item.qte }}</td>
                        <td class="px-6 py-4">{{ item.pu }}</td>   
                        <td class="px-6 py-4">{{ item.pu * item.qte }} DH</td>             
                        <td class="px-6 py-4">
                            <button v-if="userRole === 'admin' || userRole === 'super_admin'" @click="confirmDeletingAchat(item.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>