<template>
  <div v-if="can(`change_request`)">
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" color="purple" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogViewCustomer" width="110%">
      <AlarmViewCustomer @closeCustomerDialog="closeCustomerDialog" />
      <!--<v-card>
       <v-card-title dark class="popup_background_noviolet">
          <span dense> New Customer</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogViewCustomer = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title> 
        <v-card-text>  <AlarmViewCustomer @closeCustomerDialog="closeCustomerDialog" /></v-card-text>
      </v-card>
      -->
    </v-dialog>
    <v-dialog v-model="newCustomerDialog" max-width="900px">
      <v-card>
        <v-card-title dark class="popup_background">
          <span dense> New Customer</span>
          <v-spacer></v-spacer>
          <v-icon @click="newCustomerDialog = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <AlarmNewCustomer @closeDialog="getDataFromApi()" />
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-row>
      <v-col>
        <v-card elevation="0" class="mt-2">
          <v-toolbar class="mb-2 white--text" color="white" dense flat>
            <v-toolbar-title>
              <span style="color: black"> Customers</span></v-toolbar-title
            >
            <!-- <v-tooltip top color="primary">
                <template v-slot:activator="{ on, attrs }"> -->
            <v-btn
              title="Reload"
              dense
              class="ma-0 px-0"
              x-small
              :ripple="false"
              @click="getDataFromApi"
              text
            >
              <v-icon class="ml-2" dark>mdi mdi-reload</v-icon>
            </v-btn>
            <!-- </template>
                <span>Reload</span>
              </v-tooltip> -->

            <v-spacer></v-spacer>
            <span style="width: 180px"
              ><v-text-field
                style="padding-top: 7px"
                height="20"
                class="employee-schedule-search-box"
                @input="getDataFromApi()"
                v-model="commonSearch"
                label="Search (min 3)"
                dense
                outlined
                type="text"
                append-icon="mdi-magnify"
                clearable
                hide-details
              ></v-text-field
            ></span>

            <v-btn
              title="Change Request"
              x-small
              :ripple="false"
              text
              @click="newCustomerDialog = true"
            >
              <v-icon class="">mdi mdi-plus-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
              <v-btn v-bind="attrs" text @click="snack = false"> Close </v-btn>
            </template>
          </v-snackbar>
          <v-data-table
            dense
            :headers="headers"
            :items="data"
            :loading="loading"
            :options.sync="options"
            :footer-props="{
              itemsPerPageOptions: [10, 50, 100, 500, 1000],
            }"
            class="elevation-1"
            :server-items-length="totalRowsCount"
            fixed-header
            :height="tableHeight"
            :disable-sort="true"
          >
            <template v-slot:item.sno="{ item, index }">
              {{
                currentPage
                  ? (currentPage - 1) * perPage +
                    (cumulativeIndex + data.indexOf(item))
                  : ""
              }}
            </template>
            <template
              v-slot:item.building_name="{ item, index }"
              style="width: 300px"
            >
              <v-row no-gutters>
                <v-col
                  style="
                    padding: 5px;
                    padding-left: 0px;
                    width: 50px;
                    max-width: 50px;
                  "
                >
                  <v-img
                    style="
                      border-radius: 10%;
                      height: auto;
                      width: 50px;
                      max-width: 50px;
                    "
                    :src="
                      item.profile_picture
                        ? item.profile_picture
                        : '/no-business_profile.png'
                    "
                  >
                  </v-img>
                </v-col>
                <v-col style="padding: 10px">
                  <div style="font-size: 13px">
                    {{ item.building_name || "" }}
                  </div>
                  <small style="font-size: 12px; color: #6c7184">
                    {{ item.house_number }}, {{ item.street_number
                    }}{{ item.area }}{{ item.city }}
                  </small>
                </v-col>
              </v-row>
            </template>

            <template v-slot:item.building_type="{ item }">
              <div>
                {{
                  buildingTypes[item.building_type_id]
                    ? buildingTypes[item.building_type_id].name
                    : "---"
                }}
              </div>
              <small style="font-size: 12px; color: #6c7184">
                {{ item.landmark }}
              </small>
            </template>
            <template v-slot:item.area="{ item }">
              {{ item.house_number }}, {{ item.street_number }}{{ item.area
              }}{{ item.city }}
            </template>
            <template v-slot:item.options="{ item }">
              <v-menu bottom left>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark-2 icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list width="120" dense>
                  <v-list-item
                    v-if="can('device_notification_contnet_view')"
                    @click="viewItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-eye </v-icon>
                      View
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    v-if="can('device_notification_contnet_view')"
                    @click="viewItem2(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-eye </v-icon>
                      View
                    </v-list-item-title>
                  </v-list-item>
                  <!-- <v-list-item
                    v-if="can('device_notification_contnet_edit')"
                    @click="editItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-pencil </v-icon>
                      Edit
                    </v-list-item-title>
                  </v-list-item> -->
                  <v-list-item
                    v-if="can('device_notification_contnet_delete')"
                    @click="deleteItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="error" small> mdi-delete </v-icon>
                      Delete
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
  <NoAccess v-else />
