<template>
  <div>
      <apexchart type="bar" height="350" ref="bar" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  data() {
    return {
      series: [{
            data: [21, 22, 10],
            name: 'ความพึงพอใจ',
      }],
      chartOptions: {
        chart: {
          height: 350,
          type: 'bar',
        },
        colors: ['#7cb5ec','#7cb5ec','#7cb5ec'],
        plotOptions: {
          bar: {
            columnWidth: '45%',
            distributed: true,
          }
        },
        dataLabels: {
              enabled: true,
              formatter: function (val) {
                return val + "%";
              },
              offsetY: -20,
              style: {
                fontSize: '12px',
                colors: ["#ffffff"]
              }
            },
        legend: {
          show: false
        },
        xaxis: {
          categories: [
            'ไม่พึงพอใจ',
            'พึงพอใจ',
            'พึงพอใจมาก'
          ],
          labels: {
            style: {
              // colors: ['#7cb5ec','#7cb5ec','#7cb5ec'],
              fontSize: '12px'
            }
          }
        },
        yaxis: {
    labels: {
      formatter: function (value) {
        return value + "%";
      }
    }
  },
  tooltip: {
    y: {
      formatter: function (value) {
        return value + "%";
      }
    }
  }
      },
    }
  },
  props:["vote"],
  mounted(){
  },
  methods:{
  },
  watch: {
    vote: {
      immediate: true,
      handler(newVal) {
        if (!Array.isArray(newVal)) {
          console.error("vote is not array", newVal);
          return;
        }

        this.series = [{
        name: 'ความพึงพอใจ',
        data: newVal
      }];
      }
    }
  }
}
</script>