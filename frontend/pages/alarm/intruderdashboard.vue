<template>
  <div style="width: 100%; margin-top: -30px">
    <v-row style="width: 100%">
      <v-col lg="9" md="9" sm="12" xs="12">
        <v-row>
          <v-col md="12">
            <v-card class="pa-2" style="height: 300px; overflow: hidden">
              <v-row background fill>
                <v-col
                  lg="8"
                  md="8"
                  sm="12"
                  xs="12"
                  class="d-xs-flex"
                  style="flex: auto"
                >
                  <AlarmEventsDayWiseChart
                    :name="'AttendanceChart1'"
                    :height="'250'"
                  />
                </v-col>
                <v-col
                  lg="4"
                  md="4"
                  sm="12"
                  xs="12"
                  class="d-xs-flex pa-2"
                  style="border-left: 1px solid #ddd"
                >
                  <AlarmEventsTodayStas name="AlarmEventsTodayStas" />
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
        <v-row>
          <v-col lg="12" md="12" sm="12" xs="12">
            <v-card
              class="py-2"
              style="height: 600px; overflow-y: auto; overflow-x: hidden"
            >
              <AllEvents :filterDeviceType="'Intruder'" v-if="loadEvents" />
              <!-- <DashboardRealTimeLogTableview :branch_id="branch_id" /> -->
            </v-card>
          </v-col>
        </v-row>
      </v-col>

      <v-col lg="3" md="3" sm="12" xs="12">
        <AlarmCustomersStas v-if="loadCustomerStats" />

        <v-row>
          <v-col lg="12" md="12" sm="12" xs="12">
            <v-card class="py-2 mt-2" style="height: 290px; overflow: hidden">
              <DashboardOperatorLiveStatus />
            </v-card>
          </v-col>
        </v-row>
        <!-- <v-row>
          <v-col lg="12" md="12" sm="12" xs="12">
            <v-card class="py-2 mt-2" style="height: 312px; overflow: hidden">
            </v-card>
          </v-col>
        </v-row> -->
      </v-col>
    </v-row>
    <v-row class="d-xs-flex">
      <v-col lg="6" md="6" sm="12" xs="12" class="d-xs-flex" style="flex: auto">
        <v-card class="py-2 mt-2" style="height: 312px; overflow: hidden">
          <DashboardLoginActivities
            v-if="loadLoginActivites"
            :filter_user_type="'security'"
          />
        </v-card>
      </v-col>
      <v-col lg="6" md="6" sm="12" xs="12">
        <v-card class="py-2 mt-2" style="height: 312px; overflow: hidden">
          <DashboardAlarmEventsHourWiseChart
            name="DashboardAlarmEventsHourWiseChart1"
            :height="'250'"
            v-if="loadHourChart"
          />
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import AlarmCustomersStas from "../../components/Admin/DashboardAlarmCustomersStas.vue";
import AlarmEventsDayWiseChart from "../../components/Admin/DashboardAlarmEventsDayWiseChart.vue";
import DashboardAlarmEventsHourWiseChart from "../../components/Admin/DashboardAlarmEventsHourWiseChart.vue";

import AlarmEventsTodayStas from "../../components/Admin/DashboardAlarmEventsTodayStas.vue";
import AllEvents from "../../components/Admin/DashboardAllEvents.vue";
import DashboardLoginActivities from "../../components/Admin/DashboardLoginActivities.vue";
import DashboardOperatorLiveStatus from "../../components/Admin/DashboardOperatorLiveStatus.vue";
export default {
  components: {
    AlarmEventsDayWiseChart,
    AlarmEventsTodayStas,
    AlarmCustomersStas,
    AllEvents,
    DashboardLoginActivities,
    DashboardAlarmEventsHourWiseChart,
    DashboardOperatorLiveStatus,
  },
  data: () => ({
    loadCustomerStats: false,
    loadLoginActivites: false,
    loadHourChart: false,
    loadEvents: false,
  }),
  computed: {},
  mounted() {
    setTimeout(() => {
      this.loadEvents = true;
    }, 1000 * 3);
    setTimeout(() => {
      this.loadCustomerStats = true;
    }, 1000 * 6);
    setTimeout(() => {
      this.loadLoginActivites = true;
    }, 1000 * 9);
    setTimeout(() => {
      this.loadHourChart = true;
    }, 1000 * 12);
  },
  created() {},
  watch: {},
  methods: {},
};
</script>
