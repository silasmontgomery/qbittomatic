<template>
  <div class="mt-10 mx-auto w-full max-w-xs">
    <p class="font-sans text-2xl text-gray-600 text-center mb-2">
      qBittomatic
    </p>
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
          Email
        </label>
        <input class="text-input focus:outline-none focus:shadow-outline" ref="email" v-model="email" type="text" placeholder="email address">
        <p v-if="errors.email" class="text-red-500 text-xs italic my-3">{{ errors.email[0] }}</p>
      </div>
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
          Password
        </label>
        <input class="text-input focus:outline-none focus:shadow-outline" v-model="password" type="password" placeholder="********" @keypress.enter="doLogin">
        <p v-if="errors.password" class="text-red-500 text-xs italic my-3">{{ errors.password[0] }}</p>
      </div>
      <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" @click="doLogin">
          Sign In
        </button>
        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
          Forgot Password?
        </a>
      </div>
    </form>
    <p class="text-center text-gray-500 text-xs">
      &copy; 2019 qBittomatic. All rights reserved.
    </p>
  </div>
</template>

<script>
export default {
  props: {
    loggedIn: {
      type: Boolean,
      default: false
    }
  },
  data: function() {
    return {
      email: null,
      password: null,
      errors: []
    }
  },
  mounted() {
    this.$refs.email.focus();
  },
  methods: {
    doLogin: function() {
      axios.post('/auth/login', {
        email: this.email,
        password: this.password
      })
      .then(response => {
        let token = response.data.token;
        let user = response.data.user;
        this.$emit('login', { token: token, user: user });
      })
      .catch(e => {
        this.errors = e.response.data;
        console.log(this.errors);
      })
    },
  }
}
</script>