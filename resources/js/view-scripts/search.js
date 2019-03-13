import {ClientTable, Event} from 'vue-tables-2';

let options = {};
let useVuex = false;
let theme = 'bootstrap4';
let template = 'default';
Vue.use(ClientTable, options, useVuex, theme, template);

const app = new Vue({
  el: '#app',
  data() {
    // By default assume no data has been returned from server.
    return {
      queriedDB: false,
      noResults: false,
      columns: [],
      tableData: [],
      options: {
        perPage: 25,
        headings: [],
        sortable: [],
        debounce: 750  // number of milliseconds before filtering takes effect
      }
    };
  },
  mounted() {
    
    // Parse data returned from server to the view.
    let serverData = JSON.parse(window.web_app_server_data);

    // If the cloud database has been queried load results into Vue table component.
    if(serverData.queriedDB) {
      this.queriedDB = true;
      this.noResults = serverData.noResults;
      this.columns = serverData.columns;
      this.tableData = serverData.tableData;
      this.options.heading = serverData.options.headings;
      this.options.sortable = serverData.options.sortable;
    }

  }
});
