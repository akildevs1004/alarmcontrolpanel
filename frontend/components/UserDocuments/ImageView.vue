<template>
  <div>
    <v-dialog v-model="dialog" max-width="900px">
      <template v-slot:activator="{ on, attrs }">
        <v-icon v-bind="attrs" v-on="on" small color="primary"
          >mdi-paperclip</v-icon
        >
      </template>
      <v-card>
        <v-toolbar dense flat>
          Image View
          <v-spacer></v-spacer>
          <v-icon @click="dialog = false" color="primary">mdi-close</v-icon>
        </v-toolbar>

        <v-container>
          <v-row>
            <v-col cols="12" style="text-align: center">
              <!-- <div class="text-center"></div> -->
              <img
                style="width: 400px; height: auto"
                v-if="src"
                :src="src"
                alt="Front Image"
              />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["src"],
  data: () => ({
    dialog: false,
  }),
};
</script>
