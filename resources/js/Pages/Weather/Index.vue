<script setup>
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    success: Boolean,
    data: Object,
    message: String,
});

const city = ref('');
const weather = ref(null);
const loading = ref(false);
const error = ref('');

onMounted(() => {
    if (props.success && props.data) {
        weather.value = props.data;
    } else if (props.message) {
        error.value = props.message;
    }
});

const searchWeather = () => {
    if (!city.value.trim()) {
        error.value = 'Please enter a city name';
        return;
    }

    loading.value = true;
    error.value = '';
    weather.value = null;

    router.post(route('weather.get'), {
        city: city.value
    }, {
        preserveState: false,
        preserveScroll: false,
        onStart: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};

const getWeatherIcon = (icon) => {
    return `https://openweathermap.org/img/wn/${icon}@4x.png`;
};
</script>

<template>
    <Head title="Weather App">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </Head>

    <div class="min-h-screen bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-white mb-4 flex items-center justify-center gap-3">
                    <i class='bx bx-sun text-yellow-300'></i>
                    Weather App
                </h1>
                <p class="text-xl text-blue-100">
                    Get real-time weather information for any city
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
                <form @submit.prevent="searchWeather" class="space-y-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter City Name
                        </label>
                        <div class="flex gap-3">
                            <input
                                id="city"
                                v-model="city"
                                type="text"
                                placeholder="e.g., London, New York, Tokyo"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :disabled="loading"
                            />
                            <button
                                type="submit"
                                :disabled="loading"
                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                            >
                                {{ loading ? 'Searching...' : 'Search' }}
                            </button>
                        </div>
                    </div>

                    <div v-if="error" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-medium">{{ error }}</p>
                    </div>
                </form>
            </div>

            <div v-if="weather" class="bg-white rounded-2xl shadow-2xl p-8 animate-fade-in">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-gray-800 mb-2">
                        {{ weather.name }}, {{ weather.sys.country }}
                    </h2>
                    <p class="text-gray-600 text-lg capitalize">
                        {{ weather.weather[0].description }}
                    </p>
                </div>

                <div class="flex items-center justify-center mb-8">
                    <img
                        :src="getWeatherIcon(weather.weather[0].icon)"
                        :alt="weather.weather[0].description"
                        class="w-32 h-32"
                    />
                    <div class="text-6xl font-bold text-gray-800">
                        {{ Math.round(weather.main.temp) }}°C
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class='bx bxs-thermometer text-4xl text-blue-600 mb-2'></i>
                        <div class="text-sm text-gray-600 mb-1">Feels Like</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ Math.round(weather.main.feels_like) }}°C
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class='bx bx-droplet text-4xl text-blue-600 mb-2'></i>
                        <div class="text-sm text-gray-600 mb-1">Humidity</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ weather.main.humidity }}%
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class='bx bx-wind text-4xl text-blue-600 mb-2'></i>
                        <div class="text-sm text-gray-600 mb-1">Wind Speed</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ weather.wind.speed }} m/s
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <i class='bx bx-tachometer text-4xl text-blue-600 mb-2'></i>
                        <div class="text-sm text-gray-600 mb-1">Pressure</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ weather.main.pressure }} hPa
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Min Temperature</div>
                        <div class="text-2xl font-bold text-gray-800">
                            {{ Math.round(weather.main.temp_min) }}°C
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Max Temperature</div>
                        <div class="text-2xl font-bold text-gray-800">
                            {{ Math.round(weather.main.temp_max) }}°C
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="bg-white rounded-2xl shadow-2xl p-12 text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
                <p class="mt-4 text-gray-600 text-lg">Loading weather data...</p>
            </div>

            <div class="text-center mt-8">
                <p class="text-blue-100">
                    Powered by OpenWeather API
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
