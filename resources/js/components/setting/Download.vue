<template>
  <div class="mt-6 mb-11">
    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <!-- ปุ่มพิมพ์ -->
      <button
        type="button"
        @click="addData()"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-green/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-red-500 "
      >
        <i class="fas fa-plus"></i>
        <span>เพิ่ม แบบเอกสาร</span>
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

    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div @click="addData()" class="ml-[60px] border border-red-500 text-red-500  px-4 py-2 rounded-md inline-block hover:cursor-pointer"><i class="fas fa-plus"></i> เพิ่มแบบเอกสาร</div>

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ลำดับ</th>
                <th width="60%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">แบบฟอร์ม</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">สถิติ</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ i+1 }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.short_name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <div v-if="item.area == 1">ส่วนกลาง</div>
                <div v-if="item.area == 2">หน่วยบริการ</div>
                <div v-if="item.area == 3">ศูนย์สุขภาพจิต</div>
              </td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <div class="flex gap-2 justify-center">
                  <i @click="viewUnit(i)" class="far fa-edit text-[#007bff] hover:cursor-pointer"></i>
                  <i @click="removeUnit(item.id)" class="far fa-trash-alt text-[#dc3545] hover:cursor-pointer"></i>
                </div>
              </td>
            </tr>
          </tbody>
      </table>
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative animate-fade-in-down">
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-if="form.id">+ แก้ไข</h2>
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-else>+ เพิ่ม</h2>
        <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
          <div class="flex flex-col mt-3">
            <div class="text-sm">ชื่อแบบฟอร์ม : </div>
            <div class="gap-1">
              <Field name="name" type="text" class="input" v-model="form.name" />
              <ErrorMessage name="name" class="text-red-500 text-sm" />
            </div>
          </div>
          
          <div class="gap-2 flex justify-end mt-5">
            <!-- <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              บันทึก
            </button> -->
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-white rounded border">
              ปิด
            </button>
          </div>
        </Form>
      </div>
    </div>

  </div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

export default {
  name: 'SettingUnitComponent',
  data() {
    return {
      isLoading: false,
      items: [],
      showModal: false,
      form:{
        id: '',
        name: '',
        shortName: '',
        area: '',
        type: 1,
      },
      schema: yup.object({
        name: yup.string().required('กรุณากรอก'),
        shortName: yup.string().required('กรุณากรอก'),
        area: yup.string().required('กรุณาเลือก'),
      })
    }
  },
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
  },
  mounted() {
    // this.showData();
  },
  methods: {
    addData(){
      this.form.id = '';
      this.form.name = '';
      this.form.shortName = '';
      this.form.type = 1;
      this.form.area = 1;
      this.showModal = true;
    },
    viewUnit(index){
      this.form.id = this.items[index].id
      this.form.name = this.items[index].name
      this.form.shortName = this.items[index].short_name
      this.form.area =this.items[index].area
      this.form.type =this.items[index].type
      this.showModal = true;
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/unit/load') 
      .then(res => {
        if(res.data.status == 200){
          this.items = res.data.item
          this.isLoading = false;
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
    backPage(){
      location.href= '/admin/setting'
    },
    removeUnit(id){
      if(id){
        Swal.fire({
        title: 'ลบข้อมูล?',
        text: 'ยืนยันการลบข้อมูล อีกครั้ง!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ดำเนินการต่อ',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          this.isLoading = true;
          axios.delete('/admin/setting/unit',
            {
              params:{
                id: id,
                type: 'unit',
              }
            }
          )
          .then(res => {
            if(res.data.status == 200){
              this.isLoading = false;
              Swal.fire({title: 'สำเร็จ !',text: 'ลบข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
              this.showData();
            }else{
              this.isLoading = false;
              Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถลบข้อมูลได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
            }
          })
          .catch(err => {
            console.error(err)
            this.isLoading = false;
            Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถลบข้อมูลได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
          })
        }
      })
      }
    },
    saveData(values){
      this.isLoading = true;
      axios.post('/admin/setting/unit',{
        data: values,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !',text: 'บันทึกข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
          this.showData();
        }else{
          this.isLoading = false;
          Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถบันทีกข้อมูลได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
        Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถบันทีกข้อมูลได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
      })
    }
  }
}
</script>
