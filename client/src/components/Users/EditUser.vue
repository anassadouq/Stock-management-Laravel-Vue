<script setup>
    import { reactive, watch } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useQuery, useMutation } from '@tanstack/vue-query';
    import axios from 'axios';
    import { useToast } from 'vue-toastification';
    import Navbar from '../Navbar/Navbar.vue';

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const id = route.params.id;

    const form = reactive({
        name: '',
        email: '',
        role: '',
    });

    // Fetch user data using TanStack Query
    const { data, error } = useQuery({
        queryKey: ['user', id],
        queryFn: async () => {
            const response = await axios.get(`http://127.0.0.1:8000/api/users/${id}`);
            return response.data.user;
        },
        onError: () => {
            toast.error('Failed to fetch user details.');
        },
    });

    // Update form when data is fetched
    watch(data, (newData) => {
        if (newData && newData.name) {
            form.name = newData.name;
            form.email = newData.email;
            form.role = newData.role;
        }
    });

    // Mutation to update user
    const mutation = useMutation({
        mutationFn: async () => {
            const updateUser = {
                name: form.name,
                email: form.email,
                role: form.role,
            };
            await axios.put(`http://127.0.0.1:8000/api/users/${id}`, updateUser);
        },
        onSuccess: () => {
            toast.success('user updated successfully');
            router.push('/users');
        },
        onError: (err) => {
            toast.error(err?.response?.data?.message || 'user was not updated');
        },
    });

    const handleUpdate = () => {
        mutation.mutate();
    };
</script>


<template>
    <Navbar/>
    <section class="bg-green-50">
        <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white px-6 py-8 mb-4 shadow-md rounded-md border m-4 md:m-0">

                <form @submit.prevent="handleUpdate">
                    <h2 class="text-3xl text-center font-semibold mb-6">Update User</h2>

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                        <input type="text" v-model="form.name" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="text" v-model="form.email" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 w-full px-4 py-2"/>
                    </div>
                    
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select v-model="data.role" name="role" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="super_admin">Super Admin</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"><i class="pi pi-pencil"></i> Editer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>