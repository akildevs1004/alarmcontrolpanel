<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-dialog v-model="dialogNewTicket" max-width="700px" style="z-index: 9999">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense> New Ticket</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogNewTicket = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <NewTicket
            :security_id="security_id"
            :customer_id="customer_id"
            :key="key"
            @close_dialog="close_dialog_reaction"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogReply" max-width="700px" style="z-index: 9999">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense> Reply to Ticket</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogReply = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <ReplyToTicket
            :key="key"
            :editId="editId"
            :editItem="editItem"
            :ticketId="ticketId"
            @close_dialog="close_dialog_reaction"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialogViewTicket"
      max-width="700px"
      style="z-index: 9999"
    >
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense> View Ticket</span>
          <v-spacer></v-spacer>
          <v-icon @click="dialogViewTicket = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>
          <ViewTicket
            :key="key"
            :editItem="editItem"
            @close_dialog="close_dialog_reaction"
            @refreshTickets="getDataFromApi"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card flat>
      <v-row>
        <v-col cols="12" class="text-right" style="padding-top: 0px">
          <v-row>
            <v-col cols="6"></v-col>
            <v-col cols="6" class="text-right" style="max-width: 660px">
              <v-row>
                <v-col cols="1" class="mt-2">
                  <v-icon @click="getDataFromApi()">mdi-refresh</v-icon>
                </v-col>
                <v-col cols="4"
                  ><v-text-field
                    style="padding-top: 7px"
                    width="200px"
                    height="20"
                    class="employee-schedule-search-box"
                    @input="getDataFromApi()"
                    v-model="commonSearch"
                    label="Common Search"
                    dense
                    outlined
                    type="text"
                    append-icon="mdi-magnify"
                    clearable
                    hide-details
                  ></v-text-field
                ></v-col>
                <v-col cols="4">
                  <CustomFilter
                    v-if="displayDateFilter"
                    style="float: right; padding-top: 5px; z-index: 9"
                    @filter-attr="filterAttr"
                    :default_date_from="date_from"
                    :default_date_to="date_to"
                    :defaultFilterType="1"
                    :height="'40px'"
                /></v-col>
                <!-- <v-col cols="2" style="width: 100px; margin-top: 10px">
                    <v-menu bottom right>
                      <template v-slot:activator="{ on, attrs }">
                        <span v-bind="attrs" v-on="on">
                          <v-icon dark-2 icon color="violet" small
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

                <v-col cols="1" class="pt-5">
                  <v-btn
                    v-if="technician_id == null"
                    title="Add Ticket"
                    x-small
                    :ripple="false"
                    text
                    @click="addItem()"
                  >
                    <v-icon class="">mdi mdi-plus-circle</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
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
            <template v-slot:item.created_datetime="{ item }">
              <div>
                {{ $dateFormat.formatDateMonthYear(item.created_datetime) }}
              </div>
            </template>
            <template v-slot:item.subject="{ item }">
              <div :title="item.subject">
                {{ item.subject.slice(0, 10) }}...
              </div>
            </template>
            <template v-slot:item.customer="{ item }">
              <div :class="item.is_read ? '' : 'bold'" v-if="item.customer">
                Customer
                <div class="secondary-value">
                  {{ item.customer.building_name }}
                </div>
              </div>
              <div
                :class="item.is_read ? '' : 'bold'"
                v-else-if="item.security"
              >
                Operator
                <div class="secondary-value">
                  {{ item.security.first_name }} {{ item.security.last_name }}
                </div>
              </div>
              <div
                :class="item.is_read ? '' : 'bold'"
                v-else-if="item.technician"
              >
                Technician
                <div class="secondary-value">
                  {{ item.technician.first_name }}
                  {{ item.technician.last_name }}
                </div>
              </div>
            </template>
            <template v-slot:item.ticket_responses="{ item }">
              <div>{{ item.responses?.length || 0 }}</div>
            </template>
            <template v-slot:item.last_active_datetime="{ item }">
              <div :class="item.is_read ? '' : 'bold'">
                {{ $dateFormat.formatDateMonthYear(item.last_active_datetime) }}
              </div>
            </template>

            <template v-slot:item.status="{ item }">
              <div>{{ item.status == 1 ? "Open" : "Closed" }}</div>
            </template>
            <template v-slot:item.closed_datetime="{ item }">
              <div v-if="item.status == 0">
                {{ $dateFormat.formatDateMonthYear(item.last_active_datetime) }}
              </div>
              <div v-else>---</div>
            </template>
            <template v-slot:item.options="{ item }">
              <v-menu bottom left>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark-2 icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list width="120" dense>
                  <v-list-item @click="addReply(item)">
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-reply</v-icon>
                      Reply
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="viewTicket(item)">
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-information</v-icon>
                      View
                    </v-list-item-title>
                  </v-list-item>
                  <!-- <v-list-item
                      v-if="can('branch_view')"
                      @click="editTicket(item)"
                    >
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-pencil </v-icon>
                        Edit
                      </v-list-item-title>
                    </v-list-item> 
                    <v-list-item
                      v-if="can('branch_edit')"
                      @click="deleteNotes(item.id)"
                    >
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="red" small> mdi-delete </v-icon>
                        Delete
                      </v-list-item-title>
                    </v-list-item>-->
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
import NewTicket from "../../components/Tickets/NewTicket.vue";
import ReplyToTicket from "../../components/Tickets/ReplyToTicket.vue";
import ViewTicket from "../../components/Tickets/ViewTicket.vue";

