<template>
  <div>
    <div class="card">
      <div class="responsive">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Status</th>
              <th>Size</th>
              <th>% Complete</th>
              <th>DL Speed</th>
              <th>UL Speed</th>
              <th>Share Ratio</th>
            </tr>
          </thead>
          <tbody ref="torrentTable">
            <template v-for="torrent in torrents">
              <tr class="selectable" @click="onTorrentClick(torrent)" :key="torrent.hash + 'A'">
                <td class="nowrap">{{ torrent.name }}</td>
                <td>{{ torrent.state }}</td>
                <td>{{ smartSize(torrent.size) }}</td>
                <td>{{ torrent.completed.toFixed(2) }}%</td>
                <td>{{ smartSize(torrent.dl_speed) }}/s</td>
                <td>{{ smartSize(torrent.up_speed) }}/s</td>
                <td>{{ torrent.ratio.toFixed(2) }}</td>
              </tr>
              <tr :ref="torrent.hash" class="torrent-details hidden" :key="torrent.hash + 'B'">
                <td colspan="7">
                  <div class="row">
                    <div class="col">
                      Current Folder: 
                      <select v-model="torrent.path" @change="onPathChange(torrent)">
                        <option v-for="path in paths" :key="path.name">{{ path.name }}</option>
                      </select>
                    </div>
                    <div class="col text-right">
                      <button @click="onRemoveTorrentClick(torrent)">Remove Torrent</button> <input id="deleteFiles" type="checkbox" value="1" class="ml-5" @change="onDeleteFilesChange(torrent)" /> <label for="deleteFiles">Delete files</label>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
            <tr v-if="torrents.length == 0">
              <td colspan="7">No active torrents</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="text-right mt-10">
      <span v-if="searching">Searching...</span> <select v-model="api"><option v-for="api in apis" :key="api.name">{{ api.name }}</option></select> <input type="text" v-model="search" @focus="search=null" v-on:keyup.enter="onSearch" placeholder="Torrent search" /> <button class="ml-5" @click="onSearch">Search</button>
    </div>
    <div v-if="results && results.length > 0" class="card mt-10">
      <div class="responsive">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Size</th>
              <th>Seeds</th>
              <th>Peers</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="result in results" :key="result.id" class="selectable" @click="onResultClick(result)">
              <td class="nowrap">{{ result.title }}</td>
              <td>{{ result.size }}</td>
              <td>{{ result.seeds }}</td>
              <td>{{ result.peers }}</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    loggedIn: {
      type: Boolean,
      default: false,
      required: true,
    }
  },
  data: function () {
    return {
      torrents: [],
      deleteFiles: [],
      paths: [],
      apis: [],
      api: null,
      searching: false,
      search: null,
      results: [],
      errors: []
    }
  },
  mounted() {
    if(this.loggedIn) {
      this.startFetching();
    }
  },
  watch: {
    loggedIn: function() {
      if(this.loggedIn) {
        this.startFetching();
      }
    }
  },
  methods: {
    startFetching: function() {
      this.fetchTorrents();
      this.fetchPaths();
      this.fetchApis();
      window.fetchInterval = window.setInterval(f => { this.fetchTorrents() }, 1000);
    },
    fetchTorrents: function() {
      axios.get('/torrent')
      .then(response => {
        this.torrents = response.data;
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    fetchPaths: function() {
      axios.get('/paths')
      .then(response => {
        this.paths = response.data;
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    fetchApis: function() {
      axios.get('/search_apis')
      .then(response => {
        this.apis = response.data;
        this.api = this.apis[0].name;
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    onTorrentClick: function(torrent) {
      this.$refs[torrent.hash][0].classList.toggle("hidden");
    },
    onPathChange: function(torrent) {
      axios.post('/torrent/' + torrent.hash, {
        path: torrent.path
      })
      .then(response => {
        console.log(response);
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    onDeleteFilesChange: function(torrent) {
      let exists = this.deleteFiles.find(t => t.hash == torrent.hash);
      if(!exists) {
        this.deleteFiles.push({ hash: torrent.hash, delete: true });
      } else {
        this.deleteFiles = this.deleteFiles.filter(t => t.hash != torrent.hash);
      }
    },
    onRemoveTorrentClick: function(torrent) {
      axios.delete('/torrent/' + torrent.hash + '?deleteFiles=' + (this.deleteFiles.find(t => t.hash == torrent.hash) ? 1:0))
      .then(response => {
        console.log(response);
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    onSearch: function() {
      this.searching = true;
      axios.get('/search?api=' + this.api + '&q=' + this.search)
      .then(response => {
        this.results = response.data.torrents;
      })
      .catch(e => {
        this.errors.push(e)
      })
      .finally(f => {
        this.searching = false;
        this.search = null;
      })
    },
    onResultClick: function(result) {
      axios.post('/torrent', {
        magnet: result.magnet
      })
      .then(response => {
        console.log(response);
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