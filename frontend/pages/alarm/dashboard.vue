<template>
  <NoAccess v-if="!$pagePermission.can('dashboard_view', this)" />
  <div v-else>
    <v-row
      ><v-col cols="9"
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
                      src="/alarm_icons/google_offline.png"
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
                    >Technical</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.technicalCount : 0 }}</v-col
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
                /> </v-card-text
            ></v-card> </v-col
          ><v-col>
            <v-card style="height: 275px" elevation="2"
              ><v-card-text
                ><h3 style="color: black; font-weight: normal">Alarm Status</h3>

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
                      v-if="chartEventOpenStatistics"
                      :key="key + 2"
                      :name="'AlarmEventStatusCountPieChart2'"
                      :componentData="chartEventClosedStatistics"
                    /> </v-col
                  ><v-col cols="4">
                    <AlarmEventStatusCountPieChart
                      v-if="chartEventOpenStatistics"
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
            <v-card style="height: 275px" elevation="2"
              ><v-card-text>Reports Tabs</v-card-text></v-card
            >
          </v-col>
        </v-row> </v-col
      ><v-col cols="3">
        <v-row>
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
                    >Event</v-col
                  ><v-col
                    class="d-flex justify-center"
                    style="
                      font-size: 40px;
                      margin: auto;
                      font-weight: bold;
                      color: red;
                    "
                    >{{ data ? data.eventsCount : 0 }}</v-col
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
              <v-card-text>
                <v-row>
                  <v-col
                    style="padding: 5px; padding-left: 0px; padding-top: 0px"
                  >
                    <v-card style="height: 80px" elevation="2">
                      <v-card-text class="m-0 p-0">
                        <v-row
                          ><v-col style="height: 20px"
                            ><v-icon size="25" style="color: green"
                              >mdi-account-outline</v-icon
                            ></v-col
                          >
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 20px">
                              {{
                                categoriesStats ? categoriesStats.medical : 0
                              }}
                            </div>
                            <div>Medical</div>
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
                            ><v-icon size="25" style="color: green"
                              >mdi-account-outline</v-icon
                            ></v-col
                          >
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 20px">
                              {{ categoriesStats ? categoriesStats.fire : 0 }}
                            </div>
                            <div>Fire</div>
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
                            ><v-icon size="25" style="color: green"
                              >mdi-account-outline</v-icon
                            ></v-col
                          >
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 20px">
                              {{ categoriesStats ? categoriesStats.water : 0 }}
                            </div>
                            <div>Water</div>
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
                            ><v-icon size="25" style="color: green"
                              >mdi-account-outline</v-icon
                            ></v-col
                          >
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              text-align: center;
                              margin: auto;
                              margin-top: -10px;
                            "
                          >
                            <div style="font-size: 20px">
                              {{
                                categoriesStats
                                  ? categoriesStats.temperature
                                  : 0
                              }}
                            </div>
                            <div>Temperature</div>
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
                              color: #06b050;
                              font-weight: bold;
                            "
                            ><div style="font-size: 20px">100</div>
                            <div>Online</div>
                          </v-col>
                          <v-col
                            style="
                              padding: 0px;
                              text-align: center;
                              color: #fe0000;
                              font-weight: bold;
                            "
                            ><div style="font-size: 20px">100</div>
                            <div>Offline</div>
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
        <v-row
          ><v-col>
            <v-card style="height: 300px" elevation="2"
              ><v-card-text>Operators Live</v-card-text></v-card
            >
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row
      ><v-col>
        <v-card style="height: 300px" elevation="2"
          ><v-card-text>Operators Login Logs </v-card-text></v-card
        > </v-col
      ><v-col>
        <v-card style="height: 300px" elevation="2"
          ><v-card-text>Alarm events - Hourly </v-card-text></v-card
        >
      </v-col>
    </v-row>
  </div>
</template>

<script>
import AlarmEventStatusCountPieChart from "../../components/Alarm/Dashboard/AlarmEventStatusCountPieChart.vue";

import AlamDeviceCountPieChart from "../../components/Alarm/Dashboard/AlarmDeviceCountPieChart.vue";

