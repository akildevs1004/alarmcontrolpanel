<template>
  <div>
    <TicketsList :security_id="security_id" />
  </div>
</template>

<script>
import TicketsList from "../../components/Tickets/TicketsList.vue";

export default {
  layout: "security",
  components: {
    TicketsList,
  },

  data() {
    return { security_id: null };
  },
  watch: {},
  created() {
    // console.log(this.$auth.user);

    if (this.$auth.user.security)
      this.security_id = this.$auth.user.security.id;
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
