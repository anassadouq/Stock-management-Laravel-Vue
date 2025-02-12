import { createRouter, createWebHistory } from "vue-router";
// Fournisseur
import Fournisseur from "@/components/Fournisseur/Fournisseur.vue";
import CreateFournisseur from "@/components/Fournisseur/CreateFournisseur.vue";
import EditFournisseur from "@/components/Fournisseur/EditFournisseur.vue";

// Product
import Product from "@/components/Product/Product.vue";
import CreateProduct from "@/components/Product/CreateProduct.vue";
import EditProduct from "@/components/Product/EditProduct.vue";

// Client
import Client from "@/components/Client/Client.vue";
import CreateClient from "@/components/Client/CreateClient.vue";
import EditClient from "@/components/Client/EditClient.vue";

// Achat
import Achat from "@/components/Achat/Achat.vue";
import CreateAchat from "@/components/Achat/CreateAchat.vue";
import EditAchat from "@/components/Achat/EditAchat.vue";

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

        // Clients
        {
            path: '/client',
            name: 'client',
            component: Client,
        },
        {
            path: '/client/create',
            name: 'createClient',
            component: CreateClient,
        },
        {
            path: '/client/edit/:id',
            name: 'edit-client',
            component: EditClient
        },

        // Achat
        {
            path: '/achat/show/:client_id',
            name: 'show-achat',
            component: Achat
        },
        {
            path: '/achat/show/:client_id/create',
            name: 'createachat',
            component: CreateAchat,
        },
        {
            path: '/achat/show/:id/edit',
            name: 'edit-achat',
            component: EditAchat
        },
    ],
});

export default router;