<template>
  <div class="mt-6 mb-11">
    <Form :initial-values="form" :validation-schema="schema" @submit="onSubmit">
      <div class="flex flex-col mt-11">
        <div class="flex flex-col md:flex-row gap-3 w-full mt-3 items-center">
          <div class="w-full md:w-3/12 text-left md:text-right">ศูนย์ส่งเรื่อง ภายใน(วัน) :</div>
          <div class="flex flex-col w-full md:w-3/12 gap-1">
            <Field name="deadDateFinish" type="text" class="input text-right" v-model="form.deadDateFinish" />
            <ErrorMessage name="deadDateFinish" class="text-red-500 text-sm" />
          </div>
        </div>
        <div class="flex flex-col md:flex-row gap-3 w-full mt-3 items-center">
          <div class="w-full md:w-3/12 text-left md:text-right">หน่วยรับเรื่อง ภายใน(วัน) :</div>
          <div class="flex flex-col w-full md:w-3/12 gap-1">
            <Field name="deadDateReceive" type="text" class="input text-right" v-model="form.deadDateReceive" />
            <ErrorMessage name="deadDateReceive" class="text-red-500 text-sm" />
          </div>
        </div>
        <div class="flex flex-col md:flex-row gap-3 w-full mt-3 items-center">
          <div class="w-full md:w-3/12 text-left md:text-right">ศูนย์ยุติเรื่อง รายงานผู้บริหาร ภายใน(วัน) :</div>
          <div class="flex flex-col w-full md:w-3/12 gap-1">
            <Field name="deadDateSend" type="text" class="input text-right" v-model="form.deadDateSend" />
            <ErrorMessage name="deadDateSend" class="text-red-500 text-sm" />
          </div>
        </div>
      </div>
      <div class="gap-2 flex justify-center mt-11">
          <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            บันทึก
          </button>
          <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
      </div>
    </Form>
  </div>
</template>
<script>
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

export default {
  name: 'SettingTimefinishComponent',
  data() {
    return {
      isLodding: false,
      items: [],
      showModal: false,
      form:{
        id: '',
        deadDateFinish: '',
        deadDateReceive: '',
        deadDateSend: '',
      },
      schema: yup.object({
        deadDateFinish: yup.number().typeError('กรุณากรอกเป็นตัวเลข').required('กรุณากรอก'),
        deadDateReceive: yup.number().typeError('กรุณากรอกเป็นตัวเลข').required('กรุณากรอก'),
        deadDateSend: yup.number().typeError('กรุณากรอกเป็นตัวเลข').required('กรุณากรอก'),
      })
    }
  },
  props:['item'],
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
  },
  mounted() {
    const item = JSON.parse(this.item);
    this.form.id = item.id
    this.form.deadDateFinish = item.dead_date_finish
    this.form.deadDateReceive = item.dead_date_receive
    this.form.deadDateSend = item.dead_date_send
  },
  methods: {
    onSubmit(values){
      console.log('saveData')
      axios.post('/admin/setting/timefinish',{
        data: values,
      }) 
      .then(res => {
        // this.result = res.data
        if(res.data.status == 200){
          Swal.fire({
            title: 'สำเร็จ !',
            text: 'บันทึกข้อมูลสำเร็จ !',
            icon: 'success',
            confirmButtonText: 'ตกลง'
          })
        }else{
          Swal.fire({
            title: 'ผิดพลาด!',
            text: 'ไม่สามารถ บันทึกข้อมูลได้ !',
            icon: 'error',
            confirmButtonText: 'ตกลง'
          })
        }
      })
      .catch(err => {
        console.error(err)
        Swal.fire({
          title: 'ผิดพลาด!',
          text: 'ไม่สามารถ บันทึกข้อมูลได้ !',
          icon: 'error',
          confirmButtonText: 'ตกลง'
        })
      })
    }
  }
}
</script>