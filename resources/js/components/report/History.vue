<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <button
        type="button"
        @click="exportExcel()"
        aria-label="ส่งออกExcel"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-green/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-gray-800"
      >
       <i class="far fa-file-excel text-[#18CF82] text-[20px]"></i>
        <span>ส่งออก Excel</span>
      </button>
      <button
        type="button"
        @click="backPage()"
        aria-label="ย้อนกลับ"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-gray-800"
      >
        <!-- ไอคอนย้อนกลับ (SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>ย้อนกลับ</span>
      </button>
    </div>

    <div class="border-b border-gray-200">
      <nav class="flex space-x-4" aria-label="Tabs">
        <!-- Tab 1 -->
        <button
          @click="clickTab(3)"
          :class="activeTab === 3 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
          class="py-2 px-4 font-medium"
        >
          รายวัน
        </button>

        <!-- Tab 2 -->
        <button
          @click="clickTab(2)"
          :class="activeTab === 2 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
          class="py-2 px-4 font-medium"
        >
          รายเดือน
        </button>

        <!-- Tab 3 -->
        <button
          @click="clickTab(1)"
          :class="activeTab === 1 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
          class="py-2 px-4 font-medium"
        >
          รายปี
        </button>
      </nav>
    </div>

      <!-- Tab Content -->
      <div class="mt-4">
        <div v-if="activeTab === 1">
          <div class="flex flex-col text-center text-[24px] mt-11 text-[#1d6849]">สถิติผู้เข้าชม รายปี</div>

          <div>
              <apexchart ref="line_year" type="line" height="450" :options="chartOptions" :series="series"></apexchart>
          </div>

          <div class="flex flex-col w-full mt-11 mb-11">
            <table class="min-w-full border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                <th class="border px-4 py-3 text-center">#</th>
                <th class="border px-4 py-3 text-center">ปี (พ.ศ.)</th>
                <th class="border px-4 py-3 text-center">จำนวนผู้เข้าชม</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in item_year" :key="i" class="hover:bg-green-50">
                  <td class="border px-4 py-2 text-center">{{i+1 }}</td>
                  <td class="border px-4 py-2 text-center">{{ item.name}}</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(item.count) }}</td>
                </tr>
                <tr class="hover:bg-green-50">
                  <td colspan="2" class="border text-center text-bold text-[16px] py-2">รวม</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(totalCountcomputed()) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="activeTab === 2">
          <div class="flex flex-col text-center text-[24px] mt-11 text-[#1d6849]">สถิติผู้เข้าชม รายเดือน</div>

          <div class="flex w-full md:w-3/12 2xl:w-2/12">
            <select
            v-model="s_year"
            @change="showMonth()"
            class="w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-400"
          >
            <option
              v-for="y in years"
              :key="y"
              :value="y"
            >
              ปี พ.ศ. {{ y }}
            </option>
          </select>
          </div>

          <div>
              <apexchart ref="line_year" type="line" height="450" :options="chartOptions" :series="series"></apexchart>
          </div>

          <div class="flex flex-col w-full mt-11 mb-11">
            <table class="min-w-full border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                <th class="border px-4 py-3 text-center">#</th>
                <th class="border px-4 py-3 text-center">เดือน ปี (พ.ศ.)</th>
                <th class="border px-4 py-3 text-center">จำนวนผู้เข้าชม</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in item_month" :key="i" class="hover:bg-green-50">
                  <td class="border px-4 py-2 text-center">{{i+1 }}</td>
                  <td class="border px-4 py-2 text-center">{{ item.name}}</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(item.count) }}</td>
                </tr>
                <tr class="hover:bg-green-50">
                  <td colspan="2" class="border text-center text-bold text-[16px] py-2">รวม</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(totalCountcomputedMonth()) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <div v-if="activeTab === 3">
          <div class="flex flex-col text-center text-[24px] mt-11 text-[#1d6849]">สถิติผู้เข้าชม 30 วันล่าสุด</div>

          <div>
              <apexchart ref="line_year" type="line" height="450" :options="chartOptions" :series="series"></apexchart>
          </div>

          <div class="flex flex-col w-full mt-11 mb-11">
            <table class="min-w-full border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                <th class="border px-4 py-3 text-center">#</th>
                <th class="border px-4 py-3 text-center">วันที่	</th>
                <th class="border px-4 py-3 text-center">จำนวนผู้เข้าชม</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in item_day" :key="i" class="hover:bg-green-50">
                  <td class="border px-4 py-2 text-center">{{i+1 }}</td>
                  <td class="border px-4 py-2 text-center">{{ item.name}}</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(item.count) }}</td>
                </tr>
                <tr class="hover:bg-green-50">
                  <td colspan="2" class="border text-center text-bold text-[16px] py-2">รวม</td>
                  <td class="border px-4 py-2 text-center">{{ formatNumber(totalCountcomputedDay()) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

   
  </div>
</template>
<script>


export default {

  data() {
    return {
      isLoading: false,
      items:[],
      s_type: 1,
      s_date: null,
      s_month: '',
      s_year: '2567',
      years: [],
      activeTab: 3,
      item_year: [],
      item_month: [],
      item_day: [],
      series: [{
          name: "สถิติ",
          data: []
      }],
      chartOptions: {
        chart: {
          height: 450,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'],
            opacity: 0.5
          },
        },
        xaxis: {
          categories: [],
        },
        colors:['#28a745']
      },
    }
  },
  created(){
    const start = 2567
    const count = 15 

    for (let i = 0; i <= count; i++) {
      this.years.push(start + i)
    }

    this.s_month = String(new Date().getMonth() + 1).padStart(2, "0");

    const yearNow = new Date().getFullYear();
    this.s_year = yearNow + 543;
    this.selectedDate = new Date();
  },
  mounted() {
    this.clickTab(3);
  },
  components:{
  },
  methods: {
    clickTab(type){
      this.activeTab = type;
      if(type == 1){
        this.showDataYear();
      }
      if(type == 2){
        this.showMonth();
      }
      if(type == 3){
        this.showDay();
      }
    },
    backPage(){
      location.href= "/admin/report";
    },
    showDataYear(){
      this.isLoading = true;
      axios.get('/admin/report/history/year',{
        params: {
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.item_year = res.data.item

          this.$refs.line_year.updateSeries([{ name: "เยื่ยมชม", data: res.data.series }]);
          this.$refs.line_year.updateOptions({
              xaxis: {
                  categories: res.data.categories
              },
              colors:['#28a745']
          })

          this.isLoading = false;
        }else{
          this.item_year = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    showMonth(){
      this.isLoading = true;
      axios.get('/admin/report/history/month',{
        params: {
          year: this.s_year
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.item_month = res.data.item

          this.$refs.line_year.updateSeries([{ name: "เยื่ยมชม", data: res.data.series }]);
          this.$refs.line_year.updateOptions({
              xaxis: {
                  categories: res.data.categories
              },
              colors:['#fd7e14']
          })

          this.isLoading = false;
        }else{
          this.item_year = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    showDay(){
      this.isLoading = true;
      axios.get('/admin/report/history/day',{
        params: {
          year: this.s_year
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.item_day = res.data.item

          this.$refs.line_year.updateSeries([{ name: "เยื่ยมชม", data: res.data.series }]);
          this.$refs.line_year.updateOptions({
              xaxis: {
                  categories: res.data.categories
              },
              colors:['#007bff']
          })

          this.isLoading = false;
        }else{
          this.item_year = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    formatNumber(num){
      return num.toLocaleString();
    },
    totalCountcomputed() {
      return this.item_year.reduce((sum, item) => sum + item.count, 0)
    },
    totalCountcomputedMonth(){
      return this.item_month.reduce((sum, item) => sum + item.count, 0)
    },
    totalCountcomputedDay(){
      return this.item_day.reduce((sum, item) => sum + item.count, 0)
    },
    filterFloat(value) {
        if (/^(\-|\+)?([0-9]+(\.[0-9]+)?|Infinity)$/
            .test(value))
            return Number(value);
        return 0;
    },
    exportExcel(){
        this.isLoading = true;
        const item = (this.activeTab == 3) ? this.item_day : (this.activeTab == 2) ? this.item_month : this.item_year;
        axios.post('/admin/excel/history',{
          type: this.activeTab,
          item: item,
          year: this.s_year,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          location.href = "/export/"+res.data.data.filename;
        }else{
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
       
    },
  }
}
</script>