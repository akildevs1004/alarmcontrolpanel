<template>
  <div>
    <div class="text-center">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
      {{ snackText }}

      <template v-slot:action="{ attrs }">
        <v-btn v-bind="attrs" text @click="snack = false"> Close </v-btn>
      </template>
    </v-snackbar>
    <v-dialog v-model="dialogEditDevice" width="600px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Device Information </span>
          <v-spacer></v-spacer>
          <v-icon
            style="color: black"
            @click="dialogEditDevice = false"
            outlined
          >
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlarmEditDevice
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :editId="editId"
              :editDevice="editDevice"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogZones" width="1000px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Control Panel - Zones</span>
          <v-spacer></v-spacer>
          <v-icon style="color: black" @click="dialogZones = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlarmSensorZones
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :editId="editId"
              :editDevice="editDevice"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-card elevation="0" class="pa-0">
      <v-toolbar class="rounded-md" dense flat>
        <v-spacer></v-spacer>

        <span>
          <v-btn
            x-small
            :ripple="false"
            text
            title="Sync Devices"
            @click="updateDevicesHealth"
          >
            <v-icon dark white>mdi-cached</v-icon>
          </v-btn>
        </span>
        <span>
          <v-btn
            v-if="can(`device_create`)"
            x-small
            :ripple="false"
            title="New"
            color="primary"
            @click="
              key = key + 1;
              editId = null;
              editDevice = null;
              dialogEditDevice = true;
            "
            >New Device
          </v-btn>
        </span>
        <!-- <span class="ml-3">
          <v-btn
            v-if="can(`device_create`)"
            x-small
            :ripple="false"
            title="New"
            color="primary"
            @click="addItem()"
            >New Zone
          </v-btn>
        </span> -->
      </v-toolbar>

      <v-data-table
        dense
        :headers="headers"
        :items="data"
        model-value="data.id"
        :loading="loading"
        :footer-props="{
          itemsPerPageOptions: [50, 100, 500, 1000],
        }"
        class="elevation-1 pt-0"
        :options.sync="options"
        :server-items-length="totalRowsCount"
      >
        <template v-slot:item.sno="{ item, index }">
          {{ ++index }}
        </template>
        <template v-slot:item.device="{ item }">
          <div>{{ item.name }}</div>
          <div class="secondary-value">{{ item.serial_number }}</div>
        </template>
        <template v-slot:item.device_type="{ item }">
          <div>{{ item.device_type }}</div>
          <div class="secondary-value">{{ item.model_number }}</div>
        </template>
        <template v-slot:item.name="{ item }">
          {{ caps(item.name) }}
          <div class="secondary-value">{{ item.short_name }}</div>
        </template>
        <template v-slot:item.zones="{ item }">
          <div @click="editZones(item)">{{ item.sensorzones.length }}</div>
        </template>

        <template v-slot:item.location="{ item }">
          {{ caps(item.location) }}<br />
          <span class="secondary-value">{{
            item.zone ? item.zone + ", " : ""
          }}</span>
          <span class="secondary-value">{{ item.area }}</span>
        </template>

        <template v-slot:item.delay="{ item }">
          <div v-if="item.alarm_delay_minutes">
            {{
              item.alarm_delay_minutes == 0
                ? "No"
                : item.alarm_delay_minutes + " Min"
            }}
          </div>
          <span v-else>---</span>
        </template>
        <template v-slot:item.hrs_24="{ item }">
          <div v-if="item.hrs_24">
            {{ item.hrs_24 == 0 ? "No" : "Yes" }}
          </div>
          <span v-else>---</span>
        </template>
        <template v-slot:item.sensor="{ item }">
          <div v-if="item.device_type">
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Control Panel'"
              style="width: 30px"
              src="/alarm-icons/device_type_access_control.png"
            >
            </v-img>

            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Burglary'"
              style="width: 30px"
              src="/alarm-icons/burglary.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Medical'"
              style="width: 30px"
              src="/alarm-icons/medical.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Temperature'"
              style="width: 20px; margin-left: 5px"
              src="/alarm-icons/temperature.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Water'"
              style="width: 30px"
              src="/alarm-icons/water.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Humidity'"
              style="width: 22px"
              src="/alarm-icons/humidity.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="item.device_type == 'Attendance'"
              style="width: 30px"
              src="/icons/device_type_attendance.png"
            >
            </v-img>
            <v-img
              :title="item.device_type"
              v-if="
                item.device_type == 'all' ||
                item.device_type == 'Access Control'
              "
              style="width: 30px"
              src="/alarm-icons/device_type_access_control.png"
            >
            </v-img>
          </div>
        </template>

        <template v-slot:item.threshold_temperature="{ item }">
          {{
            item.threshold_temperature
              ? item.threshold_temperature + "°C"
              : "---"
          }}
        </template>
        <template v-slot:item.status="{ item }">
          <div v-if="item.status == 1">
            <v-img style="width: 30px" src="/icons/device_status_open.png">
            </v-img>
          </div>
          <div v-else>
            <v-img width="30px" src="/icons/device_status_close.png"> </v-img>
          </div>
        </template>
        <template v-slot:item.armed="{ item }">
          <div v-if="item.armed_status == 1">
            <v-icon title="Armed" color="green">mdi mdi-shield-sun</v-icon>
          </div>
          <div v-else-if="item.armed_status == 0">
            <v-icon title="DisArmed" color="#757575">mdi mdi-shield-sun</v-icon>
          </div>
          <div v-else>---</div>
        </template>
        <template v-slot:item.alarm="{ item }">
          <div style="color: red" v-if="item.alarm_status == 1">
            <v-icon color="red">mdi mdi-alarm-light-outline</v-icon>
          </div>
          <div v-else>
            <v-icon>mdi mdi-alarm-light-outline</v-icon>
          </div>
        </template>

        <template v-slot:item.options="{ item }">
          <v-menu bottom left>
            <template v-slot:activator="{ on, attrs }">
              <div class="text-center">
                <v-btn dark-2 icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </div>
            </template>
            <v-list width="120" dense>
              <!-- <v-list-item @click="findUser(item)">
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small>mdi-magnify </v-icon>
                  Find User
                </v-list-item-title>
              </v-list-item> -->
              <v-list-item
                v-if="can(`device_edit`) && item.device_type == 'Control Panel'"
                @click="editZones(item)"
              >
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small>
                    mdi-format-list-bulleted-type
                  </v-icon>
                  Zones
                </v-list-item-title>
              </v-list-item>
              <v-list-item v-if="can(`device_edit`)" @click="editItem(item)">
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small> mdi-pencil </v-icon>
                  Edit
                </v-list-item-title>
              </v-list-item>
              <!-- <v-list-item
                v-if="can(`device_edit`) && item.model_number == 'CAMERA1'"
                @click="showDeviceCameraSettings(item)"
              >
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small> mdi-cog </v-icon>
                  Settings
                </v-list-item-title>
              </v-list-item>
              <v-list-item
                v-else-if="can(`device_edit`) && item.model_number == 'OX-900'"
                @click="showDeviceMegviiSettings(item)"
              >
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small> mdi-cog </v-icon>
                  Settings
                </v-list-item-title>
              </v-list-item>
              <v-list-item v-else @click="showDeviceSettings(item)">
                <v-list-item-title style="cursor: pointer">
                  <v-icon color="secondary" small> mdi-cog </v-icon>
                  Settings
                </v-list-item-title>
              </v-list-item> -->
              <v-list-item
                v-if="can(`device_delete`)"
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
  </div>
