<template>
  <v-container>
    <v-row v-if="item && item.id">
      <v-col cols="4">
        <div class="mt-3 text-center">
          <v-img
            style="border-radius: 5px"
            v-if="item.profile_picture"
            :src="item.profile_picture"
          ></v-img>
          <v-avatar size="175" tile v-else>
            <v-img
              src="https://placehold.co/120x120?text=No Image Added"
            ></v-img>
          </v-avatar>
        </div>
      </v-col>
      <v-divider vertical></v-divider>
      <!-- Contact Details -->
      <v-col cols="8">
        <div class="text-right">
          <CustomerV2AddressEdit
            :customer_id="customer_id"
            :isEditable="isEditable"
            :customer="customer"
            @closeDialog="
              () => {
                $emit(`callrefreshData`);
              }
            "
          />
        </div>
        <table>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Building Type:</span>
              <span>{{ buildingType }}</span>
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Building Name:</span>
              <span>{{ customer_payload.building_name }}</span>
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>House Number:</span>
              <span>{{ customer_payload.house_number }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.house_number">
            <td class="text-danger mt-2">
              {{ errors.house_number[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Street Number:</span>
              <span>{{ customer_payload.street_number }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.street_number">
            <td class="text-danger mt-2">
              {{ errors.street_number[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>City:</span>
              <span>{{ customer_payload.city }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.city">
            <td class="text-danger mt-2">
              {{ errors.city[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>State:</span>
              <span>{{ customer_payload.state }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.state">
            <td class="text-danger mt-2">
              {{ errors.state[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Country:</span>
              <span>{{ customer_payload.country }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.country">
            <td class="text-danger mt-2">
              {{ errors.country[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Landmark:</span>
              <span>{{ customer_payload.landmark }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.landmark">
            <td class="text-danger mt-2">
              {{ errors.landmark[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Latitude:</span>
              <span>{{ customer_payload.latitude }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.latitude">
            <td class="text-danger mt-2">
              {{ errors.latitude[0] }}
            </td>
          </tr>
          <tr>
            <td
              class="py-1 label text-caption"
              style="
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e0e0e0;
                color: grey;
              "
            >
              <span>Longitude:</span>
              <span>{{ customer_payload.longitude }}</span>
            </td>
          </tr>
          <tr v-if="errors && errors.longitude">
            <td class="text-danger mt-2">
              {{ errors.longitude[0] }}
            </td>
          </tr>
        </table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: ["customer_id", , "item", "customer", "isMapviewOnly", "isEditable"],
  data: () => ({
    showMap: false,
    response: "",
    snackbar: false,
    show1: false,
    buildingTypes: [],
    branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    upload: {
      name: "",
    },
    start_date: "",
    end_date: "",
    customer_payload: {
      building_type_id: "",

      building_name: "",
      house_number: "",
      street_number: "",
      city: "",
      state: "",
      country: "",
      landmark: "",
      latitude: "",
      longitude: "",

      start_date: "",
      end_date: "",
      email: "",
      password: "",
    },
    e1: 1,
    errors: [],
    previewImage: null,
  }),
  created() {
    this.preloader = false;
    this.getBuildingTypes();

    if (this.customer) {
      this.previewImage = this.customer.profile_picture;
      this.customer_payload.building_type_id = this.customer.building_type_id;
      this.customer_payload.building_name = this.customer.building_name;
      this.customer_payload.house_number = this.customer.house_number;
      this.customer_payload.street_number = this.customer.street_number;
      this.customer_payload.city = this.customer.city;
      this.customer_payload.state = this.customer.state;
      this.customer_payload.country = this.customer.country;
      this.customer_payload.landmark = this.customer.landmark;
      this.customer_payload.latitude = this.customer.latitude;
      this.customer_payload.longitude = this.customer.longitude;
      this.customer_payload.contact_number = this.customer.contact_number;
      this.customer_payload.start_date = this.customer.start_date;
      this.customer_payload.end_date = this.customer.end_date;
    } else {
      this.customer_payload = {
        building_type_id: "",

        building_name: "",
        house_number: "",
        street_number: "",
        city: "",
        state: "",
        country: "",
        landmark: "",
        latitude: "",
        longitude: "",

        start_date: "",
        end_date: "",
        email: "",
        password: "",
      };
    }
  },
  watch: {
    "$store.state.location": {
      handler(newLocation) {
        this.customer_payload.latitude = newLocation.lat;
        this.customer_payload.longitude = newLocation.lng;
      },
      deep: true, // Watch for changes in nested properties
    },
  },
  computed: {
    locationData() {
      return this.$store.state.location;
    },
    buildingType() {
      let found =
        this.customer_payload &&
        this.buildingTypes.find(
          (e) => e.id == this.customer_payload.building_type_id
        );
      return found?.name;
    },
  },
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    getBuildingTypes() {
      this.$axios
        .get(`building_types`, {
          params: {
            company_id: this.$auth.user.company_id,
          },
        })
        .then(({ data }) => {
          this.buildingTypes = data;
        });
    },
  },
};
</script>
