<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogCloseAlarm" width="600px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Alarm Event Close/Turn off </span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="
              closeDialog();
              dialogCloseAlarm = false;
            "
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlramCloseNotes
              name="AlramCloseNotes"
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :alarmId="eventId"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogAddCustomerNotes" width="600px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Alarm Notes </span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="
              closeDialog();
              dialogAddCustomerNotes = false;
            "
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <EditAlarmCustomerEventNotes
              name="EditAlarmCustomerEventNotes"
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :alarmId="eventId"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogNotesList" width="900px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Alarm Notes List</span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="
              closeDialog();
              dialogNotesList = false;
            "
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlarmEventNotesListView
              name="AlarmEventNotesListView"
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :alarm_id="eventId"
              showOptions="false"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogViewCustomer" width="110%">
      <AlarmCustomerView
        @closeCustomerDialog="closeCustomerDialog()"
        :key="key"
        :_id="viewCustomerId"
        :isPopup="true"
      />
    </v-dialog>
    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px; z-index: 9">
        <v-row>
          <v-col cols="4" class="text-left mt-1"> <h3>Alarm Events</h3></v-col>
          <v-col cols="8" class="text-right" style="width: 600px">
            <v-row>
              <v-col cols="1"></v-col>
              <v-col cols="3">
                <v-icon @click="getDataFromApi(0)" class="mt-2 mr-2"
                  >mdi-reload</v-icon
                >
                <v-text-field
                  style="
                    padding-top: 7px;
                    width: 150px;

                    float: right;
                  "
                  width="150px"
                  height="20"
                  class="employee-schedule-search-box"
                  @input="getDataFromApi(0)"
                  v-model="commonSearch"
                  label="Search"
                  dense
                  outlined
                  type="text"
                  append-icon="mdi-magnify"
                  clearable
                  hide-details
                ></v-text-field
              ></v-col>
              <v-col cols="3"
                ><v-select
                  class="employee-schedule-search-box"
                  style="
                    padding-top: 7px;
                    z-index: 999;
                    width: 200px;
                    min-width: 100%;
                  "
                  height="20px"
                  outlined
                  @change="getDataFromApi(0)"
                  v-model="filterResponseInMinutes"
                  dense
                  :items="[
                    { id: null, name: 'All Responses' },
                    { id: 1, name: 'Resolved <1 min' },
                    { id: 5, name: 'Resolved 1 to 5 min' },
                    { id: 10, name: 'Resolved 5 to 10 min' },
                    { id: 0, name: 'Resolved > 10 min' },
                  ]"
                  item-text="name"
                  item-value="id"
                ></v-select>
              </v-col>
              <v-col cols="2"
                ><v-select
                  class="employee-schedule-search-box"
                  style="
                    padding-top: 7px;
                    z-index: 999;
                    min-width: 100%;
                    width: 150px;
                  "
                  height="20px"
                  outlined
                  @change="getDataFromApi(0)"
                  v-model="filterAlarmStatus"
                  dense
                  :items="[
                    { id: null, name: 'All Events' },
                    { id: 1, name: 'Alarm ON' },
                    { id: 0, name: 'Alarm OFF' },
                  ]"
                  item-text="name"
                  item-value="id"
                ></v-select>
              </v-col>
              <v-col cols="3">
                <CustomFilter
                  style="float: left; padding-top: 5px; z-index: 999"
                  @filter-attr="filterAttr"
                  :default_date_from="date_from"
                  :default_date_to="date_to"
                  :defaultFilterType="1"
                  :height="'60px'"
              /></v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <v-row v-if="sensorItems.length == 0" class="text-center">
      <v-col cols="12" class="text-center"> No Data is available</v-col>
    </v-row>
    <v-row v-if="sensorItems.length > 0">
      <v-col cols="12">
        <v-tabs v-model="tab" background-color="transparent" color="basil" grow>
          <v-tab v-for="item in sensorItems" :key="item">
            {{ item }}
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
          <v-tab-item v-for="item in sensorItems" :key="item">
            <v-card color="basil" flat>
              <v-card-text>
                <v-data-table
                  v-if="showTable"
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
                  <!-- <template v-slot:item.sno="{ item, index }">
                    {{
                      currentPage
                        ? (currentPage - 1) * perPage +
                          (cumulativeIndex + items.indexOf(item))
                        : "-"
                    }}
                  </template> -->
                  <template v-slot:item.sno="{ item, index }">
                    {{ item.id }}
                  </template>

                  <!-- <template
                    v-slot:item.building="{ item, index }"
                    style="width: 300px"
                  >
                    <v-row no-gutters @click="viewCustomerinfo(item)">
                      <v-col
                        style="
                          padding: 5px;
                          padding-left: 0px;
                          width: 50px;
                          max-width: 50px;
                        "
                      >
                        <v-img
                          style="
                            border-radius: 10%;
                            height: auto;
                            width: 50px;
                            max-width: 50px;
                          "
                          :src="
                            item.device?.customer?.profile_picture
                              ? item.device.customer.profile_picture
                              : '/no-business_profile.png'
                          "
                        >
                        </v-img>
                      </v-col>
                      <v-col style="padding: 10px">
                        <div style="font-size: 13px">
                          {{ item.device?.customer?.building_name || "" }}
                        </div>
                        <small style="font-size: 12px; color: #6c7184">
                          {{ item.device?.customer?.house_number }},
                          {{ item.device?.customer?.street_number }},
                          {{ item.device?.customer?.area }},
                          {{ item.device?.customer?.city }}
                        </small>
                      </v-col>
                    </v-row>
                  </template> -->
                  <template v-slot:item.customer="{ item }">
                    <div>
                      {{
                        item.device?.customer?.primary_contact?.first_name ??
                        "---"
                      }}
                      {{
                        item.device?.customer?.primary_contact?.last_name ??
                        "---"
                      }}
                    </div>
                    <!-- <div class="secondary-value">
                      {{
                        item.device?.customer?.primary_contact?.phone1 ?? "---"
                      }}
                    </div> -->
                  </template>
                  <template v-slot:item.address="{ item }">
                    <div>{{ item.device?.customer?.area }}</div>
                  </template>
                  <template v-slot:item.city="{ item }">
                    <div>{{ item.device?.customer?.city }}</div>
                  </template>
                  <!-- <template v-slot:item.device="{ item }">
                    <div>{{ item.device?.name }}</div>
                    <div class="secondary-value">
                      {{ item.device?.serial_number }}
                    </div>
                  </template> -->
                  <template v-slot:item.sensor="{ item }">
                    <div>
                      {{ item.alarm_type }}
                    </div>
                    <!-- <div class="secondary-value">
                      {{ item.device?.location }}
                    </div> -->

                    <!-- <div class="secondary-value">{{ item.type }}</div> -->
                  </template>
                  <template v-slot:item.property="{ item }">
                    {{ item.device?.customer?.buildingtype?.name ?? "---" }}
                  </template>
                  <template v-slot:item.zone="{ item }">
                    <div>{{ item.zone }}</div>
                    <div class="secondary-value">{{ item.area }}</div>
                  </template>
                  <template v-slot:item.start_date="{ item }">
                    <div>
                      {{
                        $dateFormat.formatDateMonthYear(
                          item.alarm_start_datetime
                        )
                      }}
                    </div>
                    <!-- <div class="secondary-value">
                      {{
                        item.alarm_end_datetime
                          ? $dateFormat.formatDateMonthYear(
                              item.alarm_end_datetime
                            )
                          : "---"
                      }}
                    </div> -->
                  </template>
                  <template v-slot:item.end_date="{ item }">
                    <div>
                      {{
                        item.alarm_end_datetime
                          ? $dateFormat.formatDateMonthYear(
                              item.alarm_end_datetime
                            )
                          : "---"
                      }}
                    </div>
                  </template>
                  <template v-slot:item.duration="{ item }">
                    <div>
                      {{
                        item.alarm_end_datetime
                          ? $dateFormat.minutesToHHMM(item.response_minutes)
                          : "---"
                      }}
                    </div>
                  </template>
                  <template v-slot:item.notes="{ item }">
                    <div @click="viewNotes(item)">{{ item.notes.length }}</div>
                  </template>

                  <template v-slot:item.alarm_category="{ item }">
                    <div>{{ item.category.name }}</div>
                  </template>

                  <template v-slot:item.status="{ item }">
                    <div v-if="item.alarm_status == 1">
                      <v-icon
                        class="alarm"
                        @click="UpdateAlarmStatus(item, 0)"
                        title="Click to Turn OFF Alarm "
                        >mdi mdi-alarm-light</v-icon
                      >
                      <br />
                      <v-btn
                        class="text--red"
                        color="red"
                        title="Click to Stop Alarm "
                        @click="UpdateAlarmStatus(item, 0)"
                        outlined
                        x-small
                        dense
                        >Stop</v-btn
                      >
                    </div>
                    <div v-else-if="item.alarm_status == 0">
                      <v-icon title="Now Alaram is OFF"
                        >mdi mdi-alarm-light-outline</v-icon
                      >
                      <div class="secondary-value">
                        {{
                          item.alarm_end_manually == 1
                            ? "Closed Manually"
                            : "Auto Closed"
                        }}
                      </div>
                    </div>
                  </template>
                  <template v-slot:item.options="{ item }">
                    <v-menu bottom left>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn dark-2 icon v-bind="attrs" v-on="on">
                          <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                      </template>
                      <v-list width="120" dense>
                        <v-list-item
                          v-if="can('branch_view')"
                          @click="viewNotes(item)"
                        >
                          <v-list-item-title style="cursor: pointer">
                            <v-icon color="secondary" small> mdi-eye </v-icon>
                            View Notes
                          </v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="can('branch_edit')">
                          <v-list-item-title
                            style="cursor: pointer"
                            @click="addNotes(item)"
                          >
                            <v-icon color="secondary" small>
                              mdi-message-bulleted
                            </v-icon>
                            Add Notes
                          </v-list-item-title>
                        </v-list-item>
                        <!-- <v-list-item v-if="can('branch_edit')">
                  <v-list-item-title
                    style="cursor: pointer"
                    @click="deleteEvent(item.id)"
                  >
                    <v-icon color="error" small> mdi-delete </v-icon>
                    Delete
                  </v-list-item-title>
                </v-list-item> -->
                      </v-list>
                    </v-menu>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import AlramCloseNotes from "../../components/Alarm/AlramCloseNotes.vue";

