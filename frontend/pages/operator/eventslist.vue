<template>
  <div>
    <v-dialog v-model="displayEventsListBusinessInfoTabs" width="1000px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span
            >Customer -
            {{
              selectedAlarm?.device?.customer
                ? selectedAlarm?.device.customer.building_name
                : "---"
            }}</span
          >
          <v-spacer></v-spacer>
          <v-icon @click="displayEventsListBusinessInfoTabs = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <EventsListBusinessInfoTabs
              v-if="selectedAlarm"
              :customer="selectedAlarm?.device.customer"
              :device="selectedAlarm?.device"
              :alarm="selectedAlarm"
              :key="selectedAlarm.id"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-row style="margin-left: 5px; margin-top: 0px">
      <v-col
        :style="
          'padding:0px;;max-width: 300px; overflow:scroll1;height:' +
          browserHeight +
          'px'
        "
      >
        <v-card>
          <v-card-text
            :style="
              'padding-top:0px;background-color: #ecf0f4;height:' +
              browserHeight +
              'px'
            "
          >
            <!-- <Topmenu
              @refreshEventsList="getDatafromApi()"
              @applyGlobalSearch="getDatafromApi"
            /> -->

            <v-row
              style="
                height: 70px;
                margin-top: 0px;
                padding-left: 0px;
                padding-right: 0px;
                padding-top: 0px;
                background-color: #fff;
                font-size: 12px;
              "
            >
              <v-col cols="4" style="padding: 2px">
                <label>Property</label>
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
                  class="selectfilter"
                  outlined
                  dense
                  height="16px"
                  v-model="filterBuildingType"
                  :items="[{ id: null, name: 'All' }, ...buildingTypes]"
                  item-text="name"
                  item-value="id"
                >
                </v-select>
              </v-col>
              <v-col cols="4" style="padding: 2px">
                <label>Alarm</label>
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
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
              <v-col cols="4" style="padding: 2px">
                <label>Event</label>
                <v-select
                  @change="getDatafromApi()"
                  style="width: 100%"
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
            <v-row v-else>
              <v-col
                class="eventslistscroll"
                :style="
                  'overflow-y: auto; overflow-x: hidden; height:' +
                  parseInt(browserHeight - 70) +
                  'px'
                "
              >
                <v-row>
                  <v-col
                    class="pl-0 pr-0 pt-0 pb-2"
                    v-if="alarm.device?.customer"
                    v-for="(alarm, index) in data"
                  >
                    <v-card
                      @click="selectAlarmEvent(alarm)"
                      :key="index + 55"
                      elevation="5"
                      style="border-bottom: 0px solid black"
                    >
                      <v-card-text
                        :style="{
                          paddingRight: '5px',
                          border:
                            selectedAlarm.id === alarm.id
                              ? '1px solid #ecf0f4'
                              : '0px',
                          backgroundColor:
                            selectedAlarm.id === alarm.id ? '#ecf0f4' : '#FFF',
                        }"
                      >
                        <v-row
                          style="min-width: 300px; height: auto; width: 100%"
                        >
                          <v-col
                            style="
                              max-width: 50px;
                              padding: 0px;
                              margin: auto;
                              text-align: center;
                            "
                          >
                            <img
                              @click="showNotes(alarm)"
                              :title="alarm.alarm_type"
                              style="width: 30px; padding-top: 0%"
                              :src="getAlarmColorObject(alarm).image + '?3=3'"
                            />
                          </v-col>
                          <v-col
                            style="
                              padding: 0px;
                              font-size: 9px;
                              padding-left: 10px;
                              line-height: 15px;
                            "
                          >
                            <div style="overflow: hidden">
                              <div>
                                {{
                                  alarm.device.customer.buildingtype
                                    ? alarm.device.customer.buildingtype.name.toUpperCase()
                                    : "---"
                                }}
                              </div>
                              <div
                                style="
                                  font-weight: bold;
                                  font-size: 12px;
                                  color: #1f1f1f;
                                "
                              >
                                {{
                                  $utils.caps(
                                    alarm.device.customer.building_name
                                  ) || "---"
                                }}
                              </div>
                              <div>
                                <v-icon style="margin-top: -3px" size="10"
                                  >mdi-account-tie</v-icon
                                >{{
                                  alarm.device.customer.primary_contact
                                    ? $utils.caps(
                                        alarm.device.customer.primary_contact
                                          .first_name
                                      ) +
                                      " " +
                                      $utils.caps(
                                        alarm.device.customer.primary_contact
                                          .last_name
                                      )
                                    : "---"
                                }}
                              </div>
                            </div>
                            <div>
                              <div style="text-align: left">
                                <div style="text-align: left">
                                  {{
                                    $dateFormat.formatTimeDateMonthYear(
                                      alarm.alarm_start_datetime
                                    )
                                  }}
                                </div>
                                <div
                                  style="color: red"
                                  v-if="alarm.alarm_status == 0"
                                >
                                  {{
                                    $dateFormat.formatTimeDateMonthYear(
                                      alarm.alarm_end_datetime
                                    )
                                  }}
                                </div>
                              </div>
                            </div>
                          </v-col>
                          <v-col
                            style="
                              max-width: 100px;
                              padding: 2px;
                              font-size: 11px;
                              text-align: center;
                              margin: auto;
                              padding-right: 20px;
                            "
                          >
                            <div style="color: blue">#{{ alarm.id }}</div>
                            <div style="margin: auto; text-align: center"></div>
                            <div style="line-height: 0px"></div>
                            <div v-if="alarm.alarm_status == 1">
                              <span style="color: GREEN"> OPEN</span>
                              <div>
                                {{
                                  $dateFormat.getTimeDifference(
                                    alarm.alarm_start_datetime
                                  )
                                }}
                              </div>
                            </div>
                            <div v-else style="color: #2f1717">
                              CLOSED

                              <div>
                                {{
                                  $dateFormat.minutesToHHMM(
                                    alarm.response_minutes
                                  )
                                }}
                              </div>
                            </div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                      <img
                        v-if="selectedAlarm.id == alarm.id"
                        src="/right-arrow.png"
                        style="
                          width: 50px;
                          position: absolute;
                          width: 11px;
                          right: 4px;
                          top: 38%;
                          vertical-align: middle;
                        "
                      />
                    </v-card>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col style="max-width: 300px; padding-top: 0px">
        <v-card elevation="0">
          <v-card-text
            :style="
              ' background-color:#ecf0f4;overflow:scroll1;height:' +
              browserHeight +
              'px'
            "
          >
            <EventContactNotes
              :key="selectedAlarm.id"
              v-if="selectedAlarm"
              :colorcodes="colorcodes"
              :customer="selectedAlarm?.device.customer"
              :alarm="selectedAlarm"
              @emitreloadEventNotes3="reloadEventNotes4()"
              @emitShowCustomerInfoTabs="changeStatusBusinessInfoTabs"
              :browserHeight="browserHeight"
            />
          </v-card-text>
        </v-card>
      </v-col>

      <v-col style="padding-top: 0px; padding-left: 0px">
        <v-card elevation="2">
          <v-card-text
            :style="' padding:0px;height:' + parseInt(browserHeight) + 'px'"
          >
            <EventsListBusinessInfoTabs
              v-if="selectedAlarm"
              :customer="selectedAlarm?.device.customer"
              :device="selectedAlarm?.device"
              :alarm="selectedAlarm"
              :key="selectedAlarm.id"
              :browserHeight="browserHeight"
            />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import google_map_style_bandw from "../../google/google_style_blackandwhite.json";
