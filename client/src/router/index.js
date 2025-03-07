import { createRouter, createWebHistory } from "vue-router";
import axios from 'axios';

// Auth
import Register from "@/components/Auth/Register.vue";
import Login from "@/components/Auth/Login.vue";

// Daschboard
import Dashboard from "@/components/Dashboard/Dashboard.vue";

// Magasin
import Magasin from "@/components/Magasin/Magasin.vue";
import CreateMagasin from "@/components/Magasin/CreateMagasin.vue";
import EditMagasin from "@/components/Magasin/EditMagasin.vue";

// Fournisseur
import Fournisseur from "@/components/Fournisseur/Fournisseur.vue";
import CreateFournisseur from "@/components/Fournisseur/CreateFournisseur.vue";
import EditFournisseur from "@/components/Fournisseur/EditFournisseur.vue";

// Product
import Product from "@/components/Product/Product.vue";
import AllProduct from "@/components/Product/AllProduct.vue";
import CreateProduct from "@/components/Product/CreateProduct.vue";
import EditProduct from "@/components/Product/EditProduct.vue";

// Detail Product
import DetailProduct from "@/components/DetailProduct/DetailProduct.vue";
import CreateDetailProduct from "@/components/DetailProduct/CreateDetailProduct.vue";
import EditDetailProduct from "@/components/DetailProduct/EditDetailProduct.vue";

// Client
import Client from "@/components/Client/Client.vue";
import CreateClient from "@/components/Client/CreateClient.vue";
import EditClient from "@/components/Client/EditClient.vue";

// Facture
import Facture from "@/components/Facture/Facture.vue";
import CreateFacture from "@/components/Facture/CreateFacture.vue";
import EditFacture from "@/components/Facture/EditFacture.vue";
import AllFacture from "@/components/Facture/AllFacture.vue";

// Achat
import Achat from "@/components/Achat/Achat.vue";
import AllAchat from "@/components/Achat/AllAchat.vue";
import CreateAchat from "@/components/Achat/CreateAchat.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: { requiresAuth: false } // Allow access without login
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { requiresAuth: false } // Allow access without login
        },
        
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: { requiresAuth: true }
        },

        // Magasin
        {
            path: '/magasin',
            name: 'magasin',
            component: Magasin,
            meta: { requiresAuth: true }
        },
        {
            path: '/magasin/create',
            name: 'create_magasin',
            component: CreateMagasin,
            meta: { requiresAuth: true }
        },
        {
            path: '/magasin/edit/:id',
            name: 'edit-magasin',
            component: EditMagasin,
            meta: { requiresAuth: true }
        },
        
        // Fournisseur
        {
            path: '/fournisseur',
            name: 'fournisseur',
            component: Fournisseur,
            meta: { requiresAuth: true }
        },
        {
            path: '/fournisseur/create',
            name: 'CreateFournisseur',
            component: CreateFournisseur,
            meta: { requiresAuth: true }
        },
        {
            path: '/fournisseur/edit/:id',
            name: 'edit-fournisseur',
            component: EditFournisseur,
            meta: { requiresAuth: true }
        },

        // Products
        {
            path: '/product',
            name: 'product',
            component: Product,
            meta: { requiresAuth: true }
        },
        {
            path: '/all_product',
            name: 'all_product',
            component: AllProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/product/show/:magasin_id/create',
            name: 'createProduct',
            component: CreateProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/product/show/:magasin_id/edit/:id',
            name: 'editProduct',
            component: EditProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/product/show/:magasin_id',
            name: 'showProduct',
            component: Product,
            meta: { requiresAuth: true }
        },

        // Detail Product
        {
            path: '/detail_product',
            name: 'detail_product',
            component: DetailProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/detail_product/show/:product_id/create',
            name: 'createDetail',
            component: CreateDetailProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/detail_product/show/:product_id/edit/:id',
            name: 'editDetail',
            component: EditDetailProduct,
            meta: { requiresAuth: true }
        },
        {
            path: '/detail_product/show/:product_id',
            name: 'showDetailProduct',
            component: DetailProduct,
            meta: { requiresAuth: true }
        },

        // Clients
        {
            path: '/client',
            name: 'client',
            component: Client,
            meta: { requiresAuth: true }
        },
        {
            path: '/client/create',
            name: 'createClient',
            component: CreateClient,
            meta: { requiresAuth: true }
        },
        {
            path: '/client/edit/:id',
            name: 'edit-client',
            component: EditClient,
            meta: { requiresAuth: true }
        },

        // Facture
        {
            path: '/facture/show/:client_id',
            name: 'show-facture',
            component: Facture,
            meta: { requiresAuth: true }
        },
        {
            path: '/facture/show/:client_id/create',
            name: 'createfacture',
            component: CreateFacture,
            meta: { requiresAuth: true }
        },
        {
            path: '/facture/show/:client_id/edit/:id',
            name: 'editFacture',
            component: EditFacture,
            meta: { requiresAuth: true }
        },
        {
            path: '/all_facture',
            name: 'all_facture',
            component: AllFacture,
            meta: { requiresAuth: true }
        },

        // Achat
        {
            path: '/achat/show/:facture_id',
            name: 'show-achat',
            component: Achat,
            meta: { requiresAuth: true }
        },
        {
            path: '/achat/show/:facture_id/create',
            name: 'createachat',
            component: CreateAchat,
            meta: { requiresAuth: true }
        },
        {
            path: '/all_achat',
            name: 'all_achat',
            component: AllAchat,
            meta: { requiresAuth: true }
        },
    ],
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('authToken'); // Check if user has token

    if (to.meta.requiresAuth) {
        if (!token) {
            next('/login'); // Redirect to login if no token
        } else {
            // Check token expiration
            axios.post('http://127.0.0.1:8000/api/check-token-expiration', {}, {
                headers: { Authorization: `Bearer ${token}` }
            }).then(response => {
                if (response.data.message === 'Token expired') {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('expiresAt');
                    next('/login'); // Redirect to login if token expired
                } else {
                    next(); // Allow access if token is valid
                }
            }).catch(() => {
                localStorage.removeItem('authToken');
                localStorage.removeItem('expiresAt');
                next('/login');
            });
        }
    } else {
        next(); // Allow access to routes that do not require authentication
    }
});

export default router;