<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    
    <div class="fixed bottom-2 right-1 z-[90]">
      <div class="relative inline-block group">
        <button @click="clickShowModal()" class="hover:cursor-pointer bg-[#0A7CFF] text-[32px] rounded-full size-[60px]">
          <i class="far fa-comments text-white"></i>
        </button>
        <div
          class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-black text-white text-sm rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300"
        >
          ข้อคิดเห็นข้อร้องเรียน
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed bottom-11 right-[60px] z-[93]">
      <div class="bg-white shadow-lg border rounded-md w-[395px]">
        <Form :initial-values="form" :validation-schema="schema" @submit="submitData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 justify-between">
            <h2 class="text-lg text-[#3fbbc0]">ข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต</h2>
            <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="px-6 md:px-8 flex flex-col gap-1">
            <div class="flex flex-col">
              <div class="w-full">ด้านความคิดเห็น</div>
              <div class="w-full">
                <Field as="select" name="commentType" class="input" v-model="form.commentType">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_comment" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="commentType" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="px-6 md:px-8 flex flex-col gap-1 mt-3">
            <div class="flex flex-col">
              <div class="w-full">ผู้แสดงความคิดเห็น</div>
              <div class="w-full">
                <Field name="name" type="text" class="input" v-model="form.name" />
                <ErrorMessage name="name" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="px-6 md:px-8 flex flex-col gap-1 mt-3">
            <div class="flex flex-col">
              <div class="w-full">ความคิดเห็น</div>
              <div class="w-full">
                <Field
                  as="textarea"
                  name="comment"
                  rows="3"
                  class="form-control w-full border rounded p-2 border-[#ccc]"
                  v-model="form.comment"
                />
                <ErrorMessage name="comment" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pb-4 justify-end mt-2 gap-2">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
                ปิด
              </button>
              <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                ส่งข้อมูล
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
  data() {
    return {
      showModal: false,
      isLoading: false,
      item_comment: [],
      form: {
        name: '',
        comment: '',
        commentType: '',
      },
      schema: yup.object({
        commentType: yup.string().required('กรุณาเลือกด้านความคิดเห็น'),
        name: yup.string().required('กรุณากรอกผู้แสดงความคิดเห็น'),
        comment: yup.string().required('กรุณากรอกความคิดเห็น'),
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
    clickShowModal(){
      if(this.showModal == false){
        this.getCommentsType();
      }else{
        this.showModal = false
      }
    },
    getCommentsType(){
      this.isLoading = true;
      axios.get('/load/comments/type') 
      .then(res => {
        if(res.data.status == 200){
          this.item_comment = res.data.item
          this.isLoading = false;
          this.showModal = true;
        }else{
          this.item_comment = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        this.item_comment = [];
        console.error(err)
        this.isLoading = false;
      })
    },
    submitData(values){
      this.isLoading = true;
      axios.post('/comment',{
        data: values,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !', html: 'บันทึกข้อคิดเห็นข้อร้องเรียน สำเร็จ! <br/>ขอบคุณที่ แสดงความคิดเห็น กับกรมสุขภาพจิต',icon: 'success',confirmButtonText: 'ตกลง'});
          this.form.name = '';
          this.form.comment = '';
          this.form.commentType = '';
        }else{
          this.isLoading = false;
          Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถทำรายการได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
        Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถทำรายการได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
      })
    }
  }
}
</script>