<script setup>
    import { ref, computed } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';

    const queryClient = useQueryClient();
    const searchFilter = ref('');

    // Fetch fournisseurs
    const { data: fournisseurs } = useQuery({
        queryKey: ['fournisseurs'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (fournisseurs.value || []).filter(item => 
            item.nom.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.adresse.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.tel.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete fournisseur mutation
    const deleteFournisseur = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/fournisseur/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['fournisseurs'] });
            // Assuming you have a toast library setup
            toast.success('Fournisseur deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting fournisseur', error);
            toast.error('Fournisseur was not deleted');
        },
    });

    // Confirm and delete
    const confirmAndDeleteFournisseur = (id) => {
        if (window.confirm('Are you sure you want to delete this fournisseur?')) {
            deleteFournisseur.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>
    
    <div>
        <RouterLink to="/fournisseur/create" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
            <i class="pi pi-plus-circle"></i>
        </RouterLink><br><br>

        <SearchForm @search="handleSearch" />

        <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Adresse</th>
                    <th scope="col" class="px-6 py-3">Téléphone</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fournisseur in filteredItems" :key="fournisseur.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ fournisseur.nom }}</td>
                    <td class="px-6 py-4">{{ fournisseur.adresse }}</td>
                    <td class="px-6 py-4">{{ fournisseur.tel }}</td>
                    <td class="px-6 py-4">
                        <RouterLink :to="`/fournisseur/edit/${fournisseur.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                            <i class="pi pi-pencil"></i>
                        </RouterLink>

                        <button @click="confirmAndDeleteFournisseur(fournisseur.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                            <i class="pi pi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>