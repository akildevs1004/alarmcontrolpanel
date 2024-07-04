<template>
  <div style="width: 100%">
    <CustomFilter
      style="float: right; padding-top: 5px; z-index: 9999"
      @filter-attr="filterAttr"
      :default_date_from="date_from"
      :default_date_to="date_to"
      :defaultFilterType="1"
      :height="'40px'"
    />

    <v-card class="py-2" style="width: 100%">
      <v-row>
        <v-col cols="8"
          ><span class="pl-5" style="font-size: 16px">
            Temperature Hourly</span
          ></v-col
        >
        <v-col cols="4" class="pull-right"
          ><v-icon @click="getDataFromApi(1)" style="float: right"
            >mdi mdi-reload</v-icon
          >
        </v-col>
      </v-row>
      <v-col lg="12" md="12" style="text-align: center; padding-top: 0px">
        <div :id="name" style="width: 100%; margin-top: 0px" class="pt-2"></div>
      </v-col>
    </v-card>
  </div>
</template>

<script>
// import VueApexCharts from 'vue-apexcharts'
export default {
  props: ["name", "height", "branch_id", "device_serial_number", "from_date"],
  data() {
    return {
      key: 1,

      series: [
        {
          name: "Temparature",
          type: "column",
          data: [],
        },
      ],
      chartOptions: {
        chart: {
          height: 190, //
          type: "line",
          toolbar: {
            show: false,
          },
        },
        stroke: {
          width: [0, 2],

          curve: "smooth",
        },
        // title: {
        //   show: false,
        //   // text: "Temperature Hourly Chart",
        // },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1],
        },
        labels: [],
        plotOptions: {
          bar: {
            columnWidth: "20%",
            colors: {
              ranges: [
                {
                  from: 0,
                  to: 23,
                  color: "#008450",
                },
                {
                  from: 23,
                  to: 50,
                  color: "#EFB700",
                },
                {
                  from: 50,
                  to: 99,
                  color: "#B81D13",
                },
              ],
            },
          },
        },
        xaxis: {
          type: "string",
        },

        dataLabels: {
          enabled: false,
        },
      },
    };
  },
  watch: {
    // branch_id() {
    //   try {
    //     this.$store.commit("AlarmDashboard/alarm_temparature_hourly", null);
    //     this.getDataFromApi();
    //   } catch (e) {}
    // },
    from_date(val) {
      this.getDataFromApi();
    },
  },

  created() {
    // const today = new Date();
    // this.from_date = today.toISOString().slice(0, 10);

    console.log("from_date", this.from_date);
  },
  mounted() {
    this.chartOptions.chart.height = this.height;
    this.chartOptions.series = this.series;
    // setTimeout(() => {
    ////this.getDataFromApi();
    setInterval(() => {
      if (this.$route.name == "alarm-dashboard") {
        this.getDataFromApi();
      }
    }, 1000 * 60 * 15);

    console.log("Mounted");
    /// }, 2000);

    // this.$store.commit(
    //   "AlarmDashboard/alarm_temperature_chart2_date",
    //   this.from_date
    // );
    try {
      new ApexCharts(
        document.querySelector("#" + this.name),
        this.chartOptions
      ).render();
    } catch (error) {}
    // this.getDataFromApi();
    setTimeout(() => {
      this.getDataFromApi();
    }, 1000 * 10);

    setTimeout(() => {
      this.getDataFromApi();
    }, 6000);
  },

  methods: {
    filterDate() {},
    viewLogs() {
      this.$router.push("/attendance_report");
    },
    getDataFromApi() {
      if (!this.device_serial_number) return;

      let data = {
        houry_data: [
          {
            date: "2024-06-24",
            hour: 0,
            count: 22.25,
          },
          {
            date: "2024-06-24",
            hour: 1,
            count: 22.32,
          },
          {
            date: "2024-06-24",
            hour: 2,
            count: 22.8,
          },
          {
            date: "2024-06-24",
            hour: 3,
            count: 22.97,
          },
          {
            date: "2024-06-24",
            hour: 4,
            count: 22.49,
          },
          {
            date: "2024-06-24",
            hour: 5,
            count: 21.59,
          },
          {
            date: "2024-06-24",
            hour: 6,
            count: 20.68,
          },
          {
            date: "2024-06-24",
            hour: 7,
            count: 20.36,
          },
          {
            date: "2024-06-24",
            hour: 8,
            count: 22.38,
          },
          {
            date: "2024-06-24",
            hour: 9,
            count: 23.05,
          },
          {
            date: "2024-06-24",
            hour: 10,
            count: 23.61,
          },
          {
            date: "2024-06-24",
            hour: 11,
            count: 23.87,
          },
          {
            date: "2024-06-24",
            hour: 12,
            count: 22.93,
          },
          {
            date: "2024-06-24",
            hour: 13,
            count: 22.54,
          },
          {
            date: "2024-06-24",
            hour: 14,
            count: 22.4,
          },
          {
            date: "2024-06-24",
            hour: 15,
            count: 22.38,
          },
          {
            date: "2024-06-24",
            hour: 16,
            count: 22.79,
          },
          {
            date: "2024-06-24",
            hour: 17,
            count: 22.06,
          },
          {
            date: "2024-06-24",
            hour: 18,
            count: 22.1,
          },
          {
            date: "2024-06-24",
            hour: 19,
            count: 21.38,
          },
          {
            date: "2024-06-24",
            hour: 20,
            count: 21.49,
          },
          {
            date: "2024-06-24",
            hour: 21,
            count: 21.36,
          },
          {
            date: "2024-06-24",
            hour: 22,
            count: 22.29,
          },
          {
            date: "2024-06-24",
            hour: 23,
            count: 23.82,
          },
        ],
      };
      this.renderChart(data.houry_data);

      return false;
      this.key = 1;
      // const data = await this.$store.dispatch("dashboard/every_hour_count");
      this.$axios
        .get("alarm_dashboard_get_hourly_data", {
          params: {
            company_id: this.$auth.user.company_id,
            branch_id: this.branch_id > 0 ? this.branch_id : null,
            device_serial_number: this.device_serial_number,
            from_date: this.from_date,
          },
        })
        .then(({ data }) => {
          this.key = this.key + 1;
          this.data = data;
          this.loading = false;
          this.$store.commit("AlarmDashboard/alarm_temparature_hourly", data);

          this.temperature_hourly_data = data.houry_data;

          this.renderChart(this.temperature_hourly_data);

          // setTimeout(() => {
          //   this.key = this.key + 1;
          // }, 1000);
        });
    },
    renderChart(data) {
      let counter = 0;

      this.chartOptions.series[0]["data"] = [];
      data.forEach((item, index) => {
        this.chartOptions.series[0]["data"][index] = item.count; //parseInt(item.count);

        //this.chartOptions.series[1]["data"][counter] = item.count;

        this.chartOptions.labels[index] = item.hour;
        counter++;
      });
      try {
        new ApexCharts(
          document.querySelector("#" + this.name),
          this.chartOptions
        ).render();
      } catch (error) {}
    },
  },
};
</script>
