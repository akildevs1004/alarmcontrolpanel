<template>
  <v-app>
    <v-container fluid>
      <v-card>
        <v-row>
          <v-col cols="9">
            <div id="map" style="height: 100vh"></div>
          </v-col>
          <v-col cols="3">
            <table style="width: 100%">
              <tr v-for="(customer, index) in customers" :key="index">
                <td>
                  <b>{{ customer?.primary_contact?.first_name || "---" }}</b>
                  <div>{{ customer.area }}</div>
                </td>
                <td v-if="customer.latest_alarm_event" class="text-center">
                  <div>
                    <v-icon class="alarm" large>mdi-alarm-light</v-icon>
                  </div>
                </td>

                <td v-else class="text-center">
                  <v-img
                    style="width: 40px; margin: 0 auto"
                    :src="$utils.getRelaventImage(customer.alarm)"
                  ></v-img>
                </td>
              </tr>
            </table>
          </v-col>
        </v-row>
      </v-card>
      <v-card class="mt-5">
        <v-row>
          <v-col>
            <Customer />
          </v-col>
        </v-row>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      map: null,
      mapKey: null,
      geocoder: null,
      infowindow: null,
      customers: [],
    };
  },
  async mounted() {
    await this.getCustomers();
    await this.getMapKey();
  },
  methods: {
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
      this.customers = data.data;
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
      this.customers.forEach((e) => {
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

<style scoped>
@import url("../../assets/tableStyles.css");
</style>
