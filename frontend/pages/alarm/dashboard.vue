<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else-if="!isMobileDevice">
    <v-row class="padding:0px">
      <v-col cols="9">
        <v-row>
          <v-col>
            <v-card elevation="2"
              ><v-card-text
                ><v-row>
                  <v-col class="d-flex justify-center" style="max-width: 40px">
                    <img
                      src="/notification_icons/sos.png"
                      style="width: 40px; margin: auto"
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
                      src="/notification_icons/critical.png"
                      style="width: 40px"
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
                      src="/notification_icons/medical.png"
                      style="width: 40px"
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
                  Alarm Status({{
                    $auth
                      ? $auth.user.company.dashboard_alarm_open_count_days
                      : 30
                  }}
                  Days)
                </h3>

                <v-row
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      :key="Openkey"
                      :name="'AlarmEventStatusCountPieChart1'"
                      :componentData="chartEventOpenStatistics"
                    /> </v-col
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      :key="Closedkey"
                      :name="'AlarmEventStatusCountPieChart2'"
                      :componentData="chartEventClosedStatistics"
                    /> </v-col
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      :key="Forwardkey"
                      :name="'AlarmEventStatusCountPieChart3'"
                      :componentData="chartEventForwardStatistics"
                    /> </v-col
                ></v-row> </v-card-text
            ></v-card>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <!-- <v-card
              style="height: 500px"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text> -->
            <CustomersAlarmMap
              :displayTable="false"
              mapid="dashboardmap"
              :mapHeight="mapHeight"
              isWithOutAlarms="true"
            />

            <!--</v-card-text
            >
           </v-card> -->
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
                      src="/notification_icons/fire.png"
                      style="width: 40px"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    :style="{
                      fontSize: mapHeight < 300 ? '15px' : '25px',
                      margin: 'auto',
                      color: 'black',
                    }"
                    >Fire</v-col
                  ><v-col
                    class="d-flex justify-center"
                    :style="{
                      fontSize: mapHeight < 300 ? '30px' : '40px',
                      fontWeight: 'bold',
                      margin: 'auto',
                      color: 'red',
                    }"
                    >{{ data ? data.fireCount : 0 }}</v-col
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
                      src="/notification_icons/water.png"
                      style="width: 40px"
                    />
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    :style="{
                      fontSize: mapHeight < 300 ? '15px' : '25px',
                      margin: 'auto',
                      color: 'black',
                    }"
                  >
                    Water
                  </v-col>

                  <v-col
                    class="d-flex justify-center"
                    :style="{
                      fontSize: mapHeight < 300 ? '30px' : '40px',
                      fontWeight: 'bold',
                      margin: 'auto',
                      color: 'red',
                    }"
                    >{{ data ? data.waterCount : 0 }}</v-col
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
                        <v-row>
                          <v-col style="max-width: 45px; margin: auto">
                            <img
                              src="/notification_icons/technical.png"
                              style="width: 45px"
                            />
                          </v-col>

                          <v-col style="text-align: center">
                            <div style="font-size: 40px">
                              {{ data ? data.technicalCount : 0 }}
                            </div>
                            <div style="padding-top: 5px">Technical</div>
                          </v-col>
                        </v-row>
                        <!-- <v-row
                          ><v-col style="height: 20px">
                            <img
                              src="/notification_icons/technical.png"
                              style="width: 30px"
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
                        </v-row> -->
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col
                    style="padding: 5px; padding-right: 0px; padding-top: 0px"
                  >
                    <v-card style="height: 80px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row>
                          <v-col style="max-width: 45px; margin: auto">
                            <img
                              src="/notification_icons/event.png"
                              style="width: 45px"
                            />
                          </v-col>

                          <v-col style="text-align: center">
                            <div style="font-size: 40px">
                              {{ data ? data.eventsCount : 0 }}
                            </div>
                            <div style="padding-top: 5px">Events</div>
                          </v-col>
                        </v-row>
                        <!-- <v-row
                          ><v-col style="height: 20px"
                            ><img
                              src="/notification_icons/event.png"
                              style="width: 30px"
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
                        </v-row> -->
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
                        <v-row>
                          <v-col style="max-width: 45px; margin: auto">
                            <img
                              src="/notification_icons/power.png"
                              style="width: 45px"
                            />
                          </v-col>

                          <v-col style="text-align: center">
                            <div style="font-size: 40px">
                              {{ data ? data.powerCount : 0 }}
                            </div>
                            <div style="padding-top: 5px">Power</div>
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
                        <v-row>
                          <v-col style="max-width: 45px; margin: auto">
                            <img
                              src="/notification_icons/temperature.png"
                              style="width: 50px"
                            />
                          </v-col>

                          <v-col style="text-align: center">
                            <div style="font-size: 40px">
                              {{ data ? data.temperatureCount : 0 }}
                            </div>
                            <div style="padding-top: 5px">Temperature</div>
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
                        <v-row>
                          <v-col style="max-width: 45px; margin: auto">
                            <img
                              src="/notification_icons/gas.png"
                              style="width: 45px"
                            />
                          </v-col>

                          <v-col style="text-align: center">
                            <div style="font-size: 40px">
                              {{ data ? data.gasCount : 0 }}
                            </div>
                            <div style="padding-top: 5px">Gas</div>
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
                            ><div style="font-size: 30px; color: #06b050">
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
                            ><div style="font-size: 30px; color: #fe0000">
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
              :style="
                'height: ' +
                (mapHeight < 300 ? mapHeight : (mapHeight - 20) / 2) +
                'px;overflow:hidden'
              "
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text>
                <DashboardAlarmEventsHourWiseChart
                  v-if="loadAllEventsTable"
                  :height="
                    mapHeight < 300 ? mapHeight - 110 : (mapHeight - 150) / 2
                  "
                  name="DashboardAlarmEventsHourWiseChart2" /></v-card-text
            ></v-card>
          </v-col>
        </v-row>
        <v-row class="mt-0" v-if="mapHeight > 300"
          ><v-col class="mt-0">
            <v-card
              :style="'height: ' + (mapHeight - 20) / 2 + 'px'"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text>
                <h3 style="color: black; font-weight: normal">Active Events</h3>

                <DashboardOpenEvents /> </v-card-text
            ></v-card>
          </v-col>
        </v-row>

        <v-row>
          <v-col
            ><v-card
              style="height: 500px; overflow-y: auto; overflow-x: hidden"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text>
                <DashboardLoginActivities
                  v-if="loadAllEventsTable"
                  :filter_user_type="'security'"
                />
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
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
import CustomersAlarmMap from "../../components/Alarm/Map/CustomersAlarmMap.vue";
import DashboardOpenEvents from "../../components/Admin/DashboardOpenEvents.vue";

