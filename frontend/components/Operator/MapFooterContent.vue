<template>
  <div>
    <v-row>
      <v-col
        ><v-card
          class="elevation-2"
          style="height: 60px; width: 140px; background-color: #afbfa4c2"
        >
          <v-row>
            <v-col
              style="text-align: right; max-width: 50px; padding-right: 0px"
              ><img :src="colorcodes.sos.image + '?3=4'" style="width: 30px"
            /></v-col>

            <v-col
              style="
                padding-left: 4px;
                padding-right: 26px;
                padding-top: 4px;
                text-align: center;
              "
            >
              <div style="font-size: 36px; line-height: 37px; color: #0064ff">
                {{ data.sos }}
              </div>
              <div style="font-size: 11px">SOS</div></v-col
            >
          </v-row>
        </v-card>
      </v-col>
      <v-col
        ><v-card
          class="elevation-2"
          style="height: 60px; width: 140px; background-color: #afbfa4c2"
        >
          <v-row>
            <v-col
              style="text-align: right; max-width: 50px; padding-right: 0px"
              ><img :src="colorcodes.alarm.image + '?3=4'" style="width: 30px"
            /></v-col>

            <v-col
              style="
                padding-left: 4px;
                padding-right: 26px;
                padding-top: 4px;
                text-align: center;
              "
            >
              <div style="font-size: 36px; line-height: 37px; color: #0064ff">
                {{ data.critical }}
              </div>
              <div style="font-size: 11px">Critical</div></v-col
            >
          </v-row>
        </v-card>
      </v-col>
      <v-col
        ><v-card
          class="elevation-2"
          style="height: 60px; width: 140px; background-color: #afbfa4c2"
        >
          <v-row>
            <v-col
              style="text-align: right; max-width: 50px; padding-right: 0px"
              ><img
                :src="colorcodes.offline.image + '?3=4'"
                style="width: 30px"
            /></v-col>

            <v-col
              style="
                padding-left: 4px;
                padding-right: 26px;
                padding-top: 4px;
                text-align: center;
              "
            >
              <div style="font-size: 36px; line-height: 37px; color: #0064ff">
                {{ data.offline }}
              </div>
              <div style="font-size: 11px">Offline</div></v-col
            >
          </v-row>
        </v-card>
      </v-col>
      <v-col
        ><v-card
          class="elevation-2"
          style="height: 60px; width: 140px; background-color: #afbfa4c2"
        >
          <v-row>
            <v-col
              style="text-align: right; max-width: 50px; padding-right: 0px"
              ><img :src="colorcodes.disarm.image + '?3=4'" style="width: 30px"
            /></v-col>

            <v-col
              style="
                padding-left: 4px;
                padding-right: 26px;
                padding-top: 4px;
                text-align: center;
              "
            >
              <div style="font-size: 36px; line-height: 37px; color: #0064ff">
                {{ data.tickets }}
              </div>
              <div style="font-size: 11px">Tickets</div></v-col
            >
          </v-row>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["colorcodes"],
  data() {
    return {
      data: {
        cancelgetOperatorStatisticsTokenSource: null,
      },
    };
  },
  mounted() {
    setInterval(() => {
      this.getOperatorStatistics();
    }, 1000 * 30);
  },
  created() {
    this.getOperatorStatistics();
  },
  methods: {
    getOperatorStatistics() {
      console.log(this.cancelgetOperatorStatisticsTokenSource);

      if (this.cancelgetOperatorStatisticsTokenSource) {
        this.cancelgetOperatorStatisticsTokenSource.cancel(
          "Operation cancelgetOperatorStatisticsTokenSource canceled due to new request."
        );
      }
      this.cancelgetOperatorStatisticsTokenSource =
        this.$axios.CancelToken.source();
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      try {
        this.$axios
          .get(`alarm_event_operator_statistics`, options)
          .then(({ data }) => {
            this.data = data;
          });
      } catch (e) {}
    },
  },
};
</script>
