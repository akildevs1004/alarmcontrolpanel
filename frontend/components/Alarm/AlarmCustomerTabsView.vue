<template>
  <div max-width="100%">
    <v-row>
      <v-col style="max-width: 115px">
        <v-tabs
          style="max-width: 90px; min-height: 100%"
          icons-and-text
          v-model="tab"
          vertical
          background-color="#203864"
          dark
          color="#0aafeb"
          class="customer-tabs-right-line"
        >
          <!-- <v-tab class="customer-tab">
        Customer
        <v-icon>mdi-card-account-details</v-icon>
      </v-tab> -->
          <v-tab class="customer-tab">
            Address
            <v-icon>mdi-card-account-details</v-icon>
          </v-tab>
          <!-- <v-tab class="customer-tab">
            Contacts
            <v-icon>mdi-card-account-details</v-icon>
          </v-tab> -->
          <v-tab class="customer-tab">
            Contacts
            <v-icon>mdi-account-tie</v-icon>
          </v-tab>
          <v-tab class="customer-tab">
            Camera
            <v-icon>mdi-camera</v-icon>
          </v-tab>
          <v-tab class="customer-tab">
            Devices
            <v-icon>mdi-account</v-icon>
          </v-tab>
          <v-tab class="customer-tab">
            Sensors
            <v-icon>mdi-shield-account</v-icon>
          </v-tab>
          <v-tab class="customer-tab" v-if="!isMapviewOnly">
            Automation
            <v-icon>mdi-car-emergency</v-icon> </v-tab
          ><v-tab class="customer-tab" v-if="!isMapviewOnly">
            Subscription
            <v-icon>mdi-medical-bag</v-icon>
          </v-tab>
          <v-tab class="customer-tab" v-if="!isMapviewOnly">
            Settings
            <v-icon>mdi mdi-briefcase-account</v-icon>
          </v-tab>
        </v-tabs>
      </v-col>
      <v-col>
        <v-tabs-items v-model="tab" style="overflow: visible">
          <!-- <v-tab-item>
        <v-card flat>
          <v-card-text
            ><NewCustomer
              v-if="data"
              :customer_id="_id"
              :customer="data"
              @closeDialog="closeDialog"
            />
          </v-card-text>
        </v-card>
      </v-tab-item> -->

          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <BuildingPhotos
                  v-if="_id"
                  @closeDialog="closeDialog"
                  :customer_id="_id"
                  :customer="data"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card>
          </v-tab-item>
          <!-- <v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlarmEditContact
                  v-if="data"
                  :customer_id="_id"
                  :customer_contacts="customer_contacts"
                  :customer="data"
                  @closeDialog="closeDialog"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card>
          </v-tab-item> -->

          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlramEmergencyContacts
                  v-if="data"
                  @closeDialog="closeDialog"
                  :customer_id="_id"
                  :customer_contacts="customer_contacts"
                  :customer="data"
                  :key="keyEmergencyy"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                  @callrefreshData="updateContactsData()"
                />
              </v-card-text>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <CameraList
                  v-if="_id"
                  @closeDialog="closeDialog"
                  :customer_id="_id"
                  :customer="data"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlarmDevices
                  v-if="_id"
                  :customer_id="_id"
                  @closeDialog="closeDialog"
                  :key="keyDevices"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card> </v-tab-item
          ><v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlramPhotos
                  v-if="_id"
                  :key="keyPhotos"
                  @closeDialog="closeDialog"
                  :customer_id="_id"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card> </v-tab-item
          ><v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlarmAutomation
                  v-if="_id"
                  :key="keyAutomation"
                  :customer_id="_id"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card> </v-tab-item
          ><v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlarmPayments
                  v-if="_id"
                  :customer="data"
                  :key="keyPayments"
                  :customer_id="_id"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card> </v-tab-item
          ><v-tab-item>
            <v-card flat>
              <v-card-text>
                <AlarmSettings
                  v-if="_id"
                  @closeDialog="closeDialog"
                  :key="keySettings"
                  :customer_id="_id"
                  :customer="customer"
                  :isMapviewOnly="isMapviewOnly"
                  :isEditable="isEditable"
                />
              </v-card-text>
            </v-card>
          </v-tab-item> </v-tabs-items
      ></v-col>
    </v-row>
  </div>
</template>

