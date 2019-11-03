<template>
  <div>
    <menu-component v-if="loggedIn" :user="user" @logout="doLogout"></menu-component>
    <router-view :loggedIn="loggedIn" @login="doLogin"></router-view>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      loggedIn: false,
      user: null,
      errors: []
    }
  },
  mounted() {
    // Get token saved as cookie
    if(this.$cookies.isKey('token') && this.$cookies.get('token') && this.$cookies.isKey('user') && this.$cookies.get('user')) {
      this.doLogin({ token: this.$cookies.get('token'), user: this.$cookies.get('user') });
    } else {
      if(this.$route.name != 'login') {
        this.$router.push('/login');
      }
    }
  },
  computed: {
  },
  methods: {
    doLogin: function(data) {
      this.$cookies.set('token', data.token);
      this.$cookies.set('user', data.user);
      axios.defaults.headers.common['Auth-Token'] = data.token;
      this.loggedIn = true;
      this.user = data.user;
      if(this.$route.name != 'dashboard') {
        this.$router.push('/dashboard');          
      }
    },
    doLogout: function() {
      this.$cookies.remove('token');
      delete axios.defaults.headers.common['Auth-Token'];
      this.loggedIn = false;
      window.clearInterval(window.fetchInterval);
      if(this.$route.name != 'login') {
        this.$router.push('/login');
      }
    }
  }
}
</script>