</template>
<script>
// import Back from "../../components/Snippets/Back.vue";
import timeZones from "../../defaults/utc_time_zones.json";
import DeviceAccessSettings from "../../components/DeviceAccessSettings.vue";
import AlarmEditDevice from "../../components/Alarm/EditDevice.vue";
import AlarmSensorZones from "../../components/Alarm/EditSensorZones.vue";

export default {
  components: { DeviceAccessSettings, AlarmEditDevice, AlarmSensorZones },
  props: ["customer_id"],
  data: () => ({
    editId: null,
    key: 1,
    dialogEditDevice: false,
    deviceTypes: [],
    oneTOsixty: [],
    deviceCAMVIISettings: { voice_volume: 0 },
    DialogDeviceMegviiSettings: false,
    valid: false,
    rules: [(value) => (value || "").length <= 10 || "Max 10 characters"],
    device_model: [
      (v) => !!v || "Device Name  is required",
      (value) => (value || "").length <= 20 || "Max 20 characters",
    ],

    menu_password: [
      (v) => !!v || "Password is required",
      (value) => (value || "").length <= 8 || "Max 8 characters",
    ],
    sdk_message: "",
    verificationModeItems: [
      { name: "Face", value: "face" },
      { name: "Face Or Card", value: "face_or_card" },
      { name: "Face and Card", value: "face_and_card" },
      {
        name: "Face and Password",
        value: "face_and_pass",
      },
    ],
    DialogDeviceSettings: false,
    deviceSettings: { maker: {} },
    to_menu_filter: false,
    to_menu_filter: "",
    to_date_filter: "",
    visitorUploadedDevicesInfo: [],
    loadingDeviceData: false,
    visitor_status_list: [],
    inputFindDeviceUserId: "",
    popupDeviceId: null,
    popupDeviceName: null,
    uploadedUserInfoDialog: false,
    dialogAccessSettings: false,
    popup_device_id: "",
    editDialog: false,
    showFilters: false,
    filters: {
      branch_id: "",
    },
    isFilter: false,
    totalRowsCount: 0,
    datatable_search_textbox: "",
    filter_employeeid: "",
    snack: false,
    snackColor: "",
    snackText: "",
    timeZones: timeZones,
    payload: {
      name: "",
      device_type: "",
      device_id: "",
      model_number: "",
      status_id: "",
      company_id: "",
      location: "",
      short_name: "",
      ip: "",
      port: "",
      camera_save_images: false,
    },
    Model: "Device",
    pagination: {
      current: 1,
      total: 0,
      per_page: 100,
    },
    options: {},
    endpoint: "device",
    search: "",
    snackbar: false,
    dialog: false,
    data: [],
    loading: false,
    total: 0,
    deviceResponse: "",
    headers: [
      { text: "#", value: "sno" },
      { text: "Device", value: "device" },
      { text: "Device Type", value: "device_type" },
      { text: "Zones", value: "zones", align: "left" },

      { text: "Location", value: "location" },

      { text: "Delay(Min)", value: "delay" },
      { text: "24 Hrs", value: "hrs_24" },
      { text: "Sensor", value: "sensor", align: "center" },
      { text: "Temperature", value: "threshold_temperature" },
      { text: "Online", value: "status" },
      { text: "Armed", value: "armed", align: "center" },

      { text: "Alarm", value: "alarm" },
      { text: "Options", value: "options", align: "center" },
    ],
    editedIndex: -1,
    response: "",
    errors: [],

    device_statusses: [],
    branches: [],
    branchesList: [],
    isCompany: true,
    timeZoneOptions: [],
    editedItem: null,
    downloadProfileLink: null,
    editDevice: null,
    dialogZones: false,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New device" : "Edit device";
    },
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
    dialog(val) {
      val || this.close();
      this.errors = [];
      this.search = "";
    },
  },
  mounted() {
    // setTimeout(() => {
    //   this.updateDevicesHealth();
    // }, 1000 * 5);
    // setInterval(() => {
    //   if (this.$route.name == "device") {
    //     this.getDataFromApi();
    //   }
    // }, 1000 * 60);
  },
  async created() {
    for (let index = 0; index <= 60; index++) {
      this.oneTOsixty.push(index);
    }
    this.loading = true;

    if (this.$auth.user.branch_id) {
      this.filters[branch_id] = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }

    try {
      const { data } = await this.$axios.get(`branches_list`, {
        params: {
          per_page: 100,
          company_id: this.$auth.user.company_id,
        },
      });
      this.branchesList = data;
    } catch (error) {
      // Handle the error
      console.error("Error fetching branch list", error);
    }

    //this.getDataFromApi();
    this.getBranches();
    this.getDeviceStatus();
    if (this.$store.state.storeAlarmControlPanel?.DeviceTypes) {
      this.deviceTypes = this.$store.state.storeAlarmControlPanel.DeviceTypes;
    }
  },

  methods: {
    closeDialog() {
      this.dialogEditDevice = false;
      this.dialogZones = false;
      this.getDataFromApi();
      this.$emit("closeDialog");
    },
    editZones(item) {
      this.key = this.key + 1;
      this.editDevice = item;
      this.dialogZones = true;
    },
    getAlarmStatus(item) {},
    copyToProfileimage(faceImage, userId) {
      if (
        confirm("Are you sure? It will override the Software Profile picture ")
      ) {
        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            face_image: faceImage,
            system_user_id: userId,
          },
        };
        this.$axios
          .post(`/copy-to-profilepic`, options.params)
          .then(({ data }) => {
            this.response = "Device Image is copied to Profile Picture";
            this.snackbar = true;
          })
          .catch((e) => console.log(e));
      }
    },
    downloadImage(faceImage, userId) {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          face_image: faceImage,
          system_user_id: userId,
        },
      };
      this.$axios
        .post(`/download-profilepic-sdk`, options.params)
        .then(({ data }) => {
          this.downloadProfileLink =
            process.env.BACKEND_URL + "/download-profilepic-disk?image=" + data;

          //this.$refs.goTo.click;

          let path = this.downloadProfileLink;
          let pdf = document.createElement("a");
          pdf.setAttribute("href", path);
          pdf.setAttribute("target", "_blank");
          pdf.click();
        })
        .catch((e) => console.log(e));
    },
    UpdateverificationModeItems() {
      if (this.deviceCAMVIISettings.recognition_mode == "single") {
        this.verificationModeItems = [
          { name: "Face", value: "face" },
          { name: "Face Or Card", value: "face_or_card" },
          { name: "Face and Card", value: "face_and_card" },
          {
            name: "Face and Password",
            value: "face_and_pass",
          },
        ];
      } else {
        this.verificationModeItems = [
          { name: "Face", value: "face" },
          { name: "Face Or Card", value: "face_or_card" },
        ];
      }
    },
    UpdateAlarmStatus(item, status) {
      if (status == 0) {
        if (confirm("Are you sure you want to TURN OFF the Alarm")) {
          let options = {
            params: {
              company_id: this.$auth.user.company_id,
              serial_number: item.serial_number,
              status: status,
            },
          };
          this.loading = true;
          this.$axios
            .post(`/update-device-alarm-status-off`, options.params)
            .then(({ data }) => {
              this.getDataFromApi();
              if (!data.status) {
                if (data.message == "undefined") {
                  this.response = "Try again. No connection available";
                } else this.response = "Try again. " + data.message;
                this.snackbar = true;

                return;
              } else {
                setTimeout(() => {
                  this.loading = false;
                  this.response = data.message;
                  this.snackbar = true;
                }, 2000);

                return;
              }
            })
            .catch((e) => console.log(e));
        }
      }
    },
    showDeviceMegviiSettings(item) {
      this.errors = [];
      this.payload = {};
      this.editedIndex = item.id;

      this.editedItem = item;
      this.loadingDeviceData = true;

      this.getDeviceCAMVIISettginsFromSDK(item.device_id);
      this.DialogDeviceMegviiSettings = true;
    },
    showDeviceCameraSettings(device) {
      return false;
    },

    updateDeviceCAMMIISettings() {
      if (confirm("Are you want to Update Device settings  ?")) {
        {
          this.deviceCAMVIISettings.voice_volume = Math.ceil(
            this.deviceCAMVIISettings.voice_volume
          );
          let options = {
            params: {
              company_id: this.$auth.user.company_id,
              deviceSettings: this.deviceCAMVIISettings,
            },
          };
          this.loading = true;
          this.$axios
            .post(`/update-device-camvii-sdk-settings`, options.params)
            .then(({ data }) => {
              if (!data.status) {
                if (data.message == "undefined") {
                  this.response = "Try again. No connection available";
                } else this.response = "Try again. " + data.message;
                this.snackbar = true;

                return;
              } else {
                setTimeout(() => {
                  this.loading = false;
                  this.response = data.message;
                  this.snackbar = true;
                }, 2000);

                return;
              }
            })
            .catch((e) => console.log(e));
        }
      }
    },
    updateDeviceSettings() {
      if (confirm("Are you want to Update Device settings  ?")) {
        if (
          this.$refs.form.validate() &&
          this.deviceSettings.menuPassword != ""
          // &&          this.deviceSettings.name != ""
        ) {
          let options = {
            params: {
              company_id: this.$auth.user.company_id,
              deviceSettings: this.deviceSettings,
            },
          };
          this.loading = true;
          this.$axios
            .post(`/update-device-sdk-settings`, options.params)
            .then(({ data }) => {
              if (!data.status) {
                if (data.message == "undefined") {
                  this.response = "Try again. No connection available";
                } else this.response = "Try again. " + data.message;
                this.snackbar = true;

                return;
              } else {
                setTimeout(() => {
                  this.loading = false;
                  this.response = data.message;
                  this.snackbar = true;
                }, 2000);

                return;
              }
            })
            .catch((e) => console.log(e));
        }
      }
    },
    findUser(item) {
      this.visitorUploadedDevicesInfo = [];
      this.popupDeviceId = item.device_id;
      this.popupDeviceName = item.name;
      this.uploadedUserInfoDialog = true;
    },

    getDeviceCAMVIISettginsFromSDK(device_id) {
      if (device_id != "") {
        this.sdk_message = "";
        this.deviceSettings = {};
        this.deviceSettings.device_id = device_id;
        this.loadingDeviceData = true;
        let counter = 1;

        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            device_id: device_id,
          },
        };
        this.loadingDeviceData = true;
        this.$axios
          .get(`get-device-camvii-settings-from-sdk`, options)
          .then(({ data }) => {
            this.loadingDeviceData = false;

            this.sdk_message = data.SDKresponseData.message;
            if (!data.SDKresponseData.data) {
              this.response = "Try again. " + data.message;
              this.snackbar = true;

              return;
            } else {
              this.deviceCAMVIISettings = data.SDKresponseData.data;
              this.deviceCAMVIISettings.device_id = device_id;

              this.UpdateverificationModeItems();

              return;
            }
          });
      }
    },
    getDeviceSettginsFromSDK(device_id) {
      if (device_id != "") {
        this.sdk_message = "";
        this.deviceSettings = {};
        this.deviceSettings.device_id = device_id;
        this.loadingDeviceData = true;
        let counter = 1;

        let options = {
          params: {
            company_id: this.$auth.user.company_id,
            device_id: device_id,
          },
        };
        this.loadingDeviceData = true;
        this.$axios
          .get(`get-device-settings-from-sdk`, options)
          .then(({ data }) => {
            this.loadingDeviceData = false;

            this.sdk_message = data.SDKresponseData.message;
            if (!data.SDKresponseData.data) {
              this.response = "Try again. " + data.message;
              this.snackbar = true;

              return;
            } else {
              this.deviceSettings = data.SDKresponseData.data;
              this.deviceSettings.device_id = device_id;

              // this.deviceSettings.time = this.deviceSettings.time.replace(
              //   "T",
              //   " "
              // );

              return;
            }
          });
      }
    },
    getUserInfoFromDevice() {
      if (this.inputFindDeviceUserId != "") {
        this.visitorUploadedDevicesInfo = [];
        this.loadingDeviceData = true;
        let counter = 1;

        let options = {
          params: {
            company_id: this.$auth.user.company_id,

            system_user_id: this.inputFindDeviceUserId,

            device_id: this.popupDeviceId,
          },
        };
        this.loadingDeviceData = true;
        this.$axios
          .get(`get-device-person-details`, options)
          .then(({ data }) => {
            this.loadingDeviceData = false;
            counter++;
            if (!data.deviceName) {
              this.response = data.message;
              this.snackbar = true;

              return;
            } else {
              data.system_user_id = this.inputFindDeviceUserId;
              data.device_id = this.popupDeviceId;

              this.visitorUploadedDevicesInfo.push(data);

              return;
            }
          });
      }
    },
    getTimezones() {
      return Object.keys(this.timeZones).map((key) => ({
        offset: this.timeZones[key].offset,
        time_zone: this.timeZones[key].time_zone,
        key: key,
        text: key + " - " + this.timeZones[key].offset,
      }));
    },
    getBranches() {
      this.$axios
        .get(`branch`, { params: { company_id: this.$auth.user.company_id } })
        .then(({ data }) => {
          this.branches = data.data;
        });
    },
    getDeviceStatus() {
      this.$axios.get(`device_status`).then(({ data }) => {
        this.device_statusses = data.data;
      });
    },
    datatable_save() {},
    datatable_cancel() {
      this.datatable_search_textbox = "";
    },
    datatable_open() {
      this.datatable_search_textbox = "";
    },
    datatable_close() {
      this.loading = false;
    },
    async sync_date_time(item) {
      if (
        confirm(
          "Are you want to change the Device Time to " +
            item.utc_time_zone +
            "?"
        )
      ) {
        try {
          const dt = new Date();
          const year = dt.getFullYear();
          const month = String(dt.getMonth() + 1).padStart(2, "0");
          const day = String(dt.getDate()).padStart(2, "0");
          const hours = String(dt.getHours()).padStart(2, "0");
          const minutes = String(dt.getMinutes()).padStart(2, "0");
          const seconds = String(dt.getSeconds()).padStart(2, "0");

          const apiUrl = `sync_device_date_time/${item.device_id}/${this.$auth.user.company_id}`;
          const sync_able_date_time = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
          const { data } = await this.$axios.get(apiUrl, {
            params: { sync_able_date_time },
          });

          this.snackbar = true;
          this.response = data.message;
        } catch (error) {
          this.snackbar = true;
          this.response = error;
          console.error("Error syncing date and time:", error);
        }
      }
    },
    closepopup() {
      this.snackbar = true;
      this.response = "Device Time details are updated successfully";
      this.dialogAccessSettings = false;
    },
    getUTC_CurentTime(targetTimezone) {
      const localDate = new Date();
      const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: targetTimezone,
        hour12: false,
      };
      const formattedDateTime = localDate.toLocaleDateString("en-US", options);

      let dt = new Date(formattedDateTime);

      let year = dt.getFullYear();
      let month = dt.getMonth() + 1;
      let day = dt.getDate();

      let hours = dt.getHours();
      hours = hours < 10 ? "0" + hours : hours;

      let minutes = dt.getMinutes();
      minutes = minutes < 10 ? "0" + minutes : minutes;

      let seconds = dt.getSeconds();
      seconds = seconds < 10 ? "0" + seconds : seconds;

      let sync_able_date_time = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

      return sync_able_date_time;
    },
    open_door(device_id) {
      let options = {
        params: { device_id },
      };
      confirm("Are you sure want to open the Door?") &&
        this.$axios.get(`open_door`, options).then(({ data }) => {
          this.snackbar = true;
          this.response = data.message;
          // this.getDataFromApi();
        });
    },
    open_door_always(device_id) {
      this.popup_device_id = device_id;
      this.dialogAccessSettings = true;

      /////////// this.$router.push(`/device/time_settings/${device_id}`);
      // let options = {
      //   params: { device_id },
      // };
      // this.$axios.get(`open_door_always`, options).then(({ data }) => {
      //   this.snackbar = true;
      //   this.response = data.message;
      //   this.getDataFromApi();
      // });
    },
    close_door(device_id) {
      let options = {
        params: { device_id },
      };
      confirm("Are you sure want to close the Door?") &&
        this.$axios.get(`close_door`, options).then(({ data }) => {
          this.snackbar = true;
          this.response = data.message;
          this.getDataFromApi();
        });
    },
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
    onPageChange() {
      this.getDataFromApi();
    },
    applyFilters() {
      this.getDataFromApi();
    },
    toggleFilter() {
      // this.filters = {};
      this.isFilter = !this.isFilter;
    },
    clearFilters() {
      this.filters = {};

      this.isFilter = false;
      this.getDataFromApi();
    },
    async getDataFromApi(
      url = this.endpoint,
      filter_column = "",
      filter_value = ""
    ) {
      this.loading = true;

      this.filters["customer_id"] = this.customer_id;
      const data = await this.$store.dispatch("fetchData", {
        key: "devices",
        options: this.options,
        refresh: true,
        endpoint: this.endpoint,
        filters: this.filters,
      });
      this.data = data.data;
      this.totalRowsCount = data.total;
      this.loading = false;

      return;

      if (url == "") url = this.endpoint;
      this.loading = true;
      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      let options = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          per_page: itemsPerPage,
          branch_id: this.branch_id,
          company_id: this.$auth.user.company_id,
          ...this.filters,
        },
      };
      if (filter_column != "") {
        if (filter_column == "serach_status_name") {
          options.params[filter_column] =
            filter_value.toLowerCase() == "online"
              ? "active"
              : filter_value.toLowerCase() == "offline"
              ? "inactive"
              : "";
        } else options.params[filter_column] = filter_value;
      }
      await this.$axios.get(`${url}?page=${page}`, options).then(({ data }) => {
        if (filter_column != "" && data.data.length == 0) {
          this.snack = true;
          this.snackColor = "error";
          this.snackText = "No Results Found";
          this.loading = false;
          return false;
        }
        this.totalRowsCount = data.total;
        this.data = data.data;
        this.pagination.current = data.current_page;
        this.pagination.total = data.last_page;
        this.loading = false;
      });
    },
    async updateDevicesHealth() {
      this.loading = true;
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this.customer_id,
        },
      };

      this.$axios.get(`device`, options).then(({ data }) => {
        this.data = data.data;
        this.totalRowsCount = data.total;
        this.loading = false;
      });

      // let options = {
      //   params: {
      //     company_id: this.$auth.user.company_id,
      //   },
      // };

      // await this.$axios
      //   .get("/check_device_health", options)
      //   .then(({ data }) => {
      //     this.snackbar = true;
      //     this.response = data;
      //     this.getDataFromApi();
      //   });
    },

    searchIt(e) {
      if (e.length == 0) {
        this.getDataFromApi();
      } else if (e.length > 2) {
        this.getDataFromApi(`${this.endpoint}/search/${e}`);
      }
    },

    editItem(item) {
      this.key = this.key + 1;
      this.errors = [];
      this.payload = {};
      this.editDevice = item;
      this.editId = item.id;
      this.dialogEditDevice = true;
    },
    showDeviceSettings(item) {
      this.errors = [];
      this.payload = {};
      this.editedIndex = item.id;

      this.editedItem = item;
      this.loadingDeviceData = true;

      this.getDeviceSettginsFromSDK(item.device_id);
      this.DialogDeviceSettings = true;
    },
    addItem() {
      this.key = this.key + 1;
      this.payload = {};
      this.errors = [];
      if (!this.isCompany) {
        this.payload.branch_id = this.branch_id;
      }

      this.editedIndex = -1;
      this.editDialog = true;

      this.deviceResponse = "";
    },
    store_device() {
      let id = this.editedIndex;
      //let company_id = console.log(this.payload);
      let payload = this.payload;

      this.payload.company_id = this.$auth.user.company_id;
      if (this.editedIndex == -1) this.payload.status_id = 2;
      this.payload.ip = "0.0.0.0";
      this.payload.serial_number = this.payload.device_id;
      this.payload.port = "0000";

      delete this.payload.status;
      delete this.payload.company;
      delete this.payload.company_branch;

      this.loading = true;
      if (this.editedIndex == -1) {
        this.$axios
          .post(`/device`, payload)
          .then(({ data }) => {
            this.loading = false;
            if (!data.status) {
              this.errors = [];
              if (data.errors) this.errors = data.errors;

              this.snackbar = true;
              this.deviceResponse = data.message;
              this.response = data.message;
            } else if (data.status == "device_api_error") {
              this.errors = [];
              this.snackbar = true;
              this.deviceResponse =
                "Check the Device information. There are errors.";

              this.response = "Check the Device information. There are errors.";
            } else {
              this.snackbar = true;
              this.deviceResponse = "Device details are  Created successfully";
              this.response = "Device details are  Created successfully";
              this.getDataFromApi();
              this.editDialog = false;
            }
          })
          .catch((e) => console.log(e));
      } else {
        this.$axios
          .put(`/device/${id}`, payload)
          .then(({ data }) => {
            this.loading = false;
            if (!data.status) {
              this.errors = data.errors;
            } else if (data.status == "device_api_error") {
              this.errors = [];
              this.snackbar = true;
              this.deviceResponse =
                "Check the Device information. There are errors.";
              this.response = "Check the Device information. There are errors.";
            } else {
              this.snackbar = true;
              this.deviceResponse = "Device details are  updated successfully";
              this.response = "Device details are updated successfully";
              this.getDataFromApi();
              this.editDialog = false;
            }
          })
          .catch((e) => console.log(e));
      }
    },

    getFunctionIcon(item) {
      if (item.function == "auto") {
        return "/icons/function_in_out.png?t=2";
      } else if (item.function == "In") {
        return "/icons/function_in.png";
      } else if (item.function == "Out") {
        return "/icons/function_out.png";
      }
    },
    getDeviceIcon(item) {
      if (item.device_type == "all") {
        return "/icons/device_type_all.png";
      } else if (item.device_type == "Access Control") {
        return "/icons/device_type_attendance.png";
      } else if (item.device_type == "Attendance") {
        return "/icons/device_type_access_control.png";
      }
    },
    getDeviceStatusIcon(item) {
      if (item.status.name == "active") {
        return "/icons/device_status_open.png";
      } else {
        return "/icons/device_status_close.png";
      }
    },

    deleteItem(item) {
      confirm(
        "Are you sure you wish to delete , to mitigate any inconvenience in future."
      ) &&
        this.$axios
          .delete(this.endpoint + "/" + item.id)
          .then(({ data }) => {
            if (!data.status) {
              this.errors = data.errors;
            } else {
              this.snackbar = data.status;
              this.response = data.message;
              this.getDataFromApi();
            }
          })
          .catch((err) => console.log(err));
    },
  },
};
</script>
