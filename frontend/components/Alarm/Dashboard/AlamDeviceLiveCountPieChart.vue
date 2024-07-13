<template>
  <div style="padding: 0px; width: 100%; height: auto">
    <v-row class="pt-0 mt-0">
      <v-col cols="12" class="text-center pt-0">
        <div
          v-if="name"
          :id="name"
          :name="name"
          style="width: 300px; margin: 0 auto; text-align: left"
        ></div>
      </v-col>
    </v-row>

    <div
      v-if="categories.length == 0"
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
    <!-- <div>
      <v-row class="bold" style="height: auto">
        <v-col cols="1">#</v-col>
        <v-col cols="6">Category</v-col>
        <v-col cols="5">Alarm Events count</v-col>
      </v-row>
      <div style="height: 160px; overflow-y: scroll; overflow-x: hidden">
        <v-row :key="index" v-for="(category, index) in categories">
          <v-col cols="1">{{ index + 1 }}</v-col>
          <v-col cols="7"
            ><v-icon :color="options?.colors[index]">mdi mdi-square</v-icon
            >{{ category.category }}</v-col
          >
          <v-col cols="3" class="text-center">{{ category.count }}</v-col>
        </v-row>
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  props: ["name"],
  data() {
    return {
      //   items: [
      //     { title: "Title1", value: 20 },
      //     { title: "Title2", value: 30 },
      //     { title: "Title3", value: 40 },
      //     { title: "Title4", value: 50 },
      //   ],
      totalCount: 0,
      filter1: "categories",
      categories: [],
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

        colors: ["#02B64B", "#f44336"],

        series: [],
        chart: {
          toolbar: {
            show: false,
          },
          height: 250,
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
          show: true,
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

      this.$axios.get(`/device_live_stats`, options).then(({ data }) => {
        this.categories = data;
        this.renderChart1(data);
      });
    },

    renderChart1(data) {
      let counter = 0;
      let total = 1000;

      this.chartOptions.labels[0] = "Online";
      this.chartOptions.series[0] = data.online;

      this.chartOptions.labels[1] = "Offline";
      this.chartOptions.series[1] = data.total - data.online;

      this.chartOptions.customTotalValue = data.total; //this.items.ExpectingCount;

      setTimeout(() => {
        try {
          new ApexCharts(
            document.querySelector("#" + this.name),
            this.chartOptions
          ).render();
        } catch (error) {}
      }, 1000);
    },
  },
  created() {
    // try {
    //   this.items.forEach((element) => {
    //     this.totalCount += element.value;
    //   });
    //   this.options.labels = this.items.map((e) => e.title);
    //   this.options.series = this.items.map((e) => e.value);
    //   new ApexCharts(document.querySelector("#pie2"), this.options).render();
    // } catch (error) {}
  },
};
</script>
