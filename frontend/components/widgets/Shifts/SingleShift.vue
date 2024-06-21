<template>
  <v-row>
    <v-col cols="12" md="3">
      <v-card elevation="1" class="p-2" style="height: 170px">
        <v-card-text style="padding: 0px">
          <TimePickerCommon
            label="On Duty Time"
            :default_value="payload.on_duty_time"
            @getTime="(value) => (payload.on_duty_time = value)"
          />
          <span v-if="errors && errors.off_duty_time" class="text-danger">{{
            errors.off_duty_time[0]
          }}</span>

          <v-row class="mt-2">
            <v-col cols="12" md="6">
              <TimePickerCommon
                label="From"
                :default_value="payload.beginning_in"
                @getTime="(value) => (payload.beginning_in = value)"
              />
              <span v-if="errors && errors.beginning_in" class="text-danger">{{
                errors.beginning_in[0]
              }}</span>
            </v-col>
            <v-col cols="12" md="6">
              <TimePickerCommon
                label="To"
                :default_value="payload.beginning_out"
                @getTime="(value) => (payload.beginning_out = value)"
              />
              <span v-if="errors && errors.beginning_out" class="text-danger">{{
                errors.beginning_out[0]
              }}</span>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" md="3">
      <v-card elevation="1" class="p-2" style="height: 170px">
        <v-card-text style="padding: 0px">
          <TimePickerCommon
            label="Off Duty Time"
            :default_value="payload.off_duty_time"
            @getTime="(value) => (payload.off_duty_time = value)"
          />
          <span v-if="errors && errors.off_duty_time" class="text-danger">{{
            errors.off_duty_time[0]
          }}</span>

          <v-row class="mt-2">
            <v-col cols="12" md="6">
              <TimePickerCommon
                label="From"
                :default_value="payload.ending_in"
                @getTime="(value) => (payload.ending_in = value)"
              />
              <span v-if="errors && errors.ending_in" class="text-danger">{{
                errors.ending_in[0]
              }}</span>
            </v-col>
            <v-col cols="12" md="6">
              <TimePickerCommon
                label="To"
                :default_value="payload.ending_out"
                @getTime="(value) => (payload.ending_out = value)"
              />
              <span v-if="errors && errors.ending_out" class="text-danger">{{
                errors.ending_out[0]
              }}</span>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" md="6" style="margin-top: -80px">
      <v-row style="width: 100%; text-align: right" class="text-right">
        <v-col cols="12" md="6">
          <v-checkbox
            v-model="additionalSettings"
            label="Additional Settings"
          ></v-checkbox>
        </v-col>
      </v-row>

      <v-row v-if="additionalSettings">
        <v-col cols="12" md="6">
          <TimePickerCommon
            label="Min working hrs"
            :default_value="payload.working_hours"
            @getTime="(value) => (payload.working_hours = value)"
          />
          <span v-if="errors && errors.working_hours" class="text-danger">{{
            errors.working_hours[0]
          }}</span>
        </v-col>
        <v-col cols="12" md="6">
          <TimePickerCommon
            label="OT start after duty hrs"
            :default_value="payload.overtime_interval"
            @getTime="(value) => (payload.overtime_interval = value)"
          />
          <span v-if="errors && errors.overtime_interval" class="text-danger">{{
            errors.overtime_interval[0]
          }}</span>
        </v-col>
        <v-col cols="12" md="6">
          <TimePickerCommon
            label="Late (  Duration)"
            :default_value="payload.late_time"
            @getTime="(value) => (payload.late_time = value)"
          />
          <span v-if="errors && errors.late_time" class="text-danger">{{
            errors.late_time[0]
          }}</span>
        </v-col>
        <v-col cols="12" md="6">
          <TimePickerCommon
            label="Early (  Duration)"
            :default_value="payload.early_time"
            @getTime="(value) => (payload.early_time = value)"
          />
          <span v-if="errors && errors.early_time" class="text-danger">{{
            errors.early_time[0]
          }}</span>
        </v-col>

        <v-col cols="12" md="6">
          <TimePickerCommon
            label="Absent For In ( Duration)"
            :default_value="payload.absent_min_in"
            @getTime="(value) => (payload.absent_min_in = value)"
          />
          <span v-if="errors && errors.absent_min_in" class="text-danger">{{
            errors.absent_min_in[0]
          }}</span>
        </v-col>
        <v-col cols="12" md="6">
          <TimePickerCommon
            label="Absent For Out ( Duration)"
            :default_value="payload.absent_min_out"
            @getTime="(value) => (payload.absent_min_out = value)"
          />
          <span v-if="errors && errors.absent_min_out" class="text-danger">{{
            errors.absent_min_out[0]
          }}</span>
        </v-col>
      </v-row>
    </v-col>
    <!-- <v-content>
        <v-container grid-list-xl>
          <v-progress-linear
            value="10"
            buffer-value="20"
            color="cyan"
            height="45"
            background-color="grey"
            class="grey lighten-2"
            buffer
          >
            <v-layout justify-space-between>
              <h1 class="title font-weight-regular pa-3">School</h1>
              <h1 class="title font-weight-regular pa-3">10%</h1>
            </v-layout>
          </v-progress-linear>
        </v-container>
      </v-content> -->

    <v-row> </v-row>

    <v-col cols="12" md="12" style="font-size: 12px">
      <table style="width: 100%">
        <tr>
          <td style="background-color: blanchedalmond"></td>

          <td
            style="
              width: 60%;
              background-color: green;
              color: #fff;
              text-align: center;
            "
          >
            Office Timing {{ payload.on_duty_time }} to
            {{ payload.off_duty_time }}
          </td>

          <td style="background-color: blanchedalmond"></td>
        </tr>
        <tr>
          <td style="background-color: blanchedalmond"></td>

          <td style="width: 60%; background-color: green">
            <table style="width: 100%">
              <tr>
                <td style="width: 30%; background-color: red; color: #fff">
                  Late - In after
                  {{ sumTime(payload.on_duty_time, payload.late_time) }}
                  <!-- {{ payload.on_duty_time }} +
                  {{ payload.absent_min_in }} -->
                </td>
                <td style="width: 30%; background-color: red; color: #fff">
                  Absent - Logout Before
                  {{ minusTime(payload.off_duty_time, payload.absent_min_out) }}
                </td>
                <td style="width: 22%; background-color: green; color: #fff">
                  <!-- 19.30 to {{ payload.off_duty_time }} -->

                  Out Device {{ payload.ending_in }} to {{ payload.ending_out }}
                </td>
              </tr>
            </table>
          </td>

          <td style="background-color: blanchedalmond"></td>
        </tr>
        <tr>
          <td style="background-color: blanchedalmond"></td>

          <td style="width: 80%; background-color: green">
            <table style="width: 100%">
              <tr>
                <td style="width: 22%; background-color: green; color: #fff">
                  <!-- {{ payload.on_duty_time }} to 11:15
                   -->
                  In Device {{ payload.beginning_in }} to
                  {{ payload.beginning_out }}
                </td>
                <td style="width: 30%; background-color: red; color: #fff">
                  Absent - In after
                  {{ sumTime(payload.on_duty_time, payload.absent_min_in) }}
                  <!-- {{ payload.on_duty_time }} +
                  {{ payload.absent_min_in }} -->
                </td>
                <td style="width: 30%; background-color: red; color: #fff">
                  Absent - Logout Before
                  {{ minusTime(payload.off_duty_time, payload.absent_min_out) }}
                </td>
                <td style="width: 22%; background-color: green; color: #fff">
                  <!-- 19.30 to {{ payload.off_duty_time }} -->

                  Out Device {{ payload.ending_in }} to {{ payload.ending_out }}
                </td>
              </tr>
            </table>
          </td>

          <td style="background-color: blanchedalmond"></td>
        </tr>
      </table>
    </v-col>

    <v-col cols="12" md="12">
      <DaysPickerCommon
        label="Working Days"
        :default_value="payload.days"
        @selectedDays="(value) => (payload.days = value)"
      />
      <span v-if="errors && errors.days" class="text-danger">{{
        errors.days[0]
      }}</span>
    </v-col>
    <v-col cols="12" md="4">
      <v-select
        label="Half Day"
        v-model="payload.halfday"
        :items="[
          { id: `Not Applicable`, name: `Not Applicable` },
          { id: `Monday`, name: `Monday` },
          { id: `Tuesday`, name: `Tuesday` },
          { id: `Wednesday`, name: `Wednesday` },
          { id: `Thursday`, name: `Thursday` },
          { id: `Friday`, name: `Friday` },
          { id: `Saturday`, name: `Saturday` },
          { id: `Sunday`, name: `Sunday` },
        ]"
        item-value="id"
        item-text="name"
        dense
        outlined
        :hide-details="true"
      ></v-select>
      <span v-if="errors && errors.halfday" class="text-danger">{{
        errors.halfday[0]
      }}</span>
    </v-col>
    <v-col cols="12" md="4">
      <TimePickerCommon
        label="Number of working hours"
        :default_value="payload.halfday_working_hours"
        @getTime="
          (value) => {
            payload.halfday_working_hours = value;
          }
        "
      />
      <span v-if="errors && errors.off_duty_time" class="text-danger">{{
        errors.off_duty_time[0]
      }}</span>
    </v-col>
    <v-col cols="12" md="4"> </v-col>
    <v-col cols="12" md="4">
      <WeekendPickerCommon
        label="Weekend 1"
        :default_value="payload.weekend1"
        @selectedWeekend="(value) => (payload.weekend1 = value)"
      />
      <span v-if="errors && errors.weekend1" class="text-danger">{{
        errors.weekend1[0]
      }}</span>
    </v-col>
    <v-col cols="12" md="4">
      <WeekendPickerCommon
        label="Weekend 2"
        :default_value="payload.weekend2"
        @selectedWeekend="(value) => (payload.weekend2 = value)"
      />
      <span v-if="errors && errors.weekend2" class="text-danger">{{
        errors.weekend2[0]
      }}</span>
    </v-col>
    <v-col cols="12" md="4">
      <MonthlyFlexiHolidays
        label="Monthly Flexible Holidays"
        :default_value="payload.monthly_flexi_holidays"
        @selectedMonthlyHolidays="
          (value) => (payload.monthly_flexi_holidays = value)
        "
      />
      <span
        v-if="errors && errors.monthly_flexi_holidays"
        class="text-danger"
        >{{ errors.monthly_flexi_holidays[0] }}</span
      >
    </v-col>
  </v-row>
