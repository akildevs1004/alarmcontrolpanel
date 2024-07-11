<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>

    <v-row>
      <v-col md="12" sm="12" cols="12" dense>
        <v-card class="elevation-0 p-2" style="padding: 5px">
          <v-row class="pt-0">
            <v-col cols="6" dense>
              <v-autocomplete
                @change="loadZonesContent()"
                label="Device"
                :items="devicesList"
                item-text="name"
                item-value="serial_number"
                v-model="payload_primary.serial_number"
                dense
                outlined
                hide-details
                small
              ></v-autocomplete>
              <span
                v-if="errors && errors.serial_number"
                class="text-danger mt-2"
                >{{ errors.serial_number[0] }}</span
              >
            </v-col>
            <v-col cols="6" dense v-if="zonesList.length > 0">
              <v-autocomplete
                label="Sensor/Zone"
                :items="zonesList"
                item-text="sensor_name"
                item-value="sensor_name"
                v-model="payload_primary.zone_name"
                dense
                outlined
                hide-details
                small
              ></v-autocomplete>
              <span
                v-if="errors && errors.zone_name"
                class="text-danger mt-2"
                >{{ errors.zone_name[0] }}</span
              >
            </v-col>
            <v-col cols="6" dense>
              <v-text-field
                label="Receiver Name"
                dense
                small
                outlined
                type="text"
                v-model="payload_primary.name"
                hide-details
              ></v-text-field>
              <span
                v-if="primary_errors && primary_errors.name"
                class="text-danger mt-2"
                >{{ primary_errors.name[0] }}</span
              >
            </v-col>
            <v-col cols="6" dense>
              <v-text-field
                label="Mobile Number"
                dense
                small
                outlined
                type="text"
                v-model="payload_primary.mobile_number"
                hide-details
              ></v-text-field>
              <span
                v-if="primary_errors && primary_errors.mobile_number"
                class="text-danger mt-2"
                >{{ primary_errors.mobile_number[0] }}</span
              >
            </v-col>
            <v-col cols="6" dense>
              <v-text-field
                label="Email"
                dense
                small
                outlined
                type="email"
                v-model="payload_primary.email"
                hide-details
              ></v-text-field>
              <span
                v-if="primary_errors && primary_errors.email"
                class="text-danger mt-2"
                >{{ primary_errors.email[0] }}</span
              >
            </v-col>
            <v-col cols="6" dense>
              <v-text-field
                label="Whatsapp Number"
                dense
                small
                outlined
                type="text"
                v-model="payload_primary.whatsapp_number"
                hide-details
              ></v-text-field>
              <span
                v-if="primary_errors && primary_errors.whatsapp_number"
                class="text-danger mt-2"
                >{{ primary_errors.whatsapp_number[0] }}</span
              >
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="text-right">
              <!-- <v-btn
                small
                :loading="loading"
                color="primary"
                @click="$emit('closeDialog')"
              >
                Close
              </v-btn> -->
              <v-btn
                small
                :loading="loading"
                color="primary"
                @click="submit_primary"
              >
                Submit
              </v-btn></v-col
            >
          </v-row>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["editItem", "editId", "customer_id"],
  data: () => ({
    show1: false,
    devicesList: [],
    zonesList: [],
    //branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    primary_upload: {
      name: "",
    },

    start_date: "",
    end_date: "",
    payload_primary: {},

    e1: 1,
    primary_errors: [],

    response: "",
    snackbar: false,
    errors: [],
    selectedItem: null,
    items: ["Apple", "Banana", "Orange"],
  }),
  created() {
    this.payload_primary = {};
    this.preloader = false;
    this.getDevicesList();
    // this.getBranchesList();

    if (this.$store.state.storeAlarmControlPanel?.AddressTypes) {
      this.addressTypes = this.$store.state.storeAlarmControlPanel.AddressTypes;
    }

    // // setTimeout(() => {
    // //console.log(this.editAddressType);
    // if (this.editId != "") {
    //   this.payload_primary.editId = this.editId;
    //   this.loadAddressContent(true);
    // }
    // // }, 1000);

    if (this.editItem) {
      this.payload_primary = {};
      this.payload_primary.editId = this.editItem.id;
      this.payload_primary.serial_number = this.editItem.serial_number;
      this.payload_primary.zone_name = this.editItem.zone_name;
      this.payload_primary.name = this.editItem.name;
      this.payload_primary.email = this.editItem.email;
      this.payload_primary.mobile_number = this.editItem.mobile_number;
      this.payload_primary.whatsapp_number = this.editItem.whatsapp_number;
      this.payload_primary.customer_id = this.editItem.customer_id;
      setTimeout(() => {
        this.loadZonesContent();
      }, 1000 * 3);
    }
  },
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    loadZonesContent() {
      let deviceFilter = this.devicesList.filter(
        (e) => e.serial_number == this.payload_primary.serial_number
      );

      if (deviceFilter[0]?.sensorzones?.length > 0)
        this.zonesList = deviceFilter[0].sensorzones;
      else this.zonesList = [];
    },
    // addNewItem(value) {
    //   console.log(this.items);
    //   // Check if the value is already in the items list
    //   if (!this.items.includes(value)) {
    //     // Add the new value to the items list
    //     this.items.push(value);
    //   }
    // },
    // loadAddressContent(is_address_type) {
    //   // let _payload_primary = this.customer_contacts.filter(
    //   //   (el) => el.address_type == this.payload_primary.address_type
    //   // );
    //   let _payload_primary = [];
    //   if (this.editId != "") {
    //     _payload_primary = this.customer_contacts.filter(
    //       (el) => el.id == this.payload_primary.editId
    //     );
    //   }

    //   if (_payload_primary[0]) {
    //     if (is_address_type)
    //       this.payload_primary.address_type = _payload_primary[0].address_type;
    //     this.payload_primary.first_name = _payload_primary[0].first_name;
    //     this.payload_primary.last_name = _payload_primary[0].last_name;
    //     this.payload_primary.address = _payload_primary[0].address;
    //     this.payload_primary.phone1 = _payload_primary[0].phone1;
    //     this.payload_primary.phone2 = _payload_primary[0].phone2;
    //     this.payload_primary.office_phone = _payload_primary[0].office_phone;
    //     this.payload_primary.email = _payload_primary[0].email;
    //     this.payload_primary.whatsapp = _payload_primary[0].whatsapp;
    //     this.payload_primary.latitude = _payload_primary[0].latitude;
    //     this.payload_primary.longitude = _payload_primary[0].longitude;
    //     this.payload_primary.working_hours = _payload_primary[0].working_hours;
    //     this.payload_primary.distance = _payload_primary[0].distance;
    //     this.payload_primary.notes = _payload_primary[0].notes;
    //     this.payload_primary.editId = _payload_primary[0].id;
    //   }
    // },
    getDevicesList() {
      this.$axios
        .get(`device-list`, {
          params: {
            company_id: this.$auth.user.company_id,
            customer_id: this.customer_id,
          },
        })
        .then(({ data }) => {
          this.devicesList = data;
        });
    },
    // getBranchesList() {
    //   this.$axios
    //     .get(`branches_list`, {
    //       params: {
    //         company_id: this.$auth.user.company_id,
    //       },
    //     })
    //     .then(({ data }) => {
    //       this.branchesList = data;
    //       this.payload_primary.branch_id = this.branchesList[0].id;
    //       //this.branch_id = this.$auth.user.branch_id || "";
    //     });
    // },
    // getBuildingTypes() {
    //   this.$axios
    //     .get(`building_types`, {
    //       params: {
    //         company_id: this.$auth.user.company_id,
    //       },
    //     })
    //     .then(({ data }) => {
    //       this.buildingTypes = data;
    //     });
    // },
    // //primary
    // onpick_primary_attachment() {
    //   this.$refs.primary_attachment_input.click();
    // },
    // primary_attachment(e) {
    //   this.primary_upload.name = e.target.files[0] || "";

    //   let input = this.$refs.primary_attachment_input;
    //   let file = input.files;
    //   //console.log(file);
    //   if (file[0] && file[0].size > 1024 * 1024) {
    //     e.preventDefault();
    //     this.primary_errors["logo"] = [
    //       "File too big (> 1MB). Upload less than 1MB",
    //     ];
    //     return;
    //   }
    //   console.log(file);
    //   if (file && file[0]) {
    //     let reader = new FileReader();
    //     reader.onload = (e) => {
    //       this.primary_previewImage = e.target.result;
    //     };
    //     reader.readAsDataURL(file[0]);
    //     this.$emit("input", file[0]);
    //   }
    // },

    submit_primary() {
      this.payload_primary.company_id = this.$auth.user.company_id;
      this.payload_primary.customer_id = this.customer_id;

      if (this.editId) {
        this.$axios
          .put("/automation/" + this.editId, this.payload_primary)
          .then(({ data }) => {
            this.loading = false;
            this.$emit("closeDialog");
            if (!data.status) {
              this.primary_errors = data.errors;
              return;
            }

            this.snackbar = data.status;
            this.response = data.message;
          })
          .catch((e) => console.log(e));
      } else {
        this.$axios
          .post("/automation", this.payload_primary)
          .then(({ data }) => {
            this.loading = false;

            if (!data.status) {
              this.primary_errors = data.errors;
              return;
            }
            this.snackbar = data.status;
            this.response = data.message;
            this.$emit("closeDialog");
          })
          .catch((e) => {
            if (e.response.data.errors) {
              this.primary_errors = e.response.data.errors;
              this.color = "red";

              this.snackbar = true;
              this.response = e.response.data.message;
            }
          });
      }
    },

    update() {
      let branch = new FormData();
      branch.append("company_id", this.$auth.user.company_id);
      branch.append("branch_name", this.branch.branch_name);
      branch.append("user_id", this.branch.user_id);

      branch.append("licence_number", this.branch.licence_number);
      branch.append(
        "licence_issue_by_department",
        this.branch.licence_issue_by_department
      );
      branch.append("licence_expiry", this.branch.licence_expiry);
      branch.append("lat", this.branch.lat);
      branch.append("lon", this.branch.lon);
      branch.append("address", this.branch.address);

      if (this.primary_upload.name) {
        branch.append("logo", this.primary_upload.name);
      }

      this.$axios
        .post(`/branch/${this.branch.id}`, branch)
        .then(({ data }) => {
          //this.loading = false;

          if (!data.status) {
            this.primary_errors = [];
            if (data.primary_errors) this.primary_errors = data.primary_errors;
            this.color = "red";

            this.snackbar = true;
            this.response = data.message;
          } else {
            this.primary_errors = [];
            this.snackbar = true;
            this.response = "Branch updated successfully";
            this.getDataFromApi();
            this.branchDialog = false;
          }
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
