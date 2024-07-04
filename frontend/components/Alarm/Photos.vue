<template>
  <div>
    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px">
        <v-btn color="primary" dense small> Edit </v-btn>
      </v-col>
      <v-col v-for="n in 8" :key="n" lass="d-flex child-flex" cols="3">
        <v-card class="elevation-2 pl-1">
          <div class="bold">Floor - {{ n }}</div>
          <v-img
            src="/floor_images/image1.png"
            lazy-src="/floor_images/image1.png"
            aspect-ratio="1"
            class="grey lighten-2"
          >
            <template v-slot:placeholder>
              <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular
                  indeterminate
                  color="grey lighten-5"
                ></v-progress-circular>
              </v-row>
            </template>
          </v-img>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data: () => ({}),
  created() {},
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
