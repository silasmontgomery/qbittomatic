<template>
  <div>
    <menu-component v-if="loggedIn" @logout="doLogout"></menu-component>
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
    if(this.$cookies.isKey('token') && this.$cookies.get('token')) {
      this.doLogin(this.$cookies.get('token'));
    } else {
      if(this.$route.name != 'login') {
        this.$router.push('/login');
      }
    }
  },
  computed: {
  },
  methods: {
    doLogin: function(token) {
      this.$cookies.set('token', token);
      axios.defaults.headers.common['Auth-Token'] = token;
      this.loggedIn = true;
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