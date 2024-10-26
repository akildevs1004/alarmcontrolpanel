<template>
  <NoAccess v-if="!$pagePermission.can('company_view', this)" />
  <div v-else style="width: 100%; margin-top: -20px">
    <div v-if="!preloader">
      <div class="text-center ma-2">
        <v-snackbar
          v-model="snackbar"
          top="top"
          color="secondary"
          elevation="24"
        >
          {{ response }}
        </v-snackbar>
      </div>

      <v-row>
        <v-col>
          <v-card class="mt-2">
            <v-tabs class="pt-3" :vertical="vertical">
              <v-tabs-slider color="violet"></v-tabs-slider>
              <v-tab>
                <v-icon left> mdi-domain </v-icon>
                <span>Profile</span>
              </v-tab>

              <v-tab>
                <v-icon left> mdi-lock </v-icon>
                <span>Password</span>
              </v-tab>

              <v-tab-item>
                <v-card flat>
                  <v-card-text>
                    <v-row>
                      <v-col cols="3">
                        <v-card class="ml-1 mr-1" style="text-align: center">
                          <v-card-title>Profile Picture</v-card-title>
                          <div style="width: 100%; text-align: center">
                            <div class="pb-2">
                              <v-img
                                @click="onpick_attachment"
                                class="company-profile-picture"
                                style="
                                  width: 200px;
                                  height: 200px;
                                  border: 1px solid #5fafa3;
                                  border-radius: 50%;
                                  margin: 0 auto;
                                "
                                :src="
                                  previewImage ||
                                  company_payload.logo ||
                                  '/no-image.PNG'
                                "
                              ></v-img>
                            </div>
                          </div>
                          <v-icon
                            v-if="!upload.name"
                            @click="onpick_attachment"
                            right
                            dark
                            fill
                            color="primary"
                            size="40"
                            title="Change Profile Picture"
                            >mdi mdi-folder-open-outline
                          </v-icon>
                          <v-btn
                            dense
                            small
                            v-if="upload.name"
                            style="width: 100px"
                            @click="cancelAttachment"
                            class="primary mb-2"
                            >Cancel
                          </v-btn>
                          <v-btn
                            dense
                            small
                            v-if="upload.name"
                            style="width: 100px"
                            @click="update_company"
                            class="primary mb-2"
                            >{{ !upload.name ? "Change Logo" : "Submit" }}
                            <v-icon right dark> </v-icon>
                          </v-btn>
                        </v-card>

                        <input
                          required
                          type="file"
                          @change="attachment"
                          style="display: none"
                          accept="image/*"
                          ref="attachment_input"
                        />

                        <span
                          v-if="errors && errors.logo"
                          class="text-danger mt-2"
                          >{{ errors.logo[0] }}</span
                        >
                      </v-col>
                      <v-col cols="4">
                        <v-card elevation="2" style="height: 500px">
                          <v-card-title>Information</v-card-title>

                          <v-card-text>
                            <v-row>
                              <v-col cols="6" md="6" sm="6">
                                <v-text-field
                                  label="Company Code"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.company_code"
                                ></v-text-field>
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <v-text-field
                                  label="Company Name"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.name"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.name"
                                  class="text-danger mt-2"
                                  >{{ errors.name[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Company Email</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Company Email"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="user_payload.email"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.email"
                                  class="text-danger mt-2"
                                  >{{ errors.email[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Member From</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Member From"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.member_from"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.member_from"
                                  class="text-danger mt-2"
                                  >{{ errors.member_from[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Expiry Date</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Expiry Date"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.expiry"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.expiry"
                                  class="text-danger mt-2"
                                  >{{ errors.expiry[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Max Branches</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Max Branches"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.max_branches"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.max_branches"
                                  class="text-danger mt-2"
                                  >{{ errors.max_branches[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Max Employees</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Max Employees"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.max_employee"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.max_employee"
                                  class="text-danger mt-2"
                                  >{{ errors.max_employee[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Max Devices</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Max Devices"
                                  color="grey"
                                  readonly
                                  filled
                                  dense
                                  outlined
                                  v-model="company_payload.max_devices"
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.max_devices"
                                  class="text-danger mt-2"
                                  >{{ errors.max_devices[0] }}</span
                                >
                              </v-col>
                            </v-row>
                          </v-card-text>
                        </v-card>
                      </v-col>
                      <v-col cols="4">
                        <v-card elevation="2" style="height: 500px">
                          <v-card-title>Contact Details</v-card-title>

                          <v-card-text>
                            <v-row>
                              <v-col cols="6">
                                <!-- <label class="col-form-label">
                              Contact Person Name
                            </label> 
                            <span class="text-danger">*</span>-->
                                <v-text-field
                                  label="Contact Person Name"
                                  dense
                                  outlined
                                  v-model="contact_payload.name"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.name"
                                  class="text-danger mt-2"
                                  >{{ errors.name[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6">
                                <!-- <label class="col-form-label">
                              Contact Person Number
                            </label>
                            <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Contact Person Number"
                                  dense
                                  outlined
                                  v-model="contact_payload.number"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.number"
                                  class="text-danger mt-2"
                                  >{{ errors.number[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6">
                                <!-- <label class="col-form-label">
                              Contact Person Position
                            </label>
                            <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Contact Person Position"
                                  dense
                                  outlined
                                  v-model="contact_payload.position"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.position"
                                  class="text-danger mt-2"
                                  >{{ errors.position[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6">
                                <!-- <label class="col-form-label">
                              Whatsapp (with Country Code ex: 9199XXX)
                            </label>
                            <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Whatsapp (with Country Code ex: 9199XXX)"
                                  dense
                                  outlined
                                  v-model="contact_payload.whatsapp"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.whatsapp"
                                  class="text-danger mt-2"
                                  >{{ errors.whatsapp[0] }}</span
                                >
                              </v-col>
                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">Mol ID</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Mobile Number"
                                  color="primary"
                                  dense
                                  outlined
                                  v-model="company_payload.mol_id"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.mol_id"
                                  class="text-danger mt-2"
                                  >{{ errors.mol_id[0] }}</span
                                >
                              </v-col>

                              <v-col cols="6" md="6" sm="6">
                                <!-- <label class="col-form-label">P.O Box</label>
                        <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="P.O Box"
                                  color="primary"
                                  dense
                                  outlined
                                  v-model="company_payload.p_o_box_no"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.p_o_box_no"
                                  class="text-danger mt-2"
                                  >{{ errors.p_o_box_no[0] }}</span
                                >
                              </v-col>
                              <v-col cols="6">
                                <!-- <label class="col-form-label"> Lat </label>
                            <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Latitude"
                                  dense
                                  outlined
                                  v-model="geographic_payload.lat"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.lat"
                                  class="text-danger mt-2"
                                  >{{ errors.lat[0] }}</span
                                >
                              </v-col>
                              <v-col cols="6">
                                <!-- <label class="col-form-label"> Lon </label>
                            <span class="text-danger">*</span> -->
                                <v-text-field
                                  label="Longitude"
                                  dense
                                  outlined
                                  v-model="geographic_payload.lon"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-text-field>
                                <span
                                  v-if="errors && errors.lon"
                                  class="text-danger mt-2"
                                  >{{ errors.lon[0] }}</span
                                >
                              </v-col>
                              <v-col cols="12">
                                <!-- <label class="col-form-label"> Location </label>
                            <span class="text-danger">*</span> -->
                                <v-textarea
                                  label="Location"
                                  dense
                                  outlined
                                  height="50px"
                                  v-model="geographic_payload.location"
                                  hide-details
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                >
                                </v-textarea>
                                <span
                                  v-if="errors && errors.location"
                                  class="text-danger mt-2"
                                  >{{ errors.location[0] }}</span
                                >
                              </v-col>
                              <v-col>
                                <v-autocomplete
                                  class="pb-0"
                                  hide-details
                                  v-model="geographic_payload.utc_time_zone"
                                  outlined
                                  dense
                                  label="Time Zone(Ex:UTC+)"
                                  :items="getTimezones()"
                                  item-value="key"
                                  item-text="text"
                                  :disabled="
                                    !$pagePermission.can('company_edit', this)
                                  "
                                ></v-autocomplete>
                              </v-col>
                            </v-row>
                          </v-card-text>
                        </v-card>
                      </v-col>
                    </v-row>

                    <v-row>
                      <v-col cols="12">
                        <div class="text-right">
                          <v-btn
                            v-if="$pagePermission.can('company_edit', this)"
                            small
                            :loading="loading"
                            color="primary"
                            @click="update_company"
                          >
                            Submit
                          </v-btn>
                        </div>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-tab-item>

              <v-tab-item>
                <v-container>
                  <v-row>
                    <v-col cols="3">
                      <v-col cols="12">
                        <!-- <label class="col-form-label"
                          >Current Password
                          <span class="text-danger">*</span></label
                        > -->
                        <v-text-field
                          label="Current Password"
                          dense
                          outlined
                          :hide-details="!errors.current_password"
                          :append-icon="
                            current_password_show ? 'mdi-eye' : 'mdi-eye-off'
                          "
                          :type="current_password_show ? 'text' : 'password'"
                          v-model="payload.current_password"
                          class="input-group--focused"
                          @click:append="
                            current_password_show = !current_password_show
                          "
                          :error="errors.current_password"
                          :error-messages="
                            errors && errors.current_password
                              ? errors.current_password
                              : ''
                          "
                          :disabled="!$pagePermission.can('company_edit', this)"
                        ></v-text-field>
                      </v-col>
                      <v-col md="12" sm="12" cols="12" dense>
                        <!-- <label class="col-form-label"
                          >Password <span class="text-danger">*</span></label
                        > -->
                        <v-text-field
                          label="New Password"
                          dense
                          outlined
                          :hide-details="!errors.password"
                          :append-icon="
                            show_password ? 'mdi-eye' : 'mdi-eye-off'
                          "
                          :type="show_password ? 'text' : 'password'"
                          v-model="payload.password"
                          class="input-group--focused"
                          @click:append="show_password = !show_password"
                          :error="errors.password"
                          :error-messages="
                            errors && errors.password ? errors.password[0] : ''
                          "
                          :disabled="!$pagePermission.can('company_edit', this)"
                        ></v-text-field>
                      </v-col>

                      <v-col md="12" sm="12" cols="12" dense>
                        <!-- <label class="col-form-label"
                          >Confirm Password
                          <span class="text-danger">*</span></label
                        > -->
                        <v-text-field
                          label="Confirm New Password"
                          dense
                          outlined
                          :hide-details="!errors.password_confirmation"
                          :append-icon="
                            show_password_confirm ? 'mdi-eye' : 'mdi-eye-off'
                          "
                          :type="show_password_confirm ? 'text' : 'password'"
                          v-model="payload.password_confirmation"
                          class="input-group--focused"
                          @click:append="
                            show_password_confirm = !show_password_confirm
                          "
                          :error="errors.show_password_confirm"
                          :error-messages="
                            errors && errors.show_password_confirm
                              ? errors.show_password_confirm[0]
                              : ''
                          "
                          :disabled="!$pagePermission.can('company_edit', this)"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <div class="text-right">
                          <v-btn
                            v-if="$pagePermission.can('company_edit', this)"
                            dark
                            small
                            :loading="loading_password"
                            color="primary"
                            @click="update_setting"
                          >
                            Submit
                          </v-btn>
                        </div>
                      </v-col>
                    </v-col>
                  </v-row>
                </v-container>
              </v-tab-item>
            </v-tabs>
          </v-card>
        </v-col>
      </v-row>
    </div>
    <Preloader v-else />
  </div>
</template>

<script>
import timeZones from "../../defaults/utc_time_zones.json";

export default {
  data: () => ({
    timeZones,
    originalURL: process.env.APP_URL + "register/visitor/walkin/", //`https://mytime2cloud.com/register/visitor/walkin/`,
    fullCompanyLink: null,
    qrCompanyCodeDataURL: null,
    show_password_confirm: false,
    current_password_show: false,
    show_password: false,
    loading_password: false,
    menuIssueDate: false,
    menuExpiryDate: false,
    IssueDate: null,
    vertical: false,
    id: "",
    loading: false,
    preloader: true,
    upload: {
      name: "",
    },

    company_payload: {
      name: "",
      logo: "",
      member_from: "",
      expiry: "",
      max_branches: "",
      max_employee: "",
      max_devices: "",
      mol_id: "",
      p_o_box_no: "",
    },

    company_trade_license: {
      license_no: "",
      license_type: "",
      emirate: "",
      makeem_no: "",
      manager: "",
      issue_date: "",
      expiry_date: "",
    },

    contact_payload: {
      name: "",
      number: "",
      position: "",
      whatsapp: "",
    },
    user_payload: {
      password: "",
      password_confirmation: "",
    },
    payload: {
      password: "",
      current_password: "",
      password_confirmation: "",
    },
    geographic_payload: {
      lat: "",
      lon: "",
      location: "",
    },
    e1: 1,
    errors: [],
    previewImage: null,
    data: {},
    response: "",
    snackbar: false,
  }),
  async created() {
    try {
      this.getDataFromApi();
      if (process.env.ENVIRONMENT == "local") {
        this.originalURL = `http://${process.env.LOCAL_IP}:${process.env.LOCAL_PORT}/register/visitor/walkin/`;
      }
      this.fullCompanyLink = this.originalURL + this.$auth.user.company_id;
      this.generateCompanyQRCode(this.fullCompanyLink);
    } catch (e) {}
  },
  methods: {
    async generateCompanyQRCode(fullLink) {
      try {
        this.qrCompanyCodeDataURL = await this.$qrcode.generate(fullLink);
      } catch (error) {
        console.error("Error generating QR code:", error);
      }
    },

    can(per) {
      return this.$pagePermission.can(per, this);
    },

    update_setting() {
      this.$axios
        .post(
          `/company/${this.$auth?.user?.company?.id}/update/user`,
          this.payload
        )
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data.errors;
          } else {
            this.snackbar = true;
            this.response = "Setting updated successfully";
          }
        })
        .catch((e) => console.log(e));
    },
    getTimezones() {
      return Object.keys(this.timeZones).map((key) => ({
        offset: this.timeZones[key].offset,
        time_zone: this.timeZones[key].time_zone,
        key: key,
        text:
          this.timeZones[key].time_zone +
          " - " +
          key +
          " - " +
          this.timeZones[key].offset,
      }));
    },
    getDataFromApi() {
      this.id = this.$auth.user.company_id;
      this.$axios
        .get(`company/${this.$auth.user.company_id}`)
        .then(({ data }) => {
          let r = data.record;
          this.company_payload = r;
          this.contact_payload = r.contact;
          this.user_payload = r.user;

          if (r.trade_license) {
            this.company_trade_license = r.trade_license;
          }

          let mf = this.formatted_date(r.member_from);
          let exp = this.formatted_date(r.expiry);
          this.company_payload.member_from = mf;
          this.company_payload.expiry = exp;

          this.geographic_payload = {
            lat: this.company_payload.lat,
            lon: this.company_payload.lon,
            location: this.company_payload.location,
            utc_time_zone: this.company_payload.utc_time_zone,
          };
          this.preloader = false;
        });
    },

    formatted_date(v) {
      let [year, month, date] = v.split("/");
      return `${year}-${month}-${date}`;
    },
    onpick_attachment() {
      this.$refs.attachment_input.click();
    },

    attachment(e) {
      this.upload.name = e.target.files[0] || "";

      let input = this.$refs.attachment_input;
      let file = input.files;
      if (file && file[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.previewImage = e.target.result;
        };
        reader.readAsDataURL(file[0]);
        this.$emit("input", file[0]);
      }
    },
    cancelAttachment() {
      this.upload.name = "";
      this.previewImage = null;
    },

    update_company() {
      this.update_contact();
      this.update_geographic();

      let payload = new FormData();

      payload.append("name", this.company_payload.name);
      if (this.upload.name) {
        payload.append("logo", this.upload.name);
      }
      payload.append("location", this.company_payload.location);
      payload.append("member_from", this.company_payload.member_from);
      payload.append("expiry", this.company_payload.expiry);
      payload.append("max_employee", this.company_payload.max_employee);
      payload.append("max_branches", this.company_payload.max_branches);
      payload.append("max_devices", this.company_payload.max_devices);

      payload.append("mol_id", this.company_payload.mol_id);
      payload.append("p_o_box_no", this.company_payload.p_o_box_no);

      this.start_process(`/company/${this.id}/update`, payload, `Company`);
    },
    update_contact() {
      this.start_process(
        `/company/${this.id}/update/contact`,
        this.contact_payload,
        `Contact`
      );
    },

    update_license() {
      this.start_process(
        `/company/${this.id}/trade-license`,
        this.company_trade_license,
        `Trade License`
      );
    },
    update_geographic() {
      this.start_process(
        `/company/${this.id}/update/geographic`,
        this.geographic_payload,
        `Geographic Info`
      );
    },
    update_user() {
      this.start_process(
        `/company/${this.id}/update/user`,
        this.user_payload,
        `User`
      );
    },
    start_process(url, payload, model) {
      this.loading = true;

      this.$axios
        .post(url, payload)
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data.errors;
          } else {
            this.snackbar = true;
            this.response = model + " updated successfully";

            this.upload.name = "";
          }
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
