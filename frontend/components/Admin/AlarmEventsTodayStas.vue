<template>
  <div class="bordertop">
    <v-row>
      <v-col md="10" sm="10" xs="10">
        <h4>Today Events</h4>
      </v-col>

      <v-col md="2" sm="2" xs="2" class="text-end">
        <v-menu bottom left>
          <template v-slot:activator="{ on, attrs }">
            <v-btn dark-2 icon v-bind="attrs" v-on="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list width="120" dense>
            <v-list-item @click="viewLogs()">
              <v-list-item-title style="cursor: pointer">
                View Logs
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="7" style="padding-right: 0px"
        ><v-row align-self="center">
          <v-col lg="2" md="2" sm="2" xs="2" align-self="center">
            <v-avatar color="red" size="30">
              <v-icon size="20">mdi-account</v-icon>
            </v-avatar>
          </v-col>
          <v-col
            style="
              font-size: 12px;
              text-align: left;
              padding-right: 0px;
              padding-left: 20px;
            "
            lg="4"
            md="4"
            sm="4"
            xs="4"
            align-self="center"
            >Critital
          </v-col>
          <v-col
            lg="6"
            md="6"
            sm="6"
            xs="6"
            class="text-red red--text text-center laptop-padding1"
            align-self="center"
            style="font-size: 34px !important"
          >
            {{ data ? data.crititalCount : 0 }}
          </v-col>
        </v-row>
        <v-row>
          <v-col lg="2" md="2" sm="2" xs="2" class="pt-md-5">
            <v-avatar color="orange" size="30">
              <v-icon size="20"> mdi-account</v-icon>
            </v-avatar>
          </v-col>
          <v-col
            lg="4"
            md="4"
            sm="4"
            xs="4"
            style="
              font-size: 12px;
              text-align: left;
              padding-right: 0px;
              padding-left: 20px;
            "
            align-self="center"
            >Medium</v-col
          >
          <v-col
            lg="6"
            md="6"
            sm="6"
            xs="6"
            class="text-red orange--text text-center laptop-padding1"
            align-self="center"
            style="font-size: 34px !important"
            >{{ data ? data.mediumCount : 0 }}
          </v-col>
        </v-row>
        <v-row>
          <v-col lg="2" md="2" sm="2" xs="2" class="pt-md-5">
            <v-avatar color="blue" size="30">
              <v-icon>mdi-account</v-icon>
            </v-avatar>
          </v-col>
          <v-col
            lg="4"
            md="4"
            sm="4"
            xs="4"
            style="
              font-size: 12px;
              text-align: left;
              padding-right: 0px;
              padding-left: 20px;
            "
            align-self="center"
            >Low</v-col
          >
          <v-col
            lg="6"
            md="6"
            sm="6"
            xs="6"
            class="text-red blue--text text-center laptop-padding1"
            align-self="center"
            style="font-size: 34px !important"
            >{{ data ? data.mediumCount : 0 }}
          </v-col>
        </v-row> </v-col
      ><v-col
        cols="5"
        style="
          margin: auto;
          text-align: center;
          text-align: center;
          padding-left: 0px;
          margin-top: 0px;
          line-height: 86px;
        "
        ><div style="color: red; font-size: 20px">SOS</div>
        <div
          class="text-red text-h3 text-center laptop-padding1"
          align-self="center"
          style="color: red; font-size: 20px"
        >
          {{ data ? data.sosCount : 0 }}
        </div></v-col
      >
    </v-row>

    <!-- <v-row>
      <v-col md="12">
        <v-btn
          @click="goToReports()"
          size="small"
          class="btn btn-block fa-lg mt-1 mb-3"
          style="background-color: #6946dd; color: #fff"
        >
          View All reports
        </v-btn>
      </v-col>
    </v-row> -->
  </div>
</template>
<script>
export default {
  props: [],
  data: () => ({
    options: {},
    date_from: null,
    loading: false,
    dataLength: 0,

    data: null,
  }),
  // watch: {
  //   branch_id() {
  //     this.$store.commit("dashboard/attendance_count", null);
  //     this.getDataFromApi();
  //   },
  // },
  created() {
    let today = new Date();
    this.date_from = today.toISOString().split("T")[0];
    setTimeout(() => {
      this.getDataFromApi();
    }, 1000 * 2);
  },

  methods: {
    goToReports() {
      this.$router.push("/attendance_report");
    },

    viewLogs() {
      this.$router.push("/alarm/armedreports");
    },
    getDataFromApi() {
      this.$axios
        .get("dashboard_statistics_date_range", {
          params: {
            company_id: this.$auth.user.company_id,
            date_from: this.date_from,
            date_to: this.date_from,
          },
        })
        .then(({ data }) => {
          if (data.data.length > 0) this.data = data.data[0];
        });
    },
  },
};
</script>

<style scoped>
.center-both {
  height: 31vh;
  /* Adjust the height as needed */
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 500px) {
  .bordertop {
    border-top: 1px solid #ddd;
    padding-bottom: 5px;
    border-left: 0px;
  }
}

@media (max-width: 1500px) {
  .laptop-padding {
    padding-left: 30px;
  }
}
</style>
