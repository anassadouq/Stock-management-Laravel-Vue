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
    const queryAchat = useQueryClient();
    const searchFilter = ref('');

    const facture_id = route.params.facture_id;

    // Fetch Achats from API
    const fetchAchats = async () => {
        try {
            const { data } = await axios.get(`http://127.0.0.1:8000/api/achat/show/${facture_id}`);
            return data.achat;  // FIXED: Correct API response key
        } catch (error) {
            console.error("Erreur lors du chargement des achats:", error);
            throw new Error("Impossible de charger les achats.");
        }
    };

    // Fetch Achats with Vue Query
    const { data: achat, error, isLoading } = useQuery({
        queryKey: ['achat', facture_id],
        queryFn: fetchAchats,
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (achat.value || []).filter(item =>
            item.product.code.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.product.designation.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.qte.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.pu.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Mutation for Deleting Achat
    const deleteAchatMutation = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/achat/${id}`);
        },
        onSuccess: () => {
            queryAchat.invalidateQueries(['achat', facture_id]);
            toast.success('Achat supprimé avec succès');
        },
        onError: () => {
            toast.error("L'achat n'a pas été supprimé");
        }
    });

    // Delete Achat Function
    const deleteAchat = (id) => {
        if (window.confirm('Êtes-vous sûr de vouloir supprimer cet achat ?')) {
            deleteAchatMutation.mutate(id);
        }
    };

    // Download PDF function
    const downloadPDF = () => {
        const pdf = new jsPDF();
        pdf.text('Liste des Achats', 10, 10);
        let y = 20;
        pdf.setFontSize(10);
            
        // Entêtes du tableau
        pdf.text('Produit', 10, y);
        pdf.text('Designation', 50, y);
        pdf.text('Qte', 120, y);
        pdf.text('PU', 140, y);
        pdf.text('PT', 170, y);
            
        y += 10;

        let prixTotal = 0; // Initialisation du prix total

        // Remplissage du tableau avec les données
        filteredItems.value.forEach(f => {
            pdf.text(f.product.code, 10, y);
            pdf.text(f.product.designation, 50, y);
            pdf.text(f.qte.toString(), 120, y);
            pdf.text(f.pu.toString(), 140, y);
            pdf.text((f.pu * f.qte).toString() + " DH", 190, y);

            prixTotal += f.qte * f.pu;
            y += 10;
        });

        // Ajout du prix total général
        pdf.text(`Total: ${prixTotal} DH`, 160, y + 10);

        pdf.save('achat.pdf');
    };
</script>


<template>
    <Navbar/><br>

    <RouterLink :to="`/achat/show/${facture_id}/create`" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
        <i class="pi pi-plus-circle"></i>
    </RouterLink><br><br>

    <button class="text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg text-sm px-5 py-2.5 mx-1" @click="downloadPDF">
        <i class="pi pi-download"></i>
    </button><br><br>

    <section>
        <div>
            <SearchForm @search="handleSearch"/>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Product</th>
                        <th scope="col" class="px-6 py-3">Designation</th>
                        <th scope="col" class="px-6 py-3">Quantity</th>
                        <th scope="col" class="px-6 py-3">UP</th>
                        <th scope="col" class="px-6 py-3">TP</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ item.product.code }}</td>
                        <td class="px-6 py-4">{{ item.product.designation }}</td>
                        <td class="px-6 py-4">{{ item.qte }}</td>
                        <td class="px-6 py-4">{{ item.pu }}</td>
                        <td class="px-6 py-4">{{ item.qte * item.pu }} DH</td>

                        <td class="px-6 py-4">
                            <button @click="deleteAchat(item.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>