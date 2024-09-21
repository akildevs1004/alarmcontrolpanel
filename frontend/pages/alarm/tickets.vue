<template>
  <div><TicketsCompDashboard :canReply="false" /></div>
</template>

<script>
import TicketsCompDashboard from "../../components/Alarm/TechnicianDashboard/TicketsDashboard.vue";

export default {
  layout: "default",
  components: { TicketsCompDashboard },
};
</script>
