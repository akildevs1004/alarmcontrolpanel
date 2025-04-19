<template>
  <div>
    <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
      {{ response }}
    </v-snackbar>
    <v-card>
      <v-card-text>
        <v-row>
          <v-col cols="3" style="width: 250px">
            <div class="form-group">
              <label class="col-form-label"
                >Alarm Notification Popup Pause (Minutes)</label
              >
              <span class="error--text">*</span>
              <v-select
                style="width: 250px"
                outlined
                dense
                small
                v-model="payload.minutes"
                :items="[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]"
              >
              </v-select>
            </div>
            <v-col cols="12" style="width: 250px">
              <div class="text-right">
                <v-btn
                  dark
                  small
                  :loading="loading"
                  color="primary"
                  @click="submit()"
                >
                  Update
                </v-btn>
              </div>
            </v-col>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ["minutes"],
  data: () => ({
    payload: { minutes: 1 },
    loading: false,
    snackbar: false,
    response: "",
  }),
  async mounted() {},
  async created() {
    this.payload.minutes = this.minutes;
  },
  methods: {
    submit() {
      this.loading = true;

      this.$axios
        .post("update_settings_notifications", this.payload)
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data.errors;
          } else {
            this.snackbar = true;
            this.response = "Alarm Settings updated successfully";

            this.upload.name = "";
            this.errors = [];
          }
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
