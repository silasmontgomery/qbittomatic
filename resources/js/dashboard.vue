<template>
  <b-container fluid>
    <b-card header="Active Torrents">
      <b-table striped hover responsive :items="computedTorrents"></b-table>
    </b-card>
  </b-container>
</template>

<script>
import axios from 'axios';

export default {
  data: function () {
    return {
      torrents: [],
      errors: []
    }
  },
  mounted() {
    window.setInterval(f => { this.fetchList() }, 1000);
  },
  computed: {
    computedTorrents: function() {
      let torrents = [];
      this.torrents.forEach(t => {
        torrents.push({ 
          name: t.name, 
          status: t.state, 
          downloaded: this.smartSize(t.completed) + '/' + this.smartSize(t.total_size) + ' (' + ((t.completed / t.total_size) * 100).toFixed(1) + '%)', 
          down_speed: this.smartSize(t.dlspeed) + '/s', 
          uploaded: this.smartSize(t.uploaded) + '/' + this.smartSize(t.total_size) + ' (' + ((t.uploaded / t.total_size) * 100).toFixed(1) + '%)',
          up_speed: this.smartSize(t.upspeed) + '/s',
          seeds: t.num_seeds + ' (' + t.num_complete + ')',
          leechs: t.num_leechs + ' (' + t.num_incomplete + ')',
        });
      });
      return torrents;
    }
  },
  methods: {
    fetchList: function() {
      axios.get('/api/v1/torrent_list')
      .then(response => {
        this.torrents = response.data
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    smartSize: function(byteSize) {
      let smartSize = 0;
      if(byteSize < 1024) {
        smartSize = byteSize + 'B';
      } else if(byteSize < 1048576) {
        smartSize = (byteSize / 1024).toFixed(1) + 'KiB';
      } else if(byteSize < 1073741824) {
        smartSize = (byteSize / 1048576).toFixed(1) + 'MiB';
      } else {
        smartSize = (byteSize / 1073741824).toFixed(1) + 'GiB';
      }
      return smartSize;
    }
  }
}
</script>

<style scoped>
h1 {
  font-size: 1.5em;
}
</style>