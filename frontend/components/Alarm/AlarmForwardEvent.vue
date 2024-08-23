<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-row v-if="loading">
      <v-col>
        <v-progress-linear
          indeterminate
          color="primary darken-2"
        ></v-progress-linear>
      </v-col>
    </v-row>
    <v-row>
      <v-col class="text-right">
        <v-btn x-small title="New" color="primary" @click="addItem()"
          >Add
        </v-btn>
      </v-col>
    </v-row>

    <v-row
      v-for="(d, index) in forward?.contacts"
      :key="index"
      style="border: 0px solid black"
      ><v-col
        cols="1"
        style="min-width: 20px; max-width: 110px; padding-top: 10px"
      >
        {{ index + 1 }} )
      </v-col>
      <v-col cols="3" style="min-width: 60px; padding: 4px">
        <v-text-field
          label="Name"
          hide-details
          outlined
          dense
          v-model="d.name"
        />
      </v-col>
      <v-col cols="4" style="min-width: 50px; padding: 4px">
        <v-text-field
          type="email"
          label="Email"
          hide-details
          outlined
          dense
          v-model="d.email"
        />
      </v-col>
      <v-col cols="3" style="min-width: 100px; padding: 4px">
        <v-text-field
          type="number"
          label="Whatsapp Number"
          hide-details
          outlined
          dense
          v-model="d.whatsapp_number"
        />
      </v-col>

      <v-col cols="1" style="min-width: 50px; max-width: 50px; padding: 4px">
        <v-icon
          v-if="forward.contacts.length > 1"
          dark
          fab
          style="color: black; padding-top: 10px"
          outlined
          @click="removeItem(index)"
          size="20"
          >mdi-delete</v-icon
        >
      </v-col>
    </v-row>

    <v-row class="pr-3">
      <v-col cols="9" class="text-right">
        <div :style="errors ? 'color:red' : 'color:green'">
          {{ response }}
        </div>
      </v-col>
      <v-col cols="2" class="text-right">
        <v-btn class="primary" small @click="save_forward_info"
          >Click to Send</v-btn
        >
      </v-col>
      <v-col cols="1" class="text-left"> </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["alarm_id"],
  data() {
    return {
      forwardTypes: [],
      SensorTypes: [],
      displayEditform: false,
      loading: false,
      forward_info: false,
      menu: false,
      date: false,
      snackbar: false,
      response: "",
      errors: null,
      zonesErrors: [],
      forward: {
        contacts: [],
      },
      oneTOsixty: [],
    };
  },
  created() {
    // for (let index = 0; index <= 60; index++) {
    //   this.oneTOsixty.push(index);
    // }
    // if (this.$store.state.storeAlarmControlPanel?.forwardTypes) {
    //   this.forwardTypes = this.$store.state.storeAlarmControlPanel.forwardTypes;
    // }
    // if (this.$store.state.storeAlarmControlPanel?.SensorTypes) {
    //   this.SensorTypes = this.$store.state.storeAlarmControlPanel.SensorTypes;
    // }
    this.addItem();
    //this.getInfo();
  },
  computed: {},
  methods: {
    displayEdit() {
      this.displayEditform = true;
    },
    cancel() {
      this.displayEditform = false;
    },
    removeItem(index) {
      this.forward.contacts.splice(index, 1);
    },
    addItem() {
      let obj = {
        name: "",
        email: "",
        whatsapp_number: "",
      };
      this.forward.contacts.push(obj);
    },
    // getInfo() {
    //   this.loading = true;
    //   //let zones = this.editforward.contacts;
    //   if (this.editforward) {
    //     this.editforward.contacts.forEach((element) => {
    //       let obj = {
    //         sensor_name: element.sensor_name,
    //         wired: element.wired,
    //         location: element.location,
    //         area_code: element.area_code,
    //         zone_code: element.zone_code,
    //         delay: parseInt(element.delay),
    //         hours24: element.hours24,
    //       };
    //       this.forward.contacts.push(obj);
    //     });
    //   } else {
    //     this.editforward.contacts = [];
    //   }

    //   if (this.editforward.contacts.length == 0) {
    //     this.addItem();
    //   }
    // },
    can(item) {
      return true;
    },
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },

    save_forward_info() {
      this.errors = null;

      let payload = {
        ...this.forward,

        company_id: this.$auth?.user?.company?.id,
        alarm_id: this.alarm_id,
      };
      this.response = "";

      this.loading = true;

      this.$axios
        .post(`/alarm_forward_notification`, payload)
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data;
            this.response = data.message;
          } else {
            this.errors = null;
            this.snackbar = true;
            this.response = data.message;
            //this.forward = data.record || { zones: [] };
            //this.close_forward_info();
            this.$emit("closeDialog");

            this.forward = {
              contacts: [],
            };
            this.addItem();
          }
        })
        .catch((e) => {
          this.errors = e;
        });
    },
    close_forward_info() {
      this.forward_info = false;
      this.errors = [];
      this.cancel();
      this.$emit("close-popup");
    },
  },
};
</script>
