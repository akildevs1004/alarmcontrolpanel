<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else>
    <v-row
      ><v-col cols="9"
        ><v-row>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row><v-col>SOS</v-col><v-col>13</v-col></v-row></v-card-text
              ></v-card
            >
          </v-col>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row><v-col>SOS</v-col><v-col>13</v-col></v-row></v-card-text
              ></v-card
            > </v-col
          ><v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row><v-col>SOS</v-col><v-col>13</v-col></v-row></v-card-text
              ></v-card
            >
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-card style="height: 250px" elevation="2"
              ><v-card-text>System Status</v-card-text></v-card
            > </v-col
          ><v-col>
            <v-card style="height: 250px" elevation="2"
              ><v-card-text>Alarm Status</v-card-text></v-card
            >
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-card style="height: 250px" elevation="2"
              ><v-card-text>Reports Tabs</v-card-text></v-card
            >
          </v-col>
        </v-row> </v-col
      ><v-col cols="3">
        <v-row>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row><v-col>SOS</v-col><v-col>13</v-col></v-row></v-card-text
              ></v-card
            >
          </v-col>
        </v-row></v-col
      ></v-row
    >
  </div>
</template>

<script>
import AlamDeviceCountPieChart from "../../components/Alarm/Dashboard/AlarmDeviceCountPieChart.vue";
import AlamDeviceLiveCountPieChart from "../../components/Alarm/Dashboard/AlamDeviceLiveCountPieChart.vue";
import AlamCustomerContractPieChart from "../../components/Alarm/Dashboard/AlamCustomerContractPieChart.vue";
import AlamCustomerSensorPieChart from "../../components/Alarm/Dashboard/AlamCustomerSensorPieChart.vue";

import AlamAllEventsDashboard from "../../components/Alarm/ComponentAllEvents.vue";
import AlamCustomerEventsPieChart from "../../components/Alarm/Dashboard/AlamCustomerEventsPieChart.vue";

export default {
  components: {
    AlamDeviceCountPieChart,
    AlamDeviceLiveCountPieChart,
    AlamCustomerContractPieChart,
    AlamCustomerSensorPieChart,
    AlamAllEventsDashboard,
    AlamCustomerEventsPieChart,
  },
  data: () => ({
    windowHeight: 1000,
    key: 1,
    profile_percentage: 60,
    tab: null,

    _id: null,
    date_from: null,
    date_to: null,
  }),
  computed: {},
  mounted() {
    setInterval(() => {
      if (window) this.windowHeight = window.innerHeight;
    }, 1000 * 5);
  },
  created() {
    // this._id = this.$route.params.id;
    let today = new Date();

    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;
  },
  watch: {},
  methods: {},
};
</script>
