<template>
  <div class="text-center">
    <v-btn small color="blue" @click="dialog = true" class="white--text" dark>
      <v-icon color="white" small> mdi-plus </v-icon>
      {{ model }}
    </v-btn>

    <v-dialog :key="key" v-model="dialog" width="500">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ snackbar_message }}
      </v-snackbar>
      <v-card>
        <v-toolbar flat class="lighten-3" dense>
          Create {{ model }} <v-spacer></v-spacer
          ><AssetsButtonClose @close="close"
        /></v-toolbar>
        <v-card-text class="py-5">
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  outlined
                  dense
                  hide-details
                  v-model="payload.name"
                  label="Contact Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  outlined
                  dense
                  hide-details
                  v-model="payload.number"
                  label="Number"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  outlined
                  dense
                  hide-details
                  v-model="payload.description"
                  label="Description"
                ></v-text-field>
              </v-col>
              <v-col cols="12" v-if="errorResponse">
                <span class="red--text">{{ errorResponse }}</span>
              </v-col>
              <v-col cols="12" class="text-right">
                <v-btn
                  small
                  color="red"
                  class="white--text"
                  dark
                  @click="close"
                >
                  Close
                </v-btn>
                <v-btn
                  :loading="loading"
                  small
                  color="blue"
                  class="white--text"
                  dark
                  @click="submit"
                >
                  Submit
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["endpoint", "model", "user_id"],

  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      payload: {
        name: "",
        description: "",
        document: null,
      },
      dialog: false,
      loading: false,
      successResponse: null,
      errorResponse: null,
      product_categories: [],
      key: 1,
    };
  },
  created() {
    //this.getVendorCategory();
  },
  watch: {
    dialog(val) {
      if (val) {
        this.resetForm();
      }
    },
  },
  methods: {
    close() {
      this.dialog = false;
      this.loading = false;
      this.errorResponse = null;
    },
    async getVendorCategory() {
      let { data } = await this.$axios.get(`${this.endpoint}-category-list`);

      this.product_categories = data;
    },
    resetForm() {
      this.payload = {
        name: "",
        description: "",
        document: null,
      };
      this.errorResponse = null;
      this.loading = false;
    },
    async submit() {
      this.loading = true;
      try {
        let formData = new FormData();
        formData.append("company_id", this.$auth.user.company_id);
        formData.append("name", this.payload.name);
        formData.append("description", this.payload.description);
        formData.append("user_id", this.user_id);
        formData.append("number", this.payload.number);

        await this.$axios.post(this.endpoint, formData);

        this.snackbar = true;
        this.snackbar_message = "Contact Details are Created";

        setTimeout(() => {
          this.close();
          this.$emit("response", "Contact Details are Created");
        }, 1000);
      } catch (error) {
        console.log(error);

        this.errorResponse = error?.response?.data?.message || "Unknown error";
        this.loading = false;
      }
    },
  },
};
</script>
