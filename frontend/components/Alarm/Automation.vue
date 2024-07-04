<template>
  <div>
    <v-row>
      <v-col cols="12" class="text-right" style="padding-top: 0px">
        <v-row>
          <v-col cols="7"></v-col>
          <v-col
            cols="5"
            class="text-right"
            style="min-width: 550px; width: 550px"
          >
            <v-row>
              <v-col cols="5" style=""
                ><v-text-field
                  style="padding-top: 7px"
                  height="20"
                  class="employee-schedule-search-box"
                  @input="getDataFromApi()"
                  v-model="commonSearch"
                  label="Search (min 3)"
                  dense
                  outlined
                  type="text"
                  append-icon="mdi-magnify"
                  clearable
                  hide-details
                ></v-text-field
              ></v-col>
              <v-col cols="5">
                <CustomFilter
                  style="float: right; padding-top: 5px; z-index: 9999"
                  @filter-attr="filterAttr"
                  :default_date_from="date_from"
                  :default_date_to="date_to"
                  :defaultFilterType="1"
                  :height="'40px'"
              /></v-col>

              <v-col cols="1" style="padding-left: 0px; width: 50px">
                <v-btn small dense color="primary" class="ma-2"> New </v-btn>
              </v-col>
              <v-col cols="1" style="width: 50px">
                <v-btn icon small dense color="primary" class="ma-2">
                  <v-icon>mdi mdi-download</v-icon>
                </v-btn></v-col
              >
            </v-row>
          </v-col>
        </v-row>
        <v-row>
          <!-- <v-spacer></v-spacer>
          <v-col cols="6"></v-col>
          <span cols="6" class="mt-8" style="width: 500px">
            <span style="width: 50%"
              ><v-text-field
                style="width: 200px"
                height="20"
                class="employee-schedule-search-box"
                @input="getDataFromApi()"
                v-model="commonSearch"
                label="Search (min 3)"
                dense
                outlined
                type="text"
                append-icon="mdi-magnify"
                clearable
              ></v-text-field
            ></span>
            <span style="width: 50%">
              <CustomFilter
                style="float: right; padding-top: 5px; z-index: 9999"
                @filter-attr="filterAttr"
                :default_date_from="date_from"
                :default_date_to="date_to"
                :defaultFilterType="1"
                :height="'40px'"
              />
            </span>
          </span> -->
          <!-- <v-col cols="2">
            <v-text-field outlined small dense label="Search"></v-text-field
          ></v-col>
          <v-col cols="3">
            <CustomFilter
              style="float: right; padding-top: 5px; z-index: 9999"
              @filter-attr="filterAttr"
              :default_date_from="date_from"
              :default_date_to="date_to"
              :defaultFilterType="1"
              :height="'40px'"
            />
          </v-col> -->
        </v-row>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-data-table :headers="headers" :items="items" class="elevation-0">
          <template v-slot:item.device="{ item }">
            <div>{{ item.device }}</div>
            <div class="secondary-value">{{ item.serial_number }}</div>
          </template>
          <template v-slot:item.sensor="{ item }">
            <div>{{ item.sensor }}</div>
            <div class="secondary-value">{{ item.type }}</div>
          </template>
          <template v-slot:item.zone="{ item }">
            <div>{{ item.zone }}</div>
            <div class="secondary-value">{{ item.area }}</div>
          </template>
          <template v-slot:item.alarm_type="{ item }">
            <div>{{ item.alarm_type }}</div>
          </template>
          <template v-slot:item.first_name="{ item }">
            <div>{{ item.first_name }} {{ item.last_name }}</div>
          </template>
          <template v-slot:item.email="{ item }">
            <div>{{ item.email }}</div>
          </template>
          <template v-slot:item.whatsapp_number="{ item }">
            <div>{{ item.whatsapp_number }}</div>
          </template>
          <template v-slot:item.mobile_number="{ item }">
            <div>{{ item.mobile_number }}</div>
          </template>
          <template v-slot:item.options="{ item }">
            <v-menu bottom left>
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark-2 icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list width="120" dense>
                <v-list-item v-if="can('branch_view')" @click="viewItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="secondary" small> mdi-pencil </v-icon>
                    Edit
                  </v-list-item-title>
                </v-list-item>
                <v-list-item v-if="can('branch_edit')" @click="editItem(item)">
                  <v-list-item-title style="cursor: pointer">
                    <v-icon color="red" small> mdi-delete </v-icon>
                    Delete
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      headers: [
        { text: "#", value: "sno" },
        { text: "Device", value: "device" },
        { text: "Sensor", value: "sensor" },
        { text: "Location", value: "location" },

        { text: "Zone", value: "zone" },
        { text: "Alarm Type", value: "alarm_type" },
        { text: "Name", value: "first_name" },
        { text: "Email", value: "email" },
        { text: "Whatsapp", value: "whatsapp_number" },
        { text: "Mobile", value: "mobile_number" },

        { text: "Options", value: "options" },
      ],
      items: [
        {
          sno: "01",
          device: "Control Panel",
          sensor: "PIR",
          alarm_type: "Burglary",
          location: "Hall",
          type: "Wired",
          zone: "Z1",
          area: "A1",
          first_name: "Venu",
          last_name: "J",
          email: "venu@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
        {
          sno: "02",
          device: "Control Panel",
          sensor: "Door Contact ",
          alarm_type: "Burglary",
          location: "Main Door ",
          type: "Wired",
          zone: "Z2",
          area: "A1",
          first_name: "Shual ",
          last_name: "Hameed",
          email: "Hameed@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
        {
          sno: "03",
          device: "Control Panel",
          sensor: "Panic button",
          alarm_type: "Medical",
          location: "Bed room ",
          type: "Wired",
          zone: "Z3",
          area: "A1",
          first_name: "Shual ",
          last_name: "Hameed",
          email: "Hameed@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
        {
          sno: "04",
          device: "Arduino1",
          sensor: "Smoke sensor",
          alarm_type: "Teperature",
          location: "Kitchen",
          type: "Wireless",
          zone: "Z4",
          area: "A1",
          first_name: "Shual ",
          last_name: "Hameed",
          email: "Hameed@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
        {
          sno: "05",
          device: "Control Panel",
          sensor: "Beam Sensor",
          alarm_type: "Burglary",
          location: "Garden Lift",
          type: "Wireless",
          zone: "Z5",
          area: "A1",
          first_name: "Shual ",
          last_name: "Hameed",
          email: "Hameed@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
        {
          sno: "06",
          device: "ESP32",
          sensor: "PIR",
          alarm_type: "Burglary",
          location: "Hall",
          type: "Wired",
          zone: "Z6",
          area: "A1",
          first_name: "Shual ",
          last_name: "Hameed",
          email: "Hameed@gmail.com",
          whatsapp_number: "97155 222 54785",
          mobile_number: "97155 222 54785",
        },
      ],
    };
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
