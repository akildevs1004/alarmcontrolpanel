<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" centered color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>

    <v-dialog v-model="dialogCameraLive" width="600px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Camera Live</span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="dialogCameraLive = false"
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text style="padding: 0px">
          <v-container style="min-height: 100px; padding: 0px">
            <iframe
              v-if="dialogCameraLive && getCameraURL() == ''"
              :src="getCameraURL()"
              width="100%"
              height="350"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
            <div>Camera Live Is not Available</div>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- <v-dialog v-model="dialog" width="1000px" style="overflow: visible">
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
    </v-dialog> -->
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
      <v-col
        class="main-leftcontent"
        style="padding-right: 0px; padding-bottom: 0px"
      >
        <v-card elevation="10" outlined>
          <div
            :key="mapkeycount"
            id="map"
            :style="'height:' + windowHeight + 'px'"
          ></div>

          <v-navigation-drawer
            temporary
            v-model="dialogCustomerRightInfo"
            right
            absolute
            :persistent="true"
            :dark="true"
            :hide-overlay="true"
          >
            <AlarmCustomerMapViewSidebar
              class="AlarmCustomerMapViewSidebar"
              style="background-color: #516067 !important"
              v-if="customerInfo"
              :key="key"
              :_id="customerInfo.id"
              :isPopup="true"
              :isMapviewOnly="true"
              :alarmId="eventId"
              :customerObject="customerInfo"
              @closeDialog="dialogCustomerRightInfo = false"
          /></v-navigation-drawer>

          <div style="position: absolute; top: 5px; left: 50%">
            <!-- <v-autocomplete
              style="padding-top: 6px"
              @change="gotoCustomerLocationOnMap()"
              class="reports-events-autocomplete bgwhite"
              v-model="filter_customer_id"
              :items="[
                { id: null, building_name: 'All Customers' },
                ...customersList,
              ]"
              dense
              outlined
              item-value="id"
              item-text="building_name"
            >
            </v-autocomplete> -->

            <v-text-field
              class="search-autocomplete bgwhite searchicon"
              outlined
              append-icon="mdi-magnify"
              v-model="filter_customer_id"
              @click:append="gotoCustomerLocationOnMap()"
              @click:clear="clearCustomerPopup()"
              dense
              clearable
              height="20px"
              label="Search Business Name"
            >
            </v-text-field>
          </div>
          <!-- <div style="position: absolute; top: 50px; left: 10px">
            <img
              title="Alarm Control Panel - Xtremeguard"
              src="/logo_header.png"
              style="width: 50px"
            />
          </div> -->
          <!-- <div style="position: absolute; top: 50px; left: 10px">
            <Clock></Clock>
          </div> -->

          <div style="position: absolute; top: -3px; left: 140px">
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
                value="fullacreen"
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

          <div style="position: absolute; bottom: 20px; left: 0px; width: 100%">
            <MapFooterContent
              v-if="loadStatistics"
              :colorcodes="colorcodes"
              style="width: 850px; margin: auto"
            />
          </div>

          <div style="position: absolute; top: 50%; right: -5px; z-index: 99">
            <v-icon
              v-if="!displayRightcontant"
              @click="changeRightContentDisplay()"
              color="green"
              >mdi-arrow-left-box</v-icon
            >
            <!-- <v-icon
              v-if="displayRightcontant"
              @click="changeRightContentDisplay()"
              color="red"
              >mdi-close-circle</v-icon
            > -->
          </div>
          <div
            v-if="displayRightcontant"
            style="position: absolute; top: 50%; right: -12px; z-index: 99"
          >
            <v-icon @click="changeRightContentDisplay()" color="red"
              >mdi-close-circle</v-icon
            >
          </div>
        </v-card>
      </v-col>

      <v-col
        v-if="displayRightcontant"
        class="main-rightcontent"
        style="padding-left: 0px; padding-bottom: 0px"
      >
        <v-card
          :loading="loading"
          elevation="10"
          outlined
          :style="
            ' height:' +
            windowHeight +
            'px;overflow-y: auto;overflow-x: hidden;margin-top:3px!important;background-color: #fff'
          "
        >
          <v-card-text style="padding: 0px">
            <!-- <Topmenu
              @applyGlobalSearch="getDatafromApi"
              style="padding: 10px"
            /> -->

            <v-row v-if="data.length == 0" class="text-center">
              <v-col style="color: #fff"> 0 Alarms </v-col>
            </v-row>

            <div style="">
              <v-card
                v-if="alarm.device?.customer"
                :key="index + 55"
                v-for="(alarm, index) in data"
                elevation="2"
                style="border-color: #d3d3d3; margin-top: 5px"
              >
                <v-card-text>
                  <v-row style="min-width: 200px; width: 100%">
                    <v-col
                      style="max-width: 40px; padding-left: 5px; margin: auto"
                    >
                      <img
                        @click="openWindow(alarm)"
                        :title="alarm.alarm_type"
                        style="width: 25px; padding-top: 0%"
                        :src="getOperatorColorObject(alarm).image + '?5=5'"
                      />
                    </v-col>
                    <v-col
                      style="
                        padding: 0px;
                        font-size: 10px;
                        padding-left: 10px;
                        line-height: 15px;
                        padding-top: 5px;
                      "
                    >
                      <div style="overflow: hidden">
                        <!-- {{
                        alarm.device.customer.primary_contact
                          ? alarm.device.customer.primary_contact.first_name +
                            " " +
                            alarm.device.customer.primary_contact.last_name
                          : "---"
                      }}
                      <br /> -->
                        <div style="color: #de651e; font-size: 10px">
                          {{
                            alarm.device.customer.buildingtype?.name || "---"
                          }}
                        </div>
                        <div style="color: black; font-size: 14px">
                          {{ alarm.device.customer.building_name || "---" }}
                        </div>
                        <div style="color: black">
                          {{
                            alarm.device?.customer?.area
                              ? alarm.device.customer.area
                              : "---"
                          }}
                          ,
                          {{ alarm.device.customer.city }}
                        </div>

                        <div style="color: #534949; text-align: left">
                          {{
                            $dateFormat.formatTimeDateMonthYear(
                              alarm.alarm_start_datetime
                            )
                          }}
                        </div>
                        <!-- <div style="color: #bbb3b3">
                        {{
                          alarm.device.customer.primary_contact?.phone1 || "---"
                        }}
                      </div> -->

                        <!-- <br />
                      {{
                        alarm.device.customer.primary_contact?.phone1 || "---"
                      }} -->
                      </div>
                      <!-- <div style="font-size: 10px">
                        <v-row
                          ><v-col style="padding-left: 0px"
                            ><div style="color: #cecece; text-align: left">
                              {{
                                $dateFormat.formatDateMonthYear(
                                  alarm.alarm_start_datetime
                                )
                              }}
                            </div></v-col
                          >
                           
                          <v-col class="text-right" style="color: #0748ff"
                            >#{{ alarm.id }}</v-col
                          >
                        </v-row>
                      </div> -->
                      <div
                        style="
                          text-align: right;
                          position: absolute;
                          top: 10px;
                          right: 10px;
                        "
                      >
                        <!-- <v-icon color="red" size="18">mdi-lock-outline</v-icon> -->
                        <div>
                          <v-icon
                            v-if="alarm.device?.customer?.cameras?.length > 0"
                            @click="openCameradialog(alarm)"
                            color="black"
                            size="25"
                            >mdi-camera-outline</v-icon
                          >
                          <v-icon v-else color="#DDD" size="25"
                            >mdi-camera-outline</v-icon
                          >
                        </div>

                        <div>#{{ alarm.id }}</div>

                        <!-- <img
                        src="/alarm_icons/alarm_open.png"
                        style="width: 30px"
                      /> -->
                      </div>

                      <!-- <div style="color: #0064ff">
                      <v-row>
                        <v-col>
                           {{
                            alarm.device.customer.buildingtype
                              ? alarm.device.customer.buildingtype.name
                              : "---"
                          }}   </v-col
                        ><v-col>
                          <v-icon
                            v-if="
                              !showMappingSection ||
                              selectedAlarm == null ||
                              (selectedAlarm && selectedAlarm.id != alarm.id)
                            "
                            title="Show Map"
                            @click="showMap(alarm)"
                            size="20"
                            color="black"
                            style="padding-bottom: 5px"
                            >mdi-map-outline</v-icon
                          >
                          <v-icon
                            v-else-if="
                              showMappingSection &&
                              selectedAlarm &&
                              selectedAlarm.id == alarm.id
                            "
                            title="Show Map"
                            @click="closeMap()"
                            size="20"
                            color="green"
                            style="padding-bottom: 5px"
                            >mdi-map-outline</v-icon
                          ></v-col
                        ><v-col
                          ><v-icon
                            :title="alarm.alarm_status == 1 ? 'Open' : 'Close'"
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
                          ></v-col
                        ></v-row
                      >
                    </div> -->
                    </v-col>
                    <!-- <v-col style="max-width: 90px; padding: 2px; font-size: 11px">
                    <div style="text-align: right">#{{ alarm.id }}</div>
                    <div style="margin: auto; text-align: center; height: 40px">
                      <img
                        @click="showNotes(alarm)"
                        :title="alarm.alarm_type"
                        style="width: 30px; padding-top: 0%"
                        :src="getAlarmColorObject(alarm).image + '?5=5'"
                      />
                    </div>
                    <div style="">
                      <div style="color: red; text-align: center; height: 14px">
                        <div v-if="alarm.alarm_status == 0">
                          {{
                            $dateFormat.formatDateMonthYear(
                              alarm.alarm_end_datetime
                            )
                          }}
                        </div>
                      </div>
                      <div style="color: red; text-align: center">
                        {{
                          $dateFormat.formatDateMonthYear(
                            alarm.alarm_start_datetime
                          )
                        }}
                      </div>
                    </div>
                  </v-col> -->
                  </v-row>
                  <!-- <v-row style="min-width: 300px; height: 95px; width: 100%">
                  <v-col
                    style="
                      max-width: 60px;
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
                        height: 60px;
                        vertical-align: bottom;
                      "
                    />
                  </v-col>
                  <v-col
                    style="
                      padding: 0px;
                      font-size: 12px;
                      padding-left: 10px;
                      line-height: 16px;
                    "
                  >
                    <div style="height: 79px; overflow: hidden">
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
                      <v-row
                        ><v-col>
                          {{
                            alarm.device.customer.buildingtype
                              ? alarm.device.customer.buildingtype.name
                              : "---"
                          }}</v-col
                        ><v-col>
                          <v-icon
                            v-if="
                              !showMappingSection ||
                              selectedAlarm == null ||
                              (selectedAlarm && selectedAlarm.id != alarm.id)
                            "
                            title="Show Map"
                            @click="showMap(alarm)"
                            size="20"
                            color="black"
                            style="padding-bottom: 5px"
                            >mdi-map-outline</v-icon
                          >
                          <v-icon
                            v-else-if="
                              showMappingSection &&
                              selectedAlarm &&
                              selectedAlarm.id == alarm.id
                            "
                            title="Show Map"
                            @click="closeMap()"
                            size="20"
                            color="green"
                            style="padding-bottom: 5px"
                            >mdi-map-outline</v-icon
                          ></v-col
                        ><v-col
                          ><v-icon
                            :title="alarm.alarm_status == 1 ? 'Open' : 'Close'"
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
                          ></v-col
                        ></v-row
                      >
                    </div>
                  </v-col>
                  <v-col style="max-width: 90px; padding: 2px; font-size: 11px">
                    <div style="text-align: right">#{{ alarm.id }}</div>
                    <div style="margin: auto; text-align: center; height: 40px">
                      <img
                        @click="showNotes(alarm)"
                        :title="alarm.alarm_type"
                        style="width: 30px; padding-top: 0%"
                        :src="getAlarmColorObject(alarm).image + '?5=5'"
                      />
                    </div>
                    <div style="">
                      <div style="color: red; text-align: center; height: 14px">
                        <div v-if="alarm.alarm_status == 0">
                          {{
                            $dateFormat.formatDateMonthYear(
                              alarm.alarm_end_datetime
                            )
                          }}
                        </div>
                      </div>
                      <div style="color: red; text-align: center">
                        {{
                          $dateFormat.formatDateMonthYear(
                            alarm.alarm_start_datetime
                          )
                        }}
                      </div>
                    </div>
                  </v-col>
                </v-row> -->

                  <v-row
                    style="width: 100%; margin-top: 20px"
                    v-if="
                      showAlarmEventNotes &&
                      selectedAlarm &&
                      selectedAlarm.id == alarm.id
                    "
                  >
                    <v-col style="padding: 0px">
                      <v-tabs
                        height="25"
                        center-active
                        right
                        class="customerEmergencyContactTabs"
                      >
                        <v-tab
                          v-if="
                            item.address_type.toLowerCase() == 'primary' ||
                            item.address_type.toLowerCase() == 'secondary' ||
                            item.address_type.toLowerCase() == 'security'
                          "
                          v-for="(item, index) in alarm.device.customer
                            .contacts"
                          :key="item.id"
                        >
                          {{ item.address_type }}</v-tab
                        >
                        <v-tab-item
                          v-if="
                            contact.address_type.toLowerCase() == 'primary' ||
                            contact.address_type.toLowerCase() == 'secondary' ||
                            contact.address_type.toLowerCase() == 'security'
                          "
                          v-for="(contact, index) in alarm.device.customer
                            .contacts"
                          :key="contact.id + 50"
                          name="index+50"
                        >
                          <v-card class="elevation-1">
                            <OperatorCustomerContacts
                              :alarmId="alarm.id"
                              v-if="contact"
                              :customer="alarm.device.customer"
                              :contact_type="contact.address_type"
                              :key="contact.address_type"
                            />
                          </v-card>
                        </v-tab-item>
                      </v-tabs>
                    </v-col>
                  </v-row>
                  <v-row
                    transition="slide-x-transition"
                    v-if="
                      showMappingSection &&
                      selectedAlarm &&
                      selectedAlarm.id == alarm.id
                    "
                  >
                    <v-col style="padding: 15px; padding-left: 0px">
                      <OperatorGoogleMap
                        style="width: 95%; text-align: center; margin: auto"
                        :key="OperatorGoogleMapKey"
                        class="rounded-lg"
                        :customer="alarm.device.customer"
                        :customer_id="alarm.device.customer.id"
                        :name="'OperatorGoogleMapCustomer' + alarm.id"
                        :mapimage="
                          getAlarmColorObject(alarm, customer).image + '?5=5'
                        "
                      />

                      <OperatorSensorPhotos
                        style="width: 95%; text-align: center; margin: auto"
                        :key="OperatorGoogleMapKey + 100"
                        :name="'OperatorSensorPhotos' + alarm.id"
                        class="rounded-lg"
                        :customer_id="alarm.device.customer.id"
                      />
                    </v-col>
                  </v-row>
                  <!-- </v-fab-transition> -->
                </v-card-text>
              </v-card>
            </div>
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
import AlarmCustomerMapViewSidebar from "../../components/Alarm/AlarmCustomerMapViewSidebar.vue";

