<template>
  <div>
    <div>
      <v-toolbar flat dense class="mb-5">
        Contacts
        <v-icon color="primary" right @click="getDataFromApi()"
          >mdi-reload</v-icon
        >

        <v-spacer></v-spacer>

        <UserContactsCreate
          v-if="user_id && editable"
          :user_id="user_id"
          :model="Model"
          :endpoint="endpoint"
          @response="getDataFromApi"
        />
      </v-toolbar>
    </div>

    <v-data-table
      dense
      :headers="headers"
      :items="security_documents"
      :loading="loading"
      :options.sync="options"
      :footer-props="{
        itemsPerPageOptions: [100, 500, 1000],
      }"
    >
      <!-- <template v-slot:top>
        <v-toolbar flat dense class="mb-5">
          {{ Model }}s
          <v-icon color="primary" right @click="getDataFromApi()"
            >mdi-reload</v-icon
          >
          <v-spacer></v-spacer>
        </v-toolbar>
      </template> -->

      <template v-slot:item.options="{ item }">
        <v-menu width="120" style="min-width: 100px !important">
          <template v-slot:activator="{ on, attrs }">
            <div class="text-center">
              <v-btn dark-2 icon v-bind="attrs" v-on="on">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </div>
          </template>
          <v-list width="120" dense style="min-width: 100px !important">
            <v-list-item>
              <v-list-item-title>
                <UserContactsView
                  :user_id="user_id"
                  :model="Model"
                  :endpoint="endpoint"
                  :item="item"
                  @response="getDataFromApi"
                />
              </v-list-item-title>
            </v-list-item>
            <v-list-item v-if="editable">
              <v-list-item-title>
                <UserContactsEdit
                  :user_id="user_id"
                  :model="Model"
                  :endpoint="endpoint"
                  :item="item"
                  @response="getDataFromApi"
                />
              </v-list-item-title>
            </v-list-item>
            <v-list-item v-if="editable">
              <v-list-item-title>
                <UserContactsDelete
                  :id="item.id"
                  :endpoint="endpoint"
                  @response="getDataFromApi"
                />
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </v-data-table>
  </div>
</template>

<script>
let date = new Date();

let d = date.getDate();
let m = (date.getMonth() + 1).toString().padStart(2, "0");
let y = date.getFullYear();
let currentDate = y + "-" + m + "-" + d;

export default {
  props: ["user_id", "editable"],

  data: () => ({
    Model: "Contact",
    endpoint: "user-contacts",
    currentDate,
    filters: {},
    options: {},
    loading: false,
    response: "",
    security_documents: [],
    errors: [],
    headers: [
      {
        text: "Contact Name",
        value: "name",
        sortable: false,
      },

      {
        text: "Number",
        value: "number",
        sortable: false,
      },
      {
        text: "Description",
        value: "description",
        sortable: false,
      },
      {
        text: "Created At",
        value: "created_datetime",
        sortable: false,
      },
      {
        text: "Action",
        align: "center",
        sortable: false,
        value: "options",
        width: "100px",
      },
    ],
    componentKey: 1,
  }),

  async created() {
    this.getDataFromApi();
  },
  mounted() {},
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  methods: {
    getRandomId() {
      return ++this.componentKey;
    },
    async getDataFromApi() {
      let config = {
        params: {
          company_id: this.$auth.user.company_id,
          user_id: this.user_id,
        },
      };
      this.loading = true;
      let { data } = await this.$axios.get(this.endpoint, config);
      this.loading = false;

      this.security_documents = data.data;
    },
  },
};
</script>