export default {
  components: { AlamDeviceCountPieChart },
  data: () => ({
    windowHeight: 1000,
    key: 1,
    date_from: "",
    data: [],
    chartEventOpenStatistics: null,
    chartEventClosedStatistics: null,

    chartEventForwardStatistics: null,
    categoriesStats: null,
  }),
  computed: {},
  mounted() {
    setInterval(() => {
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
    await this.getEventsTypeStats();

    await this.getEventCategoriesStats();
    await this.updateEventsOpenCountStatus();
  },
  watch: {},
  methods: {
    async getEventCategoriesStats() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      this.$axios.get(`/alarm_statistics`, options).then(({ data }) => {
        this.categoriesStats = data;
      });
    },
    async getEventsTypeStats() {
      this.$axios
        .get("dashboard_statistics_date_range", {
          params: {
            company_id: this.$auth.user.company_id,
            date_from: this.date_from,
            date_to: this.date_from,
          },
        })
        .then(({ data }) => {
          if (data.length > 0) {
            this.data = data[0];
          }
        });
    },

    async updateEventsOpenCountStatus() {
      let today = new Date();
      let date = today.toISOString().split("T")[0];
      this.$axios
        .get("dashboard_statistics_customers", {
          params: {
            company_id: this.$auth.user.company_id,
            date: date,
          },
        })
        .then(({ data }) => {
          if (data) {
            this.chartEventOpenStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
            };
            this.chartEventOpenStatistics.title = "Open";
            this.chartEventOpenStatistics.labels[0] = "Open";
            this.chartEventOpenStatistics.series[0] = data.openCount; // data.total - data.armed;

            this.chartEventOpenStatistics.labels[1] = "Total";
            this.chartEventOpenStatistics.series[1] =
              data.openCount + data.closedCount;

            this.chartEventOpenStatistics.labels[2] = "Forward";
            this.chartEventOpenStatistics.series[2] = data.openCount; // data.armed;

            this.chartEventOpenStatistics.colors = ["#07af50", "#DDD", "#DDD"];
            this.chartEventOpenStatistics.customTotalValue =
              data.openCount + data.closedCount; //this.items.ExpectingCount;

            //----------------------------

            this.chartEventClosedStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
            };
            this.chartEventClosedStatistics.title = "Closed";
            this.chartEventClosedStatistics.labels[0] = "Closed";
            this.chartEventClosedStatistics.series[0] = data.closedCount; // data.total - data.armed;

            this.chartEventClosedStatistics.labels[1] = "Total";
            this.chartEventClosedStatistics.series[1] =
              data.openCount + data.closedCount; // data.armed;

            this.chartEventClosedStatistics.labels[2] = "Open";
            this.chartEventClosedStatistics.series[2] = data.openCount; // data.armed;

            this.chartEventClosedStatistics.colors = [
              "#fe0000",
              "#DDD",
              "#DDD",
            ];
            this.chartEventClosedStatistics.customTotalValue =
              data.openCount + data.closedCount; //this.items.ExpectingCount;

            //----------------------------

            this.chartEventForwardStatistics = {
              labels: [],
              series: [],
              colors: [],
              customTotalValue: 0,
            };
            this.chartEventForwardStatistics.title = "Forward";
            this.chartEventForwardStatistics.labels[0] = "Forward";
            this.chartEventForwardStatistics.series[0] = data.openCount; // data.total - data.armed;

            this.chartEventForwardStatistics.labels[1] = "Total";
            this.chartEventForwardStatistics.series[1] =
              data.openCount + data.closedCount; // data.armed;

            this.chartEventForwardStatistics.labels[2] = "Open";
            this.chartEventForwardStatistics.series[2] = data.openCount; // data.armed;

            this.chartEventForwardStatistics.colors = [
              "#ffbe00",
              "#DDD",
              "#DDD",
            ];
            this.chartEventForwardStatistics.customTotalValue =
              data.openCount + data.closedCount; //this.items.ExpectingCount;
          }
        });
    },
  },
};
</script>