export default {
  components: {
    NewTicket,
    ReplyToTicket,
    ViewTicket,
  },
  props: ["customer_id", "security_id", "technician_id"],
  data() {
    return {
      displayDateFilter: false,
      dialogViewTicket: false,
      editId: null,
      editItem: null,
      ticketId: null,
      dialogReply: false,
      snackbar: false,
      response: "",
      key: "",
      eventId: "",
      dialogAddCustomerNotes: false,
      dialogNotesList: false,
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
        { text: "Requested Date", value: "created_datetime", sortable: false },
        { text: "Ticket ID", value: "id", sortable: false },

        { text: "Subject", value: "subject", sortable: false },
        { text: "Created By", value: "customer", sortable: false },
        { text: "Reply Count", value: "ticket_responses", sortable: false },

        {
          text: "Last Activity",
          value: "last_active_datetime",
          sortable: false,
        },
        { text: "Status", value: "status", sortable: false },
        { text: "Closed Date", value: "closed_datetime", sortable: false },
        { text: "Options", value: "options", sortable: false },
      ],
      items: [],
      dialogNewTicket: false,
      isPageload: true,
    };
  },
  watch: {
    options: {
      handler() {
        if (!this.isPageload) this.getDataFromApi();
      },
      deep: true,
    },
  },
  created() {
    // let today = new Date();
    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = null; //monthObj.first;
    // this.date_to = null; // monthObj.last;

    this.getDataFromApi();

    setInterval(() => {
      if (
        this.$route.name == "alarm-view-customer-id" ||
        this.$route.name == "alarm-armedreports"
      ) {
        this.getDataFromApi();
      }
    }, 1000 * 20);
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    viewTicket(item) {
      this.editItem = item;
      this.key += 1;
      this.dialogViewTicket = true;
    },
    close_dialog_reaction() {
      this.getDataFromApi();

      this.dialogReply = false;
      this.dialogNewTicket = false;
    },

    addReply(item) {
      this.key += 1;
      this.editItem = item;
      this.editId = item.id;
      this.dialogReply = true;
    },
    editTicket(item) {
      this.editId = item.id;
      this.editItem = item;
      this.key += 1;
      this.dialogNewTicket = true;
    },
    closeDialog() {
      this.dialogAddCustomerNotes = false;
      this.getDataFromApi();
      this.$emit("closeDialog");
    },
    filterAttr(data) {
      this.date_from = data.from;
      this.date_to = data.to;

      this.getDataFromApi();
    },
    downloadOptions(option) {
      let filterSensorname = this.tab > 0 ? this.sensorItems[this.tab] : null;

      if (this.eventFilter) {
        filterSensorname = this.eventFilter;
      }

      let url = process.env.BACKEND_URL;
      if (option == "print") url += "/device_armed_logs_print_pdf";
      if (option == "excel") url += "/device_armed_logs_export_excel";
      if (option == "download") url += "/device_armed_logs_download_pdf";
      url += "?company_id=" + this.$auth.user.company_id;
      url += "&date_from=" + this.date_from;
      url += "&date_to=" + this.date_to;
      if (this.commonSearch) url += "&common_search=" + this.commonSearch;

      //  url += "&alarm_status=" + this.filterAlarmStatus;

      window.open(url, "_blank");
    },

    getDataFromApi() {
      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      this.perPage = itemsPerPage;
      this.currentPage = page;
      // if (!page > 0) return false;

      this.loading = true;
      let options = {
        params: {
          page: page,
          //sortBy: sortedBy,
          sortDesc: sortedDesc,
          perPage: itemsPerPage,
          pagination: true,
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          security_id: this.security_id,
          date_from: this.date_from,
          date_to: this.date_to,
          common_search: this.commonSearch,
          sortBy: "alarm_start_datetime",
        },
      };

      try {
        this.$axios.get(`tickets`, options).then(({ data }) => {
          this.items = data.data;

          this.totalRowsCount = data.total;

          setTimeout(() => {
            this.loading = false;
          }, 1000);

          if (this.displayDateFilter == false && this.isPageload) {
            let today = new Date();
            if (this.items.length > 0) {
              today = new Date(this.items[0].created_datetime);
            }

            let monthObj = this.$dateFormat.monthStartEnd(today);
            this.date_from = monthObj.first;
            this.date_to = monthObj.last;
            this.displayDateFilter = true;
            this.isPageload = false;
          }
        });
      } catch (e) {}
    },
    addItem() {
      this.key += 1;

      this.dialogNewTicket = true;
    },
  },
};
</script>
