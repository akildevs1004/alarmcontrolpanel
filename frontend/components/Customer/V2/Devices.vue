<template>
  <NoAccess v-if="!$pagePermission.can('devices_view', this)" />
  <div v-else>
    <v-dialog v-model="dialogZones" width="900px">
      <v-card>
        <v-card-title dense class="popup_background_noviolet">
          <span style="color: black">Intruder - Sensors </span>
          <v-spacer></v-spacer>
          <v-icon style="color: black" @click="dialogZones = false" outlined>
            mdi mdi-close-circle
          </v-icon>
        </v-card-title>

        <v-card-text>
          <v-container style="min-height: 100px">
            <AlarmDeviceSensorZones
              :key="key"
              :customer_id="customer_id"
              @closeDialog="closeDialog"
              :editId="editId"
              :editDevice="editDevice"
              :isEditable="isEditable"
            />
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-data-table
      v-if="!loading"
      dense
      :headers="headers"
      :items="devices"
      :loading="loading"
      :footer-props="{
        itemsPerPageOptions: [50, 100, 500, 1000],
      }"
      class="elevation-1"
    >
      <template v-slot:item.sno="{ item, index }">
        {{ index + 1 }}
      </template>
      <template v-slot:item.device="{ item }">
        <div>{{ item.serial_number }}</div>
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
        <div v-if="item && item.device_type">
          <img
            :title="item.device_type"
            style="width: 15px; float: left"
            :src="$utils.getRelaventImage(item.device_type)"
          />
        </div>
      </template>

      <template v-slot:item.threshold_temperature="{ item }">
        {{
          item.threshold_temperature ? item.threshold_temperature + "°C" : "---"
        }}
      </template>

      <template v-slot:item.status="{ item }">
        <div v-if="item.status_id == 1">
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
        <div v-else>
          <v-icon title="DisArmed" color="#585858">mdi mdi-shield-sun</v-icon>
        </div> </template
      ><template v-slot:item.disarm="{ item }">
        <div v-if="item.armed_status == 0">
          <v-icon title="Armed" color="green">mdi mdi-shield-sun</v-icon>
        </div>
        <div v-else>
          <v-icon title="DisArmed" color="#585858">mdi mdi-shield-sun</v-icon>
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
        <v-menu bottom left v-if="!isMapviewOnly">
          <template v-slot:activator="{ on, attrs }">
            <div class="text-center">
              <v-btn dark-2 icon v-bind="attrs" v-on="on">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </div>
          </template>
          <v-list width="120" dense>
            <v-list-item
              v-if="
                can('device_edit') && item && item.device_type == 'Intruder'
              "
              @click="editZones(item)"
            >
              <v-list-item-title style="cursor: pointer">
                <v-icon color="secondary" small>
                  mdi-format-list-bulleted-type
                </v-icon>
                Zones
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import timeZones from "@/defaults/utc_time_zones.json";

export default {
  props: [
    "customer_id",
    "devices",
    "eventFilter",
    "isMapviewOnly",
    "isEditable",
  ],
  data: () => ({
    commonSearch: "",
    filterCustomersMapped: "",
    editId: null,
    key: 1,
    dialogEditDevice: false,
    deviceTypes: [],
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
    loading: true,
    total: 0,
    deviceResponse: "",
    headers: [
      { text: "#", value: "sno", sortable: false },
      { text: "Category", value: "device_type", sortable: false },
      { text: "Model", value: "model_number", sortable: false },
      { text: "Serial Number", value: "device", sortable: false },
      { text: "Online", value: "status", sortable: false },
      { text: "Armed", value: "armed", align: "center", sortable: false },
      { text: "Disarm", value: "disarm", align: "center", sortable: false },
      { text: "Options", value: "options", align: "center", sortable: false },
    ],
    editedIndex: -1,
    response: "",
    errors: [],

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
      handler() {},
      deep: true,
    },
    dialog(val) {
      val || this.close();
      this.errors = [];
      this.search = "";
    },
  },
  async mounted() {
    this.loading = false;
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    closeDialog() {
      this.dialogEditDevice = false;
      this.dialogZones = false;
      this.$emit("closeDialog");
    },
    editZones(item) {
      this.editId = item.id;
      this.key = this.key + 1;
      this.editDevice = item;
      this.dialogZones = true;
    },

    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },
  },
};
</script>
