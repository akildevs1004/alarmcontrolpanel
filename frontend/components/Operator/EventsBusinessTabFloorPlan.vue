<template>
  <div>
    <v-row
      ><v-col>
        <v-carousel
          v-model="currentSlide"
          v-if="customer?.photos"
          hide-delimiter-background
          show-arrows-on-hover
          @change="keyPlottings++"
          hide-arrows-on-hover
          hide-delimiters
          hide-arrows
          :style="'height:' + parseInt(browserHeight - 180) + 'px'"
        >
          <v-carousel-item
            v-for="(item, index) in customer.photos"
            :key="'imageplotting' + item.id"
            v-if="item.photo_plottings[0]"
          >
            <v-chip color="#203864" style="color: #fff" label
              >{{ index + 1 }}: {{ item.title }}</v-chip
            >

            <img
              :id="
                'plotting' +
                  item.photo_plottings[0]?.customer_building_picture_id ?? 0
              "
              class="photo-img"
              :src="item.picture"
              :style="
                'width: 100%; height: ' + parseInt(browserHeight - 220) + 'px'
              "
              @load="
                onImageLoad(
                  item.photo_plottings[0]?.customer_building_picture_id ?? 0
                )
              "
            />
            <span
              v-if="
                item.photo_plottings[0] &&
                imageLoaded[
                  item.photo_plottings[0]?.customer_building_picture_id ?? 0
                ]
              "
            >
              <PlottingIcon
                v-if="item.photo_plottings[0]"
                v-for="(plotting, idx) in item.photo_plottings[0].plottings"
                :key="'plotting' + idx"
                :plotting="plotting"
                :picture-id="
                  item.photo_plottings[0]?.customer_building_picture_id ?? 0
                "
                :alarm="alarm"
              />
            </span>
          </v-carousel-item>
        </v-carousel> </v-col
    ></v-row>
    <v-row>
      <v-col>
        <div
          style="
            max-width: 750px;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            margin: auto;
            text-align: center;
          "
        >
          <div
            style="
              display: inline-block;
              width: 140px;
              text-align: center;
              margin-right: 10px;
            "
            v-for="(item, index) in customer.photos"
            :key="index"
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
          </div>
        </div>
      </v-col>
    </v-row>
    <!-- <v-row>
      <v-col style="width: 600px; overflow: scroll">
        <v-row justify="center" dense>
          <v-col
            v-for="(item, index) in customer.photos"
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
    </v-row> -->
  </div>
</template>

<script>
import PlottingIcon from "./PlottingIcon.vue";
export default {
  components: { PlottingIcon },
  props: ["customer", "alarm", "browserHeight"],
  data: () => ({
    tab: "",
    currentSlide: 0,
    imageLoaded: {},

    loadingImagesstatus: false,
    keyPlottings: 1,
  }),
  computed: {},
  mounted() {},
  created() {},
  watch: {},
  methods: {
    onImageLoad(imageId) {
      this.$set(this.imageLoaded, imageId, true);
    },
  },
};
</script>
