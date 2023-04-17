<script setup>
import { vMaska } from 'maska';
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const credentials = reactive({
  phone: null,
  otp: null,
});

const waitingOnVerification = ref(false);
const router = useRouter();

onMounted(() => {
  if (localStorage.getItem('token')) {
    router.push({
      name: 'landing',
    });
  }
});

const getFormattedCredentials = () => {
  return {
    phone: credentials.phone.replaceAll(' ', ''),
    otp: credentials.otp,
  };
};

const handleLogin = () => {
  axios
    .post('http://127.0.0.1:8000/api/v1/login', getFormattedCredentials())
    .then((response) => {
      console.log(response.data);
      waitingOnVerification.value = true;
    })
    .catch((error) => {
      console.error(error);
      alert(error.response.data.message);
    });
};

const handleVerification = () => {
  axios
    .post(
      'http://127.0.0.1:8000/api/v1/login/verify',
      getFormattedCredentials()
    )
    .then((response) => {
      console.log(response.data);
      localStorage.setItem('token', response.data);

      router.push({ name: 'landing' });
    })
    .catch((error) => {
      console.error(error);
      alert(error.response.data.message);
    });
};
</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>

    <form v-if="!waitingOnVerification" @submit.prevent="handleLogin">
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              v-maska
              data-maska="+880 1### ######"
              v-model="credentials.phone"
              type="text"
              id="phone"
              placeholder="+880 1234 567890"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none" />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
            type="submit"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
            Continue
          </button>
        </div>
      </div>
    </form>

    <form v-else @submit.prevent="handleVerification">
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              v-maska
              data-maska="#####"
              v-model="credentials.otp"
              type="text"
              name="otp"
              id="otp"
              placeholder="12345"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none" />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
            type="submit"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
            Verify
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
