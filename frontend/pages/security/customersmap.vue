<template>
  <div>
    <CustomersAlarmMap :displayTable="true" />
  </div>
</template>

<script>
import CustomersAlarmMap from "../../components/Alarm/Map/CustomersAlarmMap.vue";
export default {
  layout: "security_old",
  components: { CustomersAlarmMap },
};
</script>
