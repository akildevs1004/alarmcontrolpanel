<template>
  <div>
    <v-row style="width: 100%; color: #fff; line-height: 0px">
      <v-col>
        <div v-if="alarm && customer">
          <v-row style="padding: 0px">
            <v-col style="padding: 0px">
              <table class="operatorcustomerTop1" style="width: 100%">
                <tr>
                  <td>Event ID : #{{ alarm.id }}</td>
                  <td>
                    {{
                      $dateFormat.formatDateMonthYear(
                        alarm.alarm_start_datetime
                      )
                    }}
                  </td>
                </tr>
                <tr>
                  <td>Type :{{ alarm.alarm_type ?? "---" }}</td>
                  <td>
                    Category :
                    <span v-if="alarm.alarm_category == 1">Critical</span>
                    <span v-else-if="alarm.alarm_category == 2">Medium</span>
                    <span v-else-if="alarm.alarm_category == 3">Low</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    Property :{{
                      customer.buildingtype ? customer.buildingtype.name : "---"
                    }}
                  </td>
                  <td>
                    Name :
                    {{
                      customer?.building_name
                        ? $utils.caps(customer.building_name)
                        : "---"
                    }}
                  </td>
                </tr>
              </table>
            </v-col>
          </v-row>
          <!-- <table class="operatormap" style="width: 100%">
            <tr>
              <td>Event ID : #{{ alarm.id }}</td>
              <td>
                {{
                  $dateFormat.formatDateMonthYear(alarm.alarm_start_datetime)
                }}
              </td>
            </tr>
            <tr>
              <td>Property</td>
              <td>{{ customerObject?.buildingtype?.name || "---" }}</td>
            </tr>
            <tr>
              <td>Property</td>
              <td>{{ customerObject?.buildingtype?.name || "---" }}</td>
            </tr>
          </table>
          -->

          <!-- <v-row style="border-bottom: 1px solid #fff"
            ><v-col style="">Event ID : #{{ alarm.id }}</v-col
            ><v-col>
              {{ $dateFormat.formatDateMonthYear(alarm.alarm_start_datetime) }}
            </v-col> </v-row
          ><v-row style="border-bottom: 1px solid #fff"
            ><v-col>Type :{{ alarm.alarm_type ?? "---" }} </v-col
            ><v-col
              >Category :
              <span v-if="alarm.alarm_category == 1">Critical</span>
              <span v-else-if="alarm.alarm_category == 2">Medium</span>
              <span v-else-if="alarm.alarm_category == 3">Low</span></v-col
            > </v-row
          ><v-row style="border-bottom: 1px solid #fff"
            ><v-col
              >Property :{{
                customer.buildingtype ? customer.buildingtype.name : "---"
              }}</v-col
            ><v-col
              >Name :
              {{
                customer?.building_name
                  ? $utils.caps(customer.building_name)
                  : "---"
              }}</v-col
            >
          </v-row> -->
        </div>
      </v-col>
    </v-row>

    <v-row style="padding: 0px; padding-top: 10px">
      <v-col style="padding: 0px">
        <v-tabs
          style="background-color: #909a9f"
          height="25"
          center-active
          right
          class="customerEmergencyContactTabs customerEmergencyContactTabsBGcolor"
        >
          <v-tab
            style="background-color: #909a9f"
            v-if="
              contact.address_type.toLowerCase() == 'primary' ||
              contact.address_type.toLowerCase() == 'secondary' ||
              contact.address_type.toLowerCase() == 'security'
            "
            v-for="(contact, index) in alarm.device.customer.contacts"
            :key="contact.id"
          >
            {{ contact.address_type }}</v-tab
          >
          <v-tab-item
            style="background-color: #909a9f"
            v-if="
              contact.address_type.toLowerCase() == 'primary' ||
              contact.address_type.toLowerCase() == 'secondary' ||
              contact.address_type.toLowerCase() == 'security'
            "
            v-for="(contact, index) in alarm.device.customer.contacts"
            :key="contact.id + 50"
            name="index+50"
          >
            <v-card class="elevation-1" style="background-color111: #909a9f">
              <EventCustomerTabContacts
                :alarmId="alarm.id"
                v-if="contact"
                :customer="alarm.device.customer"
                :contact_type="contact.address_type"
                :key="contact.address_type"
                @emitreloadEventNotesStep1="emitreloadEventNotes2"
              />
            </v-card>
          </v-tab-item>
        </v-tabs>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EventCustomerTabContacts from "./EventCustomerTabContacts.vue";

export default {
  components: { EventCustomerTabContacts },
  props: ["_id", "isPopup", "customer", "alarm", "colorcodes"],
  data: () => ({
    tab: "",
  }),
  computed: {},
  mounted() {},
  created() {},
  watch: {},
  methods: {
    emitreloadEventNotes2() {
      this.$emit("emitreloadEventNotes3");
    },
    getAlarmColorObject(alarm, customer = null) {
      if (alarm) {
        if (this.colorcodes[alarm.alarm_type.toLowerCase()])
          return this.colorcodes[alarm.alarm_type.toLowerCase()];
        if (alarm.alarm_status == 1) {
          return this.colorcodes.alarm;
        }
      }
      // else if (alarm.alarm_status == 0) {
      //   return this.colorcodes.closed;
      // }
      //if (
      //   alarm.customer &&
      //   this.findanyArmedDevice(alarm.customer.devices)
      // ) {
      //   return this.colorcodes.armed;
      // }
      else if (customer) {
        if (this.findAnyDeviceisOffline(customer.devices) > 0) {
          return this.colorcodes.offline;
        } else if (this.findanyArmedDevice(customer.devices)) {
          return this.colorcodes.armed;
        } else if (this.findanyDisamrDevice(customer.devices) > 0) {
          return this.colorcodes.disarm;
        }
      }
      // console.log(
      //   "findAnyDeviceisOffline",
      //   this.findAnyDeviceisOffline(item.devices)
      // );
      // console.log(alarm.alarm_status);

      return this.colorcodes.offline;
    },
  },
};
</script>
