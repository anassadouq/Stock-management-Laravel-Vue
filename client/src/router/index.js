import { createRouter, createWebHistory } from "vue-router";

// Auth
import Register from "@/components/Auth/Register.vue";
import Login from "@/components/Auth/Login.vue";

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

// Achat
import Achat from "@/components/Achat/Achat.vue";
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
            name: 'home',
            component: Magasin,
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

        // Achat
        {
            path: '/achat/show/:client_id',
            name: 'show-achat',
            component: Achat,
            meta: { requiresAuth: true }
        },
        {
            path: '/achat/show/:client_id/create',
            name: 'createachat',
            component: CreateAchat,
            meta: { requiresAuth: true }
        }
    ],
});

// Navigation Guard to Protect Routes
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('authToken'); // Check if user has token

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next(); // Allow access
    }
});

export default router;