<template>
  <div>
    <v-card>
      <v-row>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card class="elevation-2" style="height: 180px">
            <v-card-text>
              <AlarmBurglaryPieChart :name="'AlarmBurglaryPieChart'"
            /></v-card-text>
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card class="elevation-2" style="height: 180px">
            <v-card-text>
              <AlarmMedicalPieChart :name="'AlarmMedicalPieChart'"
            /></v-card-text>
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card class="elevation-2" style="height: 180px">
            <v-card-text>
              <AlarmWaterPieChart :name="'AlarmWaterPieChart3'"
            /></v-card-text>
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card class="elevation-2" style="height: 180px">
            <v-card-text>
              <AlarmFirePieChart :name="'AlarmBFirePieChart4'"
            /></v-card-text>
          </v-card>
        </v-col>
        <v-col cols="3" style="max-width: 20%">
          <v-card class="elevation-2" style="height: 180px">
            <v-card-text>
              <AlarmTemperaturePieChart :name="'AlarmTemperaturePieChart5'"
            /></v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <v-row style="margin-top: 0px">
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card style="height: 35px; padding: 10px" class="elevation-5">
            <v-progress-linear
              style="pointer-events: none"
              rounded
              v-model="onlineStats.Burglary.percentage"
              background-color="grey"
              color="green"
              height="12"
            >
              <template v-slot:default="{ value }" style="color: #fff">
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: left;
                    padding-left: 5%;
                  "
                >
                  {{ onlineStats.Burglary.online }}
                </span>
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: right;
                    padding-right: 5%;
                    color: red;
                  "
                >
                  {{ onlineStats.Burglary.offline }}
                </span>
              </template>
            </v-progress-linear>
            <v-row style="font-size: 10px"
              ><v-col>Online</v-col
              ><v-col style="text-align: right; padding-right: 6%"
                >Offline</v-col
              ></v-row
            >
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card style="height: 35px; padding: 10px" class="elevation-5">
            <v-progress-linear
              style="pointer-events: none"
              rounded
              v-model="onlineStats.Medical.percentage"
              background-color="grey"
              color="green"
              height="12"
            >
              <template v-slot:default="{ value }" style="color: #fff">
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: left;
                    padding-left: 5%;
                  "
                >
                  {{ onlineStats.Medical.online }}
                </span>
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: right;
                    padding-right: 5%;
                    color: red;
                  "
                >
                  {{ onlineStats.Medical.offline }}
                </span>
              </template>
            </v-progress-linear>
            <v-row style="font-size: 10px"
              ><v-col>Online</v-col
              ><v-col style="text-align: right; padding-right: 6%"
                >Offline</v-col
              ></v-row
            >
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card style="height: 35px; padding: 10px" class="elevation-5">
            <v-progress-linear
              style="pointer-events: none"
              rounded
              v-model="onlineStats.Water.percentage"
              background-color="grey"
              color="green"
              height="12"
            >
              <template v-slot:default="{ value }" style="color: #fff">
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: left;
                    padding-left: 5%;
                  "
                >
                  {{ onlineStats.Water.online }}
                </span>
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: right;
                    padding-right: 5%;
                    color: red;
                  "
                >
                  {{ onlineStats.Water.offline }}
                </span>
              </template>
            </v-progress-linear>
            <v-row style="font-size: 10px"
              ><v-col>Online</v-col
              ><v-col style="text-align: right; padding-right: 6%"
                >Offline</v-col
              ></v-row
            >
          </v-card>
        </v-col>
        <v-col cols="3" class="pr-0" style="max-width: 20%">
          <v-card style="height: 35px; padding: 10px" class="elevation-5">
            <v-progress-linear
              style="pointer-events: none"
              rounded
              v-model="onlineStats.Fire.percentage"
              background-color="grey"
              color="green"
              height="12"
            >
              <template v-slot:default="{ value }" style="color: #fff">
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: left;
                    padding-left: 5%;
                  "
                >
                  {{ onlineStats.Fire.online }}
                </span>
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: right;
                    padding-right: 5%;
                    color: red;
                  "
                >
                  {{ onlineStats.Fire.offline }}
                </span>
              </template>
            </v-progress-linear>
            <v-row style="font-size: 10px"
              ><v-col>Online</v-col
              ><v-col style="text-align: right; padding-right: 6%"
                >Offline</v-col
              ></v-row
            >
          </v-card>
        </v-col>
        <v-col cols="3" style="max-width: 20%">
          <v-card style="height: 35px; padding: 10px" class="elevation-5">
            <v-progress-linear
              style="pointer-events: none"
              rounded
              v-model="onlineStats.Temperature.percentage"
              background-color="grey"
              color="green"
              height="12"
            >
              <template v-slot:default="{ value }" style="color: #fff">
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: left;
                    padding-left: 5%;
                  "
                >
                  {{ onlineStats.Temperature.online }}
                </span>
                <span
                  style="
                    width: 50%;
                    font-size: 12px;
                    text-align: right;
                    padding-right: 5%;
                    color: red;
                  "
                >
                  {{ onlineStats.Temperature.offline }}
                </span>
              </template>
            </v-progress-linear>
            <v-row style="font-size: 10px"
              ><v-col>Online</v-col
              ><v-col style="text-align: right; padding-right: 6%"
                >Offline</v-col
              ></v-row
            >
          </v-card>
        </v-col>
      </v-row>
      <v-row
        ><v-col><v-divider color="#DDD" /></v-col>
      </v-row>
      <v-row>
        <v-col cols="12"><AlamAllEvents /></v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
import AlarmBurglaryPieChart from "../../components/Alarm/SecurityDashboard/AlarmBurglaryPieChart.vue";
import AlarmMedicalPieChart from "../../components/Alarm/SecurityDashboard/AlarmMedicalPieChart.vue";
import AlarmTemperaturePieChart from "../../components/Alarm/SecurityDashboard/AlarmTemperaturePieChart.vue";

import AlarmFirePieChart from "../../components/Alarm/SecurityDashboard/AlarmFirePieChart.vue";

import AlarmWaterPieChart from "../../components/Alarm/SecurityDashboard/AlarmWaterPieChart.vue";
import AlarmLivePieChart from "../../components/Alarm/SecurityDashboard/AlarmLivePieChart.vue";
import AlamAllEvents from "../../components/Alarm/AllEvents.vue";

export default {
  layout: "security",
  components: {
    AlarmBurglaryPieChart,
    AlarmTemperaturePieChart,
    AlarmMedicalPieChart,
    AlarmFirePieChart,
    AlarmWaterPieChart,
    AlarmLivePieChart,
    AlamAllEvents,
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

    key: 1,
    profile_percentage: 60,
    tab: null,

    _id: null,
    date_from: null,
    date_to: null,
  }),
  computed: {},
  mounted() {},
  created() {
    setInterval(() => {
      this.key = this.key + 1;
      this.getDatafromApi();
    }, 1000 * 10);
    // this._id = this.$route.params.id;
    let today = new Date();

    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;
    this.getDatafromApi();
  },
  watch: {},
  methods: {
    getPercentage($key) {
      let test =
        (this.onlineStats.Temperature.online * 100) /
        (this.onlineStats.Temperature.online,
        this.onlineStats.Temperature.offline);

      return test;
    },
    getDatafromApi() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      this.$axios
        .get(`/device_live_stats_groupby`, options)
        .then(({ data }) => {
          this.onlineStats = data;
        });
    },
  },
};
</script>