</template>

<script>
import AlarmNewCustomer from "../../components/Alarm/NewCustomer.vue";
import AlarmViewCustomer from "../../components/Alarm/ViewCustomer.vue";

export default {
  components: {
    AlarmNewCustomer,
    AlarmViewCustomer,
  },
  data: () => ({
    perPage: 10,
    cumulativeIndex: 1,
    currentPage: 1,
    branchesList: [],
    isCompany: true,
    tableHeight: 750,
    id: "",

    newCustomerDialog: false,
    dialogViewCustomer: false,
    totalRowsCount: 0,

    snack: false,
    snackColor: "",
    snackText: "",
    departments: [],
    Model: "Log",
    endpoint: "customers",
    payload: {},
    loading: true,
    headers: [
      {
        text: "#",
        value: "sno",
      },
      {
        text: "Building Name",
        value: "building_name",
      },
      {
        text: "Type",
        value: "building_type",
      },

      // {
      //   text: "Location",
      //   value: "area",
      // },
      {
        text: "Member Since",
        value: "created_date",
      },
      {
        text: "Renewal Date",
        value: "end_date",
      },
      {
        text: "Burglary",
        value: "burglary",
      },
      {
        text: "Medical",
        value: "medial",
      },
      {
        text: "Fire",
        value: "fire",
      },
      {
        text: "Water",
        value: "water",
      },
      {
        text: "Temp",
        value: "temperature",
      },
      {
        text: "Options",
        value: "options",
      },
    ],
    ids: [],

    data: [],
    devices: [],
    total: 0,
    pagination: {
      current: 1,
      total: 0,
      itemsPerPage: 1000,
    },
    payloadOptions: {},
    options: {
      current: 1,
      total: 0,
      itemsPerPage: 10,
    },
    errors: [],
    snackbar: false,
    branchesList: [],
    branch_id: "",

    responseStatusColor: "",
    response: "",
    buildingTypes: [],
    _id: null,
  }),
  computed: {},
  mounted() {
    this.tableHeight = window.innerHeight - 270;
    window.addEventListener("resize", () => {
      this.tableHeight = window.innerHeight - 270;
    });
  },
  created() {
    this._id = 4; //this.$route.params.id;
    this.loading = true;

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }

    // let branch_header = [
    //   {
    //     text: "Branch",
    //     value: "branch",
    //   },
    // ];

    // this.headers.splice(2, 0, ...branch_header);

    // try {
    //   const { data } = this.$axios.get(`branches_list`, {
    //     params: {
    //       per_page: 100,
    //       company_id: this.$auth.user.company_id,
    //     },
    //   });
    //   this.branchesList = data;
    // } catch (error) {
    //   // Handle the error
    //   console.error("Error fetching branch list", error);
    // }

    this.getDataFromApi();
    this.getBuildingTypes();
  },
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  methods: {
    // updateStatus(item) {
    //   this.$axios
    //     .post("update-change-request/" + item.id, item)
    //     .then(({ data }) => {
    //       this.snackbar = true;
    //       this.responseStatusColor = "green";
    //       this.response = "Request has been updated succussfully.";
    //       setTimeout(() => {
    //         this.snackbar = false;
    //         this.response = "";
    //         this.responseStatusColor = "";
    //       }, 2000);
    //     })
    //     .catch(({ response }) => {
    //       if (!response) {
    //         return false;
    //       }
    //       let { status, data, statusText } = response;
    //       this.response = status == 422 ? data.message : statusText;
    //       this.responseStatusColor = "red";
    //     });
    // },
    // getRelatedColor(item) {
    //   let colors = {
    //     P: "purple",
    //     R: "red",
    //     A: "green",
    //   };
    //   return colors[item.status];
    // },
    // handleDatesFilter(dates) {
    //   if (dates.length > 1) {
    //     this.getDataFromApi(this.endpoint, "dates", dates);
    //   }
    // },

    // applyFilters(item, columnValue) {
    //   this.getDataFromApi(item, columnValue);
    //   this.from_menu_filter = false;
    //   this.to_menu_filter = false;
    // },
    // toggleFilter() {
    //   // this.filters = {};
    //   this.isFilter = !this.isFilter;
    // },
    // clearFilters() {
    //   this.filters = {};

    //   this.isFilter = false;
    //   this.getDataFromApi();
    // },
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },

    closeCustomerDialog() {
      this.dialogViewCustomer = false;
    },
    getBuildingTypes() {
      if (this.$store.state.storeAlarmControlPanel?.BuildingTypes) {
        this.buildingTypes =
          this.$store.state.storeAlarmControlPanel.BuildingTypes;
      }
      // this.$axios
      //   .get(`building_types`, {
      //     params: {
      //       company_id: this.$auth.user.company_id,
      //     },
      //   })
      //   .then(({ data }) => {
      //     this.buildingTypes = data;
      //   });
    },
    viewItem(item) {
      this.dialogViewCustomer = true;
    },
    viewItem2(item) {
      this.$router.push("/alarm/view-customer");
    },
    // getDate() {
    //   const date = new Date();
    //   const year = date.getFullYear();
    //   const month = (date.getMonth() + 1).toString().padStart(2, "0");
    //   const day = date.getDate().toString().padStart(2, "0");
    //   return `${year}-${month}-${day}`;
    // },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    // getDataFromApi(item = "", filter_value = "") {
    //   this.filters = {};
    //   if (
    //     filter_value != "" &&
    //     filter_value.length <= 2 &&
    //     item.field_type == "text"
    //   ) {
    //     this.snack = true;
    //     this.snackColor = "error";
    //     this.snackText = "Minimum 3 Characters to filter ";
    //     this.loading = false;
    //     return false;
    //   }
    //   this.getDataFromApi(this.endpoint, item.value, filter_value);
    // },
    getDataFromApi(url = "", filter_column = "", filter_value = "") {
      url = this.endpoint;
      console.log(url);
      this.newCustomerDialog = false;
      const { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";

      this.payloadOptions = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          per_page: itemsPerPage,
          company_id: this.$auth.user.company_id,
          // branch_id: this.branch_id,
          ...this.payload,
        },
      };
      if (filter_column != "")
        this.payloadOptions.params[filter_column] = filter_value;
      this.loading = true;

      this.currentPage = page;
      this.perPage = itemsPerPage;
      try {
        this.$axios.get(url, this.payloadOptions).then(({ data }) => {
          this.data = data.data;
          this.total = data.total;
          this.loading = false;
          this.totalRowsCount = data.total;
        });
      } catch (e) {
        console.log(e);
        this.loading = false;
      }
    },
  },
};
</script>