import EditAlarmCustomerEventNotes from "../../components/Alarm/EditCustomerEventNotes.vue";
import AlarmEventNotesListView from "../../components/Alarm/AlarmEventsNotesList.vue";
import AlarmCustomerView from "../../components/Alarm/ViewCustomer.vue";

export default {
  components: {
    EditAlarmCustomerEventNotes,
    AlarmEventNotesListView,
    AlarmCustomerView,
    AlramCloseNotes,
  },
  props: ["showFilters"],
  data() {
    return {
      dialogCloseAlarm: false,
      filterResponseInMinutes: null,
      dialogViewCustomer: false,
      viewCustomerId: null,
      filterAlarmStatus: 1,
      showTable: true,
      requestStatus: false,
      tab: 0,
      sensorItems: [],
      value: "recent",
      customer_id: null,
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
        { text: "Event Id", value: "sno", sortable: false },
        // { text: "Building", value: "building", sortable: false },

        { text: "Customer", value: "customer", sortable: false },
        { text: "Property", value: "property", sortable: false },
        { text: "Address", value: "address", sortable: false },

        { text: "City", value: "city", sortable: false },

        // { text: "Device", value: "device", sortable: false },
        { text: "Type", value: "sensor", sortable: false },

        // { text: "Zone", value: "zone", sortable: false },
        // { text: "Alarm Type", value: "alarm_type" , sortable: false },
        { text: "Event Time", value: "start_date", sortable: false },
        { text: "Closed time", value: "end_date", sortable: false },
        { text: "Priority", value: "alarm_category", sortable: false },
        // { text: "End Date", value: "end_date" , sortable: false },
        {
          text: "Resolved Time(H:M)",
          value: "duration",
          sortable: false,
          align: "center",
        },
        // { text: "Category", value: "category", sortable: false },

        // { text: "Notes", value: "notes", sortable: false },
        // {
        //   text: "Status",
        //   value: "status",
        //   sortable: false,
        //   align: "center",
        // },

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

    tab: {
      handler() {
        this.showTable = false;
        this.getDataFromApi(0);
      },
      deep: true,
    },
  },
  created() {
    let today = new Date();
    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;

    setTimeout(() => {
      this.getSensorsList();
    }, 2000);

    setTimeout(() => {
      if (this.sensorItems.length == 0) {
        this.$axios
          .get(`sensor_types`, {
            params: {
              company_id: this.$auth.user.company_id,
            },
          })
          .then(({ data }) => {
            this.sensorItems = ["All", ...data];
          });
      }
    }, 5000);

    setTimeout(() => {
      setInterval(() => {
        if (
          this.$route.name == "alarm-dashboard" ||
          this.$route.name == "alarm-allevents" ||
          this.$route.name == "alarm-alarm-events"
        )
          this.getDataFromApi(0);
      }, 1000 * 20 * 1);
    }, 1000 * 40);
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    closeCustomerDialog() {
      this.dialogViewCustomer = false;
    },
    viewCustomerinfo(item) {
      this.viewCustomerId = item.customer_id;
      this.dialogViewCustomer = true;
    },
    viewNotes(item) {
      this.key = this.key + 1;
      this.eventId = item.id;
      this.customer_id = item.customer_id;
      this.dialogNotesList = true;
    },
    getSensorsList() {
      if (this.$store.state.storeAlarmControlPanel?.SensorTypes) {
        // this.sensorItems = this.$store.state.storeAlarmControlPanel.SensorTypes;
        this.sensorItems = [
          "All",
          ...this.$store.state.storeAlarmControlPanel.SensorTypes,
        ];
      }
    },
    addNotes(item) {
      this.eventId = item.id;
      this.dialogAddCustomerNotes = true;
    },
    closeDialog() {
      this.dialogAddCustomerNotes = false;
      this.dialogCloseAlarm = false;
      this.getDataFromApi(0);
      this.$emit("closeDialog");
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi(0);
    },
    UpdateAlarmStatus(item, status) {
      if (status == 0) {
        if (confirm("Are you sure you want to TURN OFF the Alarm")) {
          this.customer_id = item.customer_id;
          this.eventId = item.id;
          this.dialogCloseAlarm = true;
          // let options = {
          //   params: {
          //     company_id: this.$auth.user.company_id,
          //     customer_id: this.customer_id,
          //     event_id: item.id,
          //     status: status,
          //   },
          // };
          // this.loading = true;
          // this.$axios
          //   .post(`/update-device-alarm-event-status-off`, options.params)
          //   .then(({ data }) => {
          //     this.getDataFromApi();
          //     if (!data.status) {
          //       if (data.message == "undefined") {
          //         this.response = "Try again. No connection available";
          //       } else this.response = "Try again. " + data.message;
          //       this.snackbar = true;
          //       return;
          //     } else {
          //       setTimeout(() => {
          //         this.loading = false;
          //         this.response = data.message;
          //         this.snackbar = true;
          //       }, 2000);
          //       return;
          //     }
          //   })
          //   .catch((e) => console.log(e));
        }
      }
    },
    deleteEvent(id) {
      if (confirm("Are you sure want to delete Alarm Event notes?")) {
        this.loading = true;
        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            id: id,
          },
        };

        try {
          this.$axios.delete(`delete-event`, options).then(({ data }) => {
            this.snackbar = true;
            this.response = "Event Note Deleted Successfully";
            this.getDataFromApi();
            this.loading = false;
          });
        } catch (e) {}
      }
    },
    getDataFromApi(custompage = 1) {
      if (this.loading == true) return false;
      // console.log(
      //   "this.$route.query.alarm_status",
      //   this.$route.query.alarm_status
      // );
      // if (this.$route.query.alarm_status) {
      //   this.filterAlarmStatus = parseInt(this.$route.query.alarm_status);
      //   this.date_from = null;
      //   this.date_to = null;
      // }

      if (custompage == 0) this.options = { perPage: 10, page: 1 };

      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      this.perPage = itemsPerPage;
      this.currentPage = page;

      if (!page > 0) return false;
      this.loading = true;
      let options = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          //customer_id: this.customer_id,
          date_from: this.date_from,
          date_to: this.date_to,
          common_search: this.commonSearch,
          tab: this.tab,
          alarm_status: this.filterAlarmStatus,
          filterSensorname: this.tab > 0 ? this.sensorItems[this.tab] : null,
          filterResponseInMinutes: this.filterResponseInMinutes,
        },
      };

      try {
        this.$axios.get(`get_alarm_events`, options).then(({ data }) => {
          this.items = data.data;

          this.totalRowsCount = data.total;
          this.loading = false;
          this.showTable = true;
        });
      } catch (e) {}
    },
  },
};
</script>
