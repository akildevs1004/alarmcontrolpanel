<template>
  <div max-width="100%">
    <CustomerEdit v-if="_id" :_id="_id" :isPopup="false" :isEditable="true" />
  </div>
</template>

<script>
import CustomerEdit from "../../../components/Alarm/Customer/CustomerEdit.vue";

export default {
  components: { CustomerEdit },
  data: () => ({
    _id: null,
  }),

  created() {
    this._id = this.$route.params.id;
  },
};
</script>
