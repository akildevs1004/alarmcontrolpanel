<template>
  <div>
    <AlarmViewCustomer v-if="_id" :_id="_id" />
  </div>
</template>

<script>
import AlarmViewCustomer from "../../../components/Alarm/ViewCustomer.vue";

export default {
  components: {
    AlarmViewCustomer,
  },
  data: () => ({
    profile_percentage: 60,
    tab: null,
  }),
  computed: {},
  mounted() {},
  created() {
    this._id = this.$route.params.id;
  },
  watch: {},
  methods: {},
};
</script>
