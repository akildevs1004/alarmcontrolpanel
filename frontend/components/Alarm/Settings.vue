<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-row>
      <v-col cols="6">
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
              <v-col md="12" sm="12" cols="12" dense>
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
              <v-col md="12" sm="12" cols="12" dense>
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
        <v-row class="pl-5">
          <v-col class="pt-5" style="max-width: 100px">Login </v-col>

          <v-col class="pl-0 pt-1" style="max-width: 80px">
            <div style="cursor: pointer" v-if="payload.login_status == 0">
              <v-img
                class="ele1"
                src="/off.png"
                style="width: 60px"
                @click="payload.login_status = 1"
              >
              </v-img>
            </div>
            <div style="cursor: pointer" v-if="payload.login_status == 1">
              <v-img
                class="ele1"
                src="/on.png"
                style="width: 60px"
                @click="payload.login_status = 0"
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
        <v-row>
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
  props: ["customer"],
  data() {
    return {
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
      payload: {
        email: "",
        login_status: 1,
        account_status: 1,
        company_id: "",
        customer_id: "",
      },

      e1: 1,
      errors: [],
    };
  },

  created() {
    if (this.customer) {
      this.payload.email = this.customer.email;
      this.payload.login_status = this.customer.login_status;
      this.payload.account_status = this.customer.account_status;
      this.payload.password = "";
      this.payload.password_confirmation = "";
    }
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    update_setting() {
      this.payload.company_id = this.$auth.user.company_id;
      this.payload.customer_id = this.customer.id;
      this.payload.user_id = this.customer.user_id;
      this.errors = [];
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
