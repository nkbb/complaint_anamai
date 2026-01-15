<template>
  <div class="mt-6 mb-11">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <Form :initial-values="form" :validation-schema="schema" @submit="saveData">
      <div class="max-w-md mx-auto">
        <div class="flex flex-col mt-3">
          <div class="text-sm">รหัสผ่านเดิม : </div>
          <div class="gap-1">
            <Field name="old_password" type="password" class="input" v-model="form.old_password" />
            <ErrorMessage name="old_password" class="text-red-500 text-sm" />
          </div>
        </div>
        <div class="flex flex-col mt-3">
          <div class="text-sm">รหัสผ่านใหม่ : </div>
          <div class="gap-1">
            <Field name="new_password" type="password" class="input" v-model="form.new_password" />
            <ErrorMessage name="new_password" class="text-red-500 text-sm" />
          </div>
        </div>
        <div class="flex flex-col mt-3">
          <div class="text-sm">ยืนยันรหัสผ่านใหม่ : </div>
          <div class="gap-1">
            <Field name="confirm_password" type="password" class="input" v-model="form.confirm_password" />
            <ErrorMessage name="confirm_password" class="text-red-500 text-sm" />
            <!-- <div class="text-[12px] text-red-500">รหัสผ่าน ต้องมีมากกว่า 8 ตัวอักษร! และตัวพิมพ์ใหญ่ 1 ตัว, ตัวเลข 1 ตัว และอักขระพิเศษ 1 ตัว!</div> -->
          </div>
        </div>
        <div class="gap-2 flex justify-center mt-5">
          <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            เปลี่ยนรหัสผ่าน
          </button>
        </div>
      </div>
    </Form>



  </div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

export default {
  data() {
    return {
      isLoading: false,
      items: [],
      showModal: false,
      form:{
        old_password: '',
        new_password: '',
        confirm_password: '',
      },
      schema: yup.object({
        old_password: yup.string().required('กรุณากรอก'),
        new_password: yup
        .string()
        .required('กรุณากรอก')
        .min(8, 'รหัสผ่านต้องมีมากกว่า 8 ตัวอักษร!')
        .matches(/[A-Z]/, 'ต้องมีตัวพิมพ์ใหญ่ 1 ตัว!')
        .matches(/[0-9]/, 'ต้องมีตัวเลข 1 ตัว!')
        .matches(/[^A-Za-z0-9]/, 'ต้องมีอักขระพิเศษ 1 ตัว!'),
        confirm_password: yup
        .string()
        .oneOf([yup.ref('new_password'), null], 'รหัสผ่านใหม่ไม่ตรงกัน')
        .required('กรุณากรอก'),
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
  },
  methods: {
    saveData(values){

      Swal.fire({
        title: 'เปลี่ยนรหัสผ่าน?',
        text: 'ยืนยันการเปลี่ยนรหัสผ่าน อีกครั้ง!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ดำเนินการต่อ',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {

          this.isLoading = true;
          axios.post('/change/password',{
            data: values,
          }) 
          .then(res => {
            if(res.data.status == 200){
              this.isLoading = false;
              Swal.fire({title: 'สำเร็จ !',text: 'เปลี่ยนรหัสผ่านสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
              this.form.old_password = '';
              this.form.new_password = '';
              this.form.confirm_password = '';
            }else if(res.data.status == 201){
              this.isLoading = false;
              Swal.fire({title: 'ผิดพลาด !',text: 'รหัสผ่านเดิมไม่ถูกต้อง !',icon: 'error',confirmButtonText: 'ตกลง'});
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
      });
    }
  }
}
</script>
