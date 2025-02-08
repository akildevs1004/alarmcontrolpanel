<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else>
    <v-row class="padding:0px">
      <v-col cols="9"
        ><v-row>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row>
                  <v-col class="d-flex justify-center" style="max-width: 40px">
                    <img
                      src="/alarm_icons/google_sos_alarm.png"
                      style="width: 25px; margin: auto"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 25px;
                      margin: auto;

                      color: black;
                    "
                    >SOS</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.sosCount : 0 }}</v-col
                  ></v-row
                ></v-card-text
              ></v-card
            >
          </v-col>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row>
                  <v-col class="d-flex justify-center" style="max-width: 40px">
                    <img
                      src="/alarm_icons/google_alarm.png"
                      style="width: 25px"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 25px;
                      margin: auto;

                      color: black;
                    "
                    >Critical</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.criticalCount : 0 }}</v-col
                  ></v-row
                ></v-card-text
              ></v-card
            >
          </v-col>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row>
                  <v-col class="d-flex justify-center" style="max-width: 40px">
                    <img
                      src="/alarm_icons/google_medical_alarm.png"
                      style="width: 25px"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 25px;
                      margin: auto;

                      color: black;
                    "
                    >Medical</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.medicalCount : 0 }}</v-col
                  ></v-row
                ></v-card-text
              ></v-card
            >
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-card style="height: 275px" elevation="2"
              ><v-card-text>
                <h3 style="color: black; font-weight: normal">System Status</h3>
                <AlamDeviceCountPieChart
                  :key="key"
                  :name="'AlamDeviceCountPieChart'"
                  style="
                    height: 230px;

                    max-height: 230px;
                    overflow: hidden;
                  "
                /> </v-card-text
            ></v-card> </v-col
          ><v-col>
            <v-card style="height: 275px" elevation="2"
              ><v-card-text
                ><h3
                  style="
                    color: black;
                    font-weight: normal;
                    margin-top: -10px;
                    padding-bottom: 10px;
                  "
                >
                  Alarm Status
                </h3>

                <v-row
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      v-if="chartEventOpenStatistics"
                      :key="key"
                      :name="'AlarmEventStatusCountPieChart1'"
                      :componentData="chartEventOpenStatistics"
                    /> </v-col
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      v-if="chartEventClosedStatistics"
                      :key="key + 2"
                      :name="'AlarmEventStatusCountPieChart2'"
                      :componentData="chartEventClosedStatistics"
                    /> </v-col
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      v-if="chartEventForwardStatistics"
                      :key="key + 4"
                      :name="'AlarmEventStatusCountPieChart3'"
                      :componentData="chartEventForwardStatistics"
                    /> </v-col
                ></v-row> </v-card-text
            ></v-card>
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-card
              style="height: 500px; overflow-y: auto; overflow-x: hidden"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text
                ><AllEventsDashboard2
                  name="AllEvents1"
                  showFilters="false"
                  showTabs="true"
                  v-if="loadAllEventsTable"
                /> </v-card-text
            ></v-card>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="3">
        <v-row>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row>
                  <v-col class="d-flex justify-center" style="max-width: 40px">
                    <img
                      src="/alarm_icons/google_fire_alarm.png"
                      style="width: 25px"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 25px;
                      margin: auto;

                      color: black;
                    "
                    >Fire</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.fireCount : 0 }}</v-col
                  ></v-row
                ></v-card-text
              ></v-card
            >
          </v-col>
        </v-row>

        <v-row>
          <v-col>
            <v-card
              style="height: 300px; background-color: transparent"
              elevation="0"
            >
              <v-card-text class="font-black">
                <v-row>
                  <v-col
                    style="padding: 5px; padding-left: 0px; padding-top: 0px"
                  >
                    <v-card style="height: 80px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row
                          ><v-col style="height: 20px">
                            <img src="/icons/techical.png" style="width: 25px"
                          /></v-col>
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 30px">
                              {{
                                categoriesStats ? categoriesStats.techinical : 0
                              }}
                            </div>
                            <div style="padding-top: 5px">Technical</div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col
                    style="padding: 5px; padding-right: 0px; padding-top: 0px"
                  >
                    <v-card style="height: 80px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row
                          ><v-col style="height: 20px"
                            ><img src="/icons/intruder.png" style="width: 25px"
                          /></v-col>
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 30px">
                              {{ categoriesStats ? categoriesStats.events : 0 }}
                            </div>
                            <div style="padding-top: 5px">Events</div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col
                    style="padding: 5px; padding-left: 0px; padding-top: 8px"
                  >
                    <v-card style="height: 85px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row
                          ><v-col style="height: 20px"
                            ><img
                              src="/icons/water_icon.png"
                              style="width: 25px"
                          /></v-col>
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 30px">
                              {{ categoriesStats ? categoriesStats.water : 0 }}
                            </div>
                            <div style="padding-top: 5px">Water</div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col
                    style="padding: 5px; padding-right: 0px; padding-top: 8px"
                  >
                    <v-card style="height: 85px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row
                          ><v-col style="height: 20px"
                            ><img
                              src="/icons/temperature_icon.png"
                              style="width: 25px"
                          /></v-col>
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 30px">
                              {{
                                categoriesStats
                                  ? categoriesStats.temperature
                                  : 0
                              }}
                            </div>
                            <div style="padding-top: 5px">Temperature</div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
                <v-row
                  ><v-col style="padding: 0px; padding-top: 5px">
                    <v-card style="height: 85px" elevation="2"
                      ><v-card-text>
                        <v-row>
                          <v-col style="padding: 5px"
                            ><div style="font-size: 18px">Device</div></v-col
                          >
                        </v-row>

                        <v-row>
                          <v-col
                            style="
                              padding: 0px;
                              text-align: center;

                              font-weight: bold;
                            "
                            ><div style="font-size: 20px; color: #06b050">
                              {{
                                customerStatusData
                                  ? customerStatusData.onlineCount
                                  : 0
                              }}
                            </div>
                            <div style="color: #06b050">Online</div>
                          </v-col>
                          <v-col
                            style="
                              padding: 0px;
                              text-align: center;

                              font-weight: bold;
                            "
                            ><div style="font-size: 20px; color: #fe0000">
                              {{
                                customerStatusData
                                  ? customerStatusData.offlineCount
                                  : 0
                              }}
                            </div>
                            <div style="color: #fe0000">Offline</div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <v-row style="margin-top: 0px"
          ><v-col style="padding-top: 0px">
            <v-card
              style="height: 500px"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text
                ><DashboardOperatorLiveStatus
                  v-if="loadAllEventsTable" /></v-card-text
            ></v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row
      ><v-col>
        <v-card
          style="height: 350px"
          elevation="2"
          class="eventslistscroll1 table-font121"
          ><v-card-text>
            <DashboardLoginActivities
              v-if="loadAllEventsTable"
              :filter_user_type="'security'"
            /> </v-card-text
        ></v-card> </v-col
      ><v-col>
        <v-card
          style="height: 350px"
          elevation="2"
          class="eventslistscroll table-font12"
          ><v-card-text>
            <DashboardAlarmEventsHourWiseChart
              v-if="loadAllEventsTable"
              :height="'300'"
              name="DashboardAlarmEventsHourWiseChart1" /></v-card-text
        ></v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import DashboardLoginActivities from "../../components/Admin/DashboardLoginActivities.vue";
