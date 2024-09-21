<template>
  <div><CompDashboard :canReply="true" /></div>
</template>

<script>
import CompDashboard from "../../components/Alarm/TechnicianDashboard/TicketsDashboard.vue";

export default {
  layout: "technician",
  components: { CompDashboard },
};
</script>
