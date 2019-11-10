<template>
  <div class="flex m-3">
    <div class="flex-grow text-gray-600 text-xl">qBittomatic</div>
    <div class="flex-grow text-right">
      <div><a href="#" class="text-blue-500 hover:text-blue-800" @click.prevent="doLogout">Logout <span class="chevron bottom"></span></a></div>
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