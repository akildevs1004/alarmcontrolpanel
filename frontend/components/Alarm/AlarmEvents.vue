<template>
  <div>
    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px">
        <v-row>
          <v-col cols="8"></v-col>
          <v-col cols="4" class="text-right" style="width: 450px">
            <v-row>
              <v-col cols="6"
                ><v-text-field
                  style="padding-top: 7px"
                  width="150px"
                  height="20"
                  class="employee-schedule-search-box"
                  @input="getDataFromApi()"
                  v-model="commonSearch"
                  label="Search (min 3)"
                  dense
                  outlined
                  type="text"
                  append-icon="mdi-magnify"
                  clearable
                  hide-details
                ></v-text-field
              ></v-col>
              <v-col cols="6">
                <CustomFilter
                  style="float: right; padding-top: 5px; z-index: 9999"
                  @filter-attr="filterAttr"
                  :default_date_from="date_from"
                  :default_date_to="date_to"
                  :defaultFilterType="1"
                  :height="'40px'"
              /></v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="items"
          :server-items-length="totalRowsCount"
          :loading="loading"
          :options.sync="options"
          :footer-props="{
            itemsPerPageOptions: [10, 50, 100, 500, 1000],
          }"
          class="elevation-0"
        >
          <template v-slot:item.sno="{ item, index }">
            {{
              currentPage
                ? (currentPage - 1) * perPage +
                  (cumulativeIndex + items.indexOf(item))
                : "-"
            }}
          </template>
          <template v-slot:item.device="{ item }">
            <div>{{ item.device.name }}</div>
            <div class="secondary-value">{{ item.device.serial_number }}</div>
          </template>
          <template v-slot:item.sensor="{ item }">
            <div>
              {{ item.alarm_type }}
            </div>
            <div class="secondary-value">{{ item.type }}</div>
          </template>
          <template v-slot:item.location="{ item }">
            {{ item.device.location }}
          </template>
          <template v-slot:item.zone="{ item }">
            <div>{{ item.zone }}</div>
            <div class="secondary-value">{{ item.area }}</div>
          </template>
          <template v-slot:item.start_date="{ item }">
            <div>{{ item.alarm_start_datetime }}</div>
            <div class="secondary-value">{{ item.alarm_end_datetime }}</div>
          </template>
          <template v-slot:item.duration="{ item }">
            <div>{{ $dateFormat.minutesToHHMM(item.response_minutes) }}</div>
          </template>
          <template v-slot:item.category="{ item }">
            <div>{{ item.alarm_category }}</div>
          </template>
          <template v-slot:item.alarm="{ item }">
            <div style="color: red" v-if="item.alarm == 'ON'">
              <v-icon color="red">mdi mdi-alarm-light-outline</v-icon>
            </div>
            <div v-else>
              <v-icon>mdi mdi-alarm-light-outline</v-icon>
            </div>
          </template>
          <template v-slot:item.status="{ item }">
            <div style="color: red" v-if="item.alarm_end_datetime == ''">
              Open
            </div>
            <div v-else>Closed</div>
          </template>
          <template v-slot:item.options="{ item }">
            <v-menu bottom left>
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark-2 icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list width="120" dense>
                <v-list-item v-if="can('branch_view')" @click="viewItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-eye </v-icon>
                    View Notes
                  </v-list-item-title>
                </v-list-item>
                <v-list-item v-if="can('branch_edit')" @click="editItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-pencil </v-icon>
                    Add Notes
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["customer_id"],
  data() {
    return {
      loading: false,
      commonSearch: "",
      options: { perPage: 10 },
      cumulativeIndex: 1,
      perPage: 10,
      currentPage: 1,
      totalRowsCount: 0,
      headers: [
        { text: "#", value: "sno", sortable: false },
        { text: "Device", value: "device", sortable: false },
        { text: "Sensor", value: "sensor", sortable: false },
        { text: "Location", value: "location", sortable: false },

        { text: "Zone", value: "zone", sortable: false },
        // { text: "Alarm Type", value: "alarm_type" , sortable: false },
        { text: "Start/End Date", value: "start_date", sortable: false },
        // { text: "End Date", value: "end_date" , sortable: false },
        { text: "Resolved Time(H:M)", value: "duration", sortable: false },
        { text: "Category", value: "category", sortable: false },
        { text: "Status", value: "status", sortable: false },

        { text: "Options", value: "options", sortable: false },
      ],
      items: [],
    };
  },
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  created() {
    if (this.customer_id) {
      let today = new Date();
      let monthObj = this.$dateFormat.monthStartEnd(today);
      this.date_from = monthObj.first;
      this.date_to = monthObj.last;
      //this.getDataFromApi();
    }
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi();
    },
    getDataFromApi() {
      if (this.customer_id) {
        this.loading = true;

        let { sortBy, sortDesc, page, itemsPerPage } = this.options;

        let sortedBy = sortBy ? sortBy[0] : "";
        let sortedDesc = sortDesc ? sortDesc[0] : "";
        this.perPage = itemsPerPage;
        this.currentPage = page;
        if (!page > 0) return false;
        let options = {
          params: {
            page: page,
            sortBy: sortedBy,
            sortDesc: sortedDesc,
            perPage: itemsPerPage,
            pagination: true,
            company_id: this.$auth.user.company_id,
            customer_id: this.customer_id,
            date_from: this.date_from,
            date_to: this.date_to,
            common_search: this.commonSearch,
          },
        };

        try {
          this.$axios.get(`get_alarm_events`, options).then(({ data }) => {
            this.items = data.data;

            this.totalRowsCount = data.total;
            this.loading = false;
          });
        } catch (e) {}
      }
    },
  },
};
</script>
