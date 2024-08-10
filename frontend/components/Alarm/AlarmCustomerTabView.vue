<template>
  <div max-width="100%">
    <v-tabs
      icons-and-text
      v-model="tab"
      vertical
      background-color="#203864"
      dark
      color="yellow"
    >
      <v-tab>
        Customer
        <v-icon>mdi-card-account-details</v-icon>
      </v-tab>
      <v-tab>
        Primary
        <v-icon>mdi-account-tie</v-icon>
      </v-tab>
      <v-tab>
        Secondary
        <v-icon>mdi-account</v-icon>
      </v-tab>
      <v-tab>
        Security
        <v-icon>mdi-shield-account</v-icon>
      </v-tab>
      <v-tab>
        Poilice
        <v-icon>mdi-car-emergency</v-icon> </v-tab
      ><v-tab>
        Medical
        <v-icon>mdi-medical-bag</v-icon> </v-tab
      ><v-tab>
        Fire
        <v-icon>mdi-fire-alert</v-icon> </v-tab
      ><v-tab>
        Logs
        <v-icon>mdi-text-box-outline</v-icon>
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
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
          /></v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import SecurityBuildingInfo from "../../components/Alarm/SecurityDashboard/SecurityBuildingInfo.vue";
import SecurityContactInfo from "../../components/Alarm/SecurityDashboard/SecurityContactInfo.vue";

export default {
  components: {
    SecurityBuildingInfo,
    SecurityContactInfo,
  },
  props: ["_customerID", "alarmId"],
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
      if (this._customerID) {
        this.payloadOptions = {
          params: {
            company_id: this.$auth.user.company_id,
            customer_id: this._customerID,
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
