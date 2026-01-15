<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div @click="clickShowModal()" class="z-[92] hover:h-[110px] fixed left-0 top-[86%] -translate-y-1/2 origin-left rotate-90 text-white border border-white bg-[#00ACC1] px-2 py-1 rounded text-lg pb-[36px] hover:cursor-pointer">
        แบบประเมิน
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="form" :validation-schema="schema" @submit="submitData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#3fbbc0]">แบบประเมินความพึงพอใจ สำหรับผู้ใช้บริการ</h2>
            <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="px-6 md:px-8 mt-2 flex flex-col text-xs text-[#dc3545]">*กรุณากรอกแบบประเมินความพึงพอใจ เพื่อนำผลไปใช้ในการปรับปรุงการให้บริการ จึงขอความร่วมมือจากทุกท่านที่ใช้งานระบบรับเรื่องร้องเรียน กรมสุขภาพจิต</div>
            <div class="mx-6 px-3 py-2 mt-4 flex flex-col bg-[#1d684a] text-white">ส่วนที่ 1 ข้อมูลของผู้ใช้บริการ</div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-3/12">1. เพศ</div>
                <div class="w-full md:w-9/12">
                  <div class="flex flex-row justify-between">
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" v-model="form.gender" value="1" /> ชาย
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" v-model="form.gender" value="2" /> หญิง
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" v-model="form.gender" value="3" /> LGBTQ+
                    </label>
                  </div>
                  <ErrorMessage name="gender" class="text-red-500 text-sm mt-1" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-row items-center">
                <div class="w-3/12">2. อายุ</div>
                <div class="w-4/12">
                  <Field name="age" type="text" class="input" v-model="form.age" />
                  <ErrorMessage name="age" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12">3. ระดับการศึกษา</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="qualification" class="input" v-model="form.qualification">
                    <option value="">-- กรุณาเลือก --</option>
                    <option value="1">ต่ำกว่าปริญญาตรี</option>
                    <option value="2">ปริญญาตรี</option>
                    <option value="3">ปริญญาโท</option>
                    <option value="4">ปริญญาเอก</option>
                  </Field>
                  <ErrorMessage name="qualification" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12">4. อาชีพ</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="work" class="input" v-model="form.work">
                    <option value="">-- กรุณาเลือก --</option>
                    <option value="1">รับราชการ</option>
                    <option value="2">พนักงานบริษัท/รัฐวิสาหกิจ</option>
                    <option value="3">ธุรกิจส่วนตัว</option>
                    <option value="4">รับจ้าง</option>
                    <option value="5">นักเรียน/นักศึกษา</option>
                    <option value="6">อื่น ๆ</option>
                  </Field>
                  <ErrorMessage name="work" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div v-if="form.work == 6" class="px-6 md:px-8 mt-2 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12 pl-4">โปรดระบุ</div>
                <div class="w-full md:w-9/12">
                  <Field name="workDis" type="text" class="input" v-model="form.workDis" />
                  <ErrorMessage name="workDis" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="mx-6 px-3 py-2 mt-6 flex flex-col bg-[#1d684a] text-white">ส่วนที่ 2 ความพึงพอใจของผู้ใช้บริการ</div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1" v-for="(item, i) in item_question">
              <div class="flex flex-col">
                <div class="w-full">{{ i+1 }}. {{ item.name }}</div>
                <div class="w-full mt-1">
                  <div class="grid grid-cols-2 sm:grid-cols-3 justify-items-stretch">
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel'+item.id" :id="'sel'+item.id+'_1'" v-model="item.sel" value="1" :checked="item.checked_1">
                      <label class="hover:cursor-pointer" :for="'sel'+item.id+'_1'">
                        ไม่พึงพอใจ
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel'+item.id" :id="'sel'+item.id+'_2'" v-model="item.sel" value="2" :checked="item.checked_2">
                      <label class="hover:cursor-pointer" :for="'sel'+item.id+'_2'">
                        พึงพอใจ
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel'+item.id" :id="'sel'+item.id+'_3'" v-model="item.sel" value="3" :checked="item.checked_3">
                      <label class="hover:cursor-pointer" :for="'sel'+item.id+'_3'">
                        พึงพอใจมาก
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-2">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
                ปิด
              </button>
              <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                ส่งแบบประเมิน
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
      item_question: [],
      form: {
        gender: '',
        age: '',
        qualification: '',
        work: '',
        workDis: '',
      },
      schema: yup.object({
        gender: yup.string().required('กรุณาเลือกเพศ'),
        age: yup.number().typeError('กรุณากรอกเป็นตัวเลข').required('กรุณากรอกอายุ'),
        qualification: yup.string().required('กรุณาเลือกระดับการศึกษา'),
        work: yup.string().required('กรุณาเลือกประเภทงาน'),
        workDis: yup.string().when('work', (work, schema) => {
          return work[0] == '6'
            ? schema.required('กรุณาระบุอาชีพ')
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
  mounted() {
  },
  methods: {
    clickShowModal(){
      this.getQuestion();
    },
    getQuestion(){
      this.isLoading = true;
      axios.get('/load/question') 
      .then(res => {
        if(res.data.status == 200){
          this.item_question = res.data.item
          this.isLoading = false;
          this.showModal = true;
        }else{
          this.item_question = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        this.item_question = [];
        console.error(err)
        this.isLoading = false;
      })
    },
    submitData(values){
      this.isLoading = true;
      axios.post('/question',{
        data: values,
        question: this.item_question,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          Swal.fire({title: 'สำเร็จ !', html: 'ส่งแบบประเมินความพึงพอใจ สำเร็จ! <br/>ขอบคุณที่ ทำแบบประเมินความพีงพอใจ',icon: 'success',confirmButtonText: 'ตกลง'});
          this.form.gender = '';
          this.form.age = '';
          this.form.qualification = '';
          this.form.work = '';
          this.form.workDis = '';
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