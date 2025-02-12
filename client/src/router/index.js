import { createRouter, createWebHistory } from "vue-router";
// Fournisseur
import Fournisseur from "@/components/Fournisseur/Fournisseur.vue";
import CreateFournisseur from "@/components/Fournisseur/CreateFournisseur.vue";
import EditFournisseur from "@/components/Fournisseur/EditFournisseur.vue";

// Product
import Product from "@/components/Product/Product.vue";
import CreateProduct from "@/components/Product/CreateProduct.vue";
import EditProduct from "@/components/Product/EditProduct.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        
        {
            path: '/',
            name: 'home',
            component: Fournisseur,
        },
        
        // Fournisseur
        {
            path: '/fournisseur',
            name: 'fournisseur',
            component: Fournisseur,
        },
        {
            path: '/fournisseur/create',
            name: 'CreateFournisseur',
            component: CreateFournisseur,
        },
        {
            path: '/fournisseur/edit/:id',
            name: 'edit-fournisseur',
            component: EditFournisseur
        },

        // Products
        {
            path: '/product',
            name: 'product',
            component: Product,
        },
        {
            path: '/product/create',
            name: 'CreateProduct',
            component: CreateProduct,
        },
        {
            path: '/product/edit/:id',
            name: 'edit-product',
            component: EditProduct
        },
    ],
});

export default router;