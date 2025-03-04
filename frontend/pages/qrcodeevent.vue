<template>
  <div>
    <Componentqrcodeevent :alarm_id="alarm_id" :key="alarm_id" />
  </div>
</template>

<script>
import Componentqrcodeevent from "../components/Operator/componentqrcodeevent.vue";

// import ComponentQrcodeEvent from "../components/Operator/ComponentQrcodeEvent.vue";

export default {
  auth: false,
  layout: "qrcodelayout",
  components: { Componentqrcodeevent },
  data: () => ({
    alarm_id: null,
  }),

  created() {
    this.alarm_id = null; //parseInt("209");
    try {
      this.alarm_id = parseInt(this.$route.query.eventid) / 9999;
    } catch (e) {}
  },
};
</script>
