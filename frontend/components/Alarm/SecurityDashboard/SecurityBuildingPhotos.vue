<template>
  <div class="text-centers">
    <div class="fa-3x">
      <i class="fa-solid fa-circle-plus fa-beat"></i>
      <i class="fa-solid fa-heart fa-beat"></i>
      <i
        class="fa-solid fa-heart fa-beat"
        style="--fa-animation-duration: 0.5s"
      ></i>
      <i
        class="fa-solid fa-heart fa-beat"
        style="--fa-animation-duration: 2s"
      ></i>
      <i class="fa-solid fa-heart fa-beat" style="--fa-beat-scale: 2"></i>
    </div>
    <v-row>
      <v-col cols="8" dense hide-details></v-col>
      <v-col cols="4" class="pt-6 pb-0" style="float: right">
        <v-select
          outlined
          hide-details
          dense
          @change="changePhoto()"
          :items="photos"
          item-value="id"
          item-text="title"
          v-model="selectedPhotoId"
        ></v-select>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" style="position: relative">
        <div>
          <div v-if="item">
            <img
              style="margin: auto"
              :src="item.picture"
              :width="IMG_PLOTTING_WIDTH"
              :height="IMG_PLOTTING_HEIGHT"
            />
            <span v-if="!loading">
              <div
                v-for="(plotting, index) in plottings"
                :key="index"
                style="position: absolute"
                :style="{ top: plotting.top, left: plotting.left }"
              >
                <v-icon
                  v-if="plotting.alarm_event"
                  class="alarm-red-to-green"
                  title="Aalrm ON"
                  size="40"
                  >mdi-circle</v-icon
                ><v-icon v-else color="green" size="40" title="Aalrm OFF"
                  >mdi-circle</v-icon
                >
              </div>
            </span>
          </div>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  props: ["photos"],
  data() {
    return {
      dialog: false,
      loading: false,
      device_ids: [],
      devices: [],
      sensors: [],
      plottings: [],
      draggingIndex: null,

      existingPlottings: [],
      IMG_PLOTTING_WIDTH: "500px",
      IMG_PLOTTING_HEIGHT: "500px",

      selectedPhotoId: 0,
      item: null,
    };
  },
  async created() {
    //await this.getDevices();
    if (this.photos[0]) {
      this.item = this.photos[0];
      this.selectedPhotoId = this.item.id;
      this.getExistingPlottings();
      if (process) {
        this.IMG_PLOTTING_WIDTH = process?.env?.IMG_PLOTTING_WIDTH;
        this.IMG_PLOTTING_HEIGHT = process?.env?.IMG_PLOTTING_HEIGHT;
      }
    }
  },
  methods: {
    changePhoto() {
      this.item = this.photos.filter((e) => e.id == this.selectedPhotoId)[0];
      this.getExistingPlottings();
    },
    getDeviceName(device_id) {
      return this.devices.find((e) => e.id == device_id).name || "";
    },
    async getDevices() {
      let config = {
        params: {
          company_id: this.$auth.user.company_id,
          customer_id: this.item.customer_id,
        },
      };
      let { data } = await this.$axios.get(`device-list`, config);

      this.devices = [
        // { id: ``, name: "Select Device" },
        ...data,
      ];
    },

    async getExistingPlottings() {
      this.loading = true;
      let config = {
        params: {
          customer_building_picture_id: this.item.id,
        },
      };
      let { data } = await this.$axios.get(`plotting`, config);

      if (data) {
        this.existingPlottings = data.plottings;
        this.plottings = data.plottings;
      }

      this.loading = false;
    },
    async getSensors(device_ids) {
      await this.getExistingPlottings();

      let config = {
        params: { device_ids },
      };
      let { data: sensors } = await this.$axios.get("sensor-list", config);

      this.plottings = sensors.map((sensor) => {
        let plotting = this.existingPlottings.find(
          (e) => e.sensor_id == sensor.id
        );
        return (
          plotting || {
            sensor_id: sensor.id,
            device_id: sensor.device_id,
            label: sensor.label,
            top: "-500px",
            left: "-500px",
          }
        );
      });
    },
    dragStart(event, index) {
      this.draggingIndex = index;
    },
    allowDrop(event) {
      event.preventDefault();
    },
    async onDrop(event) {
      const dropZoneRect = event.currentTarget.getBoundingClientRect();
      const offsetX = event.clientX - dropZoneRect.left;
      const offsetY = event.clientY - dropZoneRect.top;

      this.plottings[this.draggingIndex].left = `${offsetX}px`;
      this.plottings[this.draggingIndex].top = `${offsetY}px`;

      this.draggingIndex = null;

      await this.submit();
    },

    async submit() {
      try {
        let { data } = await this.$axios.post(`plotting`, {
          customer_building_picture_id: this.item.id,
          plottings: this.plottings,
        });
        console.log(data);
      } catch (error) {
        console.log(error);
      }
    },

    getRelaventImage(label) {
      let relaventImage = {
        Burglary: "/device-icons/burglary.png",
        Medical: "/device-icons/temperature.png",
        Fire: "/device-icons/medical.png",
        Water: "/device-icons/fire.png",
        Temperature: "/device-icons/water.png",
      };
      return relaventImage[label] ?? "Unknown";
    },
  },
};
</script>
