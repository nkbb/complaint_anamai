<template>
  <div class="mt-6 mb-11">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ข้อ</th>
                <th width="60%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ประเภท</th>
                <th width="20%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ระยะเวลาดำเนินการ (ภายใน)	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(item) in items">
              <tr class="hover:bg-[#efefef]">
                <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ item.num }}</td>
                <td class="px-4 py-2 border border-[#dee2e6]">{{ item.name }}</td>
                <td class="px-4 py-2 border border-[#dee2e6] text-center" v-if="item.type == 1">{{ item.time_span }} วัน</td>
                <td class="px-4 py-2 border border-[#dee2e6] text-center" v-else></td>
                <td class="px-4 py-2 border border-[#dee2e6] text-center">
                  <div class="flex justify-center" v-if="item.type == 1">
                    <i @click="viewData(item.id, item.time_span, 'main')" class="far fa-edit text-[#007bff] hover:cursor-pointer"></i>
                  </div>
                </td>
              </tr>
              <tr v-for="(sub) in item.sub" class="hover:bg-[#efefef]">
                <td class="px-4 py-2 border border-[#dee2e6] text-center"></td>
                <td class="px-4 py-2 border border-[#dee2e6]">{{ sub.name }}</td>
                <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ sub.time_span }} วัน</td>
                <td class="px-4 py-2 border border-[#dee2e6] text-center">
                  <div class="flex justify-center">
                    <i @click="viewData(sub.id, sub.time_span, 'sub')" class="far fa-edit text-[#007bff] hover:cursor-pointer"></i>
                  </div>
                </td>
              </tr>
            </template>
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
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]">แก้ไข ระยะเวลาดำเนินการ</h2>
        <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
          <div class="flex flex-col mt-3">
            <div class="text-sm">ระยะเวลา : </div>
            <div class="gap-1">
              <Field name="timeSpan" type="text" class="input" v-model="form.timeSpan" />
              <ErrorMessage name="timeSpan" class="text-red-500 text-sm" />
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
      form:{
        id: '',
        timeSpan: '',
        type: '',
      },
      schema: yup.object({
        timeSpan: yup.number().typeError('กรุณากรอกเป็นตัวเลข').required('กรุณากรอก'),
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
    this.showData();
  },
  methods: {
    viewData(id,timeSpan,type){
      this.form.id = id,
      this.form.timeSpan = timeSpan,
      this.form.type = type,
      this.showModal = true;
    },
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/type/load') 
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
    saveData(values){
      this.isLoading = true;
      axios.post('/admin/setting/type',{
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
