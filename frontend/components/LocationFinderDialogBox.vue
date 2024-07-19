<template>
  <v-dialog v-model="dialog" width="1100">
    <template v-slot:activator="{ on, attrs }">
      <v-btn color="primary" dark v-bind="attrs" v-on="on">
        Open Google Map
      </v-btn>
    </template>
    <GoogleMap @close="dialog = false" />
  </v-dialog>
</template>

<script>
export default {
  auth: false,
  data() {
    return {
      dialog: false,
    };
  },
};
</script>
