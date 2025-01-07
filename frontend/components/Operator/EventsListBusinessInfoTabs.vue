<template>
  <div>
    <v-tabs
      height="25"
      center-active
      right
      class="customerEmergencyContactTabs1 customerEmergencyContactTabsBGcolor1"
    >
      <v-tab style="font-size: 10px; min-width: 50px !important">Address</v-tab>
      <v-tab style="font-size: 10px; min-width: 50px !important"
        >Premiss Photo</v-tab
      >
      <v-tab style="font-size: 10px; min-width: 50px !important"
        >Floor Plan</v-tab
      >
      <v-tab style="font-size: 10px; min-width: 50px !important"
        >Google Map</v-tab
      >
      <v-tab style="font-size: 10px; min-width: 50px !important">Camera</v-tab>
      <v-tab style="font-size: 10px; min-width: 50px !important">System</v-tab>
      <v-tab style="font-size: 10px; min-width: 50px !important">Logs</v-tab>
      <v-tab-item
        ><EventsBusinessTab1Address :customer="customer"
      /></v-tab-item>
      <v-tab-item>
        <v-row>
          <v-col>
            <v-carousel
              v-model="currentSlide"
              v-if="customer"
              height="400"
              hide-delimiter-background
              show-arrows-on-hover
            >
              <template
                v-for="(item, index) in customer.profile_pictures"
                :name="item.id"
              >
                <v-carousel-item>
                  <div style="text-align: Left">
                    {{ index + 1 }}: {{ item.title }}
                  </div>
                  <v-img
                    style="
                      max-width: 600px;
                      border: 1px solid #ddd;
                      overflow: hidden;
                      margin: auto;
                    "
                    :src="
                      item?.picture ? item.picture : '/no-profile-image.jpg'
                    "
                  ></v-img>
                </v-carousel-item>
              </template>
            </v-carousel>
          </v-col>
        </v-row>
        <v-row>
          <v-col style="width: 600px; overflow: scroll">
            <v-row justify="center" dense>
              <v-col
                class="thumbnail-wrapper"
                v-for="(item, index) in customer.profile_pictures"
                :key="'thumb-' + index"
                style="max-width: 150px; text-align: center"
              >
                <div
                  style="height: 100px; width: 150px; margin: auto"
                  :class="{ 'active-thumbnail': index === currentSlide }"
                >
                  <v-img
                    :src="item.picture"
                    :alt="item.title"
                    contain
                    width="140px"
                    max-height="100px"
                    height="100px"
                    style="max-height: 100px"
                    @click="currentSlide = index"
                  ></v-img>
                </div>
                <label style="font-size: 10px">{{ item.title }}</label>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-tab-item>
      <v-tab-item>
        <EventsBusinessTabFloorPlan
          v-if="customer"
          :alarm="alarm"
          :customer="customer"
        />
        <!-- <v-carousel
          v-if="customer?.photos"
          height="400"
          hide-delimiter-background
          show-arrows-on-hover
          @change="keyPlottings++"
        >
          <v-carousel-item
            v-for="(item, index) in customer.photos"
            :key="'imageplotting' + item.id"
          >
            <div class="photo-title">{{ index + 1 }}: {{ item.title }}</div>
            <img
              :id="
                'plotting' +
                item.photo_plottings[0].customer_building_picture_id
              "
              class="photo-img"
              :src="item.picture"
              style="width: 100%; height: 400px"
              @load="
                onImageLoad(
                  item.photo_plottings[0].customer_building_picture_id
                )
              "
            />
            <span
              v-if="
                item.photo_plottings[0] &&
                imageLoaded[
                  item.photo_plottings[0].customer_building_picture_id
                ]
              "
            >
              <PlottingIcon
                v-for="(plotting, idx) in item.photo_plottings[0].plottings"
                :key="'plotting' + idx"
                :plotting="plotting"
                :picture-id="
                  item.photo_plottings[0].customer_building_picture_id
                "
                :alarm="alarm"
              />
            </span>
          </v-carousel-item>
        </v-carousel> -->
      </v-tab-item>
      <v-tab-item
        ><CompGoogleMapLatLan
          v-if="customer"
          :latitude="customer.latitude"
          :longitude="customer.longitude"
          :title="customer.building_name"
          :mapheight="'500px'"
          :contact_id="customer.id"
        />
      </v-tab-item>
      <v-tab-item>
        <v-row>
          <v-col>
            <v-carousel
              v-model="currentCameraSlide"
              v-if="customer"
              height="400"
              hide-delimiter-background
              show-arrows-on-hover
            >
              <template
                v-for="(item, index) in customer.profile_pictures"
                :name="item.id"
              >
                <v-carousel-item>
                  <div style="text-align: Left">
                    {{ index + 1 }}: {{ item.title }}
                  </div>
                  <iframe
                    src="https://rtmp.oxsai.com/player.html"
                    width="100%"
                    height="500"
                    style="border: 0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                  ></iframe>
                </v-carousel-item>
              </template>
            </v-carousel>
          </v-col>
        </v-row>
        <v-row>
          <v-col style="width: 600px; overflow: scroll">
            <v-row justify="center" dense>
              <v-col
                class="thumbnail-wrapper"
                v-for="(item, index) in customer.profile_pictures"
                :key="'thumb-' + index"
                style="max-width: 150px; text-align: center"
              >
                <div
                  style="height: 100px; width: 150px; margin: auto"
                  :class="{ 'active-thumbnail': index === currentCameraSlide }"
                >
                  <v-img
                    :src="item.picture"
                    :alt="item.title"
                    contain
                    width="140px"
                    max-height="100px"
                    height="100px"
                    style="max-height: 100px"
                    @click="currentCameraSlide = index"
                  ></v-img>
                </div>
                <label style="font-size: 10px">{{ item.title }}</label>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-tab-item>
      <v-tab-item>System</v-tab-item>
      <v-tab-item>Logs</v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import CompGoogleMapLatLan from "../Alarm/CompGoogleMapLatLan.vue";
