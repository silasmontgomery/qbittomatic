<template>
  <div class="login-form">
    <h3>qBittomatic</h3>
    <div class="mb-10">
      <input type="text" v-model="email" placeholder="email address" />
      <div v-if="errors.email" class="form-error mt-5">{{ errors.email[0] }}</div>
    </div>
    <div class="mb-10">
      <input type="password" v-model="password" placeholder="password" @keyup.enter="doLogin" />
      <div v-if="errors.password" class="form-error mt-5">{{ errors.password[0] }}</div>
    </div>
    <div><button @click="doLogin">Login</button></div>
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
      })
    },
  }
}
</script>