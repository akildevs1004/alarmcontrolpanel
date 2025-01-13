<template>
  <v-dialog v-model="contactCreateDialog" width="760">
    <IconClose left="750" @click="closeContactCreateDialog" />
    <template v-slot:activator="{ on, attrs }">
      <v-btn v-bind="attrs" v-on="on" color="primary" dense x-small
        ><v-icon x-small>mdi-plus</v-icon>Add</v-btn
      >
    </template>

    <v-dialog v-model="dialogViewPhotos" width="60%">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">
            {{ editItem ? editItem.title : "---" }}</span
          >
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="dialogViewPhotos = false"
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <v-container style="min-height: 100px">
            <v-img
              :src="
                editItem ? editItem.profile_picture : '/no-business_profile.png'
              "
              aspect-ratio="1"
              class="grey lighten-2"
            >
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular
                    indeterminate
                    color="grey lighten-5"
                  ></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showMap" width="800px">
      <v-card>
        <v-card-title
          style="color: black"
          dense
          class="popup_background_noviolet"
        >
          <span style="">Move Marker to change Location </span>
          <v-spacer></v-spacer>
          <v-icon @click="showMap = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text style="padding-left: 0px; padding-right: 0px">
          <AlarmCompGoogleMapSelectLocation
            v-if="showMap"
            @triggerAddress="updateMapAddressDetails"
            @closePopup="showMap = false"
            :customer_latitude="payload_primary?.latitude"
            :customer_longitude="payload_primary?.longitude"
        /></v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="responsePopup" width="360">
      <IconClose left="350" @click="closeResponsePopup" />
      <v-card v-if="responseObject">
        <v-container class="pa-3">
          <div class="text-center text-body-1 pt-4">
            <v-icon :color="responseObject.color" large>{{
              responseObject.icon
            }}</v-icon>
            <br />
            <p class="mt-2">
              {{ responseObject.message }}
            </p>
          </div>
        </v-container>
      </v-card>
    </v-dialog>

    <v-card>
      <v-alert dense flat> Add Contact </v-alert>
      <v-container>
        <v-row>
          <v-col cols="3" dense>
            <div class="text-center mt-0" @click="onpick_primary_attachment">
              <v-badge icon="mdi-camera" overlap offset-x="35" offset-y="120">
                <v-avatar size="125">
                  <v-img
                    v-if="!isMapviewOnly && isEditable"
                    :src="primary_previewImage || '/no-profile2.png'"
                  ></v-img>
                </v-avatar>
              </v-badge>

              <input
                required
                type="file"
                @change="primary_attachment"
                style="display: none"
                accept="image/*"
                ref="primary_attachment_input"
              />

              <span
                v-if="primary_errors && primary_errors.logo"
                class="text-danger mt-2"
                >{{ primary_errors.logo[0] }}</span
              >
            </div>
          </v-col>
          <v-col cols="9"
            ><v-row
              ><v-col cols="6" dense>
                <v-combobox
                  label="Contact  Type"
                  :items="addressTypes"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.address_type"
                  dense
                  outlined
                  hide-details
                  small
                ></v-combobox
              ></v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="First Name"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.first_name"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Last Name"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.last_name"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Phone 1"
                  dense
                  maxlength="15"
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.phone1"
                  hide-details
                ></v-text-field>
                <span
                  v-if="primary_errors && primary_errors.phone1"
                  class="text-danger mt-2"
                  >{{ primary_errors.phone1[0] }}</span
                >
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Phone 2"
                  dense
                  maxlength="15"
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.phone2"
                  hide-details
                ></v-text-field>
                <span
                  v-if="primary_errors && primary_errors.phone2"
                  class="text-danger mt-2"
                  >{{ primary_errors.phone2[0] }}</span
                >
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Office Phone"
                  dense
                  maxlength="15"
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.office_phone"
                  hide-details
                ></v-text-field>
                <span
                  v-if="primary_errors && primary_errors.office_phone"
                  class="text-danger mt-2"
                  >{{ primary_errors.office_phone[0] }}</span
                >
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Email"
                  dense
                  small
                  outlined
                  type="email"
                  class="employee-schedule-search-box"
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
                  label="Whatsapp"
                  maxlength="15"
                  dense
                  small
                  outlined
                  type="whatsapp"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.whatsapp"
                  hide-details
                ></v-text-field>
                <span
                  v-if="primary_errors && primary_errors.whatsapp"
                  class="text-danger mt-2"
                  >{{ primary_errors.whatsapp[0] }}</span
                >
              </v-col>
              <v-col
                cols="6"
                dense
                v-if="
                  contact?.address_type == 'primary' ||
                  contact?.address_type == 'secondary'
                "
              >
                <v-text-field
                  label="Alarm STOP Pin"
                  dense
                  small
                  max="6"
                  outlined
                  type="number"
                  max-length="6"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.alarm_stop_pin"
                  hide-details
                ></v-text-field>
                <span
                  v-if="primary_errors && primary_errors.alarm_stop_pin"
                  class="text-danger mt-2"
                  >{{ primary_errors.alarm_stop_pin[0] }}</span
                > </v-col
              ><v-col cols="6" dense>
                <v-text-field
                  label="Working Hours"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.working_hours"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="6" dense>
                <v-text-field
                  label="Distance From Building"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.distance"
                  hide-details
                ></v-text-field>
              </v-col>

              <v-col
                :cols="
                  contact?.address_type == 'primary' ||
                  contact?.address_type == 'secondary'
                    ? 6
                    : 12
                "
              >
                <v-text-field
                  outlined
                  label="Important Notes"
                  value=""
                  dense
                  small
                  hide-details
                  class="employee-schedule-search-box"
                  v-model="payload_primary.notes"
                ></v-text-field>
              </v-col>

              <v-col cols="4">
                <div>
                  <AlarmCompGoogleMapLatLan
                    v-if="payload_primary"
                    :latitude="payload_primary.latitude"
                    :longitude="payload_primary.longitude"
                    :contact_id="payload_primary.id"
                    :key="mapkey"
                  />
                  <div class="text-center">
                    <v-btn
                      block
                      class="py-4"
                      outlined
                      v-if="isEditable || isMapviewOnly"
                      dense
                      small
                      color="primary"
                      @click="changeAddresss()"
                      ><v-icon>mdi-map-marker-radius</v-icon>Get Location</v-btn
                    >
                  </div>
                </div>
              </v-col>
              <v-col cols="4" dense>
                <v-text-field
                  label="Latitude"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.latitude"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="4" dense>
                <v-text-field
                  label="Longitude"
                  dense
                  small
                  outlined
                  type="text"
                  class="employee-schedule-search-box"
                  v-model="payload_primary.longitude"
                  hide-details
                ></v-text-field>
              </v-col> </v-row
          ></v-col>
          <v-col cols="10" class="text-right">
            <v-btn
              small
              v-if="!isMapviewOnly && isEditable && payload_primary.id"
              :loading="loading"
              color="error"
              @click="deleteContact(payload_primary)"
            >
              Delete
            </v-btn></v-col
          ><v-col cols="2" class="text-right">
            <v-btn
              small
              v-if="!isMapviewOnly && isEditable"
              :loading="loading"
              color="primary"
              @click="submit_primary"
            >
              Submit
            </v-btn></v-col
          >
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: [
    "customer_id",
    "customer",
    "isMapviewOnly",
    "isEditable",
    "contact",
  ],
  data: () => ({
    responseObject: null,
    responsePopup: false,
    response: "",
    contactCreateDialog: false,
    mapkey: 1,
    showMap: false,
    editItem: null,
    dialogViewPhotos: false,
    show1: false,
    buildingTypes: [],
    branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    primary_upload: {
      name: "",
    },
    secondary_upload: {
      name: "",
    },
    building_upload: {
      name: "",
    },
    start_date: "",
    end_date: "",
    payload_primary: {
      address_type: "primary",
      first_name: "test",
      last_name: "test",
      phone1: "1111111111",
      phone2: "1111111111",
      office_phone: "1111111111",
      email: "francisgill1000@gmail.com",
      whatsapp: "1111111111",
      alarm_stop_pin: "111111",
      working_hours: "9 hours",
      distance: "test",
      notes: "test",
      latitude: "1111",
      longitude: "1111",
    },

    default_payload: {
      address_type: "",
      first_name: "",
      last_name: "",
      phone1: "",
      phone2: "",

      office_phone: "",
      email: "",
      whatsapp: "",
      alarm_stop_pin: "",

      working_hours: "",
      distance: "",
      notes: "",

      latitude: "",
      longitude: "",
    },
    e1: 1,
    primary_errors: [],
    primary_previewImage: null,
    secondary_errors: [],
    secondary_previewImage: null,
    building_errors: [],
    building_previewImage: null,
    errors: [],
    addressTypes: [],
  }),
  created() {
    this.preloader = false;
    // this.getBranchesList();
    if (this.$store.state.storeAlarmControlPanel?.AddressTypes) {
      this.addressTypes = this.$store.state.storeAlarmControlPanel.AddressTypes;
      this.addressTypes = this.addressTypes.map((item) => item.name);
    }
    if (this.customer && this.contact) {
      this.payload_primary = this.contact;
      this.primary_previewImage = this.contact.profile_picture;
    }
  },
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    changeAddresss() {
      this.showMap = true;
    },
    updateMapAddressDetails(latitude, longitude) {
      this.payload_primary.latitude = latitude;
      this.payload_primary.longitude = longitude;
      this.mapkey++;
      this.showMap = false;
    },
    deleteContact(contact) {
      if (confirm("Are you sure you wish to delete ?")) {
        if (contact.id > 0) {
          this.$axios
            .delete(`delete_customer_contact`, {
              params: { contact_id: contact.id },
            })
            .then(({ data }) => {
              this.color = "background";
              this.primary_errors = [];
              this.response = data.message;

              this.$emit("callrefreshData");
            });
        }
      }
    },

    //primary
    onpick_primary_attachment() {
      this.$refs.primary_attachment_input.click();
    },
    primary_attachment(e) {
      this.primary_upload.name = e.target.files[0] || "";

      let input = this.$refs.primary_attachment_input;
      let file = input.files;
      //console.log(file);
      if (file[0] && file[0].size > 1024 * 1024) {
        e.preventDefault();
        this.primary_errors["logo"] = [
          "File too big (> 1MB). Upload less than 1MB",
        ];
        return;
      }

      if (file && file[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.primary_previewImage = e.target.result;
        };
        reader.readAsDataURL(file[0]);
        this.$emit("input", file[0]);
      }
    },
    viewPhoto(item) {
      this.editItem = item;
      this.dialogViewPhotos = true;
    },
    async submit_primary() {
      this.loading = true;
      let customer = new FormData();

      for (const key in this.payload_primary) {
        if (
          this.payload_primary[key] !== "" &&
          this.payload_primary[key] !== "null"
        )
          customer.append(key, this.payload_primary[key]);
      }
      customer.append("company_id", this.$auth.user.company_id);
      customer.append("customer_id", this.customer_id);
      if (this.contact?.id) customer.append("editId", this.contact.id);

      if (this.primary_upload.name) {
        customer.append("profile_picture", this.primary_upload.name);
      }

      try {
        const { data } = await this.$axios.post(
          "/customers_component_contact_update",
          customer
        );

        if (!data.status) {
          this.primary_errors = data.primary_errors || [];
          this.color = "red";
          this.response = data.message;
          this.response = "Contact cannot";

          this.responseObject = {
            icon: "mdi-thumb-down-outline",
            message: "Contact cannot",
            color: "red",
          };

          this.loading = false;
        } else {
          this.color = "background";
          this.primary_errors = [];
          this.response = "Contact has been added";
          this.responsePopup = true;
          this.responseObject = {
            icon: "mdi-thumb-up-outline",
            message: "Contact has been added",
            color: "success",
          };

          this.loading = false;
        }
      } catch (e) {
        if (e.response?.data) {
          this.primary_errors = e.response.data.errors;
          this.color = "red";

          this.responseObject = {
            icon: "mdi-thumb-down-outline",
            message: "Server Error",
            color: "red",
          };

          this.loading = false;
        }
        return false;
      }
    },
    closeResponsePopup() {
      this.responsePopup = false;
      this.$emit("callrefreshData", this.response);

      this.closeContactCreateDialog();
    },
    closeContactCreateDialog() {
      this.payload_primary = this.default_payload;
      this.primary_previewImage = null;
      this.contactCreateDialog = false;
    },
  },
};
</script>
