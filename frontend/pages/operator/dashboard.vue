<template>
  <div>
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
      <v-col class="main-leftcontent" style="padding: 4px; padding-top: 5px">
        <v-card elevation="10" outlined>
          <div
            :key="mapkeycount"
            id="map"
            :style="'height:' + windowHeight + 'px'"
          ></div>

          <div style="position: absolute; top: 0px; left: 140px">
            <v-btn-toggle
              v-model="mapStyle"
              height="20"
              tile
              color="black white "
              group
              style="
                background: #fff;
                border-radius: 4px;
                height: 24px;
                top: 4px;
              "
            >
              <v-btn
                height="22"
                width="60"
                value="bw"
                small
                dense
                style="margin-top: 1px"
                @click="changeGoogleMapColor('bw')"
                >B & W</v-btn
              >
              <v-btn
                height="22"
                width="60"
                value="map"
                small
                dense
                style="margin-top: 1px"
                @click="changeGoogleMapColor('map')"
                >Regular</v-btn
              >
              <v-btn
                height="22"
                value="map"
                small
                dense
                style="
                  margin-top: 1px;
                  background-color: #545353 !important;
                  color: #fff;
                "
                @click="toggleFullscreen"
              >
                {{ fullscreen ? "Exit Fullscreen" : "Go Fullscreen" }}
              </v-btn>
            </v-btn-toggle>
          </div>
        </v-card>
      </v-col>
      <v-col class="main-rightcontent" style="padding: 0px; padding-top: 6px">
        <v-card
          elevation="10"
          outlined
          :style="
            ' height:' + windowHeight + 'px;overflow-y: auto;overflow-x: hidden'
          "
        >
          <v-card-text style="padding: 10px">
            <Topmenu />
            <v-row style="margin-top: 10px">
              <v-col cols="4" style="padding: 4px">
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
                  label="Property Type"
                  class="selectfilter"
                  outlined
                  dense
                  height="20px"
                  v-model="filterBuildingType"
                  :items="[{ id: null, name: 'All' }, ...buildingTypes]"
                  item-text="name"
                  item-value="id"
                >
                </v-select>
              </v-col>
              <v-col cols="4" style="padding: 4px">
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
                  label="Alarm Type"
                  class="selectfilter"
                  outlined
                  dense
                  height="20px"
                  v-model="filterAlarmType"
                  :items="[{ id: null, name: 'All' }, ...alarmTypes]"
                  item-text="name"
                  item-value="id"
                >
                </v-select>
              </v-col>
              <v-col cols="4" style="padding: 4px">
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
                  label="Event Status"
                  class="selectfilter"
                  outlined
                  dense
                  height="20px"
                  v-model="filterAlarmStatus"
                  :items="[
                    { id: null, name: 'All Events' },

                    { id: 1, name: 'Open' },

                    { id: 0, name: 'Closed' },
                    { id: 3, name: 'Forwarded' },
                  ]"
                  item-text="name"
                  item-value="id"
                >
                </v-select>
              </v-col>
            </v-row>
            <v-row v-if="data.length == 0" class="text-center">
              <v-col> No data available </v-col>
            </v-row>

            <v-card
              v-if="alarm.device?.customer"
              :key="index + 55"
              v-for="(alarm, index) in data"
              elevation="4"
              style="
                border-color: black;
                margin-top: 5px;
                border: 1px solid #a5a5a5;
                border-radius: 10px;
              "
            >
              <v-card-text style="padding-right: 0px">
                <v-row style="min-width: 300px; height: 106px; width: 100%">
                  <v-col
                    style="
                      max-width: 80px;
                      padding: 0px;
                      margin: auto;
                      text-align: center;
                    "
                  >
                    <img
                      :src="alarm.device.customer.profile_picture"
                      style="
                        width: 100%;
                        border-radius: 6px;
                        height: 90px;
                        vertical-align: bottom;
                      "
                    />
                  </v-col>
                  <v-col
                    style="padding: 0px; font-size: 12px; padding-left: 10px"
                  >
                    <div style="height: 89px; overflow: hidden">
                      {{
                        alarm.device.customer.primary_contact
                          ? alarm.device.customer.primary_contact.first_name +
                            " " +
                            alarm.device.customer.primary_contact.last_name
                          : "---"
                      }}
                      <br />
                      {{ alarm.device.customer.building_name || "---" }} <br />
                      {{ alarm.device.customer.city }}
                      <br />
                      {{
                        alarm.device.customer.primary_contact?.phone1 || "---"
                      }}
                    </div>
                    <div style="color: #0064ff">
                      {{
                        alarm.device.customer.buildingtype
                          ? alarm.device.customer.buildingtype.name
                          : "---"
                      }}
                      <v-icon
                        style="
                          float: right;
                          padding-right: 17px;
                          text-align: right;
                        "
                        size="20"
                        color="#0064ff"
                        >{{
                          alarm.alarm_status == 1
                            ? "mdi-folder-open"
                            : "mdi-folder"
                        }}</v-icon
                      >
                    </div>
                  </v-col>
                  <v-col style="max-width: 90px; padding: 2px; font-size: 11px">
                    <div style="margin: auto; text-align: center; height: 85px">
                      <img
                        :title="alarm.alarm_type"
                        style="width: 40px; padding-top: 20%"
                        :src="getAlarmColorObject(alarm).image + '?3=3'"
                      />
                    </div>
                    <div style="color: red">
                      {{
                        $dateFormat.formatDateMonthYear(
                          alarm.alarm_start_datetime
                        )
                      }}
                    </div>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import google_map_style_bandw from "../../google/google_style_blackandwhite.json";
