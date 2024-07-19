<template>
  <div>
    <v-card class="elevation-2" style="height: 400px">
      <v-row>
        <!-- <v-col cols="9"> -->
        <AlarmEventsChart :name="'AlarmEventsChart'" :height="'300'" />
        <!-- </v-col>
        <v-col cols="3">
          <div>Fastest Reseponse on:</div>
          <div>Slowest Reseponse on:</div>

          <div>Average Reseponse on:</div>
          <AlamCustomerResponsePieChart
            @changeDatesComp="ApplyDateFilter()"
            :name="'AlamCustomerResponsePieChart'"
            :height="'500'"
          />
        </v-col> -->
      </v-row>
    </v-card>
  </div>
</template>

<script>
export default {
  // components: { AlamCustomerResponsePieChart },

  data() {
    return {
      items: [],
    };
  },
  watch: {},
  created() {},

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    ApplyDateFilter() {},
  },
};
</script>
