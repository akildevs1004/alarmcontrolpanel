<template>
  <div>
    <!-- <v-dialog v-model="dialog" max-width="200px">TTTTTTTTTTT</v-dialog> -->
    <div id="map" style="height: 550px; width: 100%"></div>

    <v-btn
      class="text-right"
      style="float: right"
      small
      fill
      dark
      color="blue"
      @click="openInGoogleMaps()"
      >Open In Google Map
    </v-btn>
  </div>
</template>

<script>
export default {
  props: ["customer"],
  data: () => ({
    map: null,
    mapKey: null,
    geocoder: null,
    infowindow: null,

    snack: false,
    snackColor: "",
    snackText: "",
    dialog: false,
    customerInfo: "",
  }),
  computed: {},
  async mounted() {
    await this.getMapKey();
  },
  created() {},
  watch: {
    // options: {
    //   handler() {
    //     this.getCustomers();
    //   },
    //   deep: true,
    // },
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
    async plotLocations() {
      if (this.customer) {
        const position = {
          lat: parseFloat(this.customer.latitude),
          lng: parseFloat(this.customer.longitude),
        };

        // const icon = {
        //   url: this.$utils.getRelaventMarkers(e.alarm), // Path to the customer image
        //   scaledSize: new google.maps.Size(75, 75), // Adjust the size as needed
        //   origin: new google.maps.Point(0, 0),
        //   anchor: new google.maps.Point(25, 25), // Adjust anchor point to the center
        // };

        const marker = new google.maps.Marker({
          position,
          map: this.map,
          title: this.customer.building_name,
          //icon: icon,
        });

        // marker.addListener("click", () => {
        //   this.dialog = true;
        //   this.customerInfo = this.customer.building_name;
        // });
      }
    },
    openInGoogleMaps() {
      if (this.customer && this.customer.latitude && this.customer.longitude) {
        const lat = parseFloat(this.customer.latitude);
        const lng = parseFloat(this.customer.longitude);
        const url = `https://www.google.com/maps?q=${lat},${lng}`;
        window.open(url, "_blank");
      }
    },
  },
};
</script>
