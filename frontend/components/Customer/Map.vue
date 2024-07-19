<template>
  <v-row>
    <v-col cols="9">
      <div id="map" style="height: 100vh"></div>
    </v-col>
    <v-col cols="3">
      <v-data-table
        dense
        :headers="headers"
        :items="data"
        :loading="loading"
        :options.sync="options"
        :footer-props="{
          itemsPerPageOptions: [10, 50, 100, 500, 1000],
        }"
        fixed-header
        :height="tableHeight"
        hide-default-header
      >
        <template v-slot:top>
          <v-container>
            <v-row>
              <v-col cols="4">Customers</v-col>
              <v-col>
                <v-text-field
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
          </v-container>
        </template>
        <template v-slot:item.building_name="{ item, index }">
          <div class="mt-3" style="font-size: 13px">
            {{ item.building_name || "" }}
          </div>
          <small style="font-size: 12px; color: #6c7184">
            <!-- <v-icon color="green" small>mdi-map-marker-radius</v-icon
        > -->
            {{ item.city }}
          </small>
          <div class="py-3">
            <div
              v-if="
                $dateFormat.verifyDeviceSensorName('Burglary', item.devices)
              "
            >
              <img
                title="Burglary"
                style="width: 15px; float: left"
                src="/device-icons/burglary.png"
              />
            </div>
            <div
              v-if="
                $dateFormat.verifyDeviceSensorName('Temperature', item.devices)
              "
            >
              <img
                title="Burglary"
                style="width: 15px; float: left"
                src="/device-icons/temperature.png"
              />
            </div>
            <div
              v-if="$dateFormat.verifyDeviceSensorName('Medical', item.devices)"
            >
              <img
                title="Burglary"
                style="width: 15px; float: left"
                src="/device-icons/medical.png"
              />
            </div>
            <div
              v-if="$dateFormat.verifyDeviceSensorName('Fire', item.devices)"
            >
              <img
                title="Burglary"
                style="width: 15px; float: left"
                src="/device-icons/fire.png"
              />
            </div>
            <div
              v-if="$dateFormat.verifyDeviceSensorName('Water', item.devices)"
            >
              <img
                title="Burglary"
                style="width: 15px; float: left"
                src="/device-icons/water.png"
              />
            </div>
            <br />
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    map: null,
    mapKey: null,
    geocoder: null,
    infowindow: null,
    viewCustomerId: null,
    commonSearch: "",
    perPage: 10,
    cumulativeIndex: 1,
    currentPage: 1,
    branchesList: [],
    isCompany: true,
    tableHeight: 750,
    id: "",

    newCustomerDialog: false,
    dialogViewCustomer: false,
    totalRowsCount: 0,

    snack: false,
    snackColor: "",
    snackText: "",
    departments: [],
    Model: "Log",
    endpoint: "customers",
    payload: {},
    loading: true,
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
    pagination: {
      current: 1,
      total: 0,
      itemsPerPage: 1000,
    },
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
  }),
  computed: {},
  async mounted() {
    await this.getMapKey();
  },
  created() {
    this._id = 4; //this.$route.params.id;
    this.loading = true;

    if (this.$auth.user.branch_id) {
      this.branch_id = this.$auth.user.branch_id;
      this.isCompany = false;
      return;
    }

    this.getCustomers();
    this.getBuildingTypes();
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
      if (this.mapKey) {
        this.loadGoogleMapsScript(this.initMap);
      }
    },

    async getCustomers() {
      let config = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };
      let { data } = await this.$axios.get(`customers-for-map`, config);
      this.data = data.data;
      this.loading = false;

      await this.getMapKey();
    },
    loadGoogleMapsScript(callback) {
      if (window.google && window.google.maps) {
        callback();
        return;
      }
      const script = document.createElement("script");
      script.src = `https://maps.googleapis.com/maps/api/js?key=${this.mapKey}&callback=initMap`;
      script.async = true;
      script.defer = true;
      window.initMap = callback;
      document.head.appendChild(script);
    },
    initMap() {
      this.map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: 25.276987, lng: 55.296249 },
      });
      this.geocoder = new google.maps.Geocoder();
      this.infowindow = new google.maps.InfoWindow();
      this.plotLocations();
    },
    plotLocations() {
      this.data.forEach((e) => {
        // { latitude: lat, longitude: lng, name, alarm }
        const position = {
          lat: parseFloat(e.latitude),
          lng: parseFloat(e.longitude),
        };

        const icon = {
          url: this.$utils.getRelaventMarkers(e.alarm), // Path to the customer image
          scaledSize: new google.maps.Size(75, 75), // Adjust the size as needed
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(25, 25), // Adjust anchor point to the center
        };

        const marker = new google.maps.Marker({
          position,
          map: this.map,
          title: e.name,
          icon: icon,
        });

        marker.addListener("click", () => {
          this.infowindow.setContent(`Customer: ${e.name}`);
          this.infowindow.open(this.map, marker);
        });
      });
    },
  },
};
</script>
