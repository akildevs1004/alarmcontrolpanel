<template>
  <NoAccess v-if="!$pagePermission.can('alarms_view', this)" />
  <div v-else>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <CompAlarmDashboardStatistics />

    <ComponentAllEvents name="AllEvents1" showFilters="true" showTabs="true" />
  </div>
</template>

<script>
import ComponentAllEvents from "../../components/Alarm/ComponentAllEvents.vue";

import CompAlarmDashboardStatistics from "../../components/Alarm/CompAlarmDashboardStatistics.vue";

export default {
  components: {
    ComponentAllEvents,
    CompAlarmDashboardStatistics,
  },

  data() {
    return {
      requestStatus: false,
      tab: 0,
      //sensorItems: [],
      value: "recent",
      customer_id: null,
      snackbar: false,
      response: "",
      key: 1,
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
        { text: "Customer", value: "customer", sortable: false },

        { text: "Device", value: "device", sortable: false },
        { text: "Sensor", value: "sensor", sortable: false },
        { text: "Location", value: "location", sortable: false },

        { text: "Zone", value: "zone", sortable: false },
        // { text: "Alarm Type", value: "alarm_type" , sortable: false },
        { text: "Start/End Date", value: "start_date", sortable: false },
        // { text: "End Date", value: "end_date" , sortable: false },
        { text: "Resolved Time(H:M)", value: "duration", sortable: false },
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
      items: [],
      // onlineStats: {
      //   Burglary: { online: 0, offline: 0 },
      //   Water: { online: 0, offline: 0 },
      //   Fire: { online: 0, offline: 0 },
      //   Temperature: { online: 0, offline: 0 },
      //   Medical: { online: 0, offline: 0 },
      // },
    };
  },
  watch: {
    // options: {
    //   handler() {
    //     this.getDataFromApi();
    //   },
    //   deep: true,
    // },
    // tab: {
    //   handler() {
    //     this.getDataFromApi();
    //   },
    //   deep: true,
    // },
  },
  created() {
    // setInterval(() => {
    //   if (this.$route.name == "alarm-allevents") {
    //     this.key = this.key + 1;
    //     this.getDatafromApi();
    //   }
    // }, 1000 * 30);
    // let today = new Date();
    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = monthObj.first;
    // this.date_to = monthObj.last;
    // //this.getDataFromApi();
    // if (this.$route.name == "alarm-alarm-events") {
    //   setInterval(() => {
    //     this.getDataFromApi();
    //   }, 1000 * 60);
    // }
    // this.getSensorsList();
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    // getDatafromApi() {
    //   let options = {
    //     params: {
    //       company_id: this.$auth.user.company_id,
    //     },
    //   };

    //   this.$axios
    //     .get(`/security_device_live_stats_groupby`, options)
    //     .then(({ data }) => {
    //       this.onlineStats = data;
    //     });
    // },
    // viewNotes(item) {
    //   this.key = this.key + 1;
    //   this.eventId = item.id;
    //   this.dialogNotesList = true;
    // },
    // // getSensorsList() {
    // //   if (this.$store.state.storeAlarmControlPanel?.SensorTypes) {
    // //     // this.sensorItems = this.$store.state.storeAlarmControlPanel.SensorTypes;
    // //     this.sensorItems = [
    // //       "All",
    // //       ...this.$store.state.storeAlarmControlPanel.SensorTypes,
    // //     ];
    // //   }
    // //   // this.$axios
    // //   //   .get(`sensors_types`, {
    // //   //     params: {
    // //   //       company_id: this.$auth.user.company_id,
    // //   //     },
    // //   //   })
    // //   //   .then(({ data }) => {
    // //   //     this.sensorItems = ["All", ...data];
    // //   //   });
    // // },
    // addNotes(item) {
    //   this.eventId = item.id;
    //   this.dialogAddCustomerNotes = true;
    // },
    // closeDialog() {
    //   this.dialogAddCustomerNotes = false;
    //   this.getDataFromApi();
    //   this.$emit("closeDialog");
    // },
    // filterAttr(data) {
    //   this.date_from = data.from;
    //   this.date_to = data.to;

    //   this.getDataFromApi();
    // },
    // UpdateAlarmStatus(item, status) {
    //   if (status == 0) {
    //     if (confirm("Are you sure you want to TURN OFF the Alarm")) {
    //       let options = {
    //         params: {
    //           company_id: this.$auth.user.company_id,
    //           customer_id: this.customer_id,
    //           event_id: item.id,
    //           status: status,
    //         },
    //       };
    //       this.loading = true;
    //       this.$axios
    //         .post(`/update-device-alarm-event-status-off`, options.params)
    //         .then(({ data }) => {
    //           this.getDataFromApi();
    //           if (!data.status) {
    //             if (data.message == "undefined") {
    //               this.response = "Try again. No connection available";
    //             } else this.response = "Try again. " + data.message;
    //             this.snackbar = true;

    //             return;
    //           } else {
    //             setTimeout(() => {
    //               this.loading = false;
    //               this.response = data.message;
    //               this.snackbar = true;
    //             }, 2000);

    //             return;
    //           }
    //         })
    //         .catch((e) => console.log(e));
    //     }
    //   }
    // },
    // deleteEvent(id) {
    //   if (confirm("Are you sure want to delete Alarm Event notes?")) {
    //     this.loading = true;
    //     let options = {
    //       params: {
    //         company_id: this.$auth.user.company_id,
    //         id: id,
    //       },
    //     };

    //     try {
    //       this.$axios.delete(`delete-event`, options).then(({ data }) => {
    //         this.snackbar = true;
    //         this.response = "Event Note Deleted Successfully";
    //         this.getDataFromApi();
    //         this.loading = false;
    //       });
    //     } catch (e) {}
    //   }
    // },
    // getDataFromApi() {
    //   this.loading = true;

    //   let { sortBy, sortDesc, page, itemsPerPage } = this.options;

    //   let sortedBy = sortBy ? sortBy[0] : "";
    //   let sortedDesc = sortDesc ? sortDesc[0] : "";
    //   this.perPage = itemsPerPage;
    //   this.currentPage = page;
    //   if (!page > 0) return false;
    //   let options = {
    //     params: {
    //       page: page,
    //       sortBy: sortedBy,
    //       sortDesc: sortedDesc,
    //       perPage: itemsPerPage,
    //       pagination: true,
    //       company_id: this.$auth.user.company_id,
    //       //customer_id: this.customer_id,
    //       date_from: this.date_from,
    //       date_to: this.date_to,
    //       common_search: this.commonSearch,
    //       tab: this.tab,
    //       filterSensorname: this.tab > 0 ? this.sensorItems[this.tab] : null,
    //     },
    //   };

    //   try {
    //     this.$axios.get(`get_alarm_events`, options).then(({ data }) => {
    //       this.items = data.data;

    //       this.totalRowsCount = data.total;
    //       this.loading = false;
    //     });
    //   } catch (e) {}
    // },
  },
};
</script>
