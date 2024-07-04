<template>
  <div>
    <v-row>
      <v-col cols="12" style="padding-top: 0px">
        <v-row>
          <v-col
            cols="7"
            style="font-size: 18px; font-weight: bold; padding-top: 25px"
            >Contract/Subscription: From
            <span style="color: green"> Jun 24 2024 </span>to
            <span style="color: red">Jun 23 2025</span></v-col
          >
          <v-col cols="5" class="text-right" style="width: 550px">
            <v-row>
              <v-col cols="6"
                ><v-text-field
                  style="padding-top: 7px; width: 200px"
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
              ></v-col>
              <v-col cols="4" style="width: 130px">
                <CustomFilter
                  style="float: right; padding-top: 5px; z-index: 9999"
                  @filter-attr="filterAttr"
                  :default_date_from="date_from"
                  :default_date_to="date_to"
                  :defaultFilterType="1"
                  :height="'40px'"
              /></v-col>

              <v-col cols="2" style="width: 30px">
                <v-btn small dense color="primary" class="ma-2">
                  New
                </v-btn></v-col
              >
            </v-row>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-data-table :headers="headers" :items="items" class="elevation-0">
          <template v-slot:item.payment_status="{ item }">
            <div v-if="item.payment_status == 1" style="color: green">
              Received
            </div>
            <div v-else style="color: red">Pending</div>
          </template>

          <template v-slot:item.options="{ item }">
            <v-menu bottom left>
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark-2 icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list width="120" dense>
                <v-list-item v-if="can('branch_view')" @click="viewItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-eye </v-icon>
                    View
                  </v-list-item-title>
                </v-list-item>
                <v-list-item v-if="can('branch_edit')" @click="editItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-pencil </v-icon>
                    Edit
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
export default {
  data() {
    return {
      headers: [
        { text: "#", value: "sno" },
        { text: "Invoice #", value: "invoice_id" },
        { text: "Amount", value: "total_amount" },
        { text: "Receieved On", value: "received_datetime" },
        { text: "Mode", value: "payment_mode" },

        { text: "Status", value: "payment_status" },

        { text: "Options", value: "options" },
      ],
      items: [
        {
          sno: "01",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_status: "1",
          payment_mode: "Cash",
        },
        {
          sno: "02",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_status: "1",
          payment_mode: "Online",
        },
        {
          sno: "02",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_status: "1",
          payment_mode: "Online",
        },
        {
          sno: "02",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_mode: "Online",
          payment_status: "1",
        },
        {
          sno: "02",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_status: "0",
          payment_mode: "Online",
        },
        {
          sno: "02",
          invoice_id: "1234",
          total_amount: "2000",
          received_datetime: "2024-06-22",
          payment_status: "1",
        },
      ],
    };
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