import DashboardAlarmEventsHourWiseChart from "../../components/Admin/DashboardAlarmEventsHourWiseChart.vue";

import AlarmEventStatusCountPieChart from "../../components/Alarm/Dashboard/AlarmEventStatusCountPieChart.vue";
import DashboardOperatorLiveStatus from "../../components/Admin/DashboardOperatorLiveStatus.vue";

import AlamDeviceCountPieChart from "../../components/Alarm/Dashboard/AlarmDeviceCountPieChart.vue";
import AllEventsDashboard2 from "../../components/Alarm/ComponentAllEvents.vue";

export default {
  components: {
    AlamDeviceCountPieChart,
    AllEventsDashboard2,
    DashboardOperatorLiveStatus,
    DashboardLoginActivities,
    DashboardAlarmEventsHourWiseChart,
  },
  data: () => ({
    windowHeight: 1000,
    key: 1,
    date_from: "",
    data: [],
    chartEventOpenStatistics: null,
    chartEventClosedStatistics: null,

    chartEventForwardStatistics: null,
    categoriesStats: null,
    customerStatusData: null,
    apiLoading: false,
    cancelgetEventCategoriesStatsToken: null,
    cancelgetEventsTypeStatsToken: null,
    cancelupdateEventsOpenCountStatusToken: null,
    loadAllEventsTable: false,
  }),
  computed: {},
  mounted() {
    setTimeout(() => {
      if (window) this.windowHeight = window.innerHeight;
    }, 1000 * 5);
  },
  async created() {
    // // this._id = this.$route.params.id;
    // let today = new Date();

    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = monthObj.first;
    // this.date_to = monthObj.last;

    let today = new Date();
    this.date_from = today.toISOString().split("T")[0];
    await this.updateEventsOpenCountStatus();
    await this.getEventsTypeStats();

    await this.getEventCategoriesStats();

    setTimeout(() => {
      this.loadAllEventsTable = true;
    }, 1000 * 3);

    setInterval(async () => {
      if (this.$route.name == "alarm-dashboard") {
        this.key++;
        await this.getEventsTypeStats();
        await this.getEventCategoriesStats();
        await this.updateEventsOpenCountStatus();
      }
    }, 1000 * 10);
  },
  watch: {},
  methods: {
    async getEventCategoriesStats() {
      if (this.cancelgetEventCategoriesStatsToken) {
        this.cancelgetEventCategoriesStatsToken.cancel(
          "Previous request canceled."
        );
      }

      this.cancelgetEventCategoriesStatsToken =
        this.$axios.CancelToken.source();

      try {
        const options = {
          params: {
            company_id: this.$auth.user.company_id,
            date_from: this.date_from,
          },
          cancelgetEventCategoriesStatsToken:
            this.cancelgetEventCategoriesStatsToken.token, // Attach cancel token
        };

        const { data } = await this.$axios.get(`/alarm_statistics`, options);

        // Update data
        this.categoriesStats = data;
      } catch (error) {
        if (this.$axios.isCancel(error)) {
          console.log("Request canceled:", error.message);
        } else {
          console.error("API Error:", error);
        }
      } finally {
        this.apiLoading = false; // Reset loading state
      }
    },

    // async getEventCategoriesStats() {
    //   //if (this.apiLoading) return false;

    //   this.apiLoading = true;
    //   let options = {
    //     params: {
    //       company_id: this.$auth.user.company_id,
    //       date_from: this.date_from,
    //     },
    //   };

    //   this.$axios.get(`/alarm_statistics`, options).then(({ data }) => {
    //     this.categoriesStats = data;
    //     this.apiLoading = false;
    //   });
    // },

    async getEventsTypeStats() {
      // Cancel any ongoing request
      if (this.cancelgetEventsTypeStatsToken) {
        this.cancelgetEventsTypeStatsToken.cancel("Previous request canceled.");
      }

      // Create a new cancel token
      this.cancelgetEventsTypeStatsToken = this.$axios.CancelToken.source();

      this.apiLoading = true; // Start loading state

      try {
        const response = await this.$axios.get(
          "dashboard_statistics_date_range",
          {
            params: {
              company_id: this.$auth.user.company_id,
              date_from: this.date_from,
              date_to: this.date_from, // Ensure this is intentional
            },
            cancelgetEventsTypeStatsToken:
              this.cancelgetEventsTypeStatsToken.token, // Attach cancel token
          }
        );

        // Handle response
        if (response.data.length > 0) {
          this.data = response.data[0];
        }
      } catch (error) {
        if (this.$axios.isCancel(error)) {
          console.log("Request canceled:", error.message);
        } else {
          console.error("API Error:", error);
        }
      } finally {
        this.apiLoading = false; // End loading state
      }
    },

    async updateEventsOpenCountStatus() {
      // Cancel any ongoing request
      if (this.cancelupdateEventsOpenCountStatusToken) {
        this.cancelupdateEventsOpenCountStatusToken.cancel(
          "Previous request canceled."
        );
      }

      // Create a new cancel token
      this.cancelupdateEventsOpenCountStatusToken =
        this.$axios.CancelToken.source();

      //if (this.apiLoading) return false;

      this.apiLoading = true;
      let today = new Date();
      let date = today.toISOString().split("T")[0];
      this.$axios
        .get("dashboard_statistics_customers", {
          params: {
            company_id: this.$auth.user.company_id,
            date: date,
          },
          cancelupdateEventsOpenCountStatusToken:
            this.cancelupdateEventsOpenCountStatusToken.token, // Attach cancel token
        })
        .then(({ data }) => {
          if (data) {
            this.customerStatusData = data;
            this.chartEventOpenStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
              percentage: 0,
            };

            const total =
              parseInt(data.openCount) +
              parseInt(data.closedCount) +
              parseInt(data.forwardCount);

            let openPercentage = 0;
            let closedPercentage = 0;
            let forwardPercentage = 0;
            if (total > 0) {
              openPercentage = Math.round((data.openCount / total) * 100, 2);
              closedPercentage = Math.round((data.closedCount / total) * 100);
              forwardPercentage = Math.round((data.forwardCount / total) * 100);
            }

            this.chartEventOpenStatistics.title = "Open";
            this.chartEventOpenStatistics.labels[0] = "Open";
            this.chartEventOpenStatistics.series[0] = data.openCount; // data.total - data.armed;

            this.chartEventOpenStatistics.labels[1] = "Closed";
            this.chartEventOpenStatistics.series[1] = data.closedCount;

            this.chartEventOpenStatistics.labels[2] = "Forward";
            this.chartEventOpenStatistics.series[2] = data.forwardCount; // data.armed;

            this.chartEventOpenStatistics.colors = ["#07af50", "#DDD", "#DDD"];
            this.chartEventOpenStatistics.customTotalValue = total; //this.items.ExpectingCount;

            this.chartEventOpenStatistics.percentage = openPercentage;

            //----------------------------

            this.chartEventClosedStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
              percentage: 0,
            };
            this.chartEventClosedStatistics.title = "Closed";
            this.chartEventClosedStatistics.labels[0] = "Closed";
            this.chartEventClosedStatistics.series[0] = data.closedCount; // data.total - data.armed;

            this.chartEventClosedStatistics.labels[1] = "Forward";
            this.chartEventClosedStatistics.series[1] = data.forwardCount; // data.armed;

            this.chartEventClosedStatistics.labels[2] = "Open";
            this.chartEventClosedStatistics.series[2] = data.openCount; // data.armed;

            this.chartEventClosedStatistics.colors = [
              "#fe0000",
              "#DDD",
              "#DDD",
            ];
            this.chartEventClosedStatistics.customTotalValue = total; //this.items.ExpectingCount;

            this.chartEventClosedStatistics.percentage = closedPercentage;

            //----------------------------

            this.chartEventForwardStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
              percentage: 0,
            };
            this.chartEventForwardStatistics.title = "Forward";
            this.chartEventForwardStatistics.labels[0] = "Forward";
            this.chartEventForwardStatistics.series[0] = data.forwardCount; // data.total - data.armed;

            this.chartEventForwardStatistics.labels[1] = "Closed";
            this.chartEventForwardStatistics.series[1] = data.closedCount; // data.armed;

            this.chartEventForwardStatistics.labels[2] = "Open";
            this.chartEventForwardStatistics.series[2] = data.openCount; // data.armed;

            this.chartEventForwardStatistics.colors = [
              "#ffbe00",
              "#DDD",
              "#DDD",
            ];
            this.chartEventForwardStatistics.customTotalValue = total; //this.items.ExpectingCount;

            this.chartEventForwardStatistics.percentage = forwardPercentage;

            this.apiLoading = false;
          }
        });
    },
  },
};
</script>
