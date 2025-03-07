<script setup>
    import { reactive, ref, computed, watchEffect } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import VueMultiselect from 'vue-multiselect';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import Navbar from '../Navbar/Navbar.vue';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();
    const queryClient = useQueryClient();

    const product_id = route.params.product_id;
    const detail_product_id = route.params.id;

    const fournisseurs = ref([]);

    const form = reactive({
        product_id: product_id,
        fournisseur_id: null,
        qte: '',
        pu: '',
    });

    // Récupérer les fournisseurs
    const fetchFournisseurs = async () => {
        try {
            const { data } = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            fournisseurs.value = data;
        } catch (error) {
            console.error("Erreur lors du chargement des fournisseurs:", error);
        }
    };

    // Récupérer detail_product
    const { data: productData } = useQuery({
        queryKey: ['detail_product', product_id],
        queryFn: async () => {
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/detail_product/${product_id}`);
                return response.data; 
            } catch (error) {
                console.error("API error:", error);
                toast.error(error?.response?.data?.message || 'Erreur lors de la récupération du produit');
                return {}; 
            }
        },
    });

    // Trouver le détail produit correspondant
    const detailProduct = computed(() => {
        return productData.value?.product?.detail_products?.find(p => p.id === parseInt(detail_product_id)) || null;
    });

    // Mettre à jour `form` si `detailProduct` change
    watchEffect(() => {
        if (detailProduct.value && fournisseurs.value.length > 0) {
            form.qte = detailProduct.value.qte;
            form.pu = detailProduct.value.pu;
            form.fournisseur_id = fournisseurs.value.find(f => f.id === detailProduct.value.fournisseur_id) || null;
        }
    });

    // Mutation pour mettre à jour le produit
    const updateDetailProductMutation = useMutation({
        mutationFn: async (updateDetailProduct) => {
            await axios.put(`http://127.0.0.1:8000/api/detail_product/${detail_product_id}`, updateDetailProduct);
        },
        onSuccess: () => {
            toast.success('Produit mis à jour avec succès');            
            queryClient.invalidateQueries(['detail_product', product_id]);
            // Attendre que les nouvelles données soient chargées, puis naviguer
            setTimeout(() => {
                router.push(`/detail_product/show/${form.product_id}`);
            }, 500); // Petit délai pour s'assurer que les nouvelles données sont prêtes
        },
        onError: () => toast.error('Échec de la mise à jour du produit'),
    });

    // Gérer l'update'
    const handleUpdate = () => {
        updateDetailProductMutation.mutate({
            product_id: form.product_id,
            fournisseur_id: form.fournisseur_id ? form.fournisseur_id.id : null,
            qte: form.qte,
            pu: form.pu,
        });
    };

    fetchFournisseurs();
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Modifier le détail produit</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Fournisseur</label>
                        <vue-multiselect
                            v-model="form.fournisseur_id" 
                            :options="fournisseurs" 
                            :searchable="true"
                            track-by="id"
                            label="nom"
                            placeholder="Choisissez un fournisseur"
                            class="w-full"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Quantité</label>
                        <input type="number" v-model="form.qte" placeholder="Quantité" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">PU</label>
                        <input type="number" v-model="form.pu" placeholder="PU" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            <i class="pi pi-pencil"></i> Editer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>