<template>
  <div>
    <!-- <v-dialog v-model="dialog" max-width="1100px">
        <v-card v-if="customerInfo">
          <v-toolbar class="primary white--text" flat dense>
            <v-toolbar-title>Alarm Event Info </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-icon @click="dialog = false" color="white">mdi-close</v-icon>
          </v-toolbar>
          <v-row>
            <v-col cols="12" md="6">
              <v-container>
                <v-card outlined>
                  <v-card-title>Primary Contact</v-card-title>
                  <v-card-text>
                    <v-list dense>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Name:</v-list-item-title>
                          <v-list-item-subtitle
                            >{{ customerInfo?.primary_contact?.first_name }}
                            {{
                              customerInfo?.primary_contact?.last_name
                            }}</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Phone:</v-list-item-title>
                          <v-list-item-subtitle
                            >{{ customerInfo?.primary_contact?.phone1 }} /
                            {{
                              customerInfo?.primary_contact?.phone2
                            }}</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Office Phone:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.primary_contact?.office_phone
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Email:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.primary_contact?.email
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>WhatsApp:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.primary_contact?.whatsapp
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-container>
                <v-card outlined>
                  <v-card-title>Secondary Contact</v-card-title>
                  <v-card-text>
                    <v-list dense>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Name:</v-list-item-title>
                          <v-list-item-subtitle
                            >{{ customerInfo?.secondary_contact?.first_name }}
                            {{
                              customerInfo?.secondary_contact?.last_name
                            }}</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Phone:</v-list-item-title>
                          <v-list-item-subtitle
                            >{{ customerInfo?.secondary_contact?.phone1 }} /
                            {{
                              customerInfo?.secondary_contact?.phone2
                            }}</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Office Phone:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.secondary_contact?.office_phone
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Email:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.secondary_contact?.email
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>WhatsApp:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.secondary_contact?.whatsapp
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-container>
                <v-card outlined>
                  <v-card-title>Building Info</v-card-title>
                  <v-card-text>
                    <v-list dense>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Building Name:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo.building_name
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
  
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Address:</v-list-item-title>
                          <v-list-item-subtitle
                            >{{ customerInfo.house_number }},
                            {{ customerInfo.street_number }},
                            {{ customerInfo.area }}, {{ customerInfo.city }},
                            {{ customerInfo.state }},
                            {{ customerInfo.country }}</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Email:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo.email
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Landmark:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo.landmark
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-container>
                <v-card outlined>
                  <v-card-title>Alarm Info</v-card-title>
                  <v-card-text>
                    <v-list dense>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Device:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.latest_alarm_event?.serial_number
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Zone:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.latest_alarm_event?.zone
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Area:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.latest_alarm_event?.area
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title
                            >Alarm Start Datetime:</v-list-item-title
                          >
                          <v-list-item-subtitle>{{
                            customerInfo?.latest_alarm_event?.alarm_start_datetime
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Alarm Type:</v-list-item-title>
                          <v-list-item-subtitle>{{
                            customerInfo?.latest_alarm_event?.alarm_type
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-col>
          </v-row>
        </v-card>
      </v-dialog> -->
    <v-dialog v-model="dialog" width="1000px" style="overflow: visible">
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense style="color: black"> Map Customer Information</span>
          <v-spacer></v-spacer>
          <v-icon style="color: black" @click="dialog = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text style="padding: 0px">
          <AlarmCustomerTabsView
            v-if="customerInfo"
            :key="key"
            :_id="customerInfo.id"
            :isPopup="true"
            :isMapviewOnly="true"
            :alarmId="eventId"
        /></v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialogAlarmEventCustomerContactsTabView"
      width="1000px"
      style="overflow: visible"
    >
      <v-card>
        <v-card-title dark class="popup_background_noviolet">
          <span dense style="color: black">
            Map Alarm {{ popupEventText }}</span
          >
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="dialogAlarmEventCustomerContactsTabView = false"
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text style="padding: 0px; overflow: hidden">
          <AlarmEventCustomerContactsTabView
            :key="key"
            :_customerID="viewCustomerId"
            :alarmId="eventId"
            :customer="customer"
            :isPopup="true"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-row>
      <v-col cols="9">
        <div :key="mapkeycount" id="map" style="height: 600px"></div>

        <div style="position: absolute; top: 14px; left: 140px">
          <v-btn-toggle
            v-model="mapStyle"
            height="20"
            tile
            color="black white "
            group
          >
            <v-btn
              height="22"
              width="60"
              value="bw"
              small
              dense
              @click="changeGoogleMapColor('bw')"
              >B & W</v-btn
            >
            <v-btn
              height="22"
              width="60"
              value="map"
              small
              dense
              @click="changeGoogleMapColor('map')"
              >Regular</v-btn
            >
          </v-btn-toggle>
        </div>
      </v-col>
      <v-col cols="3" style="padding: 0px; padding-top: 10px">
        <v-card elevation="2" style="height: 600px">
          <v-data-table
            style="padding: 0px"
            dense
            :headers="headers"
            :items="data"
            :loading="loading"
            :options.sync="options"
            :footer-props="{
              itemsPerPageOptions: [10, 50, 100, 500, 1000],
              'disable-items-per-page': true,
              'items-per-page-text': ' ',
            }"
            fixed-header
            height="400px"
            hide-default-header
          >
            <template v-slot:top>
              <v-container>
                <v-row>
                  <v-col cols="5" style="font-size: 12px; padding-top: 17px"
                    >Customers List
                    <span v-if="filterText != ''"
                      >({{ caps(filterText) }})</span
                    >
                  </v-col>
                  <v-col>
                    <v-row>
                      <v-col cols="2">
                        <v-icon
                          @click="getCustomers('all')"
                          title="Display All Customers"
                          >mdi-reload</v-icon
                        >
                      </v-col>

                      <v-col cols="10">
                        <v-text-field
                          class="small-custom-textbox"
                          @input="getCustomers()"
                          v-model="commonSearch"
                          label="Search..."
                          dense
                          outlined
                          type="text"
                          append-icon="mdi-magnify"
                          clearable
                          hide-details
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
                <v-divider class="mt-3" color="#DDD"></v-divider>
              </v-container>
            </template>
            <template v-slot:item.building_name="{ item, index }">
              <v-row style="border-bottom: 0px solid #ddd">
                <v-col
                  cols="1"
                  style="padding-left: 0px; padding-right: 0px; max-width: 20px"
                >
                  {{
                    currentPage
                      ? (currentPage - 1) * options.itemsPerPage +
                        (cumulativeIndex + data.indexOf(item))
                      : "-"
                  }}
                </v-col>
                <v-col style="padding-left: 0px">
                  <!-- <v-icon
                    :title="getCustomerColorObject(item).text"
                    :color="getCustomerColorObject(item).color"
                    >mdi mdi-square-medium</v-icon
                  > -->
                  <span
                    @click="setCustomerLocationOnMap(item)"
                    style="font-size: 13px; margin-bottom: 15px"
                  >
                    {{ item.building_name || "" }} ,{{ item.city }}
                  </span>

                  <v-row
                    :key="index1 + 11"
                    v-for="(alarm, index1) in item.alarm_events"
                  >
                    <v-col
                      @click="viewAlarmInformation(alarm)"
                      style="color: red"
                    >
                      <v-row style="font-size: 12px">
                        <v-col
                          style="
                            max-width: 20px;
                            margin: auto;
                            padding-top: 14px;
                          "
                        >
                          <img
                            :title="alarm.alarm_type"
                            style="width: 15px; float: left"
                            :src="$utils.getRelaventImage(alarm.alarm_type)"
                          />
                        </v-col>

                        <v-col>
                          {{
                            $dateFormat.formatDateMonthYear(
                              alarm.alarm_start_datetime
                            )
                          }}
                        </v-col>
                        <v-col cols="3"> {{ alarm.alarm_type }}</v-col>
                        <v-col cols="3"> {{ alarm.category.name }}</v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
          <div style="width: 100%">
            <v-btn-toggle
              style="width: 100%"
              tile
              color="deep-purple accent-3"
              group
            >
              <v-btn
                v-if="
                  name == 'alarm' ||
                  name == 'armed' ||
                  name == 'offline' ||
                  name == 'disarm'
                "
                :key="index + 25"
                :title="caps(name)"
                @click="getCustomers(value.text)"
                style="width: 25%"
                v-for="(value, name, index) in colorcodes"
                :value="value"
              >
                <!-- <img
                  :src="value.image + '?2=2'"
                  style="width: 35px; height: 45px"
                /> -->
                <!-- <div>{{ value.name }}</div> -->

                <v-icon :color="value.color">{{ value.icon }} </v-icon>
              </v-btn>
            </v-btn-toggle>
          </div>
          <!-- <v-btn-toggle tile color="deep-purple accent-3" group>
            <v-btn style value="left"> Left </v-btn>

            <v-btn value="center"> Center </v-btn>

            <v-btn value="right"> Right </v-btn>

            <v-btn value="justify"> Justify </v-btn>
          </v-btn-toggle> -->
          <!--<v-row>
            <v-col
              @click="getCustomers(value.text)"
              class="pl-5"
              style="min-width: 50px; text-align: center"
              v-for="(value, name, index) in colorcodes"
              :id="index"
              :key="index"
              :name="index"
            >
              <v-icon :color="value.color">{{ value.icon }}</v-icon>

               <img
                v-if="getImageicon(value)"
                style="width: 20px"
                :src="getImageicon(value)"
              /> 

              <div style="font-size: 13px">
                {{ caps(name) }}
              </div>
            </v-col>
          </v-row>-->
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import google_map_style_bandw from "../../../google/google_style_blackandwhite.json";
import google_map_style_regular from "../../../google/google_style_regular.json";

import AlarmCustomerTabsView from "../../../components/Alarm/AlarmCustomerTabsView.vue";
import AlarmEventCustomerContactsTabView from "../../../components/Alarm/AlarmEventCustomerContactsTabView.vue";
export default {
  components: { AlarmCustomerTabsView, AlarmEventCustomerContactsTabView },

  data: () => ({
    mapStyle: "bw",
    mapkeycount: 1,
    popupEventText: "",
    dialogAlarmEventCustomerContactsTabView: false,
    dialog: false,
    dialogContent: "",
    customerInfo: null,
    map: null,
    key: 1,
    mapKey: null,
    geocoder: null,
    infowindow: null,
    viewCustomerId: null,
    commonSearch: "",
    // perPage: 10,
    cumulativeIndex: 1,
    currentPage: 1,
    totalRowsCount: 0,
    branchesList: [],
    isCompany: true,
    tableHeight: 750,
    id: "",
    eventId: null,
    customer: null,
    newCustomerDialog: false,
    dialogViewCustomer: false,
    totalRowsCount: 0,

    colorcodes: {
      alarm: {
        color: "#ff0000",
        text: "Alarm",
        image: process.env.BACKEND_URL2 + "/google_map_icons/google_alarm.png",
        icon: "mdi-alarm",
      },
      temperature: {
        color: "#ff0000",
        text: "Fire Alarm",
        image:
          process.env.BACKEND_URL2 +
          "/google_map_icons/google_temperature_alarm.png",
        icon: "mdi-alarm",
      },
      fire: {
        color: "#ff0000",
        text: "Fire Alarm",
        image:
          process.env.BACKEND_URL2 + "/google_map_icons/google_fire_alarm.png",
        icon: "mdi-alarm",
      },
      water: {
        color: "#ff0000",
        text: "Fire Alarm",
        image:
          process.env.BACKEND_URL2 + "/google_map_icons/google_water_alarm.png",
        icon: "mdi-alarm",
      },
      water: {
        color: "#ff0000",
        text: "Fire Alarm",
        image:
          process.env.BACKEND_URL2 + "/google_map_icons/google_water_alarm.png",
        icon: "mdi-alarm",
      },
      sos: {
        color: "#ff0000",
        text: "SOS Alarm",
        image:
          process.env.BACKEND_URL2 + "/google_map_icons/google_sos_alarm.png",
        icon: "mdi-alarm",
      },
      medical: {
        color: "#ff0000",
        text: "Fire Alarm",
        image:
          process.env.BACKEND_URL2 +
          "/google_map_icons/google_medical_alarm.png",
        icon: "mdi-alarm",
      },
      offline: {
        color: "#626262",
        text: "Offline",
        image:
          process.env.BACKEND_URL2 + "/google_map_icons/google_offline.png",
        icon: "mdi-download-network-outline",
      },
      armed: {
        color: "#00930b",
        text: "Armed",
        image: process.env.BACKEND_URL2 + "/google_map_icons/google_armed.png",
        icon: "mdi-lock",
      },
      disarm: {
        color: "#ff0000",
        text: "Disarm",
        image: process.env.BACKEND_URL2 + "/google_map_icons/google_disarm.png",
        icon: "mdi-lock-open",
      },
    },
    snack: false,
    snackColor: "",
    snackText: "",
    departments: [],
    Model: "Log",
    endpoint: "customers",
    payload: {},
    loading: false,
    headers: [
      {
        text: "Building Name",
        value: "building_name",
      },
    ],
    ids: [],

    data: [],
    devices: [],
    total: 0,

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

    mapMarkersList: [],
    mapInfowindowsList: [],
    filterText: "",

    google_map_style_bandw,

    google_map_style_regular,
  }),
  computed: {},
  mounted() {
    // setTimeout(() => {
    //   this.getCustomers("alarm");
    // }, 1000 * 2);
    // await this.getMapKey();

    setTimeout(() => {
      this.plotLocations(true);
    }, 1000 * 2);
  },

  created() {
    //////this._id = 4; //this.$route.params.id;

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }
    // this.getGoogleicons();

    // setInterval(() => {
    //   if (
    //     this.$route.name == "security-customersmap" ||
    //     this.$route.name == "alarm-customersmap"
    //   )
    //     this.getCustomers();
    // }, 1000 * 10);
    ///this.getBuildingTypes();
  },
  watch: {
    options: {
      handler() {
        this.getCustomers();
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
    changeGoogleMapColor(type) {
      let newStyle = this.google_map_style_regular;
      if (type == "bw") newStyle = this.google_map_style_bandw;
      if (type == "map") newStyle = this.google_map_style_regular;

      this.map.setOptions({ styles: newStyle });
    },
    viewAlarmInformation(alarm) {
      this.setCustomerLocationOnMap(alarm);
      this.popupEventText =
        "#" +
        alarm.id +
        " -    " +
        alarm.alarm_type +
        " ,  " +
        "   Time " +
        alarm.alarm_start_datetime +
        " -  Priority " +
        alarm.category.name;

      this.key += 1;
      this.viewCustomerId = alarm.customer_id;
      this.eventId = alarm.id;
      this.customer = alarm.customer;
      this.dialogAlarmEventCustomerContactsTabView = true;
    },
    closeCustomerDialog() {
      this.dialogViewCustomer = false;
    },
    getBuildingTypes() {
      if (this.$store.state.storeAlarmControlPanel?.BuildingTypes) {
        this.buildingTypes =
          this.$store.state.storeAlarmControlPanel.BuildingTypes;
      }
    },
    viewItem(item) {
      this.viewCustomerId = item.id;
      this.dialogViewCustomer = true;
    },
    viewItem2(item) {
      this.$router.push("/alarm/view-customer/" + item.id);
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    async getMapKey() {
      let { data } = await this.$axios.get(`get-map-key`);
      this.mapKey = data;
      if (this.mapKey && this.loading == false) {
        this.loadGoogleMapsScript(this.initMap);
      }
    },

    getCustomers(filterText = "") {
      this.filterText = filterText;

      //console.log(this.filterText);

      // if (this.loading == true) return false;
      this.loading = true;

      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      this.perPage = itemsPerPage;
      if (page > 1) this.currentPage = page - 1;
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
          // date_from: this.date_from,
          // date_to: this.date_to,
          common_search: this.commonSearch,
          filter_text: filterText == "all" ? "" : filterText,
        },
      };

      try {
        this.$axios.get(`customers_for_map`, options).then(({ data }) => {
          this.data = data; //data.data;

          //  this.totalRowsCount = data.total;
          this.loading = false;
          //this.mapkeycount++;

          this.mapMarkersList.forEach((marker) => {
            if (marker) {
              marker.visible = false;
              marker.setMap(null);
              marker = null;
              this.mapMarkersList[index] = null;
            }
          });
          this.mapMarkersList = [];

          // // Call plotLocations to replot markers
          // this.plotLocations();

          this.getMapKey();
        });
      } catch (e) {}

      // let config = {
      //   params: {
      //     company_id: this.$auth.user.company_id,
      //     commonSearch: this.commonSearch,
      //   },
      // };
      // let { sortBy, sortDesc, page, itemsPerPage } = this.options;
      // console.log(this.options);

      // if (page) this.currentPage = page - 1;
      // let { data } = await this.$axios.get(`customers-for-map`, config);
      // this.data = data.data;
      // this.loading = false;
      // this.totalRowsCount = data.total;
    },

    // async getGoogleicons() {
    //   let config = {
    //     params: {
    //       company_id: this.$auth.user.company_id,
    //     },
    //   };
    //   let { sortBy, sortDesc, page, itemsPerPage } = this.options;
    //   console.log(this.options);
    //   if (page) this.currentPage = page - 1;
    //   let { data } = await this.$axios.get(`get_google_icons`, config);
    //   this.colorcodes = data;
    // },
    getImageicon(value) {
      if (process) return value.image;
      else return false;
    },
    getCustomerColorObject(item) {
      // console.log(
      //   "findAnyDeviceisOffline",
      //   this.findAnyDeviceisOffline(item.devices)
      // );

      if (item.latest_alarm_event) {
        return this.colorcodes.alarm;
      } else if (this.findanyArmedDevice(item.devices)) {
        return this.colorcodes.armed;
      }
      if (this.findAnyDeviceisOffline(item.devices) > 0) {
        return this.colorcodes.offline;
      } else if (this.findanyDisamrDevice(item.devices)) {
        return this.colorcodes.disarm;
      }

      return this.colorcodes.offline;
    },
    findAnyDeviceisOffline(devices) {
      let offlineArray = devices.filter((device) => device.status_id == 2);
      // console.log("offlineArray", offlineArray);
      // console.log("offlineArray", offlineArray.length);
      return offlineArray ? offlineArray.length : 0;
    },
    findallDeviceisOnline(devices) {
      let onlineArray = devices.filter((device) => device.status_id == 1);

      // console.log("offlineArray", offlineArray.length);
      return onlineArray
        ? onlineArray.length == devices.length
          ? true
          : false
        : 0;
    },
    findanyArmedDevice(devices) {
      let armedArray = devices.filter((device) => device.armed_status == 1);

      return armedArray ? armedArray.length : 0;
    },
    findanyDisamrDevice(devices) {
      let armedArray = devices.filter((device) => device.armed_status == 0);

      return armedArray ? armedArray.length : 0;
    },
    loadGoogleMapsScript(callback) {
      if (window.google && window.google.maps) {
        callback();
        return;
      }
      const script = document.createElement("script");
      script.src = `https://maps.googleapis.com/maps/api/js?loading=async&key=${this.mapKey}&callback=initMap`;
      script.async = true;
      script.defer = true;
      window.initMap = callback;
      document.head.appendChild(script);
    },

    setCustomerLocationOnMap(item) {
      try {
        if (item.latitude && item.longitude) {
          const position = {
            lat: parseFloat(item.latitude),
            lng: parseFloat(item.longitude),
          };

          this.mapInfowindowsList.forEach((infowindow) => {
            infowindow.close();
          });

          this.map.panTo(position);
          this.map.setZoom(12);

          let infowindow = this.mapInfowindowsList[item.id];
          let marker = this.mapMarkersList[item.id];
          if (infowindow) infowindow.open(this.map, marker);
        }
      } catch (e) {}
    },
    initMap() {
      if (!this.map) {
        this.map = new google.maps.Map(document.getElementById("map"), {
          // mapTypeControl: true, // Enables satellite/roadmap controls
          // mapTypeControlOptions: {
          //   style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
          //   position: google.maps.ControlPosition.TOP_RIGHT,
          // },
          controlSize: 20,
          zoom: 12,
          center: { lat: 25.2516474, lng: 55.3567738 },
          styles: this.google_map_style_bandw,
          // styles: [
          //   {
          //     featureType: "administrative",
          //     stylers: [{ visibility: "off" }],
          //   },
          //   {
          //     featureType: "administrative",
          //     stylers: [{ visibility: "off" }],
          //   },
          //   {
          //     featureType: "landscape",
          //     stylers: [{ visibility: "off" }],
          //   },
          //   {
          //     featureType: "poi",
          //     stylers: [{ visibility: "off" }],
          //   },
          // ],
        });
      }
      this.geocoder = new google.maps.Geocoder();
      // this.infowindow = new google.maps.InfoWindow();
      this.plotLocations();
    },
    plotLocations(changeMap = false) {
      if (
        this.data.length > 0 &&
        changeMap &&
        this.data[0].devices[0]?.utc_time_zone != "Asia/Dubai"
      ) {
        this.setCustomerLocationOnMap(this.data[0]);
      }
      this.data.forEach((item) => {
        try {
          const position = {
            lat: parseFloat(item.latitude),
            lng: parseFloat(item.longitude),
          };

          let iconURL =
            process.env.BACKEND_APP_URL + "/google_map_icons/google_online.png";

          let colorObject = this.getCustomerColorObject(item);
          if (colorObject) iconURL = colorObject.image;

          const icon = {
            url: iconURL + "?1=1",
            scaledSize: new google.maps.Size(28, 34),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(25, 25),
          };

          const marker = new google.maps.Marker({
            position,
            map: this.map,
            title: item.name,
            icon: icon,
          });

          let alarmHtmlLink = "";
          let customerHtmlLink = "";
          if (item.latest_alarm_event)
            alarmHtmlLink = `<button class="error v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="alarmInfowindow-btn-${item.id}">Alarm</button>`;

          customerHtmlLink = `<button class="primary v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="customerInfowindow-btn-${item.id}">View</button>`;

          let profile_picture =
            "https://alarm.xtremeguard.org/no-business_profile.png";
          if (item.profile_picture) profile_picture = item.profile_picture;

          let html = `
    <table style="width:250px; min-height:100px" id="infowindow-content-${item.id}">
      <tr>
        <td style="width:100px; vertical-align: top;">
          <img style="width:100px;max-height:100px; padding-right:5px;" src="${profile_picture}" />
          <br />

        </td>
        <td style="width:150px; vertical-align: top;">
          ${item.building_name} <br/> ${item.city}
          <div>Landmark: ${item.landmark}</div>
          <div style="text-align: right; width: 100%;">

          </div>
        </td>
      </tr>
      <tr>
  <td> <a target="_blank" href="https://www.google.com/maps?q=${item.latitude},${item.longitude}">Google Directions</a>
    </td>
    <td style="text-align:right">
   ${customerHtmlLink} &nbsp; &nbsp; ${alarmHtmlLink}
      </td>
        </tr>
    </table>`;

          const infowindow = new google.maps.InfoWindow({
            content: html,
            map: this.map,
            position: position,
          });
          infowindow.close();

          this.mapInfowindowsList[item.id] = infowindow;
          this.mapMarkersList[item.id] = marker;

          marker.addListener("mouseover", () => {
            this.mapInfowindowsList.forEach((oldinfowindow) => {
              oldinfowindow.close();
            });
            infowindow.open(this.map, marker);
          });

          google.maps.event.addListener(infowindow, "domready", () => {
            let btnObject = document.getElementById(
              "alarmInfowindow-btn-" + item.id
            );
            if (btnObject)
              btnObject.addEventListener("click", () => {
                this.viewAlarmInformation(item.latest_alarm_event);
              });

            let btnObject2 = document.getElementById(
              "customerInfowindow-btn-" + item.id
            );
            if (btnObject2)
              btnObject2.addEventListener("click", () => {
                this.dialog = true;
                this.key += 1;
                this.customerInfo = item;
              });

            const infowindowContent = document.getElementById(
              "infowindow-content-" + item.id
            );

            infowindowContent.addEventListener("mouseout", (e) => {
              // Close only if the mouse has left the entire infowindow div (and not just a child element)
              if (!infowindowContent.contains(e.relatedTarget)) {
                infowindow.close();
              }
            });
          });

          // Open Vue dialog on marker click
          marker.addListener("click", () => {
            this.dialog = true;
            this.key += 1;
            this.customerInfo = item;
          });
        } catch (e) {
          console.log(e);
        }
      });
    },
  },
};
</script>
