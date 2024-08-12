<template>
  <div max-width="100%">
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogNotes" max-width="700px">
      <v-card>
        <v-card-title dark class="popup_background">
          <span dense>Notes</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogNotes = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          {{ selectedItem ? selectedItem.notes : "---" }}
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card flat>
      <v-card-text style="padding: 0px">
        <v-row>
          <v-col>
            <v-data-table
              :style="
                contact_id
                  ? 'height: 300px; overflow: auto'
                  : 'height: 600px; overflow: auto'
              "
              :headers="headers"
              :items="items"
              :server-items-length="totalRowsCount"
              :loading="loading"
              :options.sync="options"
              :footer-props="{
                itemsPerPageOptions: [100, 500, 1000],
              }"
              class="elevation-0"
            >
              <template v-slot:item.sno="{ item, index }">
                {{
                  currentPage
                    ? (currentPage - 1) * perPage +
                      (cumulativeIndex + items.indexOf(item))
                    : "-"
                }}
              </template>

              <template v-slot:item.date="{ item, index }">
                {{ $dateFormat.format4(item.created_datetime) }}
              </template>
              <template v-slot:item.security="{ item, index }">
                {{
                  item.security
                    ? item.security.first_name + " " + item.security.last_name
                    : "---"
                }}
              </template>
              <template v-slot:item.customer="{ item, index }">
                {{
                  item.contact
                    ? item.contact.first_name + " " + item.contact.last_name
                    : "---"
                }}
                <div class="secondary-value">
                  {{ item.contact ? caps(item.contact.address_type) : "---" }}
                </div>
              </template>
              <template v-slot:item.phone="{ item, index }">
                {{ item.contact ? item.contact.phone1 : "---" }}
              </template>

              <template v-slot:item.notes="{ item, index }">
                <span
                  class="d-inline-block text-truncate"
                  style="max-width: 100px"
                  @click="displayNotes(item)"
                  >{{ item.notes }}</span
                >
              </template>

              <template v-slot:item.event_status="{ item, index }">
                <div style="color: red" v-if="item.event_status != 'Closed'">
                  {{ item.event_status }}
                </div>
                <div style="color: green" v-else>
                  {{ item.event_status }}
                </div>
              </template>
            </v-data-table>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import SecurityGoogleMap from "../../Alarm/SecurityDashboard/SecurityGoogleMap.vue";
import SecurityBuildingPhotos from "../../Alarm/SecurityDashboard/SecurityBuildingPhotos.vue";
export default {
  components: { SecurityGoogleMap, SecurityBuildingPhotos },
  props: ["alarmId", "customer", "contact_type", "contact_id"],
  data: () => ({
    snackbar: false,

    dialogNotes: false,
    selectedItem: null,
    loading: false,
    tab: "",
    event_payload: {},
    error_message: "",
    errors: [],
    response: "",
    options: { perPage: 10 },
    cumulativeIndex: 1,
    perPage: 10,
    currentPage: 1,
    totalRowsCount: 0,
    headers: [
      { text: "#", value: "sno", sortable: false },
      { text: "Date", value: "date", sortable: false },
      { text: "Security", value: "security", sortable: false },
      { text: "Customer", value: "customer", sortable: false },
      { text: "Phone", value: "phone", sortable: false },
      { text: "Call satus", value: "call_status", sortable: false },

      { text: "Response", value: "response", sortable: false },

      { text: "Notes", value: "notes", sortable: false },
      { text: "Event Status", value: "event_status", sortable: false },
      // { text: "Status", value: "status", sortable: false },
    ],
    items: [],
    globalContactDetails: null,
    key: 1,
  }),
  computed: {},
  mounted() {},
  created() {},
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  methods: {
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },
    displayNotes(item) {
      this.selectedItem = item;
      this.dialogNotes = true;
    },
    getDataFromApi() {
      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      if (itemsPerPage) this.perPage = itemsPerPage;
      if (page) this.currentPage = page;

      this.loading = true;
      let options = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          customer_id: this.customer.customer_id,
          contact_id: this.contact_id,
          alarm_id: this.alarmId,
        },
      };

      // try {
      this.$axios.get(`get_alarm_events_notes`, options).then(({ data }) => {
        //this.key += 1;
        this.items = data.data;
        this.totalRowsCount = data.total;

        this.loading = false;
      });
      //   } catch (e) {}
    },
    submit() {
      let customer = new FormData();

      for (const key in this.event_payload) {
        if (this.event_payload[key] != "")
          customer.append(key, this.event_payload[key]);
      }
      customer.append("security_id", this.$auth.user.security.id);
      customer.append("company_id", this.$auth.user.company_id);
      customer.append("customer_id", this.customer.id);
      customer.append("contact_id", this.globalContactDetails.id);
      customer.append("alarm_id", this.alarmId);
      customer.append("contact_type", this.contact_type);

      if (this.customer.id) {
        this.$axios
          .post("/customer_add_event_notes", customer)
          .then(({ data }) => {
            this.response = "";
            //this.loading = false;

            if (!data.status) {
              this.errors = [];
              if (data.errors) this.errors = data.errors;
              this.color = "red";
            } else {
              this.error_message = "";
              this.color = "background";
              this.errors = [];
              this.snackbar = true;
              this.response = "Alarm Notes Details Created successfully";

              this.event_payload = {};
              // this.$emit("closeDialog");
              //if (this.globalContactDetails)

              this.getDataFromApi();
            }
          })
          .catch((e) => {
            if (e.response.data.errors) {
              this.errors = e.response.data.errors;
              this.color = "red";

              this.snackbar = true;
              //this.response = e.response.data.message;
              if (this.errors.message) {
                this.color = "red";
                this.snackbar = true;
                this.response = this.errors.messag;
                this.error_message = this.errors.message;
              }

              // this.errors.forEach((element) => {
              //   console.log(element);
              //   this.color = "red";
              //   this.snackbar = true;
              //   this.response = element[0];
              //   this.error_message = element[0];
              //   return false;
              // });
            }
          });
      }
    },
  },
};
</script>
