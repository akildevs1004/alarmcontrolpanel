<template>
  <div>
    <style scoped>
      .customer-table {
        width: 100%;
        border-collapse: collapse;
      }

      .customer-table td {
        padding: 10px;
      }

      .customer-table .label {
        text-align: left;
        color: grey;
        font-size: 11px;
      }

      .customer-table .value {
        text-align: right;
        color: grey;
        font-size: 11px;
      }
    </style>
    <v-dialog
      v-model="viewDialog"
      :width="shouldAtleastOneContact ? '1000' : '800'"
    >
      <IconClose
        :left="shouldAtleastOneContact ? '990' : '790'"
        @click="closeViewDialog"
      />

      <template v-slot:activator="{ on, attrs }">
        <span>
          <v-icon v-bind="attrs" v-on="on" color="secondary" small>
            mdi-{{ icon }}
          </v-icon>
          {{ label }}</span
        >
      </template>
      <v-card>
        <v-container>
          <v-row no-gutters>
            <v-col
              v-if="shouldAtleastOneContact"
              class="mr-3"
              style="height: 460px"
            >
              <v-card
                v-if="!data"
                outlined
                class="d-flex justify-center align-center flex-column"
                style="height: 460px"
              >
                <div class="my-loader"></div>
              </v-card>

              <v-card
                v-else-if="!primaryCustomer && !isEditable"
                outlined
                class="d-flex justify-center align-center flex-column"
                style="height: 460px"
              >
                <div>No Data Found</div>
              </v-card>

              <v-card v-else class="pa-6" elevation="5">
                <v-card-title class="d-flex flex-column align-items-center">
                  <v-avatar size="80">
                    <v-img
                      :src="
                        (primaryCustomer && primaryCustomer.profile_picture) ||
                        '/no-profile2.png'
                      "
                      @error="onImageError"
                    />
                  </v-avatar>
                  <div class="text-center mt-2">
                    <h5 class="font-weight-bold mb-0">
                      {{ primaryCustomer && primaryCustomer.title }}
                      {{
                        (primaryCustomer && primaryCustomer.full_name) || "---"
                      }}
                    </h5>
                    <p class="text-caption mb-0">
                      <v-icon small color="primary">mdi-whatsapp</v-icon>
                      {{
                        (primaryCustomer && primaryCustomer.whatsapp) || "---"
                      }}
                    </p>
                  </div>
                </v-card-title>
                <v-divider></v-divider>

                <table class="customer-table">
                  <tbody>
                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          justify-content: space-between;
                          border-bottom: 1px solid #e0e0e0;
                        "
                      >
                        <span>Email:</span>
                        <span>{{
                          (primaryCustomer && primaryCustomer.email) || "---"
                        }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          justify-content: space-between;
                          border-bottom: 1px solid #e0e0e0;
                        "
                      >
                        <span>Office Phone:</span>
                        <span>{{
                          (primaryCustomer && primaryCustomer.office_phone) ||
                          "---"
                        }}</span>
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          justify-content: space-between;
                          border-bottom: 1px solid #e0e0e0;
                        "
                      >
                        <span>W.Hrs:</span>
                        <span>{{
                          (primaryCustomer && primaryCustomer.working_hours) ||
                          "---"
                        }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="mt-3 text-center">
                  <v-img
                    rounded
                    style="border-radius: 5px"
                    max-height="150px"
                    max-width="150px"
                    :title="item.building_name"
                    :src="item.profile_picture"
                    aspect-ratio="1"
                    class="grey lighten-2 hover-zoom-card mx-auto"
                  >
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                      >
                      </v-row>
                    </template>
                  </v-img>
                </div>
              </v-card>
            </v-col>
            <v-col :cols="shouldAtleastOneContact ? '9' : '12'">
              <v-card
                v-if="!data"
                outlined
                class="d-flex justify-center align-center flex-column"
                style="height: 460px"
              >
                <div class="my-loader"></div>
              </v-card>

              <v-card
                v-else-if="!customer_contacts.length && !isEditable"
                outlined
                class="d-flex justify-center align-center flex-column"
                style="height: 460px"
              >
                <div>No Data Found</div>
              </v-card>

              <v-card
                v-else
                style="overflow: auto"
                elevation="5"
                min-height="460px"
                max-height="460px"
              >
                <v-tabs
                  fixed-tabs
                  show-arrows
                  v-model="tab"
                  color="deep-purple accent-4"
                >
                  <v-tab>Info</v-tab>
                  <v-tab>Contacts</v-tab>
                  <v-tab>Photos</v-tab>
                  <v-tab>Camera</v-tab>
                  <v-tab>Devices</v-tab>
                  <v-tab>Sensors</v-tab>
                  <v-tab v-if="!isMapviewOnly">Automation</v-tab
                  ><v-tab v-if="!isMapviewOnly">Subscription</v-tab>
                  <v-tab v-if="!isMapviewOnly">Settings</v-tab>

                  <v-tabs-items v-model="tab" style="overflow: visible">
                    <v-tab-item class="pa-7">
                      <CustomerV2Address
                        v-if="_id"
                        @callrefreshData="
                          () => {
                            $emit(`closeCustomerDialog`);

                            closeViewDialog = false;
                          }
                        "
                        :key="compKey"
                        :customer_id="_id"
                        :customer="data"
                        :isMapviewOnly="isMapviewOnly"
                        :isEditable="isEditable"
                        :item="item"
                      />
                    </v-tab-item>

                    <v-tab-item class="pa-7">
                      <div
                        v-if="customer_contacts.length != 0"
                        class="text-right mb-1"
                      >
                        <CustomerV2ContactsCreate
                          v-if="isEditable && can(`customers_create`)"
                          :isEditable="true"
                          @callrefreshData="updateContactsData"
                          :customer_id="_id"
                        />
                      </div>

                      <CustomerV2Contacts
                        v-if="customer_contacts.length"
                        @closeDialog="closeDialog"
                        :customer_id="_id"
                        :customer_contacts="customer_contacts"
                        :customer="data"
                        :isMapviewOnly="isMapviewOnly"
                        :isEditable="isEditable"
                        @callrefreshData="updateContactsData"
                        :key="compKey"
                      />
                      <div
                        style="
                          height: 360px;
                          justify-content: center;
                          align-items: center;
                        "
                        v-else
                        class="d-flex"
                      >
                        <CustomerV2ContactsCreate
                          v-if="isEditable && can(`customers_create`)"
                          :isEditable="true"
                          :customer_id="_id"
                          @callrefreshData="updateContactsData"
                        />
                      </div>
                    </v-tab-item>

                    <v-tab-item class="pa-7">
                      <CustomerV2Photos
                        v-if="_id"
                        @closeDialog="closeDialog"
                        :customer_id="_id"
                        :isMapviewOnly="isMapviewOnly"
                        :isEditable="isEditable"
                        :photos="item?.profile_pictures"
                      />
                    </v-tab-item>

                    <v-tab-item>
                      <v-card flat>
                        <v-card-text>
                          <AlarmCameraList
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

                    <v-tab-item class="pa-7">
                      <CustomerV2Devices
                        v-if="data"
                        @closeDialog="closeDialog"
                        :customer_id="_id"
                        :customer_contacts="customer_contacts"
                        :customer="data"
                        :isMapviewOnly="isMapviewOnly"
                        :isEditable="isEditable"
                        :devices="item?.devices"
                      />
                    </v-tab-item>

                    <v-tab-item>
                      <v-card flat>
                        <v-card-text>
                          <CustomerV2Sensors
                            v-if="_id"
                            :key="keyPhotos"
                            @closeDialog="closeDialog"
                            :customer_id="_id"
                            :isMapviewOnly="isMapviewOnly"
                            :isEditable="isEditable"
                            :item="data"
                          />
                        </v-card-text>
                      </v-card>
                    </v-tab-item>

                    <v-tab-item>
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
                      </v-card>
                    </v-tab-item>

                    <v-tab-item>
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
                      </v-card>
                    </v-tab-item>

                    <v-tab-item>
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
                    </v-tab-item>
                  </v-tabs-items>
                </v-tabs>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["_id", "item", "isMapviewOnly", "isEditable", "icon", "label"],
  data: () => ({
    compKey: 1,
    viewDialog: false,
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
  computed: {
    shouldAtleastOneContact() {
      let customer_contacts = this.customer_contacts;

      return customer_contacts && customer_contacts.length;
    },
    primaryCustomer() {
      return (
        this.customer_contacts &&
        this.customer_contacts.find((e) => e.address_type == "primary")
      );
    },
  },
  mounted() {},
  created() {
    if (this._id) this.getDataFromApi();

    this.getUniqueDevices();
    this.getCustomerProfilePercentage();
  },
  watch: {},
  methods: {
    async closeViewDialog() {
      this.viewDialog = false;
      this.$emit(`closeCustomerDialog`);
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    onImageError(event) {
      event.target.src = "/no-profile2.png"; // Replace with your default image URL
    },
    async updateContactsData(e) {
      this.compKey += 1;
      await this.getDataFromApi();
      this.viewDialog = false;
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
