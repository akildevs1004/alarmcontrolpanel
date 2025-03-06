<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <!-- <v-dialog v-model="dialogEditAutomation" width="600px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Payment Information </span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="
              closeDialog();
              dialogEditAutomation = false;
            "
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlarmEditPayments
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :editId="editId"
              :editItem="editItemobject"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog> -->
    <v-row>
      <v-col>
        <v-row class="mt-0">
          <v-col cols="12" class="text-right" style="width: 600px">
            <v-card elevation="2" style="z-index: 9"
              ><v-card-text>
                <v-row>
                  <v-col class="text-left"><h3>Invoices/Payments</h3></v-col>
                  <v-col style="max-width: 30px"
                    ><v-icon
                      loading="true"
                      @click="getDataFromApi(0)"
                      class="mt-2 mr-2"
                      >mdi-reload</v-icon
                    ></v-col
                  >
                  <v-col style="max-width: 250px">
                    <v-text-field
                      style="padding-top: 7px; float: right"
                      height="20"
                      class="employee-schedule-search-box"
                      @input="getDataFromApi(0)"
                      v-model="commonSearch"
                      label="Invoice Number"
                      placeholder="Invoice Number"
                      dense
                      outlined
                      type="text"
                      append-icon="mdi-magnify"
                      clearable
                      hide-details
                    ></v-text-field
                  ></v-col>
                  <v-col style="max-width: 250px">
                    <v-autocomplete
                      class="employee-schedule-search-box"
                      style="
                        padding-top: 7px;
                        z-index: 999;

                        min-width: 100%;
                      "
                      height="20px"
                      outlined
                      @change="getDataFromApi(0)"
                      v-model="filter_customer_id"
                      dense
                      :items="[
                        { building_name: 'All Customers', id: null },
                        ...customersList,
                      ]"
                      item-text="building_name"
                      item-value="id"
                      hide-details
                    ></v-autocomplete>
                  </v-col>
                  <v-col style="max-width: 150px">
                    <v-select
                      label="Mode"
                      class="employee-schedule-search-box"
                      style="
                        padding-top: 7px;
                        z-index: 999;

                        min-width: 100%;
                      "
                      height="20px"
                      outlined
                      @change="getDataFromApi(0)"
                      v-model="filter_mode_of_payment"
                      dense
                      :items="[
                        { value: null, text: 'All' },
                        { value: 'Online', text: 'Online' },
                        { value: 'Cheque', text: 'Cheque' },
                        { value: 'Cash', text: 'Cash' },
                      ]"
                      item-text="text"
                      item-value="value"
                      hide-details
                    ></v-select>
                  </v-col>
                  <v-col style="max-width: 150px">
                    <v-select
                      label="Status"
                      class="employee-schedule-search-box"
                      style="
                        padding-top: 7px;
                        z-index: 999;

                        min-width: 100%;
                      "
                      height="20px"
                      outlined
                      @change="getDataFromApi(0)"
                      v-model="filter_status"
                      dense
                      :items="[
                        { value: null, text: 'All' },
                        { value: 'Received', text: 'Received' },
                        { value: 'Pending', text: 'Pending' },
                        { value: 'Cancelled', text: 'Cancelled' },
                      ]"
                      item-text="text"
                      item-value="value"
                      hide-details
                    ></v-select>
                  </v-col>

                  <v-col style="max-width: 250px">
                    <CustomFilter
                      style="float: left; padding-top: 5px; z-index: 999"
                      @filter-attr="filterAttr"
                      :default_date_from="date_from"
                      :default_date_to="date_to"
                      :defaultFilterType="1"
                      :height="'30px'"
                  /></v-col>
                  <!-- <v-col
                    style="
                      margin-top: 10px;
                      margin-left: -16px;
                      max-width: 100px;
                    "
                  >
                    <v-menu bottom right style="z-index: 9999">
                      <template v-slot:activator="{ on, attrs }">
                        <span v-bind="attrs" v-on="on">
                          <v-icon dark-2 icon color="violet"
                            >mdi-printer-outline</v-icon
                          >
                          Print
                        </span>
                      </template>
                      <v-list width="100" dense>
                        <v-list-item @click="downloadOptions(`print`)">
                          <v-list-item-title style="cursor: pointer">
                            <v-row>
                              <v-col cols="5"
                                ><img
                                  style="padding-top: 5px"
                                  src="/icons/icon_print.png"
                                  class="iconsize"
                              /></v-col>
                              <v-col
                                cols="7"
                                style="padding-left: 0px; padding-top: 19px"
                              >
                                Print
                              </v-col>
                            </v-row>
                          </v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="downloadOptions('download')">
                          <v-list-item-title style="cursor: pointer">
                            <v-row>
                              <v-col cols="5"
                                ><img
                                  style="padding-top: 5px"
                                  src="/icons/icon_pdf.png"
                                  class="iconsize"
                              /></v-col>
                              <v-col
                                cols="7"
                                style="padding-left: 0px; padding-top: 19px"
                              >
                                PDF
                              </v-col>
                            </v-row>
                          </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click="downloadOptions('excel')">
                          <v-list-item-title style="cursor: pointer">
                            <v-row>
                              <v-col cols="5"
                                ><img
                                  style="padding-top: 5px"
                                  src="/icons/icon_excel.png"
                                  class="iconsize"
                              /></v-col>
                              <v-col
                                cols="7"
                                style="padding-left: 0px; padding-top: 19px"
                              >
                                EXCEL
                              </v-col>
                            </v-row>
                          </v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </v-col> -->
                </v-row>
              </v-card-text></v-card
            >
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="items"
          :server-items-length="totalRowsCount"
          :loading="loading"
          :options.sync="options"
          :footer-props="{
            itemsPerPageOptions: [10, 50, 100, 500, 1000],
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
          <template v-slot:item.invoice_number="{ item, index }">
            {{ item.invoice_number }}
            <div class="secondary-value">
              {{ item.invoice_date }}
            </div>
          </template>
          <template v-slot:item.customer="{ item, index }">
            {{ item.customer.building_name }}
          </template>
          <template v-slot:item.options="{ item }">
            <v-menu bottom left v-if="isEditable">
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark-2 icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list width="120" dense>
                <v-list-item v-if="!item.received_date" @click="editItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-pencil </v-icon>
                    Edit
                  </v-list-item-title>
                </v-list-item>
                <v-list-item
                  v-if="!item.received_date"
                  @click="deleteItem(item.id)"
                >
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="red" small> mdi-delete </v-icon>
                    Delete
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// import AlarmEditPayments from "../../../components/Alarm/EditPayments.vue";

