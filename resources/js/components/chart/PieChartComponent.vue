<template>
  <div>
      <apexchart type="pie" width="380" ref="pie" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  data() {
    return {
      series: [],
      chartOptions: {
        chart: {
          width: 380,
          type: 'pie',
        },
        labels: [],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'top'
            }
          }
        }]
      },
    }
  },
  mounted(){
  },
  methods:{
    showPie(series, labels){

      this.$refs.pie.updateSeries(series);
      this.$refs.pie.updateOptions({
          labels: labels
      })

    }
  }
}
</script>