<script setup>
    import { ref } from 'vue';
    import axios from 'axios';
    import { useRouter } from 'vue-router';
    import { useToast } from 'vue-toastification';

    const router = useRouter();
    const toast = useToast();

    const form = ref({
        email: "",
        password: ""
    });

    const handleLogin = async () => {
        try {
            const response = await axios.post('http://127.0.0.1:8000/api/login', {
                email: form.value.email,
                password: form.value.password
            });

            // Extract user role from the response
            const { token, expires_at, user } = response.data; 
            
            // Save authentication details in localStorage
            localStorage.setItem('authToken', token);
            localStorage.setItem('expiresAt', expires_at);
            localStorage.setItem('role', user.role);

            toast.success('Login successful!');
            router.push('/');
        } catch (error) {
            console.error('Error logging in:', error);
            toast.error('Invalid credentials');
        }
    };
</script>


<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>
            <form @submit.prevent="handleLogin" method="post">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" v-model="form.email" placeholder="exemple@gmail.com" name="email" required class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" v-model="form.password" placeholder="Enter Password" name="password" required class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                    Login
                </button>
            </form>
        </div>
    </div>
</template>