export default {
  // components: { AlarmEditPayments },

  data() {
    return {
      customer: null,
      customer_id: null,
      filter_customer_id: null,
      customersList: [],
      filterAlarmStatus: null,
      filter_mode_of_payment: null,
      filter_status: null,
      isEditable: true,
      snackbar: false,
      response: "",
      key: 1,
      editId: "",
      editItemobject: null,
      dialogEditAutomation: false,
      date_from: "",
      date_to: "",

      loading: false,
      commonSearch: "",
      options: { perPage: 10 },
      cumulativeIndex: 1,
      perPage: 10,
      currentPage: 1,
      totalRowsCount: 0,
      headers: [
        { text: "#", value: "sno", sortable: false },
        { text: "Invoice #", value: "invoice_number", sortable: false },
        { text: "Customer", value: "customer", sortable: false },

        { text: "Amount", value: "amount", sortable: false },
        { text: "Received On", value: "received_date", sortable: false },
        { text: "Mode", value: "mode_of_payment", sortable: false },

        { text: "Status", value: "status", sortable: false },

        { text: "Options", value: "options", sortable: false },
      ],
      items: [],
    };
  },
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  created() {
    let today = new Date();
    let monthObj = this.$dateFormat.monthStartEnd(today);
    this.date_from = monthObj.first;
    this.date_to = monthObj.last;
    //this.getDataFromApi();
    this.getCustomersList();
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    getCustomersList() {
      this.payloadOptions = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      try {
        this.$axios
          .get("customers-all", this.payloadOptions)
          .then(({ data }) => {
            this.customersList = data;
          });
      } catch (e) {}
    },
    closeDialog() {
      this.getDataFromApi();
      this.dialogEditAutomation = false;
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi();
    },
    editItem(item) {
      this.key = this.key + 1;
      this.editItemobject = item;
      this.dialogEditAutomation = true;
    },

    deleteItem(id) {
      if (confirm("Are you sure want to delete Payment  ?")) {
        this.loading = true;
        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            id: id,
          },
        };

        try {
          this.$axios.delete(`delete-payment`, options).then(({ data }) => {
            this.snackbar = true;
            this.response = "Payment Deleted Successfully";
            this.getDataFromApi();
            this.loading = false;
          });
        } catch (e) {}
      }
    },
    getDataFromApi() {
      this.loading = true;

      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      this.perPage = itemsPerPage;
      this.currentPage = page;
      if (!page > 0) return false;
      let options = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          date_from: this.date_from,
          date_to: this.date_to,
          common_search: this.commonSearch,
          filter_status: this.filter_status,
          filter_mode_of_payment: this.filter_mode_of_payment,
          filter_customer_id: this.filter_customer_id,
        },
      };

      try {
        this.$axios.get(`customer_payments`, options).then(({ data }) => {
          this.items = data.data;

          this.totalRowsCount = data.total;
          this.loading = false;
        });
      } catch (e) {}
    },
  },
};
</script>