</template>

<script>
import TimePickerCommon from "../../Snippets/TimePickerCommon.vue";
import DaysPickerCommon from "../../Snippets/DaysPickerCommon.vue";
import WeekendPickerCommon from "../../Snippets/WeekendPickerCommon.vue";
import MonthlyFlexiHolidays from "../../Snippets/MonthlyFlexiHolidays.vue";

export default {
  props: ["payload", "errors"],
  components: {
    TimePickerCommon,
    DaysPickerCommon,
    WeekendPickerCommon,
    MonthlyFlexiHolidays,
  },

  data() {
    return {
      additionalSettings: false,
    };
  },

  created() {
    this.payload.halfday_working_hours = "05:00";
  },
  methods: {
    sumTime(time, addHours) {
      // Current time string in HH:mm format
      let currentTime = time;

      // Split the time string into hours and minutes
      let [hours, minutes] = currentTime.split(":").map(Number);
      let [hoursExtra, minutesExtra] = addHours.split(":").map(Number);

      // Add the extra time
      hours += hoursExtra;
      minutes += minutesExtra;

      // Adjust the minutes and hours if necessary
      if (minutes >= 60) {
        minutes -= 60;
        hours += 1;
      }
      if (hours >= 24) {
        hours -= 24;
      }

      // Format the new time to HH:mm
      let newTime = `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}`;

      console.log(newTime); // Output: "17:30"
      return newTime;
    },
    minusTime(time, addHours) {
      let currentTime = time;
      // Time to subtract in HH:mm format
      let subtractTime = addHours;

      // Split the current time string into hours and minutes
      let [currentHours, currentMinutes] = currentTime.split(":").map(Number);

      // Split the subtract time string into hours and minutes
      let [subtractHours, subtractMinutes] = subtractTime
        .split(":")
        .map(Number);

      // Subtract the hours and minutes
      currentHours -= subtractHours;
      currentMinutes -= subtractMinutes;

      // Adjust the minutes and hours if necessary
      if (currentMinutes < 0) {
        currentMinutes += 60;
        currentHours -= 1;
      }
      if (currentHours < 0) {
        currentHours += 24;
      }

      // Format the new time to HH:mm
      let newTime = `${currentHours
        .toString()
        .padStart(2, "0")}:${currentMinutes.toString().padStart(2, "0")}`;
      return newTime;
    },
  },
};
</script>
