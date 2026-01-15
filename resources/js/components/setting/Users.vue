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
        <span>เพิ่มผู้ใช้งาน</span>
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
    <div @click="addData()" class="ml-[60px] border border-red-500 text-red-500  px-4 py-2 rounded-md inline-block hover:cursor-pointer"><i class="fas fa-plus"></i> เพิ่มผู้ใช้งาน</div>

    <div class="flex flex-row mt-6 pl-3 lg:pl-20 items-center" v-if="user_level=='root'">
        <div class="text-sm font-medium text-gray-700 mr-4">ค้นหาหน่วย : </div>
         <Form :initial-values="search" @submit="onSearch" class="flex flex-row items-center">
            <div class="gap-1">
              <Field as="select" name="unit_id" class="input" v-model="search.unit_id">
                  <option value="">== ทั้งหมด ==</option>
                  <option v-for="(item) in item_unit" :value="item.id">{{ item.name }}</option>
              </Field>
            </div>
            <button type="submit" class="ml-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ค้นหา
            </button>
         </Form>
    </div>

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="3%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ข้อ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ชื่อเข้าสู่ระบบ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ผู้รับผิดชอบ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">เบอร์ติดต่อ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">email	</th>
                <th width="15%" v-if="user_level=='root'" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ระดับ	</th>
                <th width="15%" v-if="user_level=='root'" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">หน่วยงาน	</th>
                <th width="15%" v-if="user_level=='root'" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ i+1 }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.username }}
                <span v-if="item.id == user_id" class="text-[10px] text-white bg-yellow-500 px-2 py-1 rounded ml-2">ตัวเอง</span>
              </td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.phone }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.email }}</td>
              <td v-if="user_level=='root'" class="px-4 py-2 border border-[#dee2e6] text-center">
                <span v-if="item.level == 'root'">ศูนย์รับเรื่อง/ส่วนกลาง</span>
                <span v-else-if="item.level == 'unit'">หน่วย</span>
                <span v-else>ผู้ใช้งานทั่วไป</span>
              </td>
              <td v-if="user_level=='root'" class="px-4 py-2 border border-[#dee2e6] text-center">{{ item.unit_name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center" v-if="user_level=='root'">
                <div class="flex gap-2 justify-center">
                  <i @click="viewData(i)" class="far fa-edit text-[#007bff] hover:cursor-pointer"></i>
                  <i @click="removeData(item.id)" class="far fa-trash-alt text-[#dc3545] hover:cursor-pointer"></i>
                </div>
              </td>
            </tr>
          </tbody>
      </table>
    </div>
    <div class="gap-2 flex justify-end mt-11">
        <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative animate-fade-in-down">
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-if="form.id">แก้ไขผู้ใช้งาน</h2>
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-else>+ เพิ่มผู้ใช้งาน</h2>
        <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
          <Field type="hidden" name="id" v-model="form.id" />
          <div class="flex flex-col mt-3" v-if="form.id == ''">
            <div class="text-sm">ชื่อเข้าสู่ระบบ : </div>
            <div class="gap-1">
              <Field name="username" type="text" class="input" v-model="form.username" />
              <ErrorMessage name="username" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3" v-else>
            <div class="text-sm">ชื่อเข้าสู่ระบบ : </div>
            <div class="gap-1 text-gray-900 pl-4">
              {{ form.username }}
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">รหัสผ่าน : </div>
            <div class="gap-1">
              <Field name="password" type="text" class="input" v-model="form.password" />
              <ErrorMessage name="password" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">ชื่อผู้รับผิดชอบ : </div>
            <div class="gap-1">
              <Field name="name" type="text" class="input" v-model="form.name" />
              <ErrorMessage name="name" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">เบอร์ติดต่อ (ถ้ามี) : </div>
            <div class="gap-1">
              <Field name="phone" type="text" class="input" v-model="form.phone" />
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">email (ถ้ามี) : </div>
            <div class="gap-1">
              <Field name="email" type="text" class="input" v-model="form.email" />
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">ระดับ : </div>
            <div class="gap-1">
              <Field as="select" name="level" class="input" v-model="form.level">
                  <option value="">-- กรุณาเลือก --</option>
                  <option value="root">ศูนย์รับเรื่อง/ส่วนกลาง</option>
                  <option value="unit">หน่วยงาน</option>
              </Field>
              <ErrorMessage name="level" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col mt-3" v-if="form.level == 'unit'">
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
      item_unit: [],
      showModal: false,
      search: {
        unit_id: '',
      },
      form:{
        id: '',
        username: '',
        password: '',
        name: '',
        type: 1,
        phone: '',
        email: '',
        level: '',
        unit_id: '',
        
      },
      schema: yup.object({
        username: yup.string().required('กรุณากรอก'),
        password: yup.string().when('id', (id, schema) => {
          return id == ''
            ? schema.required('กรุณากรอก')
            : schema.nullable();
        }),
        name: yup.string().required('กรุณากรอก'),
        level: yup.string().required('กรุณาเลือก'),
        unit_id: yup.string().when('level', (level, schema) => {
          return level == 'unit'
            ? schema.required('กรุณาเลือกหน่วยงาน')
            : schema.nullable();
        }),
      })
    }
  },
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
  },
  props: ["unit","user_id","user_level"],
  created(){
    this.item_unit = JSON.parse(this.unit);
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
      this.form.name = this.items[index].name
      this.form.username = this.items[index].username
      this.form.phone = this.items[index].phone
      this.form.email = this.items[index].email
      this.form.level = this.items[index].level
      this.form.unit_id = this.items[index].unit_id
      this.showModal = true;
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/users/load',{
        params: {
          unit_id: this.search.unit_id,
        }
      }) 
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
          axios.delete('/admin/setting/users',
            {
              params:{
                id: id,
                type: 'users',
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
    onSearch(values){
      this.showData();
    },
    saveData(values){
      this.isLoading = true;
      axios.post('/admin/setting/users',{
        data: values,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !',text: 'บันทึกข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
          this.showData();
          this.form.id = '';
          this.form.name = '';
          this.form.username = '';
          this.form.password = '';
          this.form.phone = '';
          this.form.email = '';
          this.form.level = '';
          this.form.unit_id = '';
        }else if(res.data.status == 201){
          this.isLoading = false;
          Swal.fire({title: 'ผิดพลาด !',text: 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว !',icon: 'error',confirmButtonText: 'ตกลง'});
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
      axios.post('/admin/setting/methods',{
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
