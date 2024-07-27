<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-row>
      <v-col cols="12" md="3">
        <div class="text-center mt-7">
          <v-img
            style="
              width: 150px;
              height: 150px;
              border-radius: 50%;
              margin: 0 auto;
            "
            :src="previewImage || '/no-business_profile.png'"
          ></v-img>
          <v-btn
            class="mt-2"
            style="width: 100%"
            small
            @click="onpick_attachment"
            >{{ !upload.name ? "Upload" : "Change" }}
            <v-icon right dark color="primary">mdi-cloud-upload</v-icon>
          </v-btn>

          <input
            required
            type="file"
            @change="attachment"
            style="display: none"
            accept="image/*"
            ref="attachment_input"
          />

          <span v-if="errors && errors.logo" class="text-danger mt-2">{{
            errors.logo[0]
          }}</span>
        </div>
      </v-col>
      <v-col md="9" sm="12" cols="12" dense>
        <v-row class="pt-5">
          <!-- <v-col md="4" cols="12" sm="12" dense>
            <v-select
              dense
              v-model="customer_payload.branch_id"
              :items="branchesList"
              outlined
              item-value="id"
              item-text="branch_name"
              label="Branch"
              hide-details
            >
            </v-select>
          </v-col> -->

          <v-col md="4" cols="12" sm="12" dense>
            <v-select
              label="Building Type"
              :items="buildingTypes"
              item-text="name"
              item-value="id"
              v-model="customer_payload.building_type_id"
              dense
              outlined
              hide-details
            ></v-select>
            <span
              v-if="errors && errors.building_type_id"
              class="text-danger mt-2"
              >{{ errors.building_type_id[0] }}</span
            >
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Building Name"
              dense
              outlined
              type="text"
              v-model="customer_payload.building_name"
              hide-details
            ></v-text-field>
            <span
              v-if="errors && errors.building_name"
              class="text-danger mt-2"
              >{{ errors.building_name[0] }}</span
            >
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="House Number"
              dense
              outlined
              type="text"
              v-model="customer_payload.house_number"
              hide-details
            ></v-text-field>
            <span
              v-if="errors && errors.house_number"
              class="text-danger mt-2"
              >{{ errors.house_number[0] }}</span
            >
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Street Number"
              dense
              outlined
              type="text"
              v-model="customer_payload.street_number"
              hide-details
            ></v-text-field>
            <span
              v-if="errors && errors.street_number"
              class="text-danger mt-2"
              >{{ errors.street_number[0] }}</span
            >
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Area"
              dense
              outlined
              type="text"
              v-model="customer_payload.area"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.area" class="text-danger mt-2">{{
              errors.area[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="City"
              dense
              outlined
              type="text"
              v-model="customer_payload.city"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.city" class="text-danger mt-2">{{
              errors.city[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="State"
              dense
              outlined
              type="text"
              v-model="customer_payload.state"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.state" class="text-danger mt-2">{{
              errors.state[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Country"
              dense
              outlined
              type="text"
              v-model="customer_payload.country"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.country" class="text-danger mt-2">{{
              errors.country[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Landmark"
              dense
              outlined
              type="text"
              v-model="customer_payload.landmark"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.landmark" class="text-danger mt-2">{{
              errors.landmark[0]
            }}</span>
          </v-col>
        </v-row>
        <v-row>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Latitude"
              dense
              outlined
              type="text"
              v-model="customer_payload.latitude"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.latitude" class="text-danger mt-2">{{
              errors.latitude[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <v-text-field
              label="Longitude"
              dense
              outlined
              type="text"
              v-model="customer_payload.longitude"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.longitude" class="text-danger mt-2">{{
              errors.longitude[0]
            }}</span>
          </v-col>
          <v-col md="4" cols="12" sm="12" dense>
            <LocationFinderDialogBox />
          </v-col>
        </v-row>
        <v-row v-if="!customer_id">
          <v-col md="4" cols="2" sm="12" dense>
            <v-text-field
              label="Login Email"
              dense
              outlined
              type="email"
              v-model="customer_payload.email"
              hide-details
            ></v-text-field>
            <span v-if="errors && errors.email" class="text-danger mt-2">{{
              errors.email[0]
            }}</span>
          </v-col>
          <v-col v-if="!customer_id" md="4" cols="2" sm="12" dense>
            <v-text-field
              label="Login Password"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :type="show1 ? 'text' : 'password'"
              dense
              outlined
              v-model="customer_payload.password"
              hide-details
              @click:append="show1 = !show1"
            ></v-text-field>
            <span v-if="errors && errors.password" class="text-danger mt-2">{{
              errors.password[0]
            }}</span>
          </v-col>
          <!-- <v-col md="4" cols="2" sm="12" dense>
            <v-text-field
              label="Contact Number"
              dense
              outlined
              type="text"
              v-model="customer_payload.contact_number"
              hide-details
            ></v-text-field>
            <span
              v-if="errors && errors.contact_number"
              class="text-danger mt-2"
              >{{ errors.contact_number[0] }}</span
            >
          </v-col> -->
        </v-row>
        <v-row>
          <v-col md="4" sm="12" cols="12">
            <v-menu
              v-model="startDateMenuOpen"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  label="Licence Start Date"
                  hide-details
                  v-model="customer_payload.start_date"
                  persistent-hint
                  append-icon="mdi-calendar"
                  readonly
                  outlined
                  dense
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
                <span
                  v-if="errors && errors.start_date"
                  class="text-danger mt-2"
                  >{{ errors.start_date[0] }}</span
                >
              </template>
              <v-date-picker
                style="min-height: 320px"
                v-model="customer_payload.start_date"
                no-title
                @input="startDateMenuOpen = false"
              ></v-date-picker>
            </v-menu>
          </v-col>
          <v-col md="4" sm="12" cols="12">
            <v-menu
              v-model="endDateMenuOpen"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  label="Licence End Date"
                  hide-details
                  v-model="customer_payload.end_date"
                  persistent-hint
                  append-icon="mdi-calendar"
                  readonly
                  outlined
                  dense
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
                <span
                  v-if="errors && errors.end_date"
                  class="text-danger mt-2"
                  >{{ errors.end_date[0] }}</span
                >
              </template>
              <v-date-picker
                :min="customer_payload.start_date"
                style="min-height: 320px"
                v-model="customer_payload.end_date"
                no-title
                @input="endDateMenuOpen = false"
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" class="text-right">
            <v-btn small :loading="loading" color="primary" @click="submit">
              {{ !customer_id ? "Submit" : "Update" }}
            </v-btn></v-col
          >
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["customer_id", "customer"],
  data: () => ({
    response: "",
    snackbar: false,
    show1: false,
    buildingTypes: [],
    branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    upload: {
      name: "",
    },
    start_date: "",
    end_date: "",
    customer_payload: {
      building_type_id: "",

      building_name: "",
      house_number: "",
      street_number: "",
      city: "",
      state: "",
      country: "",
      landmark: "",
      latitude: "",
      longitude: "",

      start_date: "",
      end_date: "",
      email: "",
      password: "",
    },
    contact_payload: {
      name: "",
      number: "",
      position: "",
      whatsapp: "",
    },
    // location: "",
    geographic_payload: {
      location: "",
      lat: "",
      lon: "",
    },
    e1: 1,
    errors: [],
    previewImage: null,
  }),
  created() {
    this.preloader = false;
    // this.getBranchesList();
    this.getBuildingTypes();

    if (this.customer_id) {
      this.previewImage = this.customer.profile_picture;
      this.customer_payload.building_type_id = this.customer.building_type_id;
      this.customer_payload.building_name = this.customer.building_name;
      this.customer_payload.house_number = this.customer.house_number;
      this.customer_payload.street_number = this.customer.street_number;
      this.customer_payload.city = this.customer.city;
      this.customer_payload.state = this.customer.state;
      this.customer_payload.country = this.customer.country;
      this.customer_payload.landmark = this.customer.landmark;
      this.customer_payload.latitude = this.customer.latitude;
      this.customer_payload.longitude = this.customer.longitude;
      this.customer_payload.contact_number = this.customer.contact_number;
      this.customer_payload.start_date = this.customer.start_date;
      this.customer_payload.end_date = this.customer.end_date;
    } else {
      this.customer_payload = {
        building_type_id: "",

        building_name: "",
        house_number: "",
        street_number: "",
        city: "",
        state: "",
        country: "",
        landmark: "",
        latitude: "",
        longitude: "",

        start_date: "",
        end_date: "",
        email: "",
        password: "",
      };
    }
  },
  watch: {
    "$store.state.location": {
      handler(newLocation) {
        this.customer_payload.latitude = newLocation.lat;
        this.customer_payload.longitude = newLocation.lng;
      },
      deep: true, // Watch for changes in nested properties
    },
  },
  computed: {
    locationData() {
      return this.$store.state.location;
    },
  },
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },

    getBranchesList() {
      this.$axios
        .get(`branches_list`, {
          params: {
            company_id: this.$auth.user.company_id,
          },
        })
        .then(({ data }) => {
          this.branchesList = data;
          this.customer_payload.branch_id = this.branchesList[0].id;
          //this.branch_id = this.$auth.user.branch_id || "";
        });
    },
    getBuildingTypes() {
      this.$axios
        .get(`building_types`, {
          params: {
            company_id: this.$auth.user.company_id,
          },
        })
        .then(({ data }) => {
          this.buildingTypes = data;
        });
    },
    onpick_attachment() {
      this.$refs.attachment_input.click();
    },
    attachment(e) {
      this.upload.name = e.target.files[0] || "";

      let input = this.$refs.attachment_input;
      let file = input.files;

      if (file[0].size > 1024 * 1024) {
        e.preventDefault();
        this.errors["logo"] = ["File too big (> 1MB). Upload less than 1MB"];
        return;
      }

      if (file && file[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          //croppedimage step6
          this.previewImage = e.target.result;

          // this.selectedFile = event.target.result;

          // this.$refs.cropper.replace(this.selectedFile);
        };
        reader.readAsDataURL(file[0]);
        this.$emit("input", file[0]);

        // this.dialogCropping = true;
      }
    },
    submit() {
      let customer = new FormData();

      for (const key in this.customer_payload) {
        if (this.customer_payload[key] != "")
          customer.append(key, this.customer_payload[key]);
      }
      customer.append("company_id", this.$auth.user.company_id);
      if (this.customer_id) customer.append("customer_id", this.customer_id);

      if (this.upload.name) {
        customer.append("attachment", this.upload.name);
      }
      if (this.customer_id) {
        this.$axios
          .post("/customer-update", customer)
          .then(({ data }) => {
            //this.loading = false;

            if (!data.status) {
              this.errors = [];
              if (data.errors) this.errors = data.errors;
              this.color = "red";

              this.snackbar = true;
              this.response = data.message;
            } else {
              this.color = "background";
              this.errors = [];
              this.snackbar = true;
              this.response = "Customer Details Updated successfully";

              this.$emit("closeDialog");
            }
          })
          .catch((e) => {
            if (e.response.data.errors) {
              this.errors = e.response.data.errors;
              this.color = "red";

              this.snackbar = true;
              this.response = e.response.data.message;
            }
          });
      } else {
        this.$axios
          .post("/customers", customer)
          .then(({ data }) => {
            //this.loading = false;

            if (!data.status) {
              this.errors = [];
              if (data.errors) this.errors = data.errors;
              this.color = "red";

              this.snackbar = true;
              this.response = data.message;
            } else {
              this.color = "background";
              this.errors = [];
              this.snackbar = true;
              this.response = "Customer Details Created successfully";

              this.$emit("closeDialog");
            }
          })
          .catch((e) => {
            if (e.response.data.errors) {
              this.errors = e.response.data.errors;
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

      if (this.upload.name) {
        branch.append("logo", this.upload.name);
      }

      this.$axios
        .post(`/branch/${this.branch.id}`, branch)
        .then(({ data }) => {
          //this.loading = false;

          if (!data.status) {
            this.errors = [];
            if (data.errors) this.errors = data.errors;
            this.color = "red";

            this.snackbar = true;
            this.response = data.message;
          } else {
            this.errors = [];
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
