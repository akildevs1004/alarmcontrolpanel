<template>
  <NoAccess v-if="!can('operators_view')" />
  <div v-else>
    <div class="text-center mt-0">
      <v-snackbar v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogInquiryToQuotation" max-width="1000px">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense> Inquiry To Quotation</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogInquiryToQuotation = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <EditQuotation
            :key="key"
            :editId="editId"
            :inquiry_to_quotation="inquiry_to_quotation"
            :item="item"
            :editable="editable"
            @closeDialog="closeProductDialog"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="newProductDialog" max-width="800px">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense>
            {{ editId ? "Update" : "New" }} Inquiry Information</span
          >
          <v-spacer></v-spacer>
          <v-icon @click="newProductDialog = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <EditInquiry
            :key="key"
            :editId="editId"
            :item="item"
            :editable="editable"
            @closeDialog="closeProductDialog"
          />
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-row class="mt-0 pt-0">
      <v-col class="mt-0 pt-0">
        <v-card elevation="2" class="mt-0">
          <v-toolbar class="mb-2" dense flat>
            <v-toolbar-title> <h4>Customer Inquiries</h4></v-toolbar-title>
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
                v-model="commonSearch"
                label="Search "
                dense
                outlined
                type="text"
                append-icon="mdi-magnify"
                clearable
                hide-details
              ></v-text-field
            ></span>

            <span style="width: 200px; margin-left: 10px">
              <CustomFilter
                @filter-attr="filterAttr"
                v-if="displayDateFilter"
                style="float: right; padding-top: 5px; z-index: 9"
                :default_date_from="date_from"
                :default_date_to="date_to"
                :defaultFilterType="1"
                :height="'40px'"
              />
            </span>

            <span style="width: 100px; margin-left: 10px; margin-top: 5px">
              <v-btn @click="getDataFromApi()" small dense color="primary"
                >Submit</v-btn
              >
            </span>

            <v-btn
              v-if="can('operators_create')"
              title="Change Request"
              x-small
              :ripple="false"
              text
              @click="addItem()"
            >
              <v-icon class="">mdi mdi-plus-circle</v-icon>
            </v-btn>
          </v-toolbar>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mt-0">
      <v-col class="mt-0">
        <v-card elevation="2" class="mt-0">
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
              itemsPerPageOptions: [10, 20, 50, 100, 500, 1000],
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
            <template v-slot:item.first_name="{ item }">
              {{ caps(item.first_name) }}
              {{ caps(item.last_name) }}
            </template>
            <template v-slot:item.building_name="{ item }">
              {{ item.building_name }}
              <div class="secondary-value">
                {{ item.building_type.name }}
              </div> </template
            ><template v-slot:item.contact_number="{ item }">
              {{ item.contact_number }}
              <div class="secondary-value">{{ item.whatsapp_number }}</div>
            </template>
            <template v-slot:item.quotation="{ item }">
              <div
                v-if="item.quotation"
                @click="viewQuotation(item)"
                style="cursor: pointer"
              >
                {{ item.quotation ? item.quotation.quotation_id : "---" }}
              </div>
              <div v-else>---</div>
              <div class="secondary-value">{{ item.updated_datetime }}</div>
            </template>
            <template v-slot:item.options="{ item }">
              <v-menu bottom left>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark-2 icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list width="150" dense>
                  <v-list-item
                    v-if="can('operators_view')"
                    @click="viewItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-eye </v-icon>
                      View
                    </v-list-item-title>
                  </v-list-item>

                  <v-list-item
                    @click="editItem(item)"
                    v-if="can('operators_edit') && !item.quotation"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-pencil </v-icon>
                      Edit
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    @click="convertToQuotation(item)"
                    v-if="can('operators_edit') && !item.quotation"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="blue" small> mdi-cash </v-icon>
                      Convert QTN
                    </v-list-item-title>
                  </v-list-item>
                  <!-- <v-list-item
                    v-if="can('operators_delete')"
                    @click="deleteItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="error" small> mdi-delete </v-icon>
                      Delete
                    </v-list-item-title>
                  </v-list-item> -->
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EditInquiry from "../../components/Alarm/EditInquiry.vue";
import EditQuotation from "../../components/Alarm/EditQuotation.vue";

