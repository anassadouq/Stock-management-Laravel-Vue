<script setup>
    import { ref } from 'vue';
    import { useQuery } from '@tanstack/vue-query';
    import axios from 'axios';
    import Navbar from '../Navbar/Navbar.vue';

    const magasinCount = ref(0);
    const fournisseurCount = ref(0);
    const clientCount = ref(0);
    const factureCount = ref(0);
    const achatCount = ref(0);
    const productCount = ref(0);
    const lowStockProducts = ref([]);
    const lowStockCount = ref(0);

    // Fetch magasin
    const { data: magasins} = useQuery({
        queryKey: ['magasins'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/magasin');
            magasinCount.value = response.data.length;
        },
    })

    // Fetch fournisseur
    const { data: fournisseurs} = useQuery({
        queryKey: ['fournisseurs'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/fournisseur');
            fournisseurCount.value = response.data.length;
        },
    })

    // Fetch client
    const { data: clients} = useQuery({
        queryKey: ['clients'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/client');
            clientCount.value = response.data.length;
        },
    })

    // Fetch facture
    const { data: factures} = useQuery({
        queryKey: ['factures'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/facture');
            factureCount.value = response.data.length;
        },
    })

    // Fetch achat
        const { data: achats} = useQuery({
        queryKey: ['achats'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/achat');
            achatCount.value = response.data.length;
            return response.data;
        },
    })

    // Fetch product
    const { data: products} = useQuery({
        queryKey: ['products'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/product');
            productCount.value = response.data.length;
            return response.data;
        },
    })

    // Stock faible
    const { data: stocks } = useQuery({
        queryKey: ['stocks'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/product');

            // Filtrer les produits dont qte < stock_min
            lowStockProducts.value = response.data.filter(product => 
                product.detail_products_sum_qte <= product.stock_min
            );

            // Stocker le nombre total de ces produits
            lowStockCount.value = lowStockProducts.value.length;

            return response.data;
        },
    });
</script>


<template>
    <Navbar/><br>

    <div class="grid grid-cols-3 gap-4 mx-5">
        <RouterLink to="magasin" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-shopping-cart"></i>
                <p>Magasin</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ magasinCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="fournisseur" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-users"></i>
                <p>Fournisseur</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ fournisseurCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="client" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-users"></i>
                <p>Client</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ clientCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="all_facture" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-file"></i>
                <p>Factures</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ factureCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="all_achat" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-euro"></i>
                <p>Achats</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ achatCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="all_product" class="flex flex-col items-center max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-box"></i>
                <p>Produits</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ productCount }}</h5>
            </div>
        </RouterLink>

        <RouterLink to="all_product" class="flex flex-col items-center max-w-sm p-6 text-red-500 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="mx-5">
                <i class="icon pi pi-exclamation-triangle"></i>
                <p>Stock faible</p>
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight">
                    {{ lowStockCount }}
                </h5>
            </div>
        </RouterLink>
    </div>
</template>

<style>
    .icon {
        font-size: 30px;
    }
</style>