<script setup>
import { reactive } from 'vue';
import axios from 'axios';
import router from '@/router';
import { useToast } from 'vue-toastification';

const data = reactive({
    name: "",
    role: "",
    email: "",
});

const toast = useToast();

const handleSubmit = async () => {
    const token = localStorage.getItem('authToken');
    const userRole = localStorage.getItem('role'); 

    // Only allow super_admin to add users
    if (userRole !== 'super_admin') {
        toast.error('You do not have permission to add new users.');
        return;
    }

    if (!token) {
        toast.error('User is not authenticated');
        return;
    }

    try {
        await axios.post('http://127.0.0.1:8000/api/register', data, {
            headers: { 'Authorization': `Bearer ${token}` }
        });

        toast.success('User added successfully');
        router.push('/users'); // Redirect to users list
    } catch (error) {
        console.error('Error:', error.response || error);

        if (error.response) {
            const errors = error.response.data.errors;
            if (errors) {
                toast.error(Object.values(errors).flat().join(', '));
            } else {
                toast.error('An error occurred while adding the user');
            }
        } else {
            toast.error('Server error occurred');
        }
    }
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Register</h1>
            <form @submit.prevent="handleSubmit" method="post">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" v-model="data.name" placeholder="Enter Name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" v-model="data.email" placeholder="Enter Email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select v-model="data.role" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="controller">Controller</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                    Register
                </button>
            </form>
        </div>
    </div>
</template>