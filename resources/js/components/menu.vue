<template>
  <div class="row p-5">
    <div class="col text-left bold">qBittomatic</div>
    <div class="col text-right">
      <div><a href="#" @click.prevent="doLogout">Logout <span class="chevron bottom"></span></a></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      required: true
    },
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