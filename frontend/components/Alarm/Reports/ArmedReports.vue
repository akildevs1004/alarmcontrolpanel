<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogEventsList" max-width="80%">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span style="color: black" dense>Intruder Events</span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="dialogEventsList = false"
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text style="padding: 20px; padding-left: 0px">
          <v-card class="elevation-2">
            <v-card-text class="mt-5">
              <AlamAllEvents
                name="DeviceArmedLogs"
                style="padding: 0px; padding-top: 0px"
                :key="key"
                :popup="true"
                :compAlarmFilter="1"
                :date_from="date_from"
                :date_to="date_to"
              /> </v-card-text
          ></v-card>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px">
        <v-card>
          <v-row>
            <v-col></v-col>
            <v-col class="text-right" style="min-width: 500px">
              <v-row>
                <v-col class="mt-2">
                  <v-icon @click="getDataFromApi()">mdi-refresh</v-icon>
                </v-col>
                <v-col style="">
                  <v-autocomplete
                    clearable
                    style="padding-top: 6px"
                    @change="getDataFromApi()"
                    class="reports-events-autocomplete bgwhite"
                    v-model="filter_customer_id"
                    :items="customersList"
                    dense
                    placeholder="All Customers"
                    outlined
                    item-value="id"
                    item-text="building_name"
                  >
                  </v-autocomplete>
                  <!-- <v-text-field
                  style="padding-top: 7px; width: 200px"
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
                ></v-text-field> -->
                </v-col>
                <v-col style="">
                  <CustomFilter
                    style="float: right; padding-top: 5px; z-index: 9999"
                    @filter-attr="filterAttr"
                    :default_date_from="date_from"
                    :default_date_to="date_to"
                    :defaultFilterType="1"
                    :height="'40px'"
                /></v-col>
                <!-- <v-col cols="2" style="width: 100px; margin-top: 10px">
                  <v-menu bottom right>
                    <template v-slot:activator="{ on, attrs }">
                      <span v-bind="attrs" v-on="on">
                        <v-icon dark-2 icon color="violet" small
                          >mdi-printer-outline</v-icon
                        >
                        Print
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
                </v-col> -->
              </v-row>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>
    <v-row style="margin-top: -30px">
      <v-col>
        <div
          class="v-data-table elevation-0 v-data-table--has-bottom theme--light"
        >
          <v-data-table
            :headers="headers"
            :items="items"
            class="elevation-1"
            :items-per-page="100"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Armed Reports</v-toolbar-title>
              </v-toolbar>
            </template>
            <template v-slot:item="{ item }">
              <tr>
                <td>{{ item.date }}</td>
                <td>{{ item.customer }}</td>
                <td>{{ item.city }}</td>
                <template v-for="index in 5">
                  <td :title="item.armed[index - 1]?.armed_datetime">
                    {{
                      $dateFormat.format6(
                        item.armed[index - 1]?.armed_datetime
                      ) || "---"
                    }}
                  </td>
                  <td
                    :title="item.armed[index - 1]?.disarm_datetime"
                    style="color: red"
                  >
                    {{
                      $dateFormat.format6(
                        item.armed[index - 1]?.disarm_datetime
                      ) || "---"
                    }}
                  </td>
                </template>
                <td>{{ item.sos || "0" }}</td>
                <td>
                  {{ item.events.length ? item.events.join(", ") : "0" }}
                </td>
              </tr>
            </template>
          </v-data-table>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EditAlarmCustomerEventNotes from "../../../components/Alarm/EditCustomerEventNotes.vue";
import AlarmEventNotesListView from "../../../components/Alarm/AlarmEventsNotesList.vue";
import AlamAllEvents from "../../../components/Alarm/ComponentAllEvents.vue";
export default {
  components: {
    EditAlarmCustomerEventNotes,
    AlarmEventNotesListView,
    AlamAllEvents,
  },
  props: ["customer_id"],
  data() {
    return {
      headers: [
        { text: "Date", value: "date", sortable: false },
        { text: "Customer", value: "customer_id", sortable: false },
        { text: "City", value: "city", sortable: false },
        { text: "Armed1", value: "armed1", sortable: false },
        { text: "Disarm1", value: "disarm1", sortable: false },
        { text: "Armed2", value: "armed2", sortable: false },
        { text: "Disarm2", value: "disarm2", sortable: false },
        { text: "Armed3", value: "armed3", sortable: false },
        { text: "Disarm3", value: "disarm3", sortable: false },
        { text: "Armed4", value: "armed4", sortable: false },
        { text: "Disarm4", value: "disarm4", sortable: false },
        { text: "Armed5", value: "armed5", sortable: false },
        { text: "Disarm5", value: "disarm5", sortable: false },
        { text: "Armed Duration", value: "armed", sortable: false },

        { text: "Disarm Duration", value: "disarm", sortable: false },

        // { text: "SOS", value: "sos", sortable: false },
        // { text: "Events", value: "events", sortable: false },
      ],
      date_from: null,
      date_to: null,
      dialogEventsList: false,
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
      customersList: [],
      items: [],
      filter_customer_id: null,
    };
  },
  watch: {
    // options: {
    //   handler() {
    //     this.getDataFromApi();
    //   },
    //   deep: true,
    // },
  },
  async created() {
    let today = new Date();
    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;

    await this.getDataFromApi();
    await this.getCustomersList();
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    async getCustomersList() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };
      this.$axios.get(`/customers_list`, options).then(({ data }) => {
        this.customersList = [
          { id: "", building_name: "All Customers" },
          ...data,
        ];
      });
    },
    formattedData(jsonData) {
      const result = [];
      jsonData.forEach((day) => {
        if (day.customers.length == 0) {
          result.push({
            date: day.date,
            customer_id: "---",
            customer: "---",
            city: "---",
            armed: "---",
            sos: "---",
            events: [],
          });
        }
        day.customers.forEach((customer) => {
          result.push({
            date: day.date,
            customer_id: customer.customer_id,
            customer: customer.customer,
            city: customer.city,
            armed: customer.armed,
            sos: customer.sos || "",
            events: customer.events || [],
          });
        });
      });
      return result;
    },
    showEvents(item) {
      this.dialogEventsList = true;
      this.date_from = item.armed_datetime;
      this.date_to = item.disarm_datetime;
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

    async getDataFromApi() {
      this.loading = true;

      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          date_from: this.date_from,
          date_to: this.date_to,
          filter_customer_id: this.filter_customer_id,
        },
      };

      try {
        this.$axios.get(`device_armed_reports`, options).then(({ data }) => {
          //this.items = data;
          this.items = this.formattedData(data);
          this.totalRowsCount = data.total;
          this.loading = false;
        });
      } catch (e) {}
    },
  },
};
</script>
