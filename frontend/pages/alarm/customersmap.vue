<template>
  <NoAccess v-if="!$pagePermission.can('map_view', this)" />
  <div v-else>
    <CustomersAlarmMap :displayTable="true" />
  </div>
</template>

<script>
import CustomersAlarmMap from "../../components/Alarm/Map/CustomersAlarmMap.vue";
export default {
  layout: "default",
  components: { CustomersAlarmMap },
};
</script>
