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
            <v-card class="py-2" style="height: 772px; overflow: hidden">
              <AllEvents :filterDeviceType="'Intruder'" />
              <!-- <DashboardRealTimeLogTableview :branch_id="branch_id" /> -->
            </v-card>
          </v-col>
        </v-row>
        <!-- <v-row class="d-xs-flex">
          <v-col
            lg="6"
            md="6"
            sm="12"
            xs="12"
            class="d-xs-flex"
            style="flex: auto"
          >
            <v-card class="py-2 mt-2" style="height: 312px; overflow: hidden">
            </v-card>
          </v-col>
          <v-col lg="6" md="6" sm="12" xs="12">
            <v-card class="py-2 mt-2" style="height: 312px; overflow: hidden">
            </v-card>
          </v-col>
        </v-row> -->
      </v-col>

      <v-col lg="3" md="3" sm="12" xs="12">
        <AlarmCustomersStas />

        <v-row>
          <v-col lg="12" md="12" sm="12" xs="12">
            <v-card class="py-2 mt-2" style="height: 443px; overflow: hidden">
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
  </div>
</template>

<script>
import AlarmCustomersStas from "../../components/Admin/AlarmCustomersStas.vue";
import AlarmEventsDayWiseChart from "../../components/Admin/AlarmEventsDayWiseChart.vue";
import AlarmEventsTodayStas from "../../components/Admin/AlarmEventsTodayStas.vue";
import AllEvents from "../../components/Admin/AllEvents.vue";
export default {
  components: {
    AlarmEventsDayWiseChart,
    AlarmEventsTodayStas,
    AlarmCustomersStas,
    AllEvents,
  },
  data: () => ({}),
  computed: {},
  mounted() {},
  created() {},
  watch: {},
  methods: {},
};
</script>
