<template>
  <b-container fluid>
    <b-card class="mt-3" header="Active Torrents">
      <b-table small striped hover responsive
        ref="activeTable"
        id="activeTable"
        :currentPage=activeCurrentPage 
        :perPage=activePerPage 
        :fields="activeFields"
        :items="activeTorrents"
        @row-clicked="onRowClicked">
        <template v-slot:row-details="row">
          <b-card>
            

          </b-card>
        </template>
      </b-table>
      <b-pagination 
        v-if="activePerPage < activeTorrents.length" 
        v-model="activeCurrentPage" 
        :total-rows="activeTorrents.length" 
        :per-page="activePerPage" 
        aria-controls="activeTable">
      </b-pagination>
    </b-card>
    <b-card class="mt-3" header="Torrent Search">
      <b-table small striped hover responsive 
        id="search-table"
        :items="searchResults">
      </b-table>
    </b-card>
  </b-container>
</template>

<script>
import axios from 'axios';

export default {
  data: function () {
    return {
      activePerPage: 5,
      activeCurrentPage: 1,
      activeSortBy: 'name',
      activeSortDesc: false,
      activeTorrents: [],
      activeFields: [
        {
          key: 'name',
          //label: '',
          sortable: true,
        },
        {
          key: 'state',
          label: 'Status',
          sortable: true,
        },
        {
          key: 'size',
          //label: '',
          sortable: true,
        },
        {
          key: 'download_percent',
          label: '% Complete',
          sortable: true,
        },
        {
          key: 'share_ratio',
          //label: '',
          sortable: true,
        },
      ],
      searchResults: [],
      errors: []
    }
  },
  mounted() {
    this.fetchActive();
    window.setInterval(f => { this.fetchActive() }, 1000);
  },
  computed: {
  },
  methods: {
    fetchActive: function() {
      axios.get('/api/v1/torrent_list')
      .then(response => {
        let torrents = [];
        response.data.forEach(t => {
          torrents.push({ 
            name: t.name, 
            size: this.smartSize(t.total_size),
            state: t.state, 
            download_total: this.smartSize(t.completed) + '/' + this.smartSize(t.total_size),
            download_percent: ((t.completed / t.total_size) * 100).toFixed(1) + '%', 
            download_speed: this.smartSize(t.dlspeed) + '/s', 
            upload_total: this.smartSize(t.uploaded) + '/' + this.smartSize(t.total_size),
            upload_percent: ((t.uploaded / t.total_size) * 100).toFixed(1) + '%',
            upload_speed: this.smartSize(t.upspeed) + '/s',
            share_ratio: t.ratio.toFixed(2),
            seeds: t.num_seeds + ' (' + t.num_complete + ')',
            leechs: t.num_leechs + ' (' + t.num_incomplete + ')',
            _showDetails: this.activeTorrents.find(o => o.name == t.name && o.size == this.smartSize(t.total_size) && o._showDetails == true) ? true:false,
          });
        });
        this.activeTorrents = torrents;
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    onRowClicked: function(item, index, event) {
      this.activeTorrents.forEach((torrent, i) => {
        if(i == index && !torrent._showDetails) {
            torrent._showDetails = true;
        } else {
          torrent._showDetails = false;
        }
      });
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