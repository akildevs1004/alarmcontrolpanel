<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else>
    <v-row>
      <v-col cols="3" class="p-1">
        <v-card class="elevation-2" style="height: 200px">
          <!-- <v-card-title>Armed </v-card-title> -->
          <v-card-text>
            <AlamCustomerEventsPieChart
              :key="key"
              :name="'AlamCustomerEventsPieChart'"
              :date_from="date_from"
              :date_to="date_to"
          /></v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" class="p-1">
        <v-card class="elevation-2" style="height: 200px">
          <!-- <v-card-title>Live</v-card-title> -->
          <v-card-text>
            <AlamDeviceLiveCountPieChart
              :key="key"
              name="AlamDeviceLiveCountPieChart"
            />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" class="p-1">
        <v-card class="elevation-2" style="height: 200px">
          <!-- <v-card-title>Contract</v-card-title> -->
          <v-card-text>
            <AlamCustomerContractPieChart
              :key="key"
              name="AlamCustomerContractPieChart"
            />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3" class="p-1">
        <v-card class="elevation-2" style="height: 200px">
          <!-- <v-card-title>Alarms</v-card-title> -->
          <v-card-text class="p-0">
            <AlamDeviceCountPieChart
              :key="key"
              :name="'AlamDeviceCountPieChart'"
            />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-card class="elevation-2">
          <v-card-text
            class="mt-0 pr-8"
            :style="'min-height:' + (windowHeight - 330) + 'px'"
          >
            <AlamAllEventsDashboard name="dashboardPieChart" /> </v-card-text
        ></v-card>
      </v-col>
    </v-row>
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
