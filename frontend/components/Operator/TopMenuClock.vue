<template>
  <div v-if="currentTime" style="text-align: center">
    <label style="font-size: 35px">{{ currentTime }}</label>
    <label style="font-size: 13px">
      {{ currentDate }}
    </label>

    <!-- <div style="text-align: center">
      <img
        title="Alarm Control Panel - Xtremeguard"
        src="/logo22.png"
        style="width: 50px"
      />
    </div> -->
  </div>
</template>

<script>
export default {
  props: ["colorcodes"],
  data() {
    return {
      currentTime: "",
      currentDate: "",
    };
  },
  mounted() {
    setInterval(() => {
      let nowTime = new Date();
      this.currentTime = nowTime.toLocaleTimeString([], { hour12: false });
    }, 1000 * 1);
    let months = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ];
    const days = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ];

    let now = new Date();
    let day = now.getDate();
    let monthIndex = now.getMonth();
    let year = now.getFullYear();
    let dayIndex = now.getDay(); // Get the day of the week (0-6)

    this.currentDate = `${days[dayIndex].toUpperCase()}, ${day} ${months[
      monthIndex
    ].toUpperCase()} ${year} `;
  },
  created() {},
  methods: {},
};
</script>
