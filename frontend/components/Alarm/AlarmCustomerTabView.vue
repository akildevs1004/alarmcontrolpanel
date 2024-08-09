<template>
  <div max-width="100%">
    <v-tabs
      icons-and-text
      v-model="tab"
      vertical
      background-color="#203864"
      dark
    >
      <v-tab :style="tab == 0 ? 'color: yellow' : ''">
        Customer
        <v-icon :style="tab == 0 ? 'color: yellow' : ''"
          >mdi-card-account-details</v-icon
        >
      </v-tab>
      <v-tab :style="tab == 1 ? 'color: yellow' : ''">
        Primary
        <v-icon :style="tab == 1 ? 'color: yellow' : ''"
          >mdi-account-tie</v-icon
        >
      </v-tab>
      <v-tab :style="tab == 2 ? 'color: yellow' : ''">
        Secondary
        <v-icon :style="tab == 2 ? 'color: yellow' : ''">mdi-account</v-icon>
      </v-tab>
      <v-tab :style="tab == 3 ? 'color: yellow' : ''">
        Security
        <v-icon :style="tab == 3 ? 'color: yellow' : ''"
          >mdi-shield-account</v-icon
        >
      </v-tab>
      <v-tab :style="tab == 4 ? 'color: yellow' : ''">
        Poilice
        <v-icon :style="tab == 4 ? 'color: yellow' : ''"
          >mdi-car-emergency</v-icon
        > </v-tab
      ><v-tab :style="tab == 5 ? 'color: yellow' : ''">
        Medical
        <v-icon :style="tab == 5 ? 'color: yellow' : ''"
          >mdi-medical-bag</v-icon
        > </v-tab
      ><v-tab :style="tab == 6 ? 'color: yellow' : ''">
        Fire
        <v-icon :style="tab == 6 ? 'color: yellow' : ''"
          >mdi-fire-alert</v-icon
        > </v-tab
      ><v-tab :style="tab == 7 ? 'color: yellow' : ''">
        Logs
        <v-icon :style="tab == 7 ? 'color: yellow' : ''"
          >mdi-text-box-outline</v-icon
        >
      </v-tab>

      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityBuildingInfo v-if="customer" :customer="customer" />
          </v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text> </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import SecurityBuildingInfo from "../../components/Alarm/SecurityDashboard/SecurityBuildingInfo.vue";

export default {
  components: {
    SecurityBuildingInfo,
  },
  props: ["_id", "isPopup"],
  data: () => ({
    tab: "",
    customer: null,
  }),
  computed: {},
  mounted() {},
  created() {
    this.getDataFromApi();
  },
  watch: {},
  methods: {
    getDataFromApi() {
      if (this._id) {
        this.payloadOptions = {
          params: {
            company_id: this.$auth.user.company_id,
            customer_id: this._id,
          },
        };
        try {
          this.$axios
            .get(`customerinfo`, this.payloadOptions)
            .then(({ data }) => {
              this.customer = data;
              // if (this.data) {
              //   this.customer_contacts = this.data.contacts;
              // }
            });
        } catch (e) {}
      }
    },
  },
};
</script>
