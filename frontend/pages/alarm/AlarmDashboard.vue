<template>
  <div>
    <AlamDeviceCountPieChart />
  </div>
</template>

<script>
import AlamDeviceCountPieChart from "../../components/Alarm/Dashboard/AlarmDeviceCountPieChart.vue";

export default {
  components: {
    AlamDeviceCountPieChart,
  },
  data: () => ({
    profile_percentage: 60,
    tab: null,
    text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",

    _id: null,
  }),
  computed: {},
  mounted() {},
  created() {
    // this._id = this.$route.params.id;
  },
  watch: {},
  methods: {},
};
</script>