import EventsBusinessTab1Address from "./EventsBusinessTab1Address.vue";
import EventsBusinessTabFloorPlan from "./EventsBusinessTabFloorPlan.vue";

import PlottingIcon from "./PlottingIcon.vue";
export default {
  components: {
    EventsBusinessTab1Address,
    PlottingIcon,
    CompGoogleMapLatLan,
    EventsBusinessTabFloorPlan,
  },
  props: ["customer", "alarm"],
  data: () => ({
    tab: "",
    currentSlide: 0,
    currentCameraSlide: 0,
    imageLoaded: {},
    // IMG_PLOTTING_WIDTH: process?.env?.IMG_PLOTTING_WIDTH || "500PX",
    // IMG_PLOTTING_HEIGHT: process?.env?.IMG_PLOTTING_HEIGHT || "500PX",
    loadingImagesstatus: false,
    keyPlottings: 1,
  }),
  computed: {},
  mounted() {
    setTimeout(() => {
      this.loadingImagesstatus = true;
    }, 1000 * 10);
  },
  created() {
    if (this.customer) console.log("customer", this.customer);
  },
  watch: {},
  methods: {
    // adjustHeightPosition(heightInPx, id, plotting) {
    //   const imageElement = document.getElementById("plotting" + id);
    //   console.log(imageElement?.clientHeight);
    //   if (imageElement) {
    //     const imageHeight = imageElement.clientHeight - 50;
    //     let heightRatio =
    //       imageHeight / parseInt(this.IMG_PLOTTING_HEIGHT.replace("PX", ""));
    //     let finalHeightposition = heightInPx.replace("px", "") * heightRatio;
    //     // let sensorCount = this.sensorPlottingCount[id]
    //     //   ? this.sensorPlottingCount[id] + 1
    //     //   : 0;
    //     // this.sensorPlottingCount[id] = sensorCount;
    //     // if (plotting.alarm_event) {
    //     //   this.sensorAlarmCount[id] = this.sensorAlarmCount[id]
    //     //     ? this.sensorAlarmCount[id] + 1
    //     //     : 0;
    //     // }
    //     return finalHeightposition + "px";
    //   }
    //   return false;
    // },
    // adjustWidthPosition(widthInPx, id) {
    //   const imageElement = document.getElementById("plotting" + id);
    //   if (imageElement) {
    //     const imageWidth = imageElement.clientWidth - 50;
    //     let widthRatio = imageWidth / this.IMG_PLOTTING_WIDTH.replace("PX", "");
    //     let finalWidthposition = widthInPx.replace("px", "") * widthRatio;
    //     return finalWidthposition + "px";
    //   }
    //   return false;
    // },
    // onImageLoad(imageId) {
    //   this.$set(this.imageLoaded, imageId, true);
    // },
  },
};
</script>