import google_map_style_regular from "../../google/google_style_regular.json";

import Topmenu from "../../components/Operator/topmenu.vue";
import EventsListBusinessInfoTabs from "../../components/Operator/EventsListBusinessInfoTabs.vue";

import MapFooterContent from "../../components/Operator/MapFooterContent.vue";
import Clock from "../../components/Operator/Clock.vue";
import OperatorGoogleMap from "../../components/Operator/OperatorGoogleMap.vue";
import OperatorSensorPhotos from "../../components/Operator/OperatorSensorPhotos.vue";
import OperatorCustomerContacts from "../../components/Operator/OperatorCustomerContacts.vue";
import EventContactNotes from "../../components/Operator/EventContactNotes.vue";
import EventAlarmNotes from "../../components/Operator/EventAlarmNotes.vue";

export default {
  layout: "operator",
  components: {
    Topmenu,
    MapFooterContent,
    Clock,
    OperatorGoogleMap,
    OperatorSensorPhotos,
    OperatorCustomerContacts,
    EventContactNotes,
    EventAlarmNotes,
    EventsListBusinessInfoTabs,
  },
  // alarm_event_operator_statistics
  data: () => ({
    browserHeight: 600, // Initial height
    displayEventsListBusinessInfoTabs: false,
    globalsearch: "",
    keyLogs: 1,
    showMappingSection: false,
    showAlarmEventNotes: false,
    displayAlarmMap: [],
    displayAlarmMapId: null,
    OperatorGoogleMapKey: 1,
    selectedAlarm: null,
    //displayAlarmMap: false,
    loading: true,
    filterBuildingType: null,
    filterAlarmType: null,
    filterAlarmStatus: 1,
    fullscreen: false,
    windowHeight: 1000,
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

    colorcodes: null,
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
    cancelGetdatafromAPITokenSource: null, // Keep track of the cancel token
  }),
  computed: {},

  beforeDestroy() {
    if (window) window.removeEventListener("resize", this.onResize);
  },
  async mounted() {
    setTimeout(() => {
      this.onResize();
    }, 1000 * 20);

    if (window) window.addEventListener("resize", this.onResize);

    if (window) {
      this.windowHeight = window.innerHeight;

      const element = document.getElementById("lefteventlist");
      if (element) {
        this.windowHeight = element.getBoundingClientRect().height;
      }

      // this.windowWidth = window.innerWidth;
    }
    // setTimeout(() => {
    //   this.getDatafromApi("alarm");
    // }, 1000 * 2);
    // await this.getMapKey();

    // setInterval(() => {
    //   this.getDatafromApi();
    // }, 1000 * 30);

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }
    await this.getDatafromApi(this.filterText);
    await this.getMapKey();

    this.getBuildingTypes();
    this.getAlarmTypes();

    setInterval(() => {
      console.log(this.$auth.user);

      if (!this.$auth.user) {
        this.$router.push("/logout");
        return;
      }
    }, 1000 * 30);
  },

  async created() {
    this.colorcodes = this.$utils.getAlarmIcons();
    try {
      if (window) this.browserHeight = window.innerHeight - 70;
    } catch (e) {}
    console.log(this.browserHeight);
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
    changeStatusBusinessInfoTabs(status) {
      this.displayEventsListBusinessInfoTabs = status;
    },
    reloadEventNotes4() {
      this.keyLogs++;
      setTimeout(() => {
        this.onResize();
      }, 1000 * 5);
    },
    async getAlarmTypes() {
      const { data } = await this.$axios.get("alarm_types", {
        params: {
          company_id: this.$auth.user.company_id,
        },
      });

      this.alarmTypes = data;
    },
    closeMap() {
      this.selectedAlarm = null;
      this.showMappingSection = false;
      this.showAlarmEventNotes = false;
    },
    showMap(alarm) {
      this.showAlarmEventNotes = false;
      this.showMappingSection = true;
      this.OperatorGoogleMapKey++;
      this.selectedAlarm = alarm;
    },
    closeAlarmEventNotes() {
      this.selectedAlarm = null;
      this.showAlarmEventNotes = false;
      this.showMappingSection = false;
    },
    showNotes(alarm) {
      if (this.showAlarmEventNotes) {
        this.selectedAlarm = null;
        this.showAlarmEventNotes = false;
      } else {
        this.showAlarmEventNotes = true;
        this.OperatorGoogleMapKey++;
        this.selectedAlarm = alarm;
      }
      this.showMappingSection = false;
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
      if (window) {
        if (window) {
          this.windowHeight = window.innerHeight;

          const element = document.getElementById("lefteventlist");
          if (element) {
            this.windowHeight = element.getBoundingClientRect().height;
          }

          // this.windowWidth = window.innerWidth;
        }
      }
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
    selectAlarmEvent(alarm) {
      this.loading = true;
      this.selectedAlarm = alarm;
      this.keyLogs++;
      this.loading = false;
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

    async getDatafromApi(filterText = "") {
      if (this.cancelGetdatafromAPITokenSource) {
        this.cancelGetdatafromAPITokenSource.cancel(
          "request canceled due to new request."
        );
      }
      this.cancelGetdatafromAPITokenSource = this.$axios.CancelToken.source();
      this.filterText = filterText;

      this.loading = true;

      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
          // date_from: this.date_from,
          // date_to: this.date_to,
          common_search: this.commonSearch,
          eventID: this.filterText,
          filterBuildingType: this.filterBuildingType,
          filterAlarmStatus: this.filterAlarmStatus,

          filterAlarmType: this.filterAlarmType,
        },
        cancelToken: this.cancelGetdatafromAPITokenSource.token,
      };

      try {
        this.$axios
          .get(`get_operator_alarm_events`, options)
          .then(({ data }) => {
            //this.mapkeycount++;
            this.data = data.data; //data.data;

            this.loading = false;
            this.selectedAlarm = this.data[0];

            if (this.$route.query.id) {
              this.selectedAlarmFilter = this.data.find(
                (x) => x.id == this.$route.query.id
              );

              if (this.selectedAlarmFilter)
                this.selectedAlarm = this.selectedAlarmFilter;
            }

            this.onResize();
            // this.mapMarkersList.forEach((marker, index) => {
            //   if (marker) {
            //     marker.visible = false;
            //     marker.setMap(null);
            //     marker = null;
            //     this.mapMarkersList[index] = null;
            //   }
            // });
            // this.mapMarkersList = [];

            // //this.plotLocations();
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
    getAlarmColorObject(alarm, customer = null) {
      if (alarm) {
        if (this.colorcodes[alarm.alarm_type.toLowerCase()])
          return this.colorcodes[alarm.alarm_type.toLowerCase()];
        if (alarm.alarm_status == 1) {
          return this.colorcodes.alarm;
        }
      }
      // else if (alarm.alarm_status == 0) {
      //   return this.colorcodes.closed;
      // }
      //if (
      //   alarm.customer &&
      //   this.findanyArmedDevice(alarm.customer.devices)
      // ) {
      //   return this.colorcodes.armed;
      // }
      else if (customer) {
        if (this.findAnyDeviceisOffline(customer.devices) > 0) {
          return this.colorcodes.offline;
        } else if (this.findanyArmedDevice(customer.devices)) {
          return this.colorcodes.armed;
        } else if (this.findanyDisamrDevice(customer.devices) > 0) {
          return this.colorcodes.disarm;
        }
      }
      // console.log(
      //   "findAnyDeviceisOffline",
      //   this.findAnyDeviceisOffline(item.devices)
      // );
      // console.log(alarm.alarm_status);

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
      try {
        if (
          this.map &&
          customer.latitude &&
          customer.longitude &&
          !isNaN(customer.latitude) &&
          !isNaN(customer.longitude)
        ) {
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
      if (!this.map && document.getElementById("map")) {
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
    },
    plotLocations() {
      //set default one customer

      if (
        this.map &&
        this.data &&
        this.data[0] &&
        this.data[0].device.utc_time_zone != "Asia/Dubai" &&
        this.data[0].device.customer.latitude != "" &&
        this.data[0].device.customer.longitude != "" &&
        !isNaN(this.data[0].device.customer.latitude) &&
        !isNaN(this.data[0].device.customer.longitude)
      ) {
        const position = {
          lat: parseFloat(this.data[0].device.customer.latitude),
          lng: parseFloat(this.data[0].device.customer.longitude),
        };
        this.map.panTo(position);
      }

      this.data.forEach((item) => {
        if (item.device?.customer) {
          const customerId = item.device.customer.id;

          // Check if a marker already exists for this customer
          if (this.mapMarkersList[customerId]) {
            // Skip if marker already exists with alarm_status = 1 (high priority)
            if (
              item.alarm_status == 1 &&
              this.mapMarkersList[customerId].alarm_status != "1"
            )
              this.mapMarkersList[customerId].setMap(null);
          }

          // Determine if we should load a marker for this customer
          let loadMarker =
            item.alarm_status == "1" || !this.mapMarkersList[customerId];

          if (loadMarker) {
            try {
              const position = {
                lat: parseFloat(item.device.customer.latitude),
                lng: parseFloat(item.device.customer.longitude),
              };

              // Determine icon based on alarm or other conditions
              let iconURL =
                process.env.BACKEND_APP_URL +
                "/google_map_icons/google_online.png";
              const colorObject = this.getAlarmColorObject(item);
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
                alarm_status: item.alarm_status,
              });

              // Create content for the infowindow
              let alarmHtmlLink = "";
              let customerHtmlLink = `<button class="primary v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="customerInfowindow-btn-${item.id}">View</button>`;

              if (item.alarm_start_datetime)
                alarmHtmlLink = `<button class="error v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="alarmInfowindow-btn-${item.id}">Alarm</button>`;

              let profile_picture =
                item.device.customer.profile_picture ||
                "https://alarm.xtremeguard.org/no-business_profile.png";

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
                </td>
              </tr>
              <tr>
                <td>
                  <a target="_blank" href="https://www.google.com/maps?q=${item.device.customer.latitude},${item.device.customer.longitude}">Google Directions</a>
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

              // Store marker and infowindow references
              this.mapInfowindowsList[customerId] = infowindow;
              this.mapMarkersList[customerId] = marker;

              // Add bounce animation if alarm is active
              if (item.alarm_status == 1)
                marker.setAnimation(google.maps.Animation.BOUNCE);

              // Marker event listeners
              marker.addListener("mouseover", () => {
                this.mapInfowindowsList.forEach((oldinfowindow) =>
                  oldinfowindow.close()
                );
                infowindow.open(this.map, marker);
              });

              google.maps.event.addListener(infowindow, "domready", () => {
                const alarmBtn = document.getElementById(
                  `alarmInfowindow-btn-${item.id}`
                );
                const customerBtn = document.getElementById(
                  `customerInfowindow-btn-${item.id}`
                );

                if (alarmBtn)
                  alarmBtn.addEventListener("click", () =>
                    this.viewAlarmInformation(item.alarm_start_datetime)
                  );
                if (customerBtn)
                  customerBtn.addEventListener("click", () => {
                    this.dialog = true;
                    this.key += 1;
                    this.customerInfo = item.device.customer;
                  });

                const infowindowContent = document.getElementById(
                  `infowindow-content-${item.id}`
                );
                infowindowContent.addEventListener("mouseout", (e) => {
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
        }
      });
    },
  },
};
</script>