export default {
  components: {
    AlamDeviceCountPieChart,
    AllEventsDashboard2,
    DashboardOperatorLiveStatus,
    DashboardLoginActivities,
    DashboardAlarmEventsHourWiseChart,
    DashboardOpenEvents,
    CustomersAlarmMap,
  },
  data: () => ({
    Openkey: 1,
    Closedkey: 1,
    Forwardkey: 1,

    isMobileDevice: true,
    mapHeight: 500,
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
    cancelgetEventsOpenCountStatusToken: null,
    loadAllEventsTable: false,
    interval: null,
  }),
  computed: {},
  watch: {
    chartEventOpenStatistics: {
      deep: true,
      handler(newVal, oldVal) {
        console.log(
          newVal.series[0],
          newVal.series[1],
          newVal.series[2],
          oldVal.series[0],
          oldVal.series[1],
          oldVal.series[2]
        );

        // if (
        //   newVal.series[0] != oldVal.series[0] ||
        //   newVal.series[1] != oldVal.series[1] ||
        //   newVal.series[2] != oldVal.series[2]
        // )
        //   this.renderChartComponent();
      },
    },
  },
  beforeDestroy() {
    if (this.interval) clearInterval(this.interval);
  },
  mounted() {
    //setTimeout(() => {
    // if (typeof window !== "undefined") {
    //   this.windowHeight = window.innerHeight;
    //   this.mapHeight = this.windowHeight - 700;
    //   console.log(" this.mapHeight", this.mapHeight);
    // }
    //}, 1000 * 2);
  },
  async created() {
    if (typeof window !== "undefined" && window.innerWidth < 700) {
      console.log("window.innerWidth", window.innerWidth);
      this.$router.push("/alarm/mobiledashboard");
    }

    this.isMobileDevice = false;

    if (typeof window !== "undefined") {
      this.windowHeight = window.innerHeight;
      this.mapHeight = this.windowHeight - 460;

      console.log(" this.mapHeight", this.mapHeight);
    }
    // // this._id = this.$route.params.id;
    // let today = new Date();

    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = monthObj.first;
    // this.date_to = monthObj.last;

    let today = new Date();
    this.date_from = today.toISOString().split("T")[0];
    await this.getEventsOpenCountStatus();
    await this.getEventsTypeStats();

    await this.getEventCategoriesStats();

    this.Openkey++;
    this.Closedkey++;
    this.Forwardkey++;

    setTimeout(() => {
      this.loadAllEventsTable = true;
    }, 1000 * 3);

    this.interval = setInterval(async () => {
      if (this.$route.name == "alarm-dashboard") {
        this.key++;
        await this.getEventsTypeStats();
        await this.getEventCategoriesStats();
        await this.getEventsOpenCountStatus();
        this.key++;
      }
    }, 1000 * 10);
  },
  watch: {
    chartEventOpenStatistics: {
      deep: true,
      handler(newVal, oldVal) {
        if (oldVal && newVal && newVal.series[0] != oldVal.series[0]) {
          this.Openkey++;
        }
        if (oldVal && newVal && newVal.series[1] != oldVal.series[1]) {
          this.Closedkey++;
        }
        if (oldVal && newVal && newVal.series[2] != oldVal.series[2]) {
          this.Forwardkey++;
        }
      },
    },
  },
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
          ///////console.error("API Error:Custom ", error);
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
          "dashboard_statistics_all_statistics",
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
          /////////console.error("API Error:Custom ", error);
        }
      } finally {
        this.apiLoading = false; // End loading state
      }
    },

    async getEventsOpenCountStatus() {
      // Cancel any ongoing request
      if (this.cancelgetEventsOpenCountStatusToken) {
        this.cancelgetEventsOpenCountStatusToken.cancel(
          "Previous request canceled."
        );
      }

      // Create a new cancel token
      this.cancelgetEventsOpenCountStatusToken =
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
          cancelgetEventsOpenCountStatusToken:
            this.cancelgetEventsOpenCountStatusToken.token, // Attach cancel token
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
