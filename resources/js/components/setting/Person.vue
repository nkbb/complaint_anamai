<template>
  <div class="mt-6 mb-11">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div @click="addData()" class="ml-[60px] border border-red-500 text-red-500  px-4 py-2 rounded-md inline-block hover:cursor-pointer"><i class="fas fa-plus"></i> เพิ่มหัวข้อ</div>

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ข้อ	</th>
                <th width="60%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">หัวข้อ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ลำดับ	</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ i+1 }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">
                <div class="flex gap-2 justify-center">

                  <div class="relative inline-block group" v-if="i>0">
                    <button class="hover:cursor-pointer text-[#20c997] text-xl" @click="setOrder(item,items[i-1])">
                      <i class="fas fa-chevron-circle-up"></i>
                    </button>
                    <div
                      class="absolute top-1/2 -translate-y-1/2 right-full mr-2 bg-black text-white text-sm rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    >
                      เลื่อนขึ้น
                    </div>
                  </div>
                  
                  <div class="relative inline-block group" v-if="i < (items.length-1)" @click="setOrder(item,items[i+1])">
                      <button class="hover:cursor-pointer text-[#20c997] text-xl">
                        <i class="fas fa-chevron-circle-down "></i>
                      </button>
                      <div
                        class="absolute top-1/2 -translate-y-1/2 left-full ml-2 bg-black text-white text-sm rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                      >
                        เลื่อนลง
                      </div>
                  </div>
                    
                  
                </div>
              </td>
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
    <div class="gap-2 flex justify-end mt-11">
        <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative animate-fade-in-down">
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-if="form.id">แก้ไขบุคคุลที่ถูกร้องเรียน        </h2>
        <h2 class="text-xl font-semibold mb-4 text-[#EE7530]" v-else>+ เพิ่มบุคคุลที่ถูกร้องเรียน</h2>
        <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
          <div class="flex flex-col mt-3">
            <div class="text-sm">หัวข้อ : </div>
            <div class="gap-1">
              <Field name="name" type="text" class="input" v-model="form.name" />
              <ErrorMessage name="name" class="text-red-500 text-sm" />
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
        name: '',
        type: 1,
      },
      schema: yup.object({
        name: yup.string().required('กรุณากรอก'),
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
      axios.get('/admin/setting/person/load') 
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
          axios.delete('/admin/setting/unit',
            {
              params:{
                id: id,
                type: 'person',
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
      axios.post('/admin/setting/person',{
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
