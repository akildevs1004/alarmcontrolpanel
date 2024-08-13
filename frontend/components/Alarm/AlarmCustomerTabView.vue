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
        Watchman
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
        <v-icon>mdi-fire</v-icon> </v-tab
      ><v-tab>
        Technician
        <v-icon>mdi-fire</v-icon> </v-tab
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
              :contact_type="'primary'"
              :key="keyPrimary"
              :key1="keyPrimary"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'secondary'"
              :key="keySecondary"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'Watchman'"
              :key="keySecurity"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'Police'"
              :key="keyPolice"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'Medical'"
              :key="keyMedical"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'Fire'"
              :key="keyFire"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityContactInfo
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :contact_type="'Technician'"
              :key="keyTechnician"
          /></v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityAlarmNotes
              :alarmId="alarmId"
              v-if="customer"
              :customer="customer"
              :key="keyLogs"
          /></v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import SecurityBuildingInfo from "../../components/Alarm/SecurityDashboard/SecurityBuildingInfo.vue";
import SecurityContactInfo from "../../components/Alarm/SecurityDashboard/SecurityContactInfo.vue";
import SecurityAlarmNotes from "../../components/Alarm/SecurityDashboard/SecurityAlarmNotes.vue";

export default {
  components: {
    SecurityBuildingInfo,
    SecurityContactInfo,
    SecurityAlarmNotes,
  },
  props: ["_customerID", "alarmId"],
  data: () => ({
    tab: "",
    customer: null,
    key: 1,
    keyPrimary: 0,
    keySecondary: 0,
    keySecurity: 0,
    keyPolice: 0,

    keyMedical: 0,
    keyLogs: 0,
    keyFire: 0,
    keyTechnician: 0,
  }),
  computed: {},
  mounted() {},
  created() {
    this.getDataFromApi();
  },
  watch: {
    tab: {
      handler(value) {
        if (value == 1) this.keyPrimary += 1;
        else if (value == 2) this.keySecondary += 1;
        else if (value == 3) this.keySecurity += 1;
        else if (value == 4) this.keyPolice += 1;
        else if (value == 5) this.keyMedical += 1;
        else if (value == 6) this.keyFire += 1;
        else if (value == 7) this.keyTechnician += 1;
        else if (value == 8) this.keyLogs += 1;
      },
      deep: true,
    },
  },
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
