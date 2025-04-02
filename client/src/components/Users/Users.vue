<script setup>
    import { ref, computed } from 'vue';
    import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
    import axios from 'axios';
    import SearchForm from '../DataTable/SearchForm.vue';
    import Navbar from '../Navbar/Navbar.vue';

    const queryUser = useQueryClient();
    const searchFilter = ref('');

    // Fetch users
    const { data: users, error } = useQuery({
        queryKey: ['users'],
        queryFn: async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/users');
            return response.data;
        },
    });

    // Computed property for filtering items
    const filteredItems = computed(() => {
        return (users.value || []).filter(item => 
            item.name.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.email.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
            item.role.toLowerCase().includes(searchFilter.value.toLowerCase())
        );
    });

    // Handle search from child component
    const handleSearch = (search) => {
        searchFilter.value = search;
    };

    // Delete user mutation
    const deleteUser = useMutation({
        mutationFn: async (id) => {
            await axios.delete(`http://127.0.0.1:8000/api/users/${id}`);
        },
        onSuccess: () => {
            queryUser.invalidateQueries({ queryKey: ['users'] });
            toast.success('user deleted successfully');
        },
        onError: (error) => {
            console.error('Error deleting user', error);
            toast.error('user was not deleted');
        },
    });

    // Confirm and delete
    const confirmDeletingUser = (id) => {
        if (window.confirm('Are you sure you want to delete this user?')) {
            deleteUser.mutate(id);
        }
    };
</script>


<template>
    <Navbar/><br>

    <section>
        <div>
            <RouterLink to="/register" class="text-white bg-blue-500 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5 mx-1">
                <i class="pi pi-plus-circle"></i>
            </RouterLink><br><br>

            <SearchForm @search="handleSearch"/>
            <table width="100%" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in filteredItems" :key="user.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ user.name }}</td>
                        <td class="px-6 py-4">{{ user.email }}</td>
                        <td class="px-6 py-4">{{ user.role }}</td>

                        <td class="px-6 py-4">
                            <RouterLink :to="`/user/edit/${user.id}`" class="text-white bg-gray-500 hover:bg-gray-700 rounded-lg mx-3 px-5 py-3">
                                <i class="pi pi-pencil"></i>
                            </RouterLink>

                            <button @click="confirmDeletingUser(user.id)" class="text-white bg-red-500 hover:bg-red-800 rounded-lg px-5 py-2.5">
                                <i class="pi pi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>