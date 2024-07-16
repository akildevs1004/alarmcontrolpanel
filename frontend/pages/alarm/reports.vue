<template>
  <div>
    <v-card class="elevation-2">
      <AlarmEventsChart :name="'AlarmEventsChart'" :height="'300'" />
    </v-card>
  </div>
</template>

<script>
export default {
  components: {},

  data() {
    return {
      items: [],
    };
  },
  watch: {},
  created() {},

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
