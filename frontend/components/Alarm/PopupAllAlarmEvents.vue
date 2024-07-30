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
    <v-dialog v-model="dialogViewCustomer" width="110%">
      <AlarmCustomerView
        @closeCustomerDialog="closeCustomerDialog()"
        :_id="viewCustomerId"
        :isPopup="true"
      />
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

    <!-- <v-row v-if="sensorItems.length == 0" class="text-center">
      <v-col cols="12" class="text-center"> No Data is available</v-col>
    </v-row> -->
    <v-row>
      <v-col cols="12">
        <v-card color="basil" flat>
          <v-card-text>
            <v-data-table :headers="headers" :items="items" class="elevation-0">
              <template v-slot:item.sno="{ item, index }">
                {{
                  currentPage
                    ? (currentPage - 1) * perPage +
                      (cumulativeIndex + items.indexOf(item))
                    : "-"
                }}
              </template>

              <template
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
                    <!-- <small style="font-size: 12px; color: #6c7184">
                      {{ item.device.customer.house_number }},
                      {{ item.device.customer.street_number }},
                      {{ item.device.customer.area }},
                      {{ item.device.customer.city }}
                    </small> -->
                  </v-col>
                </v-row>
              </template>
              <template v-slot:item.customer="{ item }">
                <div>
                  {{
                    item.device?.customer?.primary_contact?.first_name ?? "---"
                  }}
                  {{
                    item.device?.customer?.primary_contact?.last_name ?? "---"
                  }}
                </div>
                <div class="secondary-value">
                  {{ item.device?.customer?.primary_contact?.phone1 ?? "---" }}
                </div>
              </template>
              <template v-slot:item.device="{ item }">
                <div>{{ item.device?.name }}</div>
                <div class="secondary-value">
                  {{ item.device?.serial_number }}
                </div>
              </template>
              <template v-slot:item.sensor="{ item }">
                <div>
                  {{ item.alarm_type }}
                </div>
                <div class="secondary-value">
                  {{ item.device?.location }}
                </div>

                <!-- <div class="secondary-value">{{ item.type }}</div> -->
              </template>
              <!-- <template v-slot:item.location="{ item }">
                    {{ item.device.location }}
                  </template> -->
              <template v-slot:item.zone="{ item }">
                <div>{{ item.zone }}</div>
                <div class="secondary-value">{{ item.area }}</div>
              </template>
              <template v-slot:item.start_date="{ item }">
                <div>
                  {{
                    $dateFormat.formatDateMonthYear(item.alarm_start_datetime)
                  }}
                </div>
                <div class="secondary-value text-center">
                  {{
                    item.alarm_end_datetime
                      ? $dateFormat.formatDateMonthYear(item.alarm_end_datetime)
                      : "---"
                  }}
                </div>
              </template>
              <template v-slot:item.duration="{ item }">
                <div>
                  {{ $dateFormat.minutesToHHMM(item.response_minutes) }}
                </div>
              </template>
              <template v-slot:item.notes="{ item }">
                <div @click="viewNotes(item)">{{ item.notes.length }}</div>
              </template>

              <template v-slot:item.category="{ item }">
                <div>{{ item.alarm_category }}</div>
              </template>

              <template v-slot:item.status="{ item }">
                <img
                  title="Click to Stop Alarm "
                  @click="UpdateAlarmStatus(item, 0)"
                  :src="'/device-icons/' + alarm_icons[item.alarm_type]"
                  style="width: 20px; vertical-align: middle"
                />
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

                <!-- <div v-if="item.alarm_status == 1">
                  <v-icon
                    class="alarm"
                    @click="UpdateAlarmStatus(item, 0)"
                    title="Click to Turn OFF Alarm "
                    >mdi mdi-alarm-light</v-icon
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
                </div> -->
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// import EditAlarmCustomerEventNotes from "../../components/Alarm/EditCustomerEventNotes.vue";
import AlarmEventNotesListView from "../../components/Alarm/AlarmEventsNotesList.vue";
import AlarmCustomerView from "../../components/Alarm/ViewCustomer.vue";
import AlramCloseNotes from "../../components/Alarm/AlramCloseNotes.vue";
export default {
  components: {
    // EditAlarmCustomerEventNotes,
    AlarmEventNotesListView,
    AlarmCustomerView,
    AlramCloseNotes,
  },
  props: ["showFilters", "alarm_icons", "items"],
  data() {
    return {
      dialogCloseAlarm: false,
      dialogViewCustomer: false,
      viewCustomerId: null,
      filterAlarmStatus: null,
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
        { text: "#", value: "sno", sortable: false },
        { text: "Building", value: "building", sortable: false },
        { text: "Customer", value: "customer", sortable: false },
        // { text: "Device", value: "device", sortable: false },
        { text: "Sensor", value: "sensor", sortable: false },

        { text: "Zone/Area", value: "zone", sortable: false },
        // { text: "Alarm Type", value: "alarm_type" , sortable: false },
        { text: "Start/End Date", value: "start_date", sortable: false },
        // { text: "End Date", value: "end_date" , sortable: false },
        // { text: "Resolved Time(H:M)", value: "duration", sortable: false },
        // { text: "Category", value: "category", sortable: false },

        { text: "Notes", value: "notes", sortable: false },
        {
          text: "Status",
          value: "status",
          sortable: false,
          align: "center",
        },

        // { text: "Options", value: "options", sortable: false },
      ],
      // items: [],
      isBackendRequestOpen: false,
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

    // setInterval(() => {
    //   this.getDataFromApi();
    // }, 1000 * 5);

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
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    closeCustomerDialog() {
      this.dialogViewCustomer = false;
    },
    viewCustomerinfo(item) {
      // this.viewCustomerId = item.customer_id;
      // this.dialogViewCustomer = true;
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

      this.getDataFromApi();
      this.$emit("closeDialog");
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi();
    },
    UpdateAlarmStatus(item, status) {
      if (status == 0) {
        if (confirm("Are you sure you want to TURN OFF the Alarm")) {
          this.customer_id = item.customer_id;
          this.eventId = item.id;
          this.dialogCloseAlarm = true;
          this.$emit("callwait5MinutesNextNotification");
        }
      }
      return false;
      if (status == 0) {
        if (confirm("Are you sure you want to TURN OFF the Alarm")) {
          let options = {
            params: {
              company_id: this.$auth.user.company_id,
              customer_id: this.customer_id,
              event_id: item.id,
              status: status,
            },
          };
          this.loading = true;
          this.$axios
            .post(`/update-device-alarm-event-status-off`, options.params)
            .then(({ data }) => {
              this.getDataFromApi();
              if (!data.status) {
                if (data.message == "undefined") {
                  this.response = "Try again. No connection available";
                } else this.response = "Try again. " + data.message;
                this.snackbar = true;

                return;
              } else {
                setTimeout(() => {
                  this.loading = false;
                  this.response = data.message;
                  this.snackbar = true;
                }, 2000);

                return;
              }
            })
            .catch((e) => console.log(e));
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
    getDataFromApi() {
      // console.log(
      //   "this.$route.query.alarm_status",
      //   this.$route.query.alarm_status
      // );
      // if (this.$route.query.alarm_status) {
      //   this.filterAlarmStatus = parseInt(this.$route.query.alarm_status);
      //   this.date_from = null;
      //   this.date_to = null;
      // }
      if (this.isBackendRequestOpen) return false;

      this.isBackendRequestOpen = true;
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
          sortBy: "alarm_start_datetime",
          sortDesc: "desc",
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          //customer_id: this.customer_id,
          // date_from: this.date_from,
          // date_to: this.date_to,
          common_search: this.commonSearch,
          tab: this.tab,
          alarm_status: 1,
          filterSensorname: this.tab > 0 ? this.sensorItems[this.tab] : null,
        },
      };

      try {
        this.$axios
          .get(`get_alarm_notification_display`, options)
          .then(({ data }) => {
            this.items = data;
            this.isBackendRequestOpen = false;
            this.totalRowsCount = 1000; //data.total;
            this.loading = false;
            this.showTable = true;
          });
      } catch (e) {
        this.isBackendRequestOpen = false;
      }
    },
  },
};
</script>
