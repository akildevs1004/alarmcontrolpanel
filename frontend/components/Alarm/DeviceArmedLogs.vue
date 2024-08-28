<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>

    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px">
        <v-row>
          <v-col cols="6"></v-col>
          <v-col cols="6" class="text-right" style="max-width: 660px">
            <v-row>
              <v-col cols="1" class="mt-2">
                <v-icon @click="getDataFromApi()">mdi-refresh</v-icon>
              </v-col>
              <v-col cols="5"
                ><v-text-field
                  style="padding-top: 7px"
                  width="200px"
                  height="20"
                  class="employee-schedule-search-box"
                  @input="getDataFromApi()"
                  v-model="commonSearch"
                  label="Device Name"
                  dense
                  outlined
                  type="text"
                  append-icon="mdi-magnify"
                  clearable
                  hide-details
                ></v-text-field
              ></v-col>
              <v-col cols="4">
                <CustomFilter
                  style="float: right; padding-top: 5px; z-index: 9999"
                  @filter-attr="filterAttr"
                  :default_date_from="date_from"
                  :default_date_to="date_to"
                  :defaultFilterType="1"
                  :height="'40px'"
              /></v-col>
              <v-col cols="2" style="width: 100px; margin-top: 10px">
                <v-menu bottom right>
                  <template v-slot:activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">
                      <v-icon dark-2 icon color="violet" small>mdi-file</v-icon>
                      Print/PDF
                    </span>
                  </template>
                  <v-list width="100" dense>
                    <v-list-item @click="downloadOptions(`print`)">
                      <v-list-item-title style="cursor: pointer">
                        <v-row>
                          <v-col cols="5"
                            ><img
                              style="padding-top: 5px"
                              src="/icons/icon_print.png"
                              class="iconsize"
                          /></v-col>
                          <v-col
                            cols="7"
                            style="padding-left: 0px; padding-top: 19px"
                          >
                            Print
                          </v-col>
                        </v-row>
                      </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="downloadOptions('download')">
                      <v-list-item-title style="cursor: pointer">
                        <v-row>
                          <v-col cols="5"
                            ><img
                              style="padding-top: 5px"
                              src="/icons/icon_pdf.png"
                              class="iconsize"
                          /></v-col>
                          <v-col
                            cols="7"
                            style="padding-left: 0px; padding-top: 19px"
                          >
                            PDF
                          </v-col>
                        </v-row>
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item @click="downloadOptions('excel')">
                      <v-list-item-title style="cursor: pointer">
                        <v-row>
                          <v-col cols="5"
                            ><img
                              style="padding-top: 5px"
                              src="/icons/icon_excel.png"
                              class="iconsize"
                          /></v-col>
                          <v-col
                            cols="7"
                            style="padding-left: 0px; padding-top: 19px"
                          >
                            EXCEL
                          </v-col>
                        </v-row>
                      </v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </v-col>
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
            <div>{{ item.device?.name ?? "---" }}</div>
            <div class="secondary-value">
              {{ item.device?.serial_number ?? "---" }}
            </div>
          </template>
          <template v-slot:item.disarm_datetime="{ item }">
            <div>
              {{ $dateFormat.formatDateMonthYear(item.disarm_datetime) }}
            </div>
          </template>
          <template v-slot:item.armed_datetime="{ item }">
            <div>
              {{
                item.armed_datetime != ""
                  ? $dateFormat.formatDateMonthYear(item.armed_datetime)
                  : "---"
              }}
            </div>
          </template>
          <template v-slot:item.duration="{ item }">
            <div>
              {{
                item.duration_in_minutes
                  ? $dateFormat.minutesToHHMM(item.duration_in_minutes)
                  : "---"
              }}
            </div>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EditAlarmCustomerEventNotes from "../../components/Alarm/EditCustomerEventNotes.vue";
import AlarmEventNotesListView from "../../components/Alarm/AlarmEventsNotesList.vue";

export default {
  components: {
    EditAlarmCustomerEventNotes,
    AlarmEventNotesListView,
  },
  props: ["customer_id"],
  data() {
    return {
      snackbar: false,
      response: "",
      key: "",
      eventId: "",
      dialogAddCustomerNotes: false,
      dialogNotesList: false,
      date_from: "",
      date_to: "",
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
        { text: "Armed Time", value: "armed_datetime", sortable: false },
        { text: "Disam Time", value: "disarm_datetime", sortable: false },

        { text: "Duration(HH:MM)", value: "duration", sortable: false },
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
    let today = new Date();
    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;
    //this.getDataFromApi();

    setInterval(() => {
      if (
        this.$route.name == "alarm-view-customer-id" ||
        this.$route.name == "alarm-armedreports"
      ) {
        this.getDataFromApi();
      }
    }, 1000 * 20);
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    viewNotes(item) {
      this.key = this.key + 1;
      this.eventId = item.id;
      this.dialogNotesList = true;
    },

    addNotes(item) {
      this.eventId = item.id;
      this.dialogAddCustomerNotes = true;
    },
    closeDialog() {
      this.dialogAddCustomerNotes = false;
      this.getDataFromApi();
      this.$emit("closeDialog");
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi();
    },
    downloadOptions(option) {
      let filterSensorname = this.tab > 0 ? this.sensorItems[this.tab] : null;

      if (this.eventFilter) {
        filterSensorname = this.eventFilter;
      }

      let url = process.env.BACKEND_URL;
      if (option == "print") url += "/device_armed_logs_print_pdf";
      if (option == "excel") url += "/device_armed_logs_export_excel";
      if (option == "download") url += "/device_armed_logs_download_pdf";
      url += "?company_id=" + this.$auth.user.company_id;
      url += "&date_from=" + this.date_from;
      url += "&date_to=" + this.date_to;
      if (this.commonSearch) url += "&common_search=" + this.commonSearch;

      //  url += "&alarm_status=" + this.filterAlarmStatus;

      window.open(url, "_blank");
    },

    getDataFromApi() {
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
          //sortBy: sortedBy,
          sortDesc: sortedDesc,
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          date_from: this.date_from,
          date_to: this.date_to,
          common_search: this.commonSearch,
          sortBy: "alarm_start_datetime",
        },
      };

      try {
        this.$axios.get(`device_armed_logs`, options).then(({ data }) => {
          this.items = data.data;

          this.totalRowsCount = data.total;
          this.loading = false;
        });
      } catch (e) {}
    },
  },
};
</script>
