<template>
  <div style="padding: 0px; width: 100%; height: auto">
    <v-row style="margin-top: -27px"
      ><v-col cols="8" style="color: black; font-size: 12px">Water</v-col>

      <v-col cols="4" class="text-right align-right"
        ><img src="/dashboard-arrow.png" style="width: 18px; padding-top: 5px"
      /></v-col>
    </v-row>
    <v-divider color="#5a82ca" />
    <v-row class="pt-0 mt-0">
      <v-col cols="7" class="text-center p-0" style="padding: 0px">
        <div
          v-if="name"
          :id="name"
          :name="name"
          style="margin: 0 auto; text-align: left; margin-left: -10px"
        ></div>
      </v-col>
      <v-col
        cols="5"
        class="p-0 pt-2"
        style="
          font-size: 11px;
          color: #000000;
          padding-left: 0px;
          padding-right: 0px;
          line-height: 32px;
          margin: auto;
        "
      >
        <v-row>
          <v-col cols="8">Active</v-col
          ><v-col cols="4" style="padding-left: 0px">{{
            data?.active ?? "0"
          }}</v-col>
        </v-row>
        <v-divider color="#dddddd" />
        <v-row>
          <v-col cols="8">Closed</v-col
          ><v-col cols="4" style="padding-left: 0px">{{
            data?.closed ?? "0"
          }}</v-col>
        </v-row>
        <v-divider color="#dddddd" />
      </v-col>
    </v-row>

    <div
      v-if="data.length == 0"
      style="
        padding: 0px;
        margin: auto;
        text-align: center;
        vertical-align: middle;
        height: auto;
        padding-top: 36%;
      "
    >
      No Data available
    </div>
  </div>
</template>

<script>
export default {
  props: ["name"],
  data() {
    return {
      totalCount: 0,
      filter1: "categories",
      data: [],
      chartOptions: {
        noData: {
          text: "There's no data",
          align: "center",
          verticalAlign: "middle",
          offsetX: 0,
          offsetY: 0,
        },

        title: {
          //text: "Alarm Events - Categories",
          align: "left",
          margin: 0,
        },

        colors: ["#ffc000", "#ff0000", "#92d050"],

        series: [],
        chart: {
          toolbar: {
            show: false,
          },
          width: 130,
          height: 150,
          type: "donut",
        },
        customTotalValue: 0,
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                name: {
                  show: true,
                  fontSize: "22px",
                  fontFamily: "Rubik",
                  color: "#dfsda",
                  offsetY: -10,
                },
                value: {
                  show: true,
                  fontSize: "16px",
                  fontFamily: "Helvetica, Arial, sans-serif",
                  color: undefined,
                  offsetY: 16,
                  formatter: function (val) {
                    return val;
                  },
                },
                total: {
                  show: true,
                  label: "Total",
                  color: "#373d3f",
                  formatter: function (val) {
                    return val.config.customTotalValue;
                  },
                },
              },
            },
          },
        },
        labels: [],

        dataLabels: {
          enabled: false,
          style: {
            fontSize: "10px",
          },
        },
        legend: {
          align: "left",
          show: false,
          fontSize: "12px",
          formatter: (seriesName, opts) => {
            return `
               ${seriesName} : ${opts.w.globals.series[opts.seriesIndex]}
            `;
          },
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                toolbar: {
                  show: false,
                },
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
    };
  },
  mounted() {
    this.loadDevicesStatistics();
  },
  methods: {
    applyFilter() {
      this.loadDevicesStatistics();
    },
    loadDevicesStatistics() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      this.$axios.get(`/device_alarm_water_stats`, options).then(({ data }) => {
        this.data = data;
        this.renderChart1(data);
      });
    },

    async renderChart1(data) {
      let counter = 0;
      let total = 0;

      this.chartOptions.labels[0] = "Active";
      this.chartOptions.series[0] = data.active;

      this.chartOptions.labels[1] = "Closed";
      this.chartOptions.series[1] = data.closed;

      total = data.active + data.closed;

      this.chartOptions.customTotalValue = total;

      if (this.chart) {
        this.chart.destroy();
      }

      // Render the chart
      this.chart = await new ApexCharts(
        document.querySelector("#" + this.name),
        this.chartOptions
      );
      this.chart.render();
    },
  },
  created() {},
};
</script>
