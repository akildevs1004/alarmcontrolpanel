<template>
  <div>
    <v-row>
      <v-col cols="3" class="pr-0" style="max-width: 20%">
        <v-card class="elevation-2" style="height: 180px">
          <v-card-text>
            <TicketsStats v-if="key" :key="key" :name="'TicketsStats'"
          /></v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" class="pr-0" style="max-width: 20%">
        <v-card class="elevation-2" style="height: 180px">
          <v-card-text>
            <TicketsTodayStats
              @emitStoprefresh="stopRefreshdata"
              @emitStartrefresh="startRefreshdata"
              v-if="key"
              :key="key"
              :name="'TicketsTodayStats'"
            />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" class="pr-0" style="max-width: 20%">
        <v-card class="elevation-2" style="height: 180px">
          <v-card-text>
            <TicketsMonthlyStats
              @emitStoprefresh="stopRefreshdata"
              @emitStartrefresh="startRefreshdata"
              v-if="key"
              :key="key"
              :name="'TicketsMonthlyStats'"
          /></v-card-text>
        </v-card>
      </v-col>

      <v-col cols="3" class="pr-0" style="max-width: 20%">
        <v-card class="elevation-2" style="height: 180px">
          <v-card-text>
            <Tickets10DaysPendingStats
              @emitStoprefresh="stopRefreshdata"
              @emitStartrefresh="startRefreshdata"
              v-if="key"
              :key="key"
              :name="'Tickets10DaysPendingStats'"
            />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" style="max-width: 20%">
        <v-card class="elevation-2" style="height: 180px">
          <v-card-text>
            <Tickets30DaysPendingStats
              @emitStoprefresh="stopRefreshdata"
              @emitStartrefresh="startRefreshdata"
              v-if="key"
              :key="key"
              :name="'Tickets30DaysPendingStats'"
            />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row class="mt-5">
      <v-col cols="12">
        <TechnicianTicketList
          :canReply="canReply"
          :status="1"
          :technician_id="technician_id"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import TicketsStats from "../../../components/Alarm/TechnicianDashboard/TicketsStats.vue";
import TicketsMonthlyStats from "../../../components/Alarm/TechnicianDashboard/TicketsMonthlyStats.vue";
import TicketsTodayStats from "../../../components/Alarm/TechnicianDashboard/TicketsTodayStats.vue";

import Tickets10DaysPendingStats from "../../../components/Alarm/TechnicianDashboard/Tickets10DaysPendingStats.vue";

import Tickets30DaysPendingStats from "../../../components/Alarm/TechnicianDashboard/Tickets30DaysPendingStats.vue";

import TechnicianTicketList from "../../../components/Tickets/TechnicianTicketList.vue";

export default {
  props: ["canReply"],
  components: {
    TicketsStats,
    TicketsMonthlyStats,
    TicketsTodayStats,
    Tickets10DaysPendingStats,
    Tickets30DaysPendingStats,
    TechnicianTicketList,
  },
  data: () => ({
    dialogEventsList: false,
    technician_id: null,
    // burglaryOnline: { offline: 0, online: 0 },
    // fireOnline: { offline: 0, online: 0 },
    // waterOnline: { offline: 0, online: 0 },
    // temperatureOnline: { offline: 0, online: 0 },
    // medicalOnline: { offline: 0, online: 0 },

    onlineStats: {
      Burglary: { online: 0, offline: 0 },
      Water: { online: 0, offline: 0 },
      Fire: { online: 0, offline: 0 },
      Temperature: { online: 0, offline: 0 },
      Medical: { online: 0, offline: 0 },
    },

    key: false,
    profile_percentage: 60,
    tab: null,

    _id: null,
    date_from: null,
    date_to: null,
    refreshData: true,
  }),
  computed: {},
  mounted() {
    setTimeout(() => {
      this.key = 1;
      this.getDatafromApi();
    }, 2000);
  },
  created() {
    if (this.$auth.user.technician)
      this.technician_id = this.$auth.user.technician.id;
    setInterval(() => {
      if (this.$route.name == "technician-dashboard" && this.refreshData) {
        this.key = this.key + 1;
        this.getDatafromApi();
      }
    }, 1000 * 30);
    // this._id = this.$route.params.id;
    let today = new Date();

    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;
  },
  watch: {},
  methods: {
    stopRefreshdata() {
      this.refreshData = false;
    },
    startRefreshdata() {
      this.refreshData = true;
      this.key = this.key + 1;
      this.getDatafromApi();
    },
    getPercentage($key) {
      let test =
        (this.onlineStats.Temperature.online * 100) /
        (this.onlineStats.Temperature.online,
        this.onlineStats.Temperature.offline);

      return test;
    },
    getDatafromApi() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      this.$axios
        .get(`/security_device_live_stats_groupby`, options)
        .then(({ data }) => {
          this.onlineStats = data;
        });
    },
  },
};
</script>
