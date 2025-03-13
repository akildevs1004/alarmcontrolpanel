<template>
  <div>
    <v-btn @click="openSecondScreen" color="primary">
      Open in Second Screen
    </v-btn>
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
    filterEventType: 1,
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
        text: "Customer Name",
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
    openSecondScreen() {
      const url = "http://localhost:3000/operator/dashboard";
      // Open the URL in the second screen using custom positioning
      this.openInSecondScreen(url);
    },
    openInSecondScreen(url) {
      // Get screen dimensions
      const screenWidth = window.screen.width;
      const screenHeight = window.screen.height;

      console.log(screenWidth);

      // Open the new window and position it on the second monitor (assuming it's placed on the right)
      window.open(
        "http://www.google.com/",
        "name",
        "width=5000,height=320,toolbar=no,scrollbars=yes,resizable=yes,top=0,left=2000,right:2000"
      );
    },
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
      this.windowHeight = window.innerHeight;
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
      this.setCustomerLocationOnMap(alarm);
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
      this.viewCustomerId = alarm.customer_id;
      this.eventId = alarm.id;
      this.customer = alarm.customer;
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
          filterEventType: this.filterEventType,

          filterAlarmType: this.filterAlarmType,
        },
      };

      try {
        this.$axios
          .get(`customers_for_map_operator`, options)
          .then(({ data }) => {
            this.data = data; //data.data;

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
          this.map.setZoom(14);

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
            url: iconURL + "?4=4",
            // scaledSize: new google.maps.Size(28, 34),
            scaledSize: new google.maps.Size(40, 40),
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
          if (item.latest_alarm_event)
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
