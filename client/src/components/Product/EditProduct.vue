<script setup>
import { reactive, watchEffect } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const productId = route.params.id || null;

const form = reactive({
    fournisseur_id: '',
    code: '',
    designation: '',
    qte: '',
    pu: ''
});

// Fetch Product Data
const fetchProduct = async () => {
    if (!productId) {
        toast.error('Invalid product ID');
        return;
    }

    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/product/${productId}`);
        if (response.data) {
            // Assign values directly to keep reactivity
            form.fournisseur_id = response.data.fournisseur_id;
            form.code = response.data.code;
            form.designation = response.data.designation;
            form.qte = response.data.qte;
            form.pu = response.data.pu;
        } else {
            toast.error('Product data not found.');
        }
    } catch (error) {
        console.error('Error fetching product', error);
        toast.error('Failed to fetch product details.');
    }
};

// Watch for changes to `productId` and fetch data
watchEffect(fetchProduct);

const handleUpdate = async () => {
    if (!productId) {
        toast.error('Invalid product ID');
        return;
    }

    try {
        await axios.put(`http://127.0.0.1:8000/api/product/${productId}`, { ...form });
        toast.success('Product Updated Successfully');
        await router.push('/fournisseur');
    } catch (error) {
        console.error('Error updating product', error);
        toast.error('Failed to update product.');
    }
};
</script>

<template>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-24">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">
                <h2 class="text-3xl text-center font-semibold mb-6">Edit Product</h2>
                <form @submit.prevent="handleUpdate">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="code">Code</label>
                        <input type="text" v-model="form.code" id="code" class="border rounded w-full py-2 px-3 mb-2" required />
                    </div>

                    <div class="mb-4">
                        <label for="designation" class="block text-gray-700 font-bold mb-2">Designation</label>
                        <textarea v-model="form.designation" class="border rounded w-full py-2 px-3" rows="4" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="qte">Quantity</label>
                        <input type="number" v-model="form.qte" id="qte" class="border rounded w-full py-2 px-3 mb-2" min="0" required />
                    </div>

                    <div class="mb-4">
                        <label for="pu" class="block text-gray-700 font-bold mb-2">PU</label>
                        <input type="number" v-model="form.pu"class="border rounded w-full py-2 px-3" min="0" required />
                    </div>

                    <div>
                        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline" type="submit">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>