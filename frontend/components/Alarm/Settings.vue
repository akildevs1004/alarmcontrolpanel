<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-row>
      <v-col cols="4">
        <v-row>
          <v-col cols="12">
            <v-form autocomplete="off">
              <v-col md="12" sm="12" cols="12" dense>
                <!-- <label class="col-form-label"
                          >Current Password
                          <span class="text-danger">*</span></label
                        > -->
                Email:
                <span style="font-weight: bold">{{ payload.email }}</span>
                <!-- <v-text-field
                  readonly
                  disabled
                  autocomplete="off"
                  label="Login Email Id"
                  dense
                  outlined
                  :hide-details="!errors.email"
                  v-model="payload.email"
                  class="input-group--focused"
                  :error="errors.email"
                  :error-messages="errors && errors.email ? errors.email : ''"
                ></v-text-field> -->
              </v-col>
              <v-col md="12" sm="12" cols="12" dense v-if="isEditable">
                <v-row>
                  <v-col md="6" sm="6" cols="6" dense v-if="isEditable">
                    <v-select
                      :items="timmingArray"
                      label="Store Close Time"
                      dense
                      outlined
                      hide-details
                      v-model="payload.close_time"
                      class="input-group--focused"
                      :error="errors.close_time"
                      :error-messages="
                        errors && errors.close_time ? errors.close_time[0] : ''
                      "
                    ></v-select>
                  </v-col>
                  <v-col md="6" sm="6" cols="6" dense v-if="isEditable">
                    <v-select
                      :items="timmingArray"
                      label="Store Open Time"
                      dense
                      outlined
                      hide-details
                      v-model="payload.open_time"
                      class="input-group--focused"
                      :error="errors.open_time"
                      :error-messages="
                        errors && errors.open_time ? errors.open_time[0] : ''
                      "
                    ></v-select>
                  </v-col>
                </v-row>
              </v-col>
              <v-col md="12" sm="12" cols="12" dense v-if="isEditable">
                <!-- <label class="col-form-label"
                          >Password <span class="text-danger">*</span></label
                        > -->
                <v-text-field
                  label="Change Password"
                  dense
                  outlined
                  :hide-details="!errors.password"
                  :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show_password ? 'text' : 'password'"
                  v-model="payload.password"
                  class="input-group--focused"
                  @click:append="show_password = !show_password"
                  :error="errors.password"
                  :error-messages="
                    errors && errors.password ? errors.password[0] : ''
                  "
                ></v-text-field>
              </v-col>
              <v-col md="12" sm="12" cols="12" dense v-if="isEditable">
                <!-- <label class="col-form-label"
                          >Password <span class="text-danger">*</span></label
                        > -->
                <v-text-field
                  label="Password Confirmation"
                  dense
                  outlined
                  :append-icon="
                    show_password_confirm ? 'mdi-eye' : 'mdi-eye-off'
                  "
                  :type="show_password_confirm ? 'text' : 'password'"
                  v-model="payload.password_confirmation"
                  class="input-group--focused"
                  @click:append="show_password_confirm = !show_password_confirm"
                  :error="errors.password_confirmation"
                  :error-messages="
                    errors && errors.password_confirmation
                      ? errors.password_confirmation[0]
                      : ''
                  "
                ></v-text-field>
                <span>{{
                  errors && errors.password_confirmation
                    ? errors.password_confirmation[0]
                    : ""
                }}</span>
              </v-col>
            </v-form>
          </v-col>
        </v-row>
        <v-row class="pl-5" v-if="isEditable">
          <v-col class="pt-5" style="max-width: 100px">Login Status </v-col>

          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <div style="cursor: pointer" v-if="web_login_access == 0">
              <v-img
                class="ele1"
                src="/off.png"
                style="width: 60px"
                @click="web_login_access = 1"
              >
              </v-img>
            </div>
            <div style="cursor: pointer" v-if="web_login_access == 1">
              <v-img
                class="ele1"
                src="/on.png"
                style="width: 60px"
                @click="web_login_access = 0"
              >
              </v-img>
            </div>
          </v-col>
        </v-row>
        <!-- <v-row class="pl-5">
          <v-col class="pt-5" style="max-width: 100px">Account </v-col>
          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <div
              style="cursor: pointer"
              v-if="payload.account_status == 0"
              @click="payload.account_status = 1"
            >
              <v-img class="ele1" src="/off.png" style="width: 60px"> </v-img>
            </div>
            <div
              style="cursor: pointer"
              v-if="payload.account_status == 1"
              @click="payload.account_status = 0"
            >
              <v-img class="ele1" src="/on.png" style="width: 60px"> </v-img>
            </div>
          </v-col>
        </v-row> -->
        <!-- <v-row>
          <v-col class="pt-5" style="max-width: 100px">Account </v-col>
          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <v-autocomplete
              class="pb-0"
              :hide-details="!payload.temperature_threshold"
              v-model="payload.temperature_threshold"
              placeholder="Temperature Threshold"
              outlined
              dense
              label="Temperature Threshold"
              :items="getTemperatureList()"
            ></v-autocomplete>
            <span
              v-if="errors && errors.temperature_threshold"
              class="error--text"
              >{{ errors.temperature_threshold[0] }}
            </span>
          </v-col>
        </v-row> -->
        <!-- <v-row class="pl-5">
          <v-col class="pt-5" style="max-width: 100px">Setting 1 </v-col>

          <v-col class="pl-0 pt-1" style="max-width: 80px">
           
            <v-img
              class="ele1"
              v-if="!item.is_over_time"
              src="/off.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
            <v-img
              class="ele1"
              v-if="item.is_over_time"
              src="/on.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
          </v-col>
        </v-row> -->
        <!-- <v-row class="pl-5">
          <v-col class="pt-5" style="max-width: 100px">Setting 1 </v-col>

          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <v-img
              class="ele1"
              v-if="!item.is_over_time"
              src="/off.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
            <v-img
              class="ele1"
              v-if="item.is_over_time"
              src="/on.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
          </v-col>
        </v-row> -->
        <!-- <v-row class="pl-5">
          <v-col class="pt-5" style="max-width: 100px">Setting 1 </v-col>

          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <v-img
              class="ele1"
              v-if="!item.is_over_time"
              src="/off.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
            <v-img
              class="ele1"
              v-if="item.is_over_time"
              src="/on.png"
              style="width: 60px"
              @click="item.is_over_time = !item.is_over_time"
            >
            </v-img>
          </v-col>
        </v-row> -->
      </v-col>

      <v-col cols="6">
        <v-row v-if="isEditable">
          <v-col cols="12" class="text-right"
            ><v-btn
              v-if="can('setting_company_change_password_access')"
              dark
              small
              :loading="loading_password"
              color="primary"
              @click="update_setting"
            >
              Update
            </v-btn></v-col
          >
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["customer", "isEditable"],
  data() {
    return {
      timmingArray: null,
      snackbar: false,
      response: "",
      item: { is_over_time: true },
      show_password_confirm: false,
      current_password_show: false,
      show_password: false,
      loading_password: false,
      user_payload: {
        password: "",
        password_confirmation: "",
      },
      web_login_access: 0,
      payload: {
        email: "",

        company_id: "",
        customer_id: "",
      },

      e1: 1,
      errors: [],
    };
  },
  mounted() {
    if (this.customer) {
      this.payload.email = this.customer.email;
      //this.payload.web_login_access = this.customer.user.web_login_access;
      this.web_login_access = this.customer.user.web_login_access;
      //this.payload.account_status = this.customer.account_status;
      this.payload.password = "";
      this.payload.password_confirmation = "";

      this.payload.close_time = this.customer.close_time ?? "00:00";
      this.payload.open_time = this.customer.open_time ?? "00:00";
    }

    this.timmingArray = this.generateTimingArray();
  },
  created() {},

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    generateTimingArray() {
      const times = [];
      for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += 60) {
          const hour = h.toString().padStart(2, "0");
          const minute = m.toString().padStart(2, "0");
          times.push(`${hour}:${minute}`);
        }
      }
      return times;
    },
    update_setting() {
      this.payload.company_id = this.$auth.user.company_id;
      this.payload.customer_id = this.customer.id;
      this.payload.web_login_access = this.web_login_access;
      this.payload.user_id = this.customer.user_id;
      this.errors = [];

      if (this.payload.password === "") delete this.payload.password;
      if (this.payload.password_confirmation === "")
        delete this.payload.password_confirmation;

      this.$axios
        .post("/update_customer_settings", this.payload)
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data.errors;
          }

          this.snackbar = data.status;
          this.response = data.message;

          this.$emit("closeDialog");
        })
        .catch((e) => {
          if (e.response.data.errors) {
            this.snackbar = true;
            this.response = e.response.data.message;
          }
        });
    },
  },
};
</script>
