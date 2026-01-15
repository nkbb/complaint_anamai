<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <div class="flex md:flex-row flex-col gap-3 mt-11 items-center">
      <h1 class="items-center ">ค้นหา</h1>
      <div class="flex w-full md:w-3/12 2xl:w-2/12">
        <select
          v-model="s_type"
          class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700
                focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <option value="1">ประจำวัน</option>
            <option value="2">ประจำเดือน</option>
            <option value="3">ประจำปี</option>
        </select>
      </div>
      <div class="flex w-full lg:w-[233px]" v-if="s_type == 1">
        <date-picker 
        v-model:value="selectedDate" 
        range
        placeholder="วันที่"
        input-class="h-[42px] border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-400" 
        :formatter="momentFormat">
        </date-picker>
      </div>
      <div class="flex w-full md:w-3/12 2xl:w-2/12" v-if="s_type == 2">
        <select
          v-model="s_month"
          class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700
                focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none">
          <option value="01">มกราคม</option>
          <option value="02">กุมภาพันธ์</option>
          <option value="03">มีนาคม</option>
          <option value="04">เมษายน</option>
          <option value="05">พฤษภาคม</option>
          <option value="06">มิถุนายน</option>
          <option value="07">กรกฎาคม</option>
          <option value="08">สิงหาคม</option>
          <option value="09">กันยายน</option>
          <option value="10">ตุลาคม</option>
          <option value="11">พฤศจิกายน</option>
          <option value="12">ธันวาคม</option>
        </select>
      </div>
      <div class="flex w-full md:w-3/12 2xl:w-2/12" v-if="s_type == 2 || s_type == 3">
        <select
        v-model="s_year"
        class="w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-400"
      >
        <option
          v-for="y in years"
          :key="y"
          :value="y"
        >
          {{ y }}
        </option>
      </select>
      </div>
      <div class="flex justify-center">
        <button
          class="flex items-center gap-2 text-center bg-blue-600 text-white px-4 py-2 rounded-lg
                hover:bg-blue-700 active:scale-95 transition" @click="showData()">
          <i class="fas fa-search"></i> ค้นหา
        </button>
      </div>
    </div>
    <div class="flex flex-col w-full mt-11 mb-11">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
          <th class="border px-4 py-3 text-center">ระดับความรุนแรง</th>
          <th class="border px-4 py-3 text-center">จำนวนเรื่อง</th>
          <th class="border px-4 py-3 text-center">ร้อยละ</th>
          </tr>
        </thead>
         <tbody>
          <template v-for="(item,i) in items">
            <tr class="hover:bg-green-50">
              <td class="border px-4 py-2">ระดับ {{ i+1 }} {{ item.name }}</td>
              <td class="border px-4 py-2 text-center">
                <span v-if="item.count > 0">{{ item.count }}</span>
              </td>
              <td class="border px-4 py-2 text-center">
                 <span v-if="item.count > 0">{{ getPercent(item.count) }}%</span>
              </td>
            </tr>
          </template>
          <tr class="hover:bg-green-50">
            <td class="border text-center text-bold text-[16px] py-2">รวม</td>
            <td class="border text-center text-bold text-[16px] py-2">{{ Total() }}</td>
            <td class="border text-center text-bold text-[16px] py-2">100%</td>
          </tr>

        </tbody>
      </table>
    </div>
    <div class="flex flex-col w-full mt-11 mb-11">
        <apexchart ref="line" type="line" height="560" :options="chartOptions" :series="series"></apexchart>
    </div>

    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <!-- ปุ่มพิมพ์ -->
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

      <!-- ปุ่มย้อนกลับ -->
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


  </div>
</template>
<script>

import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import moment from 'moment'
import { ref } from 'vue'

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
      momentFormat: {
      // Date → String
      stringify: (date) => {
        // ถ้ามีค่า date ให้แปลงเป็น dd/MM/YY
        return date ? moment(date).format('DD/MM/YY') : ''
      },
      
      // String → Date
      parse: (value) => {
        // แปลงจาก dd/MM/YY กลับเป็น Date()
        return value ? moment(value, 'DD/MM/YY').toDate() : null
      },

      // get week number (optional)
      getWeek: (date) => {
        return moment(date).week() // ใช้ moment คำนวณเลขสัปดาห์
      }
    },
    series: [{
          name: "ร้อยละ",
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
          enabled: true,
          formatter: function (val) {
            return val + ' %'
          }
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
    // this.selectedDate = new Date();
    
  },
  setup() {
    const selectedDate = ref(null)

    const momentFormat = {
      // Date → String (แสดงเป็น พ.ศ.)
      stringify: (date) => {
        if (!date) return ''
        const d = moment(date)
        const day = d.date().toString().padStart(2, '0')
        const month = (d.month() + 1).toString().padStart(2, '0')
        const yearBE = d.year() + 543
        return `${day}/${month}/${yearBE}`
      },

      // String → Date (แปลงจาก พ.ศ. → ค.ศ.)
      parse: (value) => {
        if (!value) return null
        const [day, month, yearBE] = value.split('/')
        const yearCE = Number(yearBE) - 543
        return moment(`${day}/${month}/${yearCE}`, 'DD/MM/YYYY').toDate()
      },

      // เลขสัปดาห์ (optional)
      getWeek: (date) => moment(date).week()
    }

    return { selectedDate, momentFormat }
  },
  mounted() {
    this.selectedDate = [new Date(), new Date()]
    this.showData();
  },
  components:{
    DatePicker,
  },
  methods: {
    backPage(){
      location.href= "/admin/report";
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/report/severity/load',{
        params: {
          type: this.s_type,
          month: this.s_month,
          year: this.s_year,
          day: this.selectedDate,
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.items = res.data.item
          this.isLoading = false;

          let series = [];
          let categories = [];
          this.items.forEach((item, i) => {
            series.push(this.getPercent(item.count));
            categories.push('ระดับ ' + (i+1) + ' ');
          })
          this.$refs.line.updateSeries([{ name: "ร้อยละ", data: series }]);
          this.$refs.line.updateOptions({
              xaxis: {
                  categories: categories
              },
              colors:['#28a745']
          })
        }else{
          this.items = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    Total(){
      var total = 0;
      this.items.forEach((item) => {
        total += parseInt(item.count);
      })
      return this.filterFloat(total);
    },
    getPercent(num) {
      const total = parseInt(this.Total());
      if (!total || total === 0) return 0
      return ((num / total) * 100).toFixed(2)
    },
    filterFloat(value) {
        if (/^(\-|\+)?([0-9]+(\.[0-9]+)?|Infinity)$/
            .test(value))
            return Number(value);
        return 0;
    },
    exportExcel(){
        this.isLoading = true;

        const totalSub = this.items.reduce((sum, item) => {
          return sum + item.sub.length;
        }, 0);

        axios.post('/admin/excel/severity',{
          type: this.s_type,
          month: this.s_month,
          year: this.s_year,
          day: this.selectedDate,
          item: this.items, 
          total_sub: totalSub,
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