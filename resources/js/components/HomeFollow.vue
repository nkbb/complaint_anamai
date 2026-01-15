<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div class="flex flex-col mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] mb-[80px] py-11 justify-items-center gap-6 mt-11 border shadow-md ">
      <h1 class="flex flex-row justify-center">
        <div class="border-b-[5px] border-[#3fbbc0] text-[#3fbbc0] text-[28px]">ติดตามเรื่องร้องเรียน</div>
      </h1>

      <div class="flex justify-start px-4 md:px-[280px] xl:px-[420px]">
        <Form :initial-values="form" :validation-schema="schema" @submit="followData" class="flex flex-col w-full">
          <div class="mt-5">กรุณากรอก เลขที่ร้องเรียน</div>
          <div class="flex flex-col w-full mt-1">
            <Field name="code" type="text" class="input" v-model="form.code" placeholder="เช่น SEC000000"/>
            <div class="flex flex-col lg:flex-row gap-2 mt-2">
              <ErrorMessage name="code" class="text-red-500 text-sm" />
              <div class="text-[#1d684a] text-sm">รหัสนี้จะได้มาเมื่อคุณแจ้งเรื่องร้องเรียน</div>
            </div>
          </div>
          <div class="mt-6">กรุณากรอก เบอร์มือถือ ผู้ร้อง 4 ตัวท้าย</div>
          <div class="flex flex-col w-full mt-1">
            <Field name="phone" type="text" class="input" v-model="form.phone" placeholder="กรุณากรอกเฉพาะ ตัวเลข ไม่ต้องใส่ - (ขีด)"/>
            <div class="flex flex-col lg:flex-row gap-2 mt-2">
              <ErrorMessage name="phone" class="text-red-500 text-sm" />
              <div class=" text-sm">ตัวอย่าง xxx-xxx-xxxx<span class="text-[#FB8C00]">1234</span></div>
            </div>
          </div>
          <div class="flex flex-row justify-center mt-6">
            <button
              type="submit"
              class="inline-block mt-2 px-6 py-2 text-white bg-[#3fbbc0] border border-[#3fbbc0] rounded"
            >
            ค้นหา
            </button>
          </div>
        </Form>
      </div>
    </div>


    <div
      v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative animate-fade-in-down ">
        <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
          <h2 class="text-lg text-[#3fbbc0]">รายละเอียดเรื่องที่ร้องเรียน</h2>
          <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
        </div>
        <div class="overflow-y-auto max-h-[60vh] px-4">
          <div class="flex justify-center bg-[#1d684a] text-white py-2 mt-6">ข้อมูลเกี่ยวกับผู้ร้องเรียน</div>
          <div v-if="items?.concealed" class="pl-0 md:pl-11 my-3"><i class="far fa-window-close text-2xl"></i> ปกปิด เรื่องร้องเรียน <span class="text-red-500 text-sm">(หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียนจะไม่มีการรับทราบข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนแต่อย่างใด)</span></div>
          <div v-else class="pl-0 md:pl-11 my-3"><i class="fas fa-check text-xl"></i> ไม่ปกปิด เรื่องร้องเรียน <span class="text-red-500 text-sm">(ข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนจะถูกเปิดเผยแก่หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียน)</span></div>
          <div v-if="!items?.concealed" class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ชื่อ ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ items?.fname }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">นามสกุล ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ items?.lname }}</span></div>
          </div>
          <div v-if="!items?.concealed" class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">อาชีพ: <span class="text-[#6c757d] pl-2">{{ items?.work }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">เพศ : 
              <span v-if="items?.gender == 1" class="text-[#6c757d] pl-2">ชาย</span>
              <span v-if="items?.gender == 2" class="text-[#6c757d] pl-2">หญิง</span>
              <span v-if="items?.gender == 3" class="text-[#6c757d] pl-2">LGBTQ+</span>
            </div>
          </div>
          <div v-if="!items?.concealed" class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex flex-col md:flex-row w-ufll mt-2">
              <div>ที่อยู่: </div>
              <span class="text-[#6c757d] pl-2">{{ items?.address }}</span>
            </div>
          </div>
          <div v-if="!items?.concealed" class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">เบอร์โทรศัพท์ : <span class="text-[#6c757d] pl-2">{{ items?.tel }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">เบอร์มือถือ : <span class="text-[#6c757d] pl-2">{{ items?.phone }}</span></div>
          </div>
          <div v-if="!items?.concealed" class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">email : <span class="text-[#6c757d] pl-2">{{ items?.email }}</span></div>
          </div>

          <div class="flex justify-center bg-[#1d684a] text-white py-2 mt-5">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufllmt-2">ร้องเรียนถึง : <span class="text-[#6c757d] pl-2">{{  items?.unit_name }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll mt-2">ประเด็นการร้องเรียน : <span class="text-[#6c757d] pl-2">{{ items?.type_name }} <span v-if="items?.sub_name">({{ items?.sub_name }})</span></span></div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll mt-2">ร้องเรียนบุคคล : <span class="text-[#6c757d] pl-2">{{ items?.person_name }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex flex-col md:flex-row w-ufll mt-2">
              <div>เรื่องที่ร้องเรียน : </div>
              <span class="text-[#6c757d] pl-2">{{ items?.name }}</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex flex-col md:flex-row w-ufll mt-2">
              <div>รายละเอียดเรื่องที่ร้องเรียน : </div>
              <span class="text-[#6c757d] pl-2">{{ items?.description }}</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex flex-col md:flex-row w-ufll mt-2">
              <div>สิ่งที่ต้องการให้แก้ไข ปรับปรุง : </div>
              <span class="text-[#6c757d] pl-2">{{ items?.improvement }}</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-full mt-2">เอกสารประกอบ : 
              <span v-if="items?.file" class="text-[#6c757d] pl-2">{{ items?.file }}
                <a :href="items?.file_url" target="_blank" class="text-blue-500 underline pl-2">[ดาวน์โหลด]</a>
              </span>
              <span v-else class="text-[#6c757d] pl-2">ไม่มี</span>
            </div>
          </div>

           <div class="flex justify-center bg-[#1d684a] text-white py-2 mt-5">กระบวนการดำเนินการ</div>
           <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll mt-2">สถานะ : 
              <span v-if="items?.trace_approve == 1" class="text-[#6c757d] pl-2">กำลังดำเนินการ</span>
              <span v-if="items?.trace_approve == 2" class="text-[#6c757d] pl-2">ดำเนินการเรียบร้อย</span>
              <span v-if="items?.trace_approve == 3" class="text-[#6c757d] pl-2">ผู้ร้องรับทราบ</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8">
            <div class="flex w-ufll mt-2">วันที่ร้องเรียน : 
              <span v-if="items?.created_at" class="text-[#6c757d] pl-2">{{ formatThaiDate(items?.created_at) }}</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-3 md:px-8" v-if="items?.trace_show">
            <div class="flex w-ufll mt-2"><span class="text-[#6c757d] pl-2">{{ items?.trace_show }}</span></div>
          </div>

          
        </div>
        <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-2">
          <button type="button" @click="allowData()" class="px-4 py-2 bg-[#28a745] text-white rounded ">
              รับทราบข้อมูล
          </button>
        </div>
      </div>
    </div>


    <div
      v-if="showEvaluation"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="formEvaluation" :validation-schema="schemaEvaluation" @submit="sendEvaluation">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#3fbbc0]">แบบประเมินความพึงพอใจ สำหรับผู้ใช้บริการ</h2>
            <i @click="showEvaluation = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
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
                      <Field type="radio" name="gender" v-model="formEvaluation.gender" value="1" /> ชาย
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" v-model="formEvaluation.gender" value="2" /> หญิง
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" v-model="formEvaluation.gender" value="3" /> LGBTQ+
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
                  <Field name="age" type="text" class="input" v-model="formEvaluation.age" />
                  <ErrorMessage name="age" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12">3. ระดับการศึกษา</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="qualification" class="input" v-model="formEvaluation.qualification">
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
                  <Field as="select" name="work" class="input" v-model="formEvaluation.work">
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
            <div v-if="formEvaluation.work == 6" class="px-6 md:px-8 mt-2 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12 pl-4">โปรดระบุ</div>
                <div class="w-full md:w-9/12">
                  <Field name="workDis" type="text" class="input" v-model="formEvaluation.workDis" />
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
            <button type="button" @click="showEvaluation = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
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
      isLoading: false,
      items: [],
      showModal: false,
      showEvaluation: false,
      item_question: [],
      form:{
        code: '',
        phone: '',
      },
      schema: yup.object({
        code: yup.string().required('*** กรุณากรอก'),
        phone: yup
        .string()
        .required('*** กรุณากรอก')
        .matches(/^\d{4}$/, '*** ต้องเป็นตัวเลข 4 หลัก'),
      }),
      formEvaluation: {
        gender: '',
        age: '',
        qualification: '',
        work: '',
        workDis: '',
      },
      schemaEvaluation: yup.object({
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
    followData(values){
      this.isLoading = true;
      axios.post('/follow',{
        data: values,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          this.items = res.data.item;
          this.showModal = true;
          if(this.item_question.length == 0){
            this.getQuestion();
          }
         
        }else{
          this.isLoading = false;
          Swal.fire({title: 'ไม่พบข้อมูล !',text: 'ไม่พบข้อมูล รหัสเรื่องร้องเรียน !',icon: 'error',confirmButtonText: 'ตกลง'});
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
        Swal.fire({title: 'ผิดพลาด !',text: 'ไม่สามารถตรวจสอบข้อมูลได้ !',icon: 'error',confirmButtonText: 'ตกลง'});
      })
    },
    allowData(){
      this.showEvaluation = true;
      this.showModal = false;
    },
    getQuestion(){
      this.isLoading = true;
      axios.get('/load/question') 
      .then(res => {
        if(res.data.status == 200){
          this.item_question = res.data.item
          this.isLoading = false;
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
    sendEvaluation(values){
      this.isLoading = true;
      axios.post('/question',{
        data: values,
        question: this.item_question,
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showEvaluation = false;
          Swal.fire({title: 'สำเร็จ !', html: 'ส่งแบบประเมินความพึงพอใจ สำเร็จ! <br/>ขอบคุณที่ ทำแบบประเมินความพีงพอใจ',icon: 'success',confirmButtonText: 'ตกลง'});
          this.formEvaluation.gender = '';
          this.formEvaluation.age = '';
          this.formEvaluation.qualification = '';
          this.formEvaluation.work = '';
          this.formEvaluation.workDis = '';
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
    },
    formatThaiDate(datetimeStr) {
      const date = new Date(datetimeStr);
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear() + 543; // แปลงเป็น พ.ศ.
      const shortYear = String(year).slice(-2); // ปีแบบ 2 หลัก
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');

      return `${day}/${month}/${shortYear} ${hours}:${minutes}`;
    }
  }
}
</script>