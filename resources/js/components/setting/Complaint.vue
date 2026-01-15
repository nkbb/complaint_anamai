<template>
  <div class="mt-6 mb-11">
    <Form :initial-values="form" :validation-schema="schema" @submit="onSubmit">
      <div class="flex flex-col mt-11">
        <div class="flex flex-col md:flex-row gap-3 w-full mt-3 items-center">
          <div class="w-full md:w-3/12 text-left md:text-right">รหัส เรื่องร้องเรียน :</div>
          <div class="flex flex-col w-full md:w-3/12 gap-1">
            <Field name="keyTitle" type="text" class="input" v-model="form.keyTitle" />
            <ErrorMessage name="keyTitle" class="text-red-500 text-sm" />
          </div>
        </div>
        <div class="flex flex-col gap-3 w-full mt-3 items-center">
          <div class="flex w-full text-left md:text-right">หลักเกณฑ์การรับเรื่องร้องเรียน : </div>
          <div class="flex w-full">
            <ckeditor
                :editor="editor"
                :config="editorConfig"
                v-model="form.conditions"
                tag-name="textarea"
              />
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
import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'



export default {
  name: 'SettingComplaintComponent',
  data() {
    return {
      isLodding: false,
      items: [],
      showModal: false,
      form:{
        id: '',
        keyTitle: '',
        conditions: '',
      },
      schema: yup.object({
        keyTitle: yup.string().required('กรุณากรอก'),
      }),
      editor: ClassicEditor,
      editorConfig: {
      }

    }
  },
  props:['item'],
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
    ckeditor: CKEditor.component,
  },
  mounted() {
    const item = JSON.parse(this.item);
    this.form.id = item.id
    this.form.keyTitle = item.key_title
    this.form.conditions = item.conditions
  },
  methods: {
    onSubmit(values){
      console.log('saveData')
      axios.post('/admin/setting/complaint',{
        data: values,
        conditions: this.form.conditions,
      }) 
      .then(res => {
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