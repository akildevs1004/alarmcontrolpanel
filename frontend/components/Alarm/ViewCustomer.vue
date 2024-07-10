<template>
  <div max-width="100%">
    <!-- <h3>Customer Details</h3>
      -->
    <v-card max-width="100%" style="margin-top: 10px">
      <div
        style="
          background: linear-gradient(to right, #645af1 60%, #9f77f3);
          height: 80px;
        "
      >
        <v-row
          ><v-col cols="4"> </v-col>
          <v-col
            cols="4"
            class="text-center"
            style="color: #fff; font-size: 20px"
            >Building Type: {{ building_type_name ?? "---" }} </v-col
          ><v-col cols="4" class="text-right" style="padding-right: 40px">
            <!-- <v-btn color="primary" dense small> Edit Profile </v-btn> -->
            <v-icon @click="changeTab()" color="white" outlined>
              mdi mdi-refresh
            </v-icon>
            <v-icon
              v-if="$route.name == 'alarm-customers'"
              @click="closePopup()"
              outlined
            >
              mdi mdi-close-circle
            </v-icon>
          </v-col>
        </v-row>
      </div>

      <v-card-text class="text--primary">
        <v-row style="margin-top: -80px">
          <v-col cols="2">
            <v-img
              style="
                width: 150px;
                height: 150px;
                border-radius: 5%;
                margin: 0 auto;
                border: 1px solid #ddd;
              "
              :src="
                customer ? customer.profile_picture : '/no-business_profile.png'
              "
            ></v-img>
          </v-col>
          <v-col cols="10">
            <v-row v-if="customer" style="padding-top: 60px">
              <v-col cols="8">
                <div>
                  {{ customer ? customer.building_name : "---" }}
                  <v-chip
                    x-small
                    v-if="customer.account_status == 1"
                    color="green"
                    style="color: #fff"
                    >Active</v-chip
                  >
                  <v-chip
                    x-small
                    v-if="customer.account_status == 0"
                    color="red"
                    style="color: #fff"
                    >In-Active</v-chip
                  >
                </div>
                <span style="padding-top: 5px">
                  Valid Till :
                  <span style="font-weight: bold">
                    {{
                      $dateFormat.format_date_month_name_year(customer.end_date)
                    }}</span
                  >
                </span>
                <!-- <div style="padding-left: 0px">
                  <span style="padding-top: 5px">customermail@gmail.com</span>
                </div> -->
                <div style="padding-top: 15px; font-size: 12px">
                  <v-icon size="15" color="black">mdi mdi-map-marker</v-icon
                  ><a
                    href="http://www.google.com/maps/"
                    target="_blank"
                    style="text-decoration: none; color: black"
                  >
                    {{
                      customer.house_number
                        ? customer.house_number +
                          "," +
                          customer.street_number +
                          "," +
                          customer.city +
                          "," +
                          customer.city
                        : "---"
                    }}</a
                  >
                  <div>
                    Landmark:
                    {{ customer.house_number ? customer.landmark : "---" }}
                  </div>
                  <div style="font-size: 10px">
                    {{
                      customer.house_number
                        ? customer.state + ", " + customer.country
                        : "---"
                    }}
                  </div>
                </div>
              </v-col>

              <v-col cols="4">
                <div>Complete Profile {{ profile_percentage }}%</div>
                <v-progress-linear
                  :value="profile_percentage"
                  color="#089259"
                  height="10"
                  rounded
                >
                </v-progress-linear>

                <v-row style="padding-top: 50px; float: right">
                  <v-col cols="12" style="font-size: 20px">
                    <span style="float: left; width: 60px">
                      <img
                        title="Burglary"
                        style="width: 23px; float: left"
                        src="/device-icons/burglary.png"
                      />
                    </span>

                    <span style="float: left; width: 60px">
                      <img
                        title="Medial"
                        style="width: 25px; float: left"
                        src="/device-icons/medical.png"
                      />
                    </span>
                    <span style="float: left; width: 60px">
                      <img
                        title="Fire"
                        style="width: 20px; float: left"
                        src="/device-icons/fire.png"
                      />
                    </span>
                    <span style="float: left; width: 60px">
                      <img
                        title="Water Leakage"
                        style="width: 25px; float: left"
                        src="/device-icons/water.png"
                      />
                    </span>
                    <span style="float: left; width: 60px">
                      <img
                        title="Temperature"
                        style="width: 17px; float: left"
                        src="/device-icons/temperature.png"
                      />
                    </span>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-col>
        </v-row>

        <v-row style="padding: 0px">
          <v-tabs
            @change="changeTab()"
            small
            v-model="tab"
            right
            class="popup_background_noviolet customer-tabs"
          >
            <v-tabs-slider></v-tabs-slider>

            <v-tab href="#tab-1"> <v-icon>mdi-phone</v-icon>Contacts </v-tab>

            <v-tab href="#tab-2">
              <v-icon>mdi mdi-car-emergency</v-icon>Emergency
            </v-tab>

            <v-tab href="#tab-3">
              <v-icon>mdi mdi-domain</v-icon>
              Photos
            </v-tab>
            <v-tab href="#tab-4">
              <v-icon>mdi mdi-shield-home-outline</v-icon>
              Devices/Sensor
            </v-tab>
            <v-tab href="#tab-5">
              <v-icon>mdi mdi-chart-pie</v-icon>
              Reports
            </v-tab>
            <v-tab href="#tab-6">
              <v-icon>mdi mdi-alarm</v-icon>
              Event Logs
            </v-tab>
            <v-tab href="#tab-7">
              <v-icon>mdi mdi-message-badge</v-icon>
              Automation
            </v-tab>
            <v-tab href="#tab-8">
              <v-icon>mdi mdi-cash</v-icon>
              Subscription
            </v-tab>
            <v-tab href="#tab-9">
              <v-icon>mdi mdi-account-cog</v-icon>
              Settings
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab" style="width: 100%; min-height: 400px">
            <v-tab-item value="tab-1">
              <v-card flat>
                <v-card-text>
                  <AlramCustomerContacts
                    @closeDialog="closeDialog"
                    :key="keyContacts"
                    :customer_id="_id"
                    :customer_contacts="customer_contacts"
                    :customer="data"
                    :building_type_name="building_type_name"
                /></v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item value="tab-2">
              <v-card flat>
                <v-card-text
                  ><AlramEmergencyContacts
                    @closeDialog="closeDialog"
                    :customer_id="_id"
                    :customer_contacts="customer_contacts"
                    :customer="data"
                    :key="keyEmergencyy"
                /></v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item value="tab-3">
              <v-card flat>
                <v-card-text
                  ><AlramPhotos
                    :key="keyPhotos"
                    @closeDialog="closeDialog"
                    :customer_id="_id"
                  />
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item value="tab-4">
              <v-card flat>
                <v-card-text>
                  <AlarmDevices
                    :customer_id="_id"
                    @closeDialog="closeDialog"
                    :key="keyDevices"
                  />
                </v-card-text>
              </v-card> </v-tab-item
            ><v-tab-item value="tab-5">
              <AlarmAlarmReports
                :key="keyReports"
                :customer_id="_id"
              /> </v-tab-item
            ><v-tab-item value="tab-6">
              <v-card flat>
                <v-card-text>
                  <AlarmEvents :key="keyEvents" :customer_id="_id"
                /></v-card-text>
              </v-card> </v-tab-item
            ><v-tab-item value="tab-7">
              <v-card flat>
                <v-card-text> <AlarmAutomation :key="key" /> </v-card-text>
              </v-card> </v-tab-item
            ><v-tab-item value="tab-8">
              <v-card flat>
                <v-card-text><AlarmPayments :key="key" /></v-card-text>
              </v-card> </v-tab-item
            ><v-tab-item value="tab-9">
              <v-card flat>
                <v-card-text> <AlarmSettings :key="key" /></v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-row>
      </v-card-text>

      <!-- <v-card-actions>
          <v-btn color="orange" text> Share </v-btn>
  
          <v-btn color="orange" text> Explore </v-btn>
        </v-card-actions> -->
    </v-card>
  </div>
</template>

<script>
import AlramCustomerContacts from "../../components/Alarm/CustomerContacts.vue";
import AlramEmergencyContacts from "../../components/Alarm/EmergencyContacts.vue";
import AlramPhotos from "../../components/Alarm/Photos.vue";
import AlarmDevices from "../../components/Alarm/Devices.vue";
import AlarmDashboardTemparatureChart1 from "../../components/Alarm/AlarmDashboardTemparatureChart1.vue";
import AlarmDashboardHumidityChart1 from "../../components/Alarm/AlarmDashboardHumidityChart1.vue";
import AlarmDashboardTemparatureChart2 from "../../components/Alarm/AlarmDashboardTemparatureChart2.vue";
import AlarmEvents from "../../components/Alarm/AlarmEvents.vue";
import AlarmSettings from "../../components/Alarm/Settings.vue";
import AlarmAlarmReports from "../../components/Alarm/AlarmReports.vue";

export default {
  components: {
    AlramCustomerContacts,
    AlramEmergencyContacts,
    AlramPhotos,
    AlarmDevices,
    AlarmDashboardTemparatureChart1,
    AlarmDashboardHumidityChart1,
    AlarmSettings,
    AlarmAlarmReports,
  },
  props: ["_id"],
  data: () => ({
    keyContacts: 1,
    keyEmergencyy: 1,
    keyPhotos: 1,
    keyDevices: 1,
    keyReports: 1,
    keyEvents: 1,
    key: 1,
    profile_percentage: 60,
    tab: null,
    text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
    data: null,
    BuildingTypes: null,
    building_type_name: null,
    customer_contacts: null,
    customer_primary_contact: null,
    customer_secondary_contact: null,

    customer: null,
  }),
  computed: {},
  mounted() {},
  created() {
    //this._id = this.$route.params.id;
    if (this._id) this.getDataFromApi();

    // setTimeout(() => {
    //   this.getBuildingTypes();
    // }, 3000);
  },
  watch: {},
  methods: {
    changeTab() {
      if (this.tab == "tab-1") {
        this.keyContacts = this.keyContacts + 1;
      } else if (this.tab == "tab-2") {
        this.keyEmergencyy = this.keyEmergencyy + 1;
      } else if (this.tab == "tab-3") {
        this.keyPhotos = this.keyPhotos + 1;
      } else if (this.tab == "tab-4") {
        this.keyDevices = this.keyDevices + 1;
      } else if (this.tab == "tab-5") {
        this.keyReports = this.keyReports + 1;
      } else if (this.tab == "tab-6") {
        this.keyEvents = this.keyEvents + 1;
      }
    },
    closeDialog() {
      this.key = this.key + 1;
      this.getDataFromApi();
    },
    getBuildingTypeById(id) {
      let buildingTypes =
        this.$store.state.storeAlarmControlPanel.BuildingTypes;
      if (buildingTypes) {
        let specificValue = buildingTypes.find((e) => e.id == id);

        return (this.data.building_type_name = specificValue.name);
      } else {
        return "";
      }
    },
    getBuildingTypes() {
      // console.log(
      //   " BuildingTypes2222",
      //   this.$store.state.storeAlarmControlPanel.BuildingTypes
      // );
    },
    closePopup() {
      this.$emit("closeCustomerDialog");
    },

    async getDataFromApi() {
      if (this._id) {
        this.payloadOptions = {
          params: {
            company_id: this.$auth.user.company_id,
            customer_id: this._id,
          },
        };

        try {
          this.$axios.get(`customers`, this.payloadOptions).then(({ data }) => {
            this.data = data.data[0] || null;
            this.customer = this.data;
            if (this.data) {
              this.customer_contacts = this.data.contacts;
            }

            setTimeout(() => {
              this.getBuildingTypes();
              this.building_type_name = this.getBuildingTypeById(
                this.data.building_type_id
              );
              this.data.building_type_name = this.building_type_name;
            }, 3000);
          });
        } catch (e) {}

        const { data } = await this.$axios.get("address_types", {
          params: {
            company_id: this.$auth.user.company_id,
          },
        });

        this.$store.commit("storeAlarmControlPanel/AddressTypes", data);
      }
    },
  },
};
</script>
