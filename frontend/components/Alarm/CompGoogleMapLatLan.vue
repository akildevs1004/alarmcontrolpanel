<template>
  <div>
    <!-- <v-dialog v-model="dialog" max-width="200px">TTTTTTTTTTT</v-dialog> -->
    <div
      :id="'mapCustomer' + contact_id"
      style="height: 210px; width: 100%"
    ></div>

    <!-- <v-btn
      class="text-right"
      style="float: right"
      small
      fill
      dark
      color="blue"
      @click="openInGoogleMaps()"
      >Open In Google Map
    </v-btn> -->
  </div>
</template>

<script>
export default {
  props: ["latitude", "longitude", "title", "contact_id"],
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
      script.src = `https://maps.googleapis.com/maps/api/js?loading=async&key=${this.mapKey}&callback=initMap`;
      script.async = true;
      script.defer = true;
      window.initMap = callback;
      document.head.appendChild(script);
    },
    initMap() {
      this.map = new google.maps.Map(
        document.getElementById("mapCustomer" + this.contact_id),
        {
          mapTypeControl: false,
          streetViewControl: false,
          zoom: 8,
          center: { lat: 25.276987, lng: 55.296249 },
          styles: [
            {
              featureType: "administrative",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "administrative",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "landscape",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi",
              stylers: [{ visibility: "off" }],
            },
          ],
        }
      );
      this.geocoder = new google.maps.Geocoder();
      this.infowindow = new google.maps.InfoWindow();
      this.plotLocations();
    },
    async plotLocations() {
      if (!isNaN(this.latitude) && !isNaN(this.longitude)) {
        const position = {
          lat: parseFloat(this.latitude),
          lng: parseFloat(this.longitude),
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
          title: this.title,
          //icon: icon,
        });

        // marker.addListener("click", () => {
        //   this.dialog = true;
        //   this.customerInfo = this.customer.building_name;
        // });

        let html =
          "<div style='width:250px'><div style='width:100px;float:left'>  " +
          "</div>";
        html +=
          "<div style='width:150px; float:left'>" +
          this.title +
          "</div>" +
          "" +
          " ";
        html +=
          "<br/> <a target='_blank' href='https://www.google.com/maps?q=" +
          this.latitude +
          "," +
          this.longitude +
          "'>Google Map Link</a>" +
          " ";
        html += "</div></div>";

        var infowindow = new google.maps.InfoWindow({
          content: html,
          map: this.map,
          position: position,
        });

        infowindow.close();

        this.map.panTo(position);

        marker.addListener("mouseover", function () {
          infowindow.open(this.map, this);
        });
      }
    },
    openInGoogleMaps() {
      if (this.latitude && this.longitude) {
        const lat = parseFloat(this.latitude);
        const lng = parseFloat(this.longitude);
        const url = `https://www.google.com/maps?q=${lat},${lng}`;
        window.open(url, "_blank");
      }
    },
  },
};
</script>
