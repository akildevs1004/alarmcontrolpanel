<template>
  <NoAccess v-if="!$pagePermission.can('armedreports_view', this)" />
  <div v-else style="width: 100%; margin-top: -20px">
    <DeviceArmedLogs />
  </div>
</template>

<script>
import DeviceArmedLogs from "../../components/Alarm/Reports/ArmedReports.vue";
export default {
  components: {
    DeviceArmedLogs,
  },
};
</script>
