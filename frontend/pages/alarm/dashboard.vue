<template>
  <div>
    <v-row>
      <v-col cols="3">
        <v-card class="elevation-2" style="height: 230px">
          <v-card-title>Total </v-card-title>
          <v-card-text
            ><AlamDeviceCountPieChart :name="'AlamDeviceCountPieChart'"
          /></v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card class="elevation-2" style="height: 230px">
          <v-card-title>Status</v-card-title>
          <v-card-text>
            <AlamDeviceLiveCountPieChart name="AlamDeviceLiveCountPieChart" />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card class="elevation-2" style="height: 230px">
          <v-card-title>Contract</v-card-title>
          <v-card-text>
            <AlamCustomerContractPieChart name="AlamCustomerContractPieChart" />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card class="elevation-2" style="height: 230px">
          <v-card-title>Sensor Devices</v-card-title>
          <v-card-text class="p-0">
            <AlamCustomerSensorPieChart name="AlamCustomerSensorPieChart" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import AlamDeviceCountPieChart from "../../components/Alarm/Dashboard/AlarmDeviceCountPieChart.vue";
import AlamDeviceLiveCountPieChart from "../../components/Alarm/Dashboard/AlamDeviceLiveCountPieChart.vue";
import AlamCustomerContractPieChart from "../../components/Alarm/Dashboard/AlamCustomerContractPieChart.vue";
import AlamCustomerSensorPieChart from "../../components/Alarm/Dashboard/AlamCustomerSensorPieChart.vue";

export default {
  components: {
    AlamDeviceCountPieChart,
    AlamDeviceLiveCountPieChart,
    AlamCustomerContractPieChart,
    AlamCustomerSensorPieChart,
  },
  data: () => ({
    profile_percentage: 60,
    tab: null,

    _id: null,
  }),
  computed: {},
  mounted() {},
  created() {
    // this._id = this.$route.params.id;
  },
  watch: {},
  methods: {},
};
</script>