import AlarmEventCustomerContactsTabView from "../../components/Alarm/AlarmEventCustomerContactsTabView.vue";
import Topmenu from "../../components/Operator/topmenu.vue";
import MapFooterContent from "../../components/Operator/MapFooterContent.vue";
import Clock from "../../components/Operator/Clock.vue";
import OperatorGoogleMap from "../../components/Operator/OperatorGoogleMap.vue";
import OperatorSensorPhotos from "../../components/Operator/OperatorSensorPhotos.vue";
import OperatorCustomerContacts from "../../components/Operator/OperatorCustomerContacts.vue";
export default {
  layout: "operator",
  components: {
    AlarmCustomerTabsView,
    AlarmEventCustomerContactsTabView,
    Topmenu,
    MapFooterContent,
    Clock,
    OperatorGoogleMap,
    OperatorSensorPhotos,
    OperatorCustomerContacts,
    AlarmCustomerMapViewSidebar,
  },
  // alarm_event_operator_statistics
  data: () => ({
    dialogCustomerRightInfo: false,
    dialogCameraLive: false,
    snackbar: false,
    response: "",
    filter_customer_id: null,
    customersList: [],
    displayRightcontant: true,
    loadStatistics: false,
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
    windowHeight: 650,
    windowWidth: 600,
    mapStyle: "map",
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

    colorcodes: [],
    colorcodesMap: [],
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
    customersData: [],
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
    }, 1000 * 5);

    if (window) window.addEventListener("resize", this.onResize);

    // if (window) {
    //   this.windowHeight = window.innerHeight - 20;
    //   this.windowWidth = window.innerWidth;
    // }
    // setTimeout(() => {
    //   this.getDatafromApi("alarm");
    // }, 1000 * 2);
    // await this.getMapKey();
    // setInterval(async () => {}, 1000 * 8);
    setInterval(async () => {
      if (this.$route.name == "operator-dashboard") {
        if (this.filterText == "") await this.getDatafromApi(this.filterText);
        setTimeout(async () => {
          await this.getCustomersDatafromApi();
        }, 1000);
      }
    }, 1000 * 15);

    setInterval(() => {
      if (this.$route.name == "operator-dashboard") {
        this.updateOperatorLiveStatus();
      }
    }, 1000 * 60);

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }
    await this.getMapKey();
    await this.getDatafromApi();
    await this.getCustomersDatafromApi("", true);

    await this.getBuildingTypes();
    await this.getAlarmTypes();
    this.loadStatistics = true;
    // setTimeout(() => {}, 1000 * 2);
  },

  async created() {
    this.colorcodes = this.$utils.getAlarmIcons();
    this.colorcodesTable = this.$utils.getAlarmIconsNoGoogle();

    this.loadCustomersList();
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

    getCameraURL() {
      if (
        this.selectedAlarm &&
        this.selectedAlarm?.device.customer.cameras[0]
      ) {
        return (
          process?.env.CAMERA_RTMP_PLAYER +
          "?url=" +
          this.selectedAlarm?.device.customer.cameras[0].camera_url
        );
      }
      return "";
    },
    openCameradialog(alarm) {
      this.selectedAlarm = alarm;
      this.dialogCameraLive = true;
    },
    loadCustomersList() {
      this.customersList = [];
      this.$axios
        .get("customers_list", {
          params: { company_id: this.$auth.user.company_id },
        })
        .then((data) => {
          this.customersList = data.data;
        });
    },
    changeRightContentDisplay() {
      this.displayRightcontant = !this.displayRightcontant;
    },
    openWindow(item) {
      const width = window.screen.width;
      const height = window.screen.height;
      window.open(
        process.env.APP_URL + "/operator/eventslist?id=" + item.id,
        "_blank",
        `width=${width},height=${height},toolbar=yes, location=yes,resizable=yes,scrollbars=yes`
      );
    },
    updateOperatorLiveStatus() {
      this.$axios.post("operator_live_update", {
        company_id: this.$auth.user.company_id,
        security_id: this.$auth.user.security.id,
      });
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
        this.windowWidth = window.innerWidth;
        this.windowHeight = window.innerHeight - 70;
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
      // console.log(this.cancelGetdatafromAPITokenSource);

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
            if (this.customersData) this.plotLocations();
            // this.mapMarkersList.forEach((marker, index) => {
            //   if (marker) {
            //     marker.visible = false;
            //     marker.setMap(null);
            //     marker = null;
            //     this.mapMarkersList[index] = null;
            //   }
            // });
            // this.mapMarkersList = [];
            // setTimeout(() => {
            //   this.plotLocations();
            // }, 1000 * 5);
          });
      } catch (e) {}
    },
    async getCustomersDatafromApi(filterText = "", loadMap = false) {
      if (this.cancelgetCustomersDatafromApiTokenSource) {
        this.cancelgetCustomersDatafromApiTokenSource.cancel(
          "request canceled due to new request."
        );
      }
      this.cancelgetCustomersDatafromApiTokenSource =
        this.$axios.CancelToken.source();
      this.filterText = filterText;

      this.loading = true;

      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          // customer_id: this.customer_id,
          // // date_from: this.date_from,
          // // date_to: this.date_to,
          // common_search: this.commonSearch,
          // //filter_text: filterText == "" ? "alarm" : filterText,
          // filterBuildingType: this.filterBuildingType,
          // filterAlarmStatus: this.filterAlarmStatus,

          // filterAlarmType: this.filterAlarmType,
        },
        cancelToken: this.cancelgetCustomersDatafromApiTokenSource.token,
      };

      try {
        this.$axios.get(`customers_for_map`, options).then(({ data }) => {
          //this.mapkeycount++;
          this.customersData = data; //data.data;

          //console.log(this.customersData[0].devices[0].utc_time_zone);

          if (
            this.customersData.length > 0 &&
            loadMap &&
            this.customersData[0].devices[0]?.utc_time_zone != "Asia/Dubai"
          ) {
            this.setCustomerLocationOnMap(this.customersData[0]);
          }

          this.loading = false;

          // setTimeout(() => {
          this.mapMarkersList.forEach((marker, index) => {
            if (marker) {
              marker.visible = false;
              marker.setMap(null);
              marker = null;
              this.mapMarkersList[index] = null;
            }
          });
          this.plotLocations();
          //}, 1000 * 5);
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
    getOperatorColorObject(alarm, customer = null) {
      if (alarm) {
        if (this.colorcodesTable[alarm.alarm_type.toLowerCase()])
          return this.colorcodesTable[alarm.alarm_type.toLowerCase()];
        if (alarm.alarm_status == 1) {
          return this.colorcodesTable.alarm;
        }
      } else if (customer) {
        if (this.findAnyDeviceisOffline(customer.devices) > 0) {
          return this.colorcodesTable.offline;
        } else if (this.findanyArmedDevice(customer.devices)) {
          return this.colorcodesTable.armed;
        } else if (this.findanyDisamrDevice(customer.devices) > 0) {
          return this.colorcodesTable.disarm;
        }
      }

      return this.colorcodesTable.offline;
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
      return onlineArray ? onlineArray.length : 0;
      // console.log("offlineArray", offlineArray.length);
      // return onlineArray
      //   ? onlineArray.length == devices.length
      //     ? true
      //     : false
      //   : 0;
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
    gotoCustomerLocationOnMap() {
      if (this.filter_customer_id) {
        let customer = this.customersList.filter((customer1) =>
          customer1.building_name
            ?.toLowerCase()
            .startsWith(this.filter_customer_id?.toLowerCase())
        );
        //console.log("customer", customer);

        if (customer) {
          this.setCustomerLocationOnMap(customer[0]);
        } else {
          this.snackbar = true;
          this.response = "No Customers found";
        }
      }
    },
    clearCustomerPopup() {
      this.mapInfowindowsList.forEach((infowindow) => {
        infowindow.close();
      });
    },
    setCustomerLocationOnMap(customer) {
      try {
        if (
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
          this.map.setZoom(12);

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
          styles: this.google_map_style_regular,
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
    findCustomerInAlarmList(customerId) {
      let CustomerLatestAlarmEvent = [];
      CustomerLatestAlarmEvent = this.data.find(
        (event) => event.customer_id == customerId && event.alarm_status == 1
      );

      return CustomerLatestAlarmEvent;
    },
    plotLocations() {
      //this.mapMarkersList = [];
      let mapMarkersListUpdated = [];

      // var myoverlay = new google.maps.OverlayView();
      // myoverlay.draw = function () {
      //   // Accessing the panes
      //   const panes = this.getPanes();
      //   if (panes) {
      //     panes.markerLayer.id = "markerLayer"; // Assign an ID for custom styling
      //   }
      // };
      // myoverlay.setMap(this.map); // Add overlay to the map

      this.customersData.forEach((item) => {
        if (item) {
          const customerId = item.id;
          let loadMarker = true;

          if (this.mapMarkersList[customerId]) {
            this.mapMarkersList[customerId].visible = false;
            this.mapMarkersList[customerId].setMap(null);
            this.mapMarkersList[customerId] = null;
          }

          let alarm = this.findCustomerInAlarmList(item.id);

          item.latest_alarm_event = null;

          if (alarm) {
            item.latest_alarm_event = alarm;
          }

          if (loadMarker) {
            try {
              const position = {
                lat: parseFloat(item.latitude),
                lng: parseFloat(item.longitude),
              };

              // Determine icon based on alarm or other conditions
              let iconURL =
                process.env.BACKEND_URL2 +
                "/google_map_icons/google_offline.png";
              const colorObject = this.getAlarmColorObject(
                item.latest_alarm_event,
                item
              );
              if (colorObject) iconURL = colorObject.image;

              const icon = {
                url: iconURL + "?5=5",
                scaledSize: new google.maps.Size(28, 34),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(25, 25),
              };

              const marker = new google.maps.Marker({
                position,
                map: this.map,
                title: item.name,
                icon: icon,
                alarm_status: item.latest_alarm_event?.alarm_status,
              });
              let alarmId = item.latest_alarm_event?.id;

              let googleDirectionIcon =
                process.env.APP_URL + "/icons/google-directions.png";
              let googleInfoIcon =
                process.env.APP_URL + "/icons/google-info.png";

              // Create content for the infowindow
              let alarmHtmlLink = "";
              let customerHtmlLink = `<button class="primary v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="customerInfowindow-btn-${item.id}">View</button>`;

              customerHtmlLink = `<img  id="customerInfowindow-btn-${item.id}" src="${googleInfoIcon}" style="width:20px;"/>`;

              if (item.latest_alarm_event?.alarm_start_datetime) {
                // alarmHtmlLink = `<button class="error v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--x-small" id="alarmInfowindow-btn-${item.id}">Alarm</button>`;

                alarmHtmlLink = `<img id="alarmInfowindow-btn-${item.id}" src="${iconURL}" style="width:16px;"/>`;
              }

              let profile_picture =
                item.profile_picture ||
                "https://alarm.xtremeguard.org/no-business_profile.png";

              let html = `
            <table style="width:250px; min-height:100px" id="infowindow-content-${item.id}">

               <tr>
                <td colspan="2" style="width:100%;;text-align:center; vertical-align: top;">
                 <div style="width:100%;margin:auto">
                   <img style="margin:auto;width:auto; max-height:120px; padding-right:5px;" src="${profile_picture}" />
                  </div>

                </td>
                </tr>
              <tr>

                <td style=" vertical-align: top;font-size:10px">
                  <div>${item.buildingtype.name}</div>
                 <div style="font-weight:bold;font-size:12px"> ${item.building_name}</div>

                 <div>${item.house_number},${item.street_number}</div>
                 <div>${item.city}</div>

                  <div>Landmark: ${item.landmark}</div>
                </td>
<td style=" vertical-align: middle;text-align:right">
 <a  title="Directions"  target="_blank" href="https://www.google.com/maps?q=${item.latitude},${item.longitude}"><img title="Directions" style="width:20px" src="${googleDirectionIcon}"/></a>

 <span>
 ${customerHtmlLink}   ${alarmHtmlLink}
  </span>
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
              if (item.latest_alarm_event?.alarm_status == 1)
                marker.setAnimation(google.maps.Animation.BOUNCE);

              // Marker event listeners
              marker.addListener("mouseover", () => {
                this.mapInfowindowsList.forEach((oldinfowindow) =>
                  oldinfowindow.close()
                );
                infowindow.open(this.map, marker);
              });

              // marker.addListener("mouseout", () => {
              //   this.mapInfowindowsList.forEach((oldinfowindow) =>
              //     oldinfowindow.close()
              //   );
              //   infowindow.open(this.map, marker);
              // });

              google.maps.event.addListener(infowindow, "domready", () => {
                if (item.latest_alarm_event) {
                  const alarmBtn = document.getElementById(
                    `alarmInfowindow-btn-${item.id}`
                  );
                  if (alarmBtn)
                    alarmBtn.addEventListener("click", () =>
                      this.viewAlarmInformation(
                        item.latest_alarm_event.alarm_start_datetime
                      )
                    );
                }
                const customerBtn = document.getElementById(
                  `customerInfowindow-btn-${item.id}`
                );

                if (customerBtn)
                  customerBtn.addEventListener("click", () => {
                    //this.dialog = true;

                    this.key += 1;
                    this.customerInfo = item;
                    this.dialogCustomerRightInfo = true;

                    let position = {
                      lat: parseFloat(this.customerInfo.latitude),
                      lng: parseFloat(this.customerInfo.longitude),
                    };
                    this.map.panTo(position);

                    infowindow.open(this.map, marker);
                  });

                const infowindowContent = document.getElementById(
                  `infowindow-content-${item.id}`
                );
                infowindowContent.addEventListener("mouseout", (e) => {
                  // if (!infowindowContent.contains(e.relatedTarget)) {
                  //   infowindow.close();
                  // }
                });
              });

              // Open Vue dialog on marker click
              marker.addListener("click", () => {
                this.dialogCustomerRightInfo = true;

                //this.dialog = true;
                this.key += 1;
                this.customerInfo = item;

                let position = {
                  lat: parseFloat(this.customerInfo.latitude),
                  lng: parseFloat(this.customerInfo.longitude),
                };

                this.map.panTo(position);
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
