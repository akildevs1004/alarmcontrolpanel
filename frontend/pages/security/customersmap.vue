<template>
  <div>
    <CustomersAlarmMap />
  </div>
</template>

<script>
import CustomersAlarmMap from "../../components/Alarm/Map/CustomersAlarmMap.vue";
export default {
  layout: "security",
  components: { CustomersAlarmMap },
};
</script>
