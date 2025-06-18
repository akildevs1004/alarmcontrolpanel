<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else-if="!isMobileDevice">
    <v-row class="dashboard">
      <v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="sos-border-bottom custom-card"
          ><v-card-text
            ><v-row style="height: 130px">
              <!-- <v-col
                class="d-flex justify-center"
                style="font-size: 25px; margin: auto"
                >SOS</v-col
              > -->

              <v-col
                class="d-flex1 justify-center"
                style="margin: auto; line-height: 20px; text-align: center"
              >
                <div style="font-size: 40px">
                  {{ data ? data.sosCount : 0 }}
                </div>
                <br />
                <div style="font-size: 16px">SOS Count</div>
                <!-- <div class="sos" style="font-size: 12px">+10%</div> -->
              </v-col>
              <v-col class="d-flex justify-right" style="max-width: 150px">
                <div style="" class="image-box sos-border">
                  <img
                    src="/notification_icons/sos.png"
                    alt="sos"
                    style="width: 30px"
                  />
                </div>
              </v-col> </v-row></v-card-text
        ></v-card>
      </v-col>
      <v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="critical-border-bottom custom-card"
          ><v-card-text
            ><v-row style="height: 130px">
              <!-- <v-col
                class="d-flex justify-center"
                style="font-size: 25px; margin: auto"
                >SOS</v-col
              > -->

              <v-col
                class="d-flex1 justify-center"
                style="margin: auto; line-height: 20px; text-align: center"
              >
                <div style="font-size: 40px">
                  {{ data ? data.criticalCount : 0 }}
                </div>
                <br />
                <div style="font-size: 16px">Critical Count</div>
                <!-- <div class="critical" style="font-size: 12px">+10%</div> -->
              </v-col>
              <v-col class="d-flex justify-right" style="max-width: 150px">
                <div style="" class="image-box critical-border">
                  <img
                    src="/notification_icons/critical.png"
                    style="width: 30px; margin: auto"
                  />
                </div>
              </v-col> </v-row></v-card-text
        ></v-card>
      </v-col>
      <v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="medical-border-bottom custom-card"
          ><v-card-text
            ><v-row style="height: 130px">
              <!-- <v-col
                class="d-flex justify-center"
                style="font-size: 25px; margin: auto"
                >SOS</v-col
              > -->

              <v-col
                class="d-flex1 justify-center"
                style="margin: auto; line-height: 20px; text-align: center"
              >
                <div style="font-size: 40px">
                  {{ data ? data.medicalCount : 0 }}
                </div>
                <br />
                <div style="font-size: 16px">Medical Count</div>
                <!-- <div class="medical" style="font-size: 12px">+10%</div> -->
              </v-col>
              <v-col class="d-flex justify-right" style="max-width: 150px">
                <div style="" class="image-box medical-border">
                  <img
                    src="/notification_icons/medical.png"
                    style="width: 30px; margin: auto"
                  />
                </div>
              </v-col> </v-row></v-card-text
        ></v-card>
      </v-col>
      <v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="fire-border-bottom custom-card"
          ><v-card-text
            ><v-row style="height: 130px">
              <!-- <v-col
                class="d-flex justify-center"
                style="font-size: 25px; margin: auto"
                >SOS</v-col
              > -->

              <v-col
                class="d-flex1 justify-center"
                style="margin: auto; line-height: 20px; text-align: center"
              >
                <div style="font-size: 40px">
                  {{ data ? data.fireCount : 0 }}
                </div>
                <br />
                <div style="font-size: 16px">Fire Count</div>
                <!-- <div class="fire" style="font-size: 12px">+10%</div> -->
              </v-col>
              <v-col class="d-flex justify-right" style="max-width: 150px">
                <div style="" class="image-box fire-border">
                  <img
                    src="/notification_icons/fire.png"
                    style="width: 30px; margin: auto"
                  />
                </div>
              </v-col> </v-row></v-card-text
        ></v-card>
      </v-col>
      <v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="water-border-bottom custom-card"
          ><v-card-text
            ><v-row style="height: 130px">
              <!-- <v-col
                class="d-flex justify-center"
                style="font-size: 25px; margin: auto"
                >SOS</v-col
              > -->

              <v-col
                class="d-flex1 justify-center"
                style="margin: auto; line-height: 20px; text-align: center"
              >
                <div style="font-size: 40px">
                  {{ data ? data.waterCount : 0 }}
                </div>
                <br />
                <div style="font-size: 16px">Water Count</div>
                <!-- <div class="water" style="font-size: 12px">+10%</div> -->
              </v-col>
              <v-col class="d-flex justify-right" style="max-width: 150px">
                <div style="" class="image-box water-border">
                  <img
                    src="/notification_icons/water.png"
                    style="width: 30px; margin: auto"
                  />
                </div>
              </v-col> </v-row></v-card-text
        ></v-card> </v-col
      ><v-col style="max-width: 16.66%">
        <v-card
          elevation="2"
          style="height: 130px; border-radius: 0.5rem"
          class="sos-border-bottom custom-card"
          ><v-card-text>
            <v-row style="height: 45px">
              <v-col style="padding: 5px"
                ><div style="font-size: 18px">Devices</div></v-col
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
                  {{ customerStatusData ? customerStatusData.onlineCount : 0 }}
                </div>
                <div style="color: #06b050">Online</div>
              </v-col>
              <v-col
                style="
                  padding: 0px;
                  text-align: center;

                  font-weight: bold;
                "
                ><div style="font-size: 30px; color: #fff">
                  {{ customerStatusData ? customerStatusData.offlineCount : 0 }}
                </div>
                <div style="color: #fff">Offline</div>
              </v-col>
            </v-row></v-card-text
          ></v-card
        >
      </v-col>
    </v-row>

    <v-row class="pt-0 mt-0">
      <v-col cols="6">
        <CustomersAlarmMap
          name="googlemap"
          :displayTable="false"
          mapid="dashboardmap"
          :mapHeight="mapHeight"
          isWithOutAlarms="true"
          borderRadius="5px"
        />
      </v-col>
      <v-col cols="3">
        <v-row>
          <v-col>
            <v-card
              :style="'height: ' + (mapHeight - 30) / 2 + 'px'"
              elevation="2"
            >
              <v-card-text>
                <DashboardAlarmEventsHourWiseChart
                  v-if="loadAllEventsTable"
                  :height="
                    mapHeight < 300 ? mapHeight - 110 : (mapHeight - 150) / 2
                  "
                  name="DashboardAlarmEventsHourWiseChart23"
                />
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-card
              :style="'height: ' + (mapHeight - 30) / 2 + 'px'"
              elevation="2"
              ><v-card-text>
                <h3 style="font-weight: normal">Devices</h3>
                <AlamDeviceCountPieChart
                  :name="'AlamDeviceCountPieChart'"
                  style="
                    height: 230px;

                    max-height: 230px;
                    overflow: hidden;
                  "
                /> </v-card-text
            ></v-card>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="3">
        <v-row>
          <v-col>
            <v-card
              :style="'height: ' + (mapHeight - 30) / 2 + 'px;overflow:hidden'"
              elevation="2"
              class="eventslistscroll table-font12"
            >
              <v-card-text>
                <!-- <h3 style="font-weight: normal" class="font-color">
                  Active Events
                </h3> -->
                <v-row style="border-bottom: 1px solid #353538; padding: 0px">
                  <v-col style="max-width: 40px">
                    <img
                      src="/notification_icons/technical.png"
                      style="width: 20px"
                  /></v-col>
                  <v-col> Technical </v-col>
                  <v-col style="font-size: 40px">
                    {{ data ? data.technicalCount : 0 }}
                  </v-col> </v-row
                ><v-row style="border-bottom: 1px solid #353538; padding: 0px">
                  <v-col style="max-width: 40px">
                    <img
                      src="/notification_icons/event.png"
                      style="width: 20px"
                  /></v-col>
                  <v-col> Events </v-col>
                  <v-col style="font-size: 40px">
                    {{ data ? data.eventsCount : 0 }}
                  </v-col> </v-row
                ><v-row style="border-bottom: 1px solid #353538; padding: 0px">
                  <v-col style="max-width: 40px">
                    <img
                      src="/notification_icons/power.png"
                      style="width: 20px"
                  /></v-col>
                  <v-col> Power </v-col>
                  <v-col style="font-size: 40px">
                    {{ data ? data.powerCount : 0 }}
                  </v-col> </v-row
                ><v-row style="border-bottom: 1px solid #353538; padding: 0px">
                  <v-col style="max-width: 40px">
                    <img
                      src="/notification_icons/temperature.png"
                      style="width: 20px"
                  /></v-col>
                  <v-col> Temperature </v-col>
                  <v-col style="font-size: 40px">
                    {{ data ? data.temperatureCount : 0 }}
                  </v-col> </v-row
                ><v-row style="padding: 0px">
                  <v-col style="max-width: 40px">
                    <img src="/notification_icons/gas.png" style="width: 20px"
                  /></v-col>
                  <v-col> Gas </v-col>
                  <v-col style="font-size: 40px">
                    {{ data ? data.gasCount : 0 }}
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <!-- <v-row>
          <v-col>
            <v-card
              :style="'height: ' + (mapHeight - 30) / 2 + 'px'"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text>
                <h3 style="font-weight: normal" class="font-color">
                  Active Events
                </h3>

                <DashboardOpenEvents
                  name="DashboardOpenEvents"
                /> </v-card-text></v-card
          ></v-col>
        </v-row> -->
        <v-row>
          <v-col>
            <v-card
              :style="'height: ' + (mapHeight - 30) / 2 + 'px;overflow:hidden'"
              elevation="2"
              class="eventslistscroll table-font12"
              ><v-card-text>
                <h3 style="font-weight: normal" class="font-color">
                  Operators Status
                </h3>

                <DashboardLoginActivities
                  v-if="loadAllEventsTable"
                  :filter_user_type="'security'"
                /> </v-card-text></v-card
          ></v-col>
        </v-row>
      </v-col>
    </v-row>

    <v-row class="padding:0px">
      <v-col cols="12">
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
    mapHeight: 600,
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
      this.mapHeight = this.windowHeight - 360;

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