export default {
  components: {
    EditInquiry,
    EditQuotation,
  },
  data: () => ({
    dialogQuotation: false,
    inquiry_to_quotation: null,
    dialogInquiryToQuotation: false,
    displayDateFilter: false,
    date_from: null,
    date_to: null,
    dialogSecurityCustomers: false,
    editId: null,
    item: null,
    editable: false,
    key: 1,
    viewCustomerId: null,
    commonSearch: "",
    perPage: 10,
    cumulativeIndex: 1,
    currentPage: 1,
    branchesList: [],
    isCompany: true,
    tableHeight: 750,
    id: "",

    newProductDialog: false,
    dialogViewCustomer: false,
    totalRowsCount: 0,

    snack: false,
    snackColor: "",
    snackText: "",
    departments: [],
    Model: "Log",
    security_id: null,
    endpoint: "device_product_services",
    payload: {},
    loading: true,
    browserHeight: 700,

    data: [],
    headers: [
      {
        text: "#",
        value: "sno",
      },
      {
        text: "Business Source",
        value: "business_source.name",
      },
      {
        text: "Cusotmer Name",
        value: "first_name",
      },
      {
        text: "Alarm Type",
        value: "device_type",
      },
      {
        text: "Sensor Count",
        value: "sensor_count",
      },
      {
        text: "Business/Building Name",
        value: "building_name",
      },
      // {
      //   text: "Type of Business",
      //   value: "building_type.name",
      // },
      {
        text: "Contact Number",
        value: "contact_number",
      },
      // {
      //   text: "Whatsapp Number",
      //   value: "whatsapp_number",
      // },
      {
        text: "Email",
        value: "email",
      },
      {
        text: "Location",
        value: "address",
      },
      {
        text: "Date",
        value: "created_datetime",
      },
      {
        text: "Quotation",
        value: "quotation",
      },

      // {
      //   text: "Receiption Remarks",
      //   value: "month_amount",
      // },
      // {
      //   text: "Customer Remarks",
      //   value: "month_amount",
      // },
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
    options: { perPage: 10 },
    errors: [],
    snackbar: false,
    branchesList: [],
    branch_id: "",

    responseStatusColor: "",
    response: "",
    buildingTypes: [],
    _id: null,
    isBackendRequestOpen: false,
  }),
  computed: {},
  mounted() {
    this.tableHeight = window.innerHeight - 210;
    window.addEventListener("resize", () => {
      this.tableHeight = window.innerHeight - 210;
    });

    this.getDataFromApi();
  },
  created() {
    this._id = 4; //this.$route.params.id;
    this.loading = true;

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }
    try {
      if (window) this.browserHeight = window.innerHeight - 70;
    } catch (e) {}
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
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },

    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      //this.getDataFromApi();
    },
    closeProductDialog() {
      this.dialogInquiryToQuotation = false;
      this.newProductDialog = false;
      this.dialogSecurityCustomers = false;
      this.getDataFromApi();
    },

    viewQuotation(item) {
      // this.editId = item.quotation.id;
      // this.editable = false;
      // this.key += 1;
      // this.item = item.quotation;
      // this.viewCustomerId = null;

      // this.inquiry_to_quotation = null;
      // this.dialogInquiryToQuotation = true;
      if (item.quotation.id) {
        let url = process.env.BACKEND_URL;

        url += "/quotation_print_pdf?company_id=" + this.$auth.user.company_id;
        url += "&type=print";
        url += "&quotation_id=" + item.quotation.id;

        window.open(url, "_blank");
      }
    },

    addItem() {
      this.editId = null;
      this.editable = true;
      this.key += 1;
      this.item = null;
      this.viewCustomerId = null;
      this.inquiry_to_quotation = null;

      this.newProductDialog = true;
    },
    viewItem(item) {
      this.editId = item.id;
      this.editable = false;
      this.viewCustomerId = item.id;
      this.key += 1;
      this.item = item;
      this.inquiry_to_quotation = null;

      this.newProductDialog = true;
    },
    // viewItem2(item) {
    //   this.$router.push("/alarm/view-customer/" + item.id);
    // },
    editItem(item) {
      this.editable = true;
      this.editId = item.id;
      this.key += 1;
      this.item = item;
      this.inquiry_to_quotation = null;
      this.newProductDialog = true;
    },

    convertToQuotation(item) {
      this.editId = null;
      this.editable = true;
      this.key += 1;
      this.item = null;
      this.viewCustomerId = null;

      this.inquiry_to_quotation = item;
      this.dialogInquiryToQuotation = true;
    },

    deleteItem(item) {
      if (confirm("Are you sure want to delete  ?")) {
        this.loading = true;
        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            id: item.id,
          },
        };

        this.$axios.delete(`sales_inquiry/${item.id}`).then(({ data }) => {
          this.snackbar = true;
          this.response = "Product Service Deleted Successfully";
          this.getDataFromApi();
          this.loading = false;
        });
      }
    },

    getDataFromApi(url = "", filter_column = "", filter_value = "") {
      console.log(this.date_from, this.date_to);

      if (this.isBackendRequestOpen) return false;
      this.isBackendRequestOpen = true;

      url = this.endpoint;

      this.newCustomerDialog = false;
      if (this.browserHeight > 600) this.options.itemsPerPage = 20;

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
          common_search: this.commonSearch || null,
          date_from: this.date_from,
          date_to: this.date_to,

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
        this.$axios
          .get("sales_inquiry", this.payloadOptions)
          .then(({ data }) => {
            this.isBackendRequestOpen = false;
            this.data = data.data;
            this.total = data.total;
            this.loading = false;
            this.totalRowsCount = data.total;
            //--------------------
            let today = new Date();
            if (this.data.length > 0) {
              today = new Date(this.data[0].created_datetime);
            }

            let monthObj = this.$dateFormat.monthStartEnd(today);
            this.date_from = monthObj.first;
            this.date_to = monthObj.last;
            this.displayDateFilter = true;
            this.isPageload = false;
          });
      } catch (e) {
        console.log(e);
        this.loading = false;
      }
    },
  },
};
</script>
