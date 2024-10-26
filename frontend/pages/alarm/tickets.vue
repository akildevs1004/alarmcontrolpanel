<template>
  <NoAccess v-if="!$pagePermission.can('tickets_view', this)" />
  <div v-else style="width: 100%; margin-top: -20px">
    <TicketsCompDashboard :canReply="false" />
  </div>
</template>

<script>
import TicketsCompDashboard from "../../components/Alarm/TechnicianDashboard/TicketsDashboard.vue";

export default {
  layout: "default",
  components: { TicketsCompDashboard },
};
</script>
