<template>
  <div>
    <CompAlarmDashboardStatistics :compFilterSupervisor="false" />
    <!-- <v-row
        ><v-col><v-divider color="#DDD" /></v-col>
      </v-row> -->
    <v-row class="mt-5">
      <v-col cols="12" style="padding-right: 25px">
        <v-card class="elevation-2">
          <v-card-text class="mt-5">
            <AlamAllEventsDashboardsecurity
              :compFilterAlarmStatus="1"
              name="securitydashboard"
            /> </v-card-text
        ></v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// import AlarmBurglaryPieChart from "../../components/Alarm/SecurityDashboard/AlarmBurglaryPieChart.vue";
// import AlarmMedicalPieChart from "../../components/Alarm/SecurityDashboard/AlarmMedicalPieChart.vue";
// import AlarmTemperaturePieChart from "../../components/Alarm/SecurityDashboard/AlarmTemperaturePieChart.vue";

// import AlarmFirePieChart from "../../components/Alarm/SecurityDashboard/AlarmFirePieChart.vue";

// import AlarmWaterPieChart from "../../components/Alarm/SecurityDashboard/AlarmWaterPieChart.vue";
// import AlarmLivePieChart from "../../components/Alarm/SecurityDashboard/AlarmLivePieChart.vue";
import AlamAllEventsDashboardsecurity from "../../components/Alarm/ComponentAllEvents.vue";
import CompAlarmDashboardStatistics from "../../components/Alarm/CompAlarmDashboardStatistics.vue";

export default {
  layout: "security",
  components: {
    // AlarmBurglaryPieChart,
    // AlarmTemperaturePieChart,
    // AlarmMedicalPieChart,
    // AlarmFirePieChart,
    // AlarmWaterPieChart,
    // AlarmLivePieChart,
    AlamAllEventsDashboardsecurity,
    CompAlarmDashboardStatistics,
  },
  data: () => ({
    dialogEventsList: false,
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
  }),
  computed: {},
  mounted() {
    setTimeout(() => {
      this.key = 1;
      //this.getDatafromApi();
    }, 2000);
  },
  async created() {
    // setInterval(() => {
    //   if (this.$route.name == "security-dashboard") {
    //     this.key = this.key + 1;
    //     this.getDatafromApi();
    //   }
    // }, 1000 * 30);
    // // this._id = this.$route.params.id;
    // let today = new Date();
    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = monthObj.first;
    // this.date_to = monthObj.last;
  },
  watch: {},
  methods: {
    // getPercentage($key) {
    //   let test =
    //     (this.onlineStats.Temperature.online * 100) /
    //     (this.onlineStats.Temperature.online,
    //     this.onlineStats.Temperature.offline);
    //   return test;
    // },
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
  },
};
</script>
