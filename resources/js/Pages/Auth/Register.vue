<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen p-6 bg-indigo-800">
    <div class="w-full max-w-md">
      <logo class="block w-full max-w-xs mx-auto fill-white" height="50" />
      <form class="mt-8 overflow-hidden bg-white rounded-lg shadow-xl" @submit.prevent="store">
        <div class="px-10 py-12">
          <h1 class="text-3xl font-bold text-center">Welcome Back!</h1>
          <div class="w-full mx-auto mt-6 border-b-2" />
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="w-full pb-8 pr-6 " label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="w-full pb-8 pr-6 " label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="w-full pb-8 pr-6 " label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="w-full pb-8 pr-6 " type="password" autocomplete="new-password" label="Password" />
          <select-input v-model="form.owner" :error="form.errors.owner" class="w-full pb-8 pr-6 " label="Owner">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 " type="file" accept="image/*" label="Photo" />
          <label class="flex items-center mt-6 select-none" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">Remember Me</span>
          </label>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <loading-button :loading="form.processing" class="ml-auto btn-indigo" type="submit">Register</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import FileInput from '@/Shared/FileInput'


export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
    SelectInput,
    FileInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        owner: false,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/registerUser')
    },
  },
}
</script>
