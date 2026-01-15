<template>
  <div class="mt-6 mb-11">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3">
      <!-- ปุ่มพิมพ์ -->
      <button
        type="button"
        @click="addData()"
        aria-label="เพิ่ม"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-red-500"
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
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>ย้อนกลับ</span>
      </button>
    </div>

    <div @click="addData()" class="ml-[60px] border border-red-500 text-red-500  px-4 py-2 rounded-md inline-block hover:cursor-pointer"><i class="fas fa-plus"></i> เพิ่ม</div>

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">#	</th>
                <th width="60%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">รูปภาพ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">link	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ i+1 }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">
                <img v-if="item.image" :src="item.image_url" alt="preview" style="max-width: 200px; margin-top: 10px;">
                
              </td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.uri }}</td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <div class="flex gap-2 justify-center">
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
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-if="form.id">แก้ไขประเภทช่องทาง</h2>
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-else>+ เพิ่ม</h2>
        <Form :initial-values="form" @submit="saveData">
          <div class="flex flex-col mt-3">
            <div class="text-sm">รูปภาพ : </div>
            <div class="gap-1">
              <div @click="changeFile()" class="border-[#ccc] border rounded px-4 py-1 inline-block hover:cursor-pointer bg-[#e9ecef]">เลือกไฟล์</div>
              <div class="text-xs text-[#6c757d]">กรุณาเลือกไฟล์ รูปภาพ</div>
              <div v-if="file.name" class="text-[#007bff] underline">{{ file.name }}</div>
              <img v-if="file.preview" :src="file.preview" alt="preview" style="max-width: 200px; margin-top: 10px;">

              <input id="fileSelect" @change="selectFile()" ref="file" type="file" accept="image/png,image/jpeg" style="display:none;" />  
            </div>
          </div>
          <div class="flex flex-col mt-3">
            <div class="text-sm">Link (ถ้ามี) : </div>
            <div class="gap-1">
              <Field name="url" type="text" class="input" v-model="form.url" />
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
        image: '',
        url: '',
        status: '',
        preview: ""
      },
      file: {
        name: '',
        size: 0,
        type: '',
        base64: '',
      },
    }
  },
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
  },
  props: [],
  created(){
    // this.item_unit = JSON.parse(this.unit);
  },
  mounted() {
    this.showData();
  },
  methods: {
    backPage(){
      location.href= "/admin/setting";
    },
    addData(){
      this.form.id = '';
      this.form.name = '';
      this.showModal = true;
    },
    viewData(index){
      this.form.id = this.items[index].id
      this.form.name = this.items[index].name
      this.showModal = true;
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/banner/load',{
        params: {
          type: 1
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
          axios.delete('/admin/setting/banner',
            {
              params:{
                id: id,
                type: 'methods',
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
      if(!this.file.name && !this.form.image){
        Swal.fire({title: 'แจ้งเตือน !',text: 'กรุณาเลือกไฟล์ภาพ !',icon: 'warning',confirmButtonText: 'ตกลง'});
        return;
      }
      const formData = new FormData();
      formData.append('file', this.$refs.file?.files[0]);
      formData.append('uri', values.url);
      formData.append('type', 1);

      this.isLoading = true;
      axios.post('/admin/setting/banner', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !',text: 'บันทึกข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
          this.file.name = '';
          this.file.preview = '';
          this.form.url = '';
          this.form.image = '';
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
    changeFile(){
      this.$refs.file.click();
    },
    selectFile(){
      const file = this.$refs.file?.files[0];
      if (!file) return;

      // เก็บชื่อ
      this.file.name = file.name;
      this.file.type = "new";

      // ทำพรีวิวภาพ
      const reader = new FileReader();
      reader.onload = e => {
        this.file.preview = e.target.result; // base64
      };
      reader.readAsDataURL(file);
    },
  }
}
</script>
