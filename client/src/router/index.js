import { createRouter, createWebHistory } from "vue-router";
import Fournisseur from "@/components/Fournisseur.vue";
import AddFournisseur from "@/components/AddFournisseur.vue";
import EditFournisseur from "@/components/EditFournisseur.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/fournisseur',
            name: 'fournisseur',
            component: Fournisseur,
        },
        {
            path: '/fournisseur/add',
            name: 'AddFournisseur',
            component: AddFournisseur,
        },
        {
            path: '/fournisseur/edit/:id',
            name: 'edit-fournisseur',
            component: EditFournisseur
        },
    ],
});

export default router;