<template>
  <v-dialog v-model="dialog" width="500">
    <template v-slot:activator="{ on, attrs }">
      <div v-bind="attrs" v-on="on">
        <v-icon color="blue" small> mdi-pencil </v-icon>
        Edit
      </div>
    </template>

    <v-card>
      <div class="text-center ma-2">
        <v-snackbar
          v-model="snackbar"
          top="top"
          color="secondary"
          elevation="24"
        >
          {{ snackbar_message }}
        </v-snackbar>
      </div>
      <v-toolbar flat class="grey2222 lighten-3" dense>
        Edit {{ model }} <v-spacer></v-spacer><AssetsButtonClose @close="close"
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
                label="Name"
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
                label="description"
              ></v-text-field>
            </v-col>

            <v-col cols="12" v-if="errorResponse">
              <span class="red--text">{{ errorResponse }}</span>
            </v-col>
            <v-col cols="12" class="text-right">
              <v-btn small color="grey" class="white--text" dark @click="close">
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
</template>
<script>
export default {
  props: ["item", "endpoint", "model", "user_id"],
  data() {
    return {
      snackbar_message: "",
      snackbar: false,
      payload: {
        name: "",
        description: "",
        document: null,
      },
      dialog: false,
      loading: false,
      successResponse: null,
      errorResponse: null,
    };
  },
  created() {
    this.payload = {
      name: this.item.name,
      description: this.item.description,
      number: this.item.number,
    };
  },
  methods: {
    close() {
      this.dialog = false;
      this.loading = false;
      this.errorResponse = null;
    },
    async submit() {
      this.loading = true;
      let formData = new FormData();
      formData.append("name", this.payload.name);
      formData.append("description", this.payload.description);
      formData.append("user_id", this.user_id);

      formData.append("number", this.payload.number);

      try {
        await this.$axios.post(`${this.endpoint}/${this.item.id}`, formData);

        this.snackbar = true;
        this.snackbar_message = "Contact Details are Updated";

        setTimeout(() => {
          this.close();
          this.$emit("response", "Contact has been updated");
        }, 1000);
      } catch (error) {
        this.errorResponse = error?.response?.data?.message || "Unknown error";
        this.loading = false;
      }
    },
  },
};
</script>
