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
                  <b>{{ customer.name }}</b>
                  <div>{{ customer.area }}</div>
                </td>
                <td class="text-right">
                  <v-img
                    style="width: 40px; margin: 0 auto"
                    :src="getRelaventImage(customer.alarm)"
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
            <v-data-table
              :headers="headers"
              :items="customers"
              class="elevation-1"
            >
              <template v-slot:item.alarm="{ item }">
                <v-img
                  style="width: 23px"
                  :src="getRelaventImage(item.alarm)"
                ></v-img>
              </template>

              <template v-slot:item.category="{ item }">
                <span
                  :class="`${getRelaventCategoryColor(item.category)}--text`"
                  >{{ item.category }}</span
                >
              </template>

              <template v-slot:item.options="{ item }">
                <v-menu bottom left>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn dark-2 icon v-bind="attrs" v-on="on">
                      <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                  </template>
                  <v-list width="170" dense>
                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-note </v-icon>
                        Add Notes
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-alarm </v-icon>
                        Recent Alarm
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-eye </v-icon>
                        View
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-pencil </v-icon>
                        Edit
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-delete </v-icon>
                        Delete
                      </v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                      <v-list-item-title style="cursor: pointer">
                        <v-icon color="secondary" small> mdi-email </v-icon>
                        Send Renewal email
                      </v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </template>
            </v-data-table>
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
      geocoder: null,
      infowindow: null,

      headers: [
        { text: "S.No", value: "sno" },
        { text: "Customer", value: "primary_contact.first_name" },
        { text: "Type", value: "building_type" },
        { text: "Phone", value: "primary_contact.phone1" },
        { text: "Location", value: "area" },
        // { text: "Device", value: "device" },
        { text: "Alarm", value: "alarm" },
        { text: "Time", value: "time" },
        { text: "Category", value: "category" },
        { text: "Status", value: "status" },

        { text: "More", value: "options" },
      ],
      customers: [
        {
          sno: 101,
          primary_contact: {
            first_name: "Venu Jackcu",
            phone1: "+97155221435",
          },
          building_type: "Residential",
          area: "KB Road Burdubai",
          device: "Glass Break Sensor",
          alarm: "Burglary",
          time: "23:02:07",
          category: "Critical",
          status: "Open",
          more: "",
          lat: 25.25,
          lng: 55.3,
        },
        {
          sno: 102,
          primary_contact: { first_name: "Liam Smith", phone1: "+97155221436" },
          building_type: "Commercial",
          area: "Sheikh Zayed Road",
          device: "Motion Detector",
          alarm: "Medical",
          time: "12:15:30",
          category: "High",
          status: "Closed",
          more: "",
          lat: 25.2085,
          lng: 55.271,
        },
        {
          sno: 103,
          primary_contact: {
            first_name: "Olivia Brown",
            phone1: "+97155221437",
          },
          building_type: "Residential",
          area: "Jumeirah Beach Road",
          device: "Smoke Detector",
          alarm: "Fire",
          time: "03:45:22",
          category: "Critical",
          status: "Open",
          more: "",
          lat: 25.1925,
          lng: 55.2644,
        },
        {
          sno: 104,
          primary_contact: {
            first_name: "Noah Johnson",
            phone1: "+97155221438",
          },
          building_type: "Commercial",
          area: "Business Bay",
          device: "Access Control",
          alarm: "Water",
          time: "08:20:11",
          category: "Medium",
          status: "In Progress",
          more: "",
          lat: 25.1853,
          lng: 55.2633,
        },
        {
          sno: 105,
          primary_contact: { first_name: "Emma Jones", phone1: "+97155221439" },
          building_type: "Residential",
          area: "Palm Jumeirah",
          device: "Carbon Monoxide Detector",
          alarm: "Temperature",
          time: "19:30:45",
          category: "Critical",
          status: "Open",
          more: "",
          lat: 25.1121,
          lng: 55.138,
        },
        {
          sno: 106,
          primary_contact: { first_name: "Ava Davis", phone1: "+97155221440" },
          building_type: "Commercial",
          area: "Al Barsha",
          device: "Glass Break Sensor",
          alarm: "Burglary",
          time: "22:10:18",
          category: "High",
          status: "Closed",
          more: "",
          lat: 25.1164,
          lng: 55.2003,
        },
        {
          sno: 107,
          primary_contact: {
            first_name: "Sophia Wilson",
            phone1: "+97155221441",
          },
          building_type: "Residential",
          area: "Downtown Dubai",
          device: "Smoke Detector",
          alarm: "Fire",
          time: "14:05:30",
          category: "Critical",
          status: "In Progress",
          more: "",
          lat: 25.1972,
          lng: 55.2744,
        },
        {
          sno: 108,
          primary_contact: {
            first_name: "Mason Garcia",
            phone1: "+97155221442",
          },
          building_type: "Commercial",
          area: "Dubai Marina",
          device: "Motion Detector",
          alarm: "Water",
          time: "06:40:22",
          category: "Medium",
          status: "Open",
          more: "",
          lat: 25.0848,
          lng: 55.1482,
        },
        {
          sno: 109,
          primary_contact: {
            first_name: "Isabella Martinez",
            phone1: "+97155221443",
          },
          building_type: "Residential",
          area: "The Springs",
          device: "Access Control",
          alarm: "Temperature",
          time: "11:25:09",
          category: "Low",
          status: "Closed",
          more: "",
          lat: 25.0672,
          lng: 55.1813,
        },
        {
          sno: 110,
          primary_contact: {
            first_name: "Ethan Hernandez",
            phone1: "+97155221444",
          },
          building_type: "Commercial",
          area: "Al Quoz",
          device: "Carbon Monoxide Detector",
          alarm: "Fire",
          time: "16:50:33",
          category: "Critical",
          status: "Open",
          more: "",
          lat: 25.145,
          lng: 55.238,
        },
      ],
    };
  },
  mounted() {
    this.loadGoogleMapsScript(this.initMap);
  },
  methods: {
    loadGoogleMapsScript(callback) {
      if (window.google && window.google.maps) {
        callback();
        return;
      }
      const script = document.createElement("script");
      script.src = `https://maps.googleapis.com/maps/api/js?key=MAP_kEY&callback=initMap`;
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
      this.customers.forEach(({ lat, lng, name, alarm }) => {
        const position = { lat, lng };
        const icon = {
          url: this.getRelaventImage(alarm), // Path to the customer image
          scaledSize: new google.maps.Size(50, 50), // Adjust the size as needed
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(25, 25), // Adjust anchor point to the center
        };

        const marker = new google.maps.Marker({
          position,
          map: this.map,
          title: name,
          icon: icon,
        });

        marker.addListener("click", () => {
          this.infowindow.setContent(`Customer: ${name}`);
          this.infowindow.open(this.map, marker);
        });
      });
    },
    getColor(category) {
      const colors = {
        Critical: "red",
        Information: "blue",
        // Define other colors as needed
      };
      return colors[category] || "grey";
    },

    getRelaventImage(alarm) {
      let relaventImage = {
        Burglary: "/device-icons/burglary.png",
        Medical: "/device-icons/temperature.png",
        Fire: "/device-icons/medical.png",
        Water: "/device-icons/fire.png",
        Temperature: "/device-icons/water.png",
      };
      return relaventImage[alarm] ?? "Unknown";
    },
    getRelaventCategoryColor(category) {
      let relaventImage = {
        Critical: "red",
        Low: "primary",
        High: "",
        Medium: "orange",
      };
      return relaventImage[category] ?? "grey";
    },
  },
};
</script>

<style scoped>
@import url("../../assets/tableStyles.css");
</style>