<script>
import AlramCustomerContacts from "../../components/Alarm/CustomerContacts.vue";
import AlramEmergencyContacts from "../../components/Alarm/AlarmEmergencyContacts.vue";
import AlramPhotos from "../../components/Alarm/Photos.vue";
import AlarmDevices from "../../components/Alarm/Devices.vue";
import AlarmDashboardTemparatureChart1 from "../../components/Alarm/CustomerDashboardTemparatureChart1.vue";
import AlarmDashboardHumidityChart1 from "../../components/Alarm/CustomerDashboardHumidityChart1.vue";
import AlarmDashboardTemparatureChart2 from "../../components/Alarm/CustomerDashboardTemparatureChart2.vue";
import CustomerAlarmEvents from "../../components/Alarm/CustomerAlarmEvents.vue";
import AlarmSettings from "../../components/Alarm/Settings.vue";
import CustomerDashboard from "../../components/Alarm/CustomerDashboard.vue";
// import NewCustomer from "../../components/Alarm/NewCustomer.vue";
import DeviceArmedLogs from "./DeviceArmedLogs.vue";
import AlamAllEvents from "../Alarm/ComponentAllEvents.vue";
import AlarmEditContact from "../../components/Alarm/EditContacts.vue";
import BuildingPhotos from "./BuildingPhotos.vue";
import CameraList from "./CameraList.vue";

export default {
  components: {
    AlramCustomerContacts,
    AlramEmergencyContacts,
    AlramPhotos,
    AlarmDevices,
    AlarmDashboardTemparatureChart1,
    AlarmDashboardHumidityChart1,
    AlarmSettings,
    CustomerDashboard,
    CustomerAlarmEvents,
    // NewCustomer,
    DeviceArmedLogs,
    AlamAllEvents,
    AlarmEditContact,
    BuildingPhotos,
    CameraList,
  },
  props: ["_id", "isPopup", "isMapviewOnly", "isEditable"],
  data: () => ({
    dialogEditBuilding: false,
    messages: [],
    customerSensors: [],
    keyContacts: 1,
    keyEmergencyy: 1,
    keyPhotos: 1,
    keyDevices: 1,
    keyReports: 1,
    keyEvents: 1,
    keyAutomation: 1,
    keyPayments: 1,
    keySettings: 1,
    keyArmed: 1,

    key: 1,
    profile_percentage: 0,
    tab: null,

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
    // if (this.customer_id) {
    //   this._id = this.customer_id;
    // }
    //this._id = this.$route.params.id;
    if (this._id) this.getDataFromApi();
    // setTimeout(() => {
    //   if (this._id) this.getDataFromApi();
    // }, 1000 * 2);

    // setTimeout(() => {
    //   this.getBuildingTypes();
    // }, 3000);

    this.getUniqueDevices();
    this.getCustomerProfilePercentage();
  },
  watch: {},
  methods: {
    async updateContactsData() {
      console.log("reloadContent");
      try {
        await this.getDataFromApi();
      } catch (e) {}
      this.keyEmergencyy++;
    },
    gotoCustomers() {
      this.$router.push("/alarm/customers");
      return;
    },
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
      } else if (this.tab == "tab-7") {
        this.keyAutomation = this.keyAutomation + 1;
      } else if (this.tab == "tab-8") {
        this.keyPayments = this.keyPayments + 1;
      } else if (this.tab == "tab-9") {
        this.keySettings = this.keySettings + 1;
      } else if (this.tab == "tab-10") {
        this.keyArmed = this.keyArmed + 1;
      }
    },
    closeDialog() {
      this.key = this.key + 1;
      this.getDataFromApi();
      this.getCustomerProfilePercentage();
      this.$emit("closeCustomerDialog");
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
    // verifyDeviceSensorName(sensorName, devices) {
    //   for (let device of devices) {
    //     if (device.device_type == sensorName) {
    //       return true;
    //     }

    //     if (device.sensorzones) {
    //       let filter = device.sensorzones.filter(
    //         (e) => e.sensor_name == sensorName
    //       );
    //       if (filter.length > 0) {
    //         return true;
    //       }
    //     }
    //   }

    //   return false;
    // },
    getBuildingTypes() {
      // console.log(
      //   " BuildingTypes2222",
      //   this.$store.state.storeAlarmControlPanel.BuildingTypes
      // );
    },
    closePopup() {
      this.$emit("closeCustomerDialog");
    },
    getUniqueDevices() {
      this.payloadOptions = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this._id,
        },
      };

      try {
        this.$axios
          .get(`customer_device_types`, this.payloadOptions)
          .then(({ data }) => {
            this.customerSensors = data;
          });
      } catch (e) {}
    },
    getCustomerProfilePercentage() {
      this.payloadOptions = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this._id,
        },
      };

      try {
        this.$axios
          .get(`customer_profile_completion_percentage`, this.payloadOptions)
          .then(({ data }) => {
            this.profile_percentage = data.percentage;
            if (data.percentage == 100) {
              this.messages = "Profile is successfully completed";
            } else
              data.message.forEach((element) => {
                this.messages = this.messages + element + "\n";
              });
          });
      } catch (e) {}
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

            //console.log("customer", this.customer);

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
