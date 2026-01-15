<template>
  <div>
    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <!-- <button
        type="button"
        aria-label="ส่งออกExcel"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-green/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-gray-800"
      >
       <i class="far fa-file-excel text-[#18CF82] text-[20px]"></i>
        <span>ส่งออก Excel</span>
      </button> -->

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


    <template v-if="items.length > 0">
      <div class="flex flex-col text-center text-[24px] mt-11 text-[#1d6849]">ส่วนที่ 1 ข้อมูลของผู้ใช้บริการ <span class="text-md">จากผู้ประเมินทั้งหมด {{ items.length }}</span></div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 ">
        <div class="flex flex-col w-full md:w-1/2">
          <div class="text-center text-[#EE7530] text-[18px]">เพศ</div>
          <PieChart ref="pieGender"></PieChart>
        </div>
        <div class="flex flex-col w-full md:w-1/2">
          <div class="text-center text-[#EE7530] text-[18px]">อายุ</div>
          <PieChart ref="pieAge"></PieChart>
        </div>
        <div class="flex flex-col w-full md:w-1/2">
          <div class="text-center text-[#EE7530] text-[18px]">การศึกษา</div>
          <PieChart ref="pieEeducation"></PieChart>
        </div>
        <div class="flex flex-col w-full md:w-1/2">
          <div class="text-center text-[#EE7530] text-[18px]">อาชีพ</div>
          <PieChart ref="pieWork"></PieChart>
        </div>
      </div>

      <div class="flex flex-col text-center text-[24px] mt-11 text-[#1d6849]">ส่วนที่ 2 ความพึงพอใจของผู้ใช้บริการ <span class="text-md">จากผู้ประเมินทั้งหมด {{ items.length }}</span></div>

      <div class="mt-4 flex lg:mx-[220px] md:mx-[60px] mx-[10px] flex-col" v-for="(item, i) in item_question">
          <div class="text-left  pl-6 text-[#EE7530] text-[16px]">{{i+1}}. {{ item.name }}</div>
          <div>
            <ColumnChart :vote="item.vote"></ColumnChart>
          </div>
      </div>
    </template>
    <div v-else class="text-center text-red-500 text-lg mt-11">-- ไม่มีข้อมูล --</div>
  </div>
</template>
<script>

import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import moment from 'moment'
import { ref } from 'vue'
import PieChart from '../chart/PieChartComponent.vue';
import ColumnChart from '../chart/ColumnChartComponent.vue';

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
      gender:[],
      item_question: [],
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
    PieChart,
    ColumnChart,
  },
  methods: {
    backPage(){
      location.href= "/admin/report";
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/report/questionnaire/load',{
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
          this.item_question = res.data.question
          this.isLoading = false;


          setTimeout(() => {
            this.$refs.pieGender.showPie(res.data.gender,['ชาย','หญิง','LGBTQ+'])
            this.$refs.pieAge.showPie(res.data.age,['ต่ำกว่า 20 ปี','20-30 ปี','31-40 ปี','41-50 ปี', '51 ปีขึ้นไป'])
            this.$refs.pieEeducation.showPie(res.data.education,['ต่ำกว่าปริญญาตรี','ปริญญาตรี','ปริญญาโท','ปริญญาเอก'])
            this.$refs.pieWork.showPie(res.data.work,['รับราชการ','พนักงานบริษัท/รัฐวิสาหกิจ','ธุรกิจส่วนตัว','รับจ้าง','นักเรียน/นักศึกษา','อื่น ๆ'])
          }, 1000);

          
          
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
        item.sub.forEach((sub)=>{
          total += parseInt(sub.count);
        });
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
  }
}
</script>