import google_map_style_regular from "../../google/google_style_regular.json";

import AlarmCustomerTabsView from "../../components/Alarm/AlarmCustomerTabsView.vue";
import AlarmEventCustomerContactsTabView from "../../components/Alarm/AlarmEventCustomerContactsTabView.vue";
import Topmenu from "../../components/Operator/topmenu.vue";
export default {
  layout: "operator",
  components: {
    AlarmCustomerTabsView,
    AlarmEventCustomerContactsTabView,
    Topmenu,
  },

  data: () => ({
    filterBuildingType: null,
    filterAlarmType: null,
    filterAlarmStatus: 1,
    fullscreen: false,
    windowHeight: 600,
    windowWidth: 600,
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
      closed: {
        color: "#626262",
        text: "Closed",
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
    alarmTypes: [],

    _id: null,

    mapMarkersList: [],
    mapInfowindowsList: [],
    filterText: "",

    google_map_style_bandw,

    google_map_style_regular,
  }),
  computed: {},

  beforeDestroy() {
    window.removeEventListener("resize", this.onResize);
  },
  mounted() {
    this.onResize();
    window.addEventListener("resize", this.onResize);
    // if (window) {
    //   this.windowHeight = window.innerHeight - 20;
    //   this.windowWidth = window.innerWidth;
    // }
    // setTimeout(() => {
    //   this.getDatafromApi("alarm");
    // }, 1000 * 2);
    // await this.getMapKey();
  },

  created() {
    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }
    this.getDatafromApi();
    this.getBuildingTypes();
    this.getAlarmTypes();
  },
  watch: {},
  methods: {
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },
    async getAlarmTypes() {
      const { data } = await this.$axios.get("alarm_types", {
        params: {
          company_id: this.$auth.user.company_id,
        },
      });

      this.alarmTypes = data;
    },
    async getBuildingTypes() {
      const { data } = await this.$axios.get("building_types", {
        params: {
          company_id: this.$auth.user.company_id,
        },
      });

      this.buildingTypes = data;
    },
    onResize() {
      this.windowWidth = window.innerWidth;
      this.windowHeight = window.innerHeight - 20;
    },
    toggleFullscreen() {
      let newStyle = "fullscreen";
      if (!document.fullscreenElement) {
        document.documentElement
          .requestFullscreen()
          .then(() => {
            this.fullscreen = true;
          })
          .catch((err) => {
            console.error(
              `Error attempting to enable fullscreen mode: ${err.message}`
            );
          });
      } else {
        document
          .exitFullscreen()
          .then(() => {
            this.fullscreen = false;
          })
          .catch((err) => {
            console.error(
              `Error attempting to exit fullscreen mode: ${err.message}`
            );
          });
      }
    },
    changeGoogleMapColor(type) {
      let newStyle = this.google_map_style_regular;
      if (type == "bw") newStyle = this.google_map_style_bandw;
      if (type == "map") newStyle = this.google_map_style_regular;

      this.map.setOptions({ styles: newStyle });
    },
    viewAlarmInformation(alarm) {
      try {
        this.setCustomerLocationOnMap(alarm.device.customer);
      } catch (e) {
        console.error(e);
      }
      this.popupEventText =
        "#" +
          alarm.id +
          " -    " +
          alarm.alarm_type +
          " ,  " +
          "   Time " +
          alarm?.alarm_start_datetime ||
        "" + " -  Priority " + alarm.category.name;

      this.key += 1;
      this.viewCustomerId = alarm.device.customer_id;
      this.eventId = alarm.id;
      this.customer = alarm.device.customer;
      this.dialogAlarmEventCustomerContactsTabView = true;
    },
    closeCustomerDialog() {
      this.dialogViewCustomer = false;
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

    getDatafromApi(filterText = "") {
      this.filterText = filterText;

      this.loading = true;

      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          // date_from: this.date_from,
          // date_to: this.date_to,
          common_search: this.commonSearch,
          //filter_text: filterText == "" ? "alarm" : filterText,
          filterBuildingType: this.filterBuildingType,
          filterAlarmStatus: this.filterAlarmStatus,

          filterAlarmType: this.filterAlarmType,
        },
      };

      try {
        this.$axios.get(`get_alarm_events_map`, options).then(({ data }) => {
          this.data = data.data; //data.data;

          this.loading = false;

          this.mapMarkersList.forEach((marker) => {
            if (marker) {
              marker.setMap(null);
            }
          });
          this.mapMarkersList = [];

          this.getMapKey();
        });
      } catch (e) {}
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
    getAlarmColorObject(alarm) {
      // console.log(
      //   "findAnyDeviceisOffline",
      //   this.findAnyDeviceisOffline(item.devices)
      // );
      // console.log(alarm.alarm_status);
      if (alarm.alarm_status == 1) {
        return this.colorcodes.alarm;
      } else if (alarm.alarm_status == 0) {
        return this.colorcodes.closed;
      } else if (
        alarm.customer &&
        this.findanyArmedDevice(alarm.customer.devices)
      ) {
        return this.colorcodes.armed;
      }
      if (
        this.findAnyDeviceisOffline(alarm.customer && alarm.customer.devices) >
        0
      ) {
        return this.colorcodes.offline;
      } else if (
        this.findanyDisamrDevice(alarm.customer && alarm.customer.devices)
      ) {
        return this.colorcodes.disarm;
      }

      return this.colorcodes.offline;
    },
    getCustomerColorObject(customer) {
      // console.log(
      //   "findAnyDeviceisOffline",
      //   this.findAnyDeviceisOffline(item.devices)
      // );

      if (customer.latest_alarm_event) {
        return this.colorcodes.alarm;
      } else if (this.findanyArmedDevice(customer.devices)) {
        return this.colorcodes.armed;
      }
      if (this.findAnyDeviceisOffline(customer.devices) > 0) {
        return this.colorcodes.offline;
      } else if (this.findanyDisamrDevice(customer.devices)) {
        return this.colorcodes.disarm;
      }

      return this.colorcodes.offline;
    },
    findAnyDeviceisOffline(devices) {
      if (!devices) return 0;
      let offlineArray = devices.filter((device) => device.status_id == 2);
      // console.log("offlineArray", offlineArray);
      // console.log("offlineArray", offlineArray.length);
      return offlineArray ? offlineArray.length : 0;
    },
    findallDeviceisOnline(devices) {
      if (!devices) return 0;
      let onlineArray = devices.filter((device) => device.status_id == 1);

      // console.log("offlineArray", offlineArray.length);
      return onlineArray
        ? onlineArray.length == devices.length
          ? true
          : false
        : 0;
    },
    findanyArmedDevice(devices) {
      if (!devices) return 0;
      let armedArray = devices.filter((device) => device.armed_status == 1);

      return armedArray ? armedArray.length : 0;
    },
    findanyDisamrDevice(devices) {
      if (!devices) return 0;
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

    setCustomerLocationOnMap(customer) {
      console.log(customer);

      try {
        if (customer.latitude && customer.longitude) {
          const position = {
            lat: parseFloat(customer.latitude),
            lng: parseFloat(customer.longitude),
          };

          this.mapInfowindowsList.forEach((infowindow) => {
            infowindow.close();
          });

          this.map.panTo(position);
          this.map.setZoom(14);

          let infowindow = this.mapInfowindowsList[customer.id];
          let marker = this.mapMarkersList[customer.id];
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
          center: { lat: 25.2265191, lng: 55.395225 },
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
    plotLocations() {
      this.data.forEach((item) => {
        if (item.device?.customer) {
          try {
            const position = {
              lat: parseFloat(item.device.customer.latitude),
              lng: parseFloat(item.device.customer.longitude),
            };

            let iconURL =
              process.env.BACKEND_APP_URL +
              "/google_map_icons/google_online.png";

            // let colorObject = this.getCustomerColorObject(item.device.customer);
            let colorObject = this.getAlarmColorObject(item);
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
              title: item.device.customer.name,
              icon: icon,
            });

            let alarmHtmlLink = "";
            let customerHtmlLink = "";
            if (item.alarm_start_datetime)
              alarmHtmlLink = `<button class="error v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="alarmInfowindow-btn-${item.id}">Alarm</button>`;

            customerHtmlLink = `<button class="primary v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="customerInfowindow-btn-${item.id}">View</button>`;

            let profile_picture =
              "https://alarm.xtremeguard.org/no-business_profile.png";
            if (item.device.customer.profile_picture)
              profile_picture = item.device.customer.profile_picture;

            let html = `
    <table style="width:250px; min-height:100px" id="infowindow-content-${item.id}">
      <tr>
        <td style="width:100px; vertical-align: top;">
          <img style="width:100px;max-height:100px; padding-right:5px;" src="${profile_picture}" />
          <br />

        </td>
        <td style="width:150px; vertical-align: top;">
          ${item.device.customer.building_name} <br/> ${item.device.customer.city}
          <div>Landmark: ${item.device.customer.landmark}</div>
          <div style="text-align: right; width: 100%;">

          </div>
        </td>
      </tr>
      <tr>
  <td> <a target="_blank" href="https://www.google.com/maps?q=${item.device.customer.latitude},${item.device.customer.longitude}">Google Directions</a>
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
            if (item.alarm_start_datetime)
              marker.setAnimation(google.maps.Animation.BOUNCE);

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
                  this.viewAlarmInformation(item.alarm_start_datetime);
                });

              let btnObject2 = document.getElementById(
                "customerInfowindow-btn-" + item.id
              );
              if (btnObject2)
                btnObject2.addEventListener("click", () => {
                  this.dialog = true;
                  this.key += 1;
                  this.customerInfo = item.device.customer;
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
              this.customerInfo = item.device.customer;
            });
          } catch (e) {
            console.error(e);
          }
        }
      });
    },
  },
};
</script>

<style>
.main-leftcontent {
}
.main-rightcontent {
  min-width: 360px;
  max-width: 360px;
}

.selectfilter .v-label--active {
  padding: 0 12px !important;
}

.selectfilter .v-label {
  font-size: 14px !important;
}
.selectfilter .v-select__selections {
  font-size: 12px !important;
}
.selectfilter .v-input__slot {
  min-height: 33px !important;
  padding: 0 5px !important;
}
.selectfilter .v-label {
  line-height: 15px !important;
}
.selectfilter .v-input__icon {
  height: 20px !important;
}
</style>
