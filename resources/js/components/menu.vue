<template>
  <div class="row p-5">
    <div class="col text-left bold">qBittomatic</div>
    <div class="col text-right"><a href="#" @click.prevent="doLogout">Logout</a></div>
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
  data: function () {
    return {
      errors: []
    }
  },
  methods: {
    doLogout: function() {
      this.$emit('logout');
    }
  }
}
</script>