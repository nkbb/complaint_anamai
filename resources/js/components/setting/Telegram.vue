<template>
  <div class="mt-6 mb-11">
    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <!-- ปุ่มพิมพ์ -->
      <button
        v-if="user_level == 'root'"
        type="button"
        @click="addData()"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-green/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-red-500 "
        >
        <i class="fas fa-plus"></i> 
        <span>เพิ่ม</span>
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
    <div v-if="user_level == 'root'" @click="addData()" class="ml-[60px] border border-red-500 text-red-500  px-4 py-2 rounded-md inline-block hover:cursor-pointer"><i class="fas fa-plus"></i> เพิ่ม</div>

    <div class="flex mt-11" v-if="user_level == 'root'">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">#	</th>
                <th width="25%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">token	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">chat_id	</th>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ประเภท	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">หน่วย	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ i+1 }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.token }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.chat_id }}</td>
               <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <span v-if="item.type == '1'">ศูนย์รับเรื่อง</span>
                <span v-else-if="item.type == '2'">หน่วย</span>
                <span v-else>ผู้ใช้งานทั่วไป</span>
              </td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ item.unit_name }}</td>
              
              <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <div class="flex gap-2 justify-center">
                  <i @click="viewData(i)" class="far fa-edit text-[#007bff] hover:cursor-pointer"></i>
                  <i @click="removeData(item.id)" class="far fa-trash-alt text-[#dc3545] hover:cursor-pointer"></i>
                </div>
              </td>
            </tr>
          </tbody>
      </table>
    </div>

    <div class="gap-2 flex justify-end mt-11" v-if="user_level == 'root'">
        <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
    </div>

    <div class="flex mt-11 justify-center " v-if="user_level == 'unit'">
       <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
        <div class="flex flex-col mt-3 lg:min-w-[600px]">
            <div class="text-sm">Token : </div>
            <div class="gap-1">
              <Field name="token" type="text" class="input" v-model="form.token" />
              <ErrorMessage name="token" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3 lg:min-w-[600px]">
            <div class="text-sm">Chat id : </div>
            <div class="gap-1 flex flex-row">
              <div>
              <Field name="chat_id" disabled type="text" class="input disabled:bg-gray-200" v-model="form.chat_id" />
              <ErrorMessage name="chat_id" class="text-red-500 text-sm" />
              </div>
              <div @click="checkId" class="border px-3 py-1 rounded-md hover:cursor-pointer hover:bg-gray-300">สร้าง Chat ID</div>
            </div>
          </div>

          <div class="gap-2 flex justify-end mt-11">
              <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
              <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              บันทึก
            </button>
          </div>
       </Form>
    </div>

   

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative animate-fade-in-down">
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-if="form.id">แก้ไข        </h2>
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-else>+ เพิ่ม</h2>
        <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
          <div class="flex flex-col mt-3">
            <div class="text-sm">Token : </div>
            <div class="gap-1">
              <Field name="token" type="text" class="input" v-model="form.token" />
              <ErrorMessage name="token" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">Chat id : </div>
            <div class="gap-1 flex felx-row items-center">
              <div>
              <Field name="chat_id" disabled type="text" class="input disabled:bg-gray-200" v-model="form.chat_id" />
              <ErrorMessage name="chat_id" class="text-red-500 text-sm" />
              </div>
              <div @click="checkId" class="border px-3 py-1 rounded-md hover:cursor-pointer hover:bg-gray-300">สร้าง Chat ID</div>
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">ระดับ : </div>
            <div class="gap-1">
              <Field as="select" name="type" class="input" v-model="form.type">
                  <option value="">-- กรุณาเลือก --</option>
                  <option value="1">ศูนย์รับเรื่อง</option>
                  <option value="2">หน่วยงาน</option>
              </Field>
              <ErrorMessage name="type" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3" v-if="form.type == '2'">
            <div class="text-sm">หน่วยงาน : </div>
            <div class="gap-1">
              <Field as="select" name="unit_id" class="input" v-model="form.unit_id">
                  <option value="">-- กรุณาเลือก --</option>
                   <option v-for="(item) in item_unit" :value="item.id">{{ item.name }}</option>
              </Field>
              <ErrorMessage name="unit_id" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="gap-2 flex justify-end mt-5">
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              บันทึก
            </button>
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
      item_unit: [],
      form:{
        id: '',
        token: '',
        chat_id: '',
        type: 1,
        unit_id: '',
      },
      schema: yup.object({
        token: yup.string().required('กรุณากรอก'),
        chat_id: yup.string().required('กรุณากรอก'),
        type: yup.string().required('กรุณาเลือก'),
        unit_id: yup.string().when('type', (type, schema) => {
          return type == '2'
            ? schema.required('กรุณาเลือกหน่วยงาน')
            : schema.nullable();
        }),
      })
    }
  },
  props:["unit","user_level","unit_id"],
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
  },
  created(){
    this.item_unit = JSON.parse(this.unit);
    if(this.user_level == 'unit'){
      this.form.type = 2;
      this.form.unit_id = this.unit_id;
    }
  },
  mounted() {
    this.showData();
  },
  methods: {
    backPage(){
      location.href= '/admin/setting'
    },
    addData(){
      this.form.id = '';
      this.form.name = '';
      this.showModal = true;
    },
    viewData(index){
      this.form.id = this.items[index].id
      this.form.token = this.items[index].token
      this.form.chat_id = this.items[index].chat_id
      this.form.type = this.items[index].type.toString()
      this.form.unit_id = this.items[index].unit_id
      this.showModal = true;
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/telegram/load') 
      .then(res => {
        if(res.data.status == 200){
          this.items = res.data.item
          this.isLoading = false;

          if(this.user_level == 'unit' && this.items.length > 0){
            this.form.id = this.items[0].id
            this.form.token = this.items[0].token
            this.form.chat_id = this.items[0].chat_id
          }
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
    checkId(){
      if(!this.form.token){
        Swal.fire({title: 'แจ้งเตือน !',text: 'กรุณากรอก Token !',icon: 'warning',confirmButtonText: 'ตกลง'});
        return;
      }
      this.isLoading = true;
      axios.get('https://api.telegram.org/bot'+this.form.token+'/getUpdates')
      .then(res => {
        this.isLoading = false;
        if(res.data.ok){
          res.data.result.forEach((item, index) => {
              if(item.my_chat_member != undefined){
                this.form.chat_id = item.my_chat_member.chat.id
              }
          });
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
        Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถสร้าง Chat ID ได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
      })
    },
    removeData(id){
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
          axios.delete('/admin/setting/telegram',
            {
              params:{
                id: id,
                type: 'question',
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
      axios.post('/admin/setting/telegram',{
        data: values,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !',text: 'บันทึกข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
          this.form.id = '';
          this.form.name = '';
          this.form.token = '';
          this.form.chat_id = '';
          this.form.type = '';
          this.form.unit_id = '';
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
    },
    setOrder(item,update){
      this.isLoading = true;
      axios.post('/admin/setting/person',{
        id: item.id,
        num: update.num,
        cid: update.id,
        cnum: item.num,
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
