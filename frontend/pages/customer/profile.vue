<template>
  <div v-if="customer && customer.id">
    <AlarmViewCustomer :isPopup="false" :_id="customer.id" />
  </div>
</template>
<script>
export default {
  layout: "customer",
  data() {
    return {
      customer: null,
    };
  },

  async created() {
    await this.getDataFromApi(this.$auth.user?.customer?.id || null);
  },
  methods: {
    async getDataFromApi(customer_id) {
      try {
        this.$axios
          .get(`customers?customer_id=${customer_id}`)
          .then(({ data }) => {
            this.data = data.data[0] || null;
            this.customer = this.data;
          });
      } catch (e) {}
    },
  },
};
</script>
