<template>
  <div class="page-content" ref="pageContent">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div v-if="!isEdit && !isShow" v-html="title_name"></div>
    <div v-if="!isEdit && !isShow" class="flex items-center space-x-2 ml-11 mt-8">
      <!-- Input -->
      <div class="flex lg:flex-row flex-col gap-4 ">
      <input
        type="text"
        v-model="s_code"
        placeholder="ไม่ต้องใส่ SEC"
        class="border lg:w-[233px] w-full border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-400"
      />
      <div class="lg:w-[233px] w-full">
        <date-picker 
        v-model:value="selectedDate" 
        placeholder="วันที่ร้องเรียน"
        input-class="h-[42px] border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-400" 
        :formatter="momentFormat">
        </date-picker>
      </div>
      <button @click="showData()" class="flex items-center bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded">
        <i class="fas fa-search mr-2"></i>
        ค้นหา
      </button>
      </div>
    </div>

    <div v-if="!isEdit && !isShow && items.length > 0" class="grid grid-cols-1 md:grid-cols-2 mt-6 gap-5">
      <div v-for="(item, i) in items" class="border rounded-md shadow-sm">
        <div class="border-b px-4 py-2 bg-[#00000008]">
          <div @click="showComplaint(item.id)" class="text-orange-600 font-semibold hover:cursor-pointer">
            <span>{{ item.type_name }}</span> <span v-if="item.sub_name">({{ item.sub_name }})</span>
          </div>
          <div class="flex flex-col  md:flex-row text-sm text-gray-500 mt-1 gap-0 md:gap-3">
            <div class="flex flex-row gap-2">
              <i class="fas fa-clock"></i>
              <div>{{ formatThaiDate(item.created_at) }}</div>
            </div>
            <div class="flex flex-row gap-2">
              <i class="far fa-paper-plane"></i>
              <div>{{ item.user_add }}</div>
            </div>
          </div>
        </div>
        <div class="mt-4 px-4 text-sm space-y-1">
          <div><span class="text-blue-600 font-medium">รหัสเรื่อง :</span> {{ item.code }}</div>
          <div><span class="text-blue-600 font-medium">เรื่องร้องเรียน :</span> {{ item.name }}</div>
          <div><span class="text-blue-600 font-medium">ร้องเรียนถึง :</span> {{ item.unit }}</div>
          <div><span class="text-blue-600 font-medium">ข่องทางการร้องเรียน :</span> {{ item.methods }}</div>
          <div><span class="text-blue-600 font-medium">สถานะ : </span>

            <span v-if="item.type == 0" class="results-status type-1 bg-red-600">ยุติเรื่อง-ไม่ใช่</span>
            <span v-if="item.type == 2" class="results-status type-1 bg-orange-600">รอศูนย์รับเรื่อง</span>
            <span v-if="item.type == 3 && user_level == 'root'" class="results-status type-2 bg-yellow-600">ศูนย์รับเรื่อง - รอหน่วยรับเรื่อง</span>
            <span v-if="item.type == 3 && user_level == 'unit'" class="results-status type-2 bg-yellow-600">รอรับเรื่อง</span>
            <span v-if="item.type == 4 && user_level == 'root'" class="results-status type-3 bg-blue-400">หน่วย รับเรื่อง - กำลังดำเนินการ</span>
            <span v-if="item.type == 4 && user_level == 'unit'" class="results-status type-3 bg-blue-800">รับเรื่องแล้ว - กำลังดำเนินการ</span>
            <span v-if="item.type == 5" class="results-status type-4 bg-green-600">หน่วย ยุติเรื่อง</span>
            <span v-if="item.type == 6 && user_level == 'root'" class="results-status type-4 bg-red-400">ส่งกลับให้หน่วย ดำเนินการแก้ไข</span>
            <span v-if="item.type == 6 && user_level == 'unit'" class="results-status type-4 bg-red-400">ศูนย์ส่งกับ - ให้แก้ไข</span>
            <span v-if="item.type == 7" class="results-status type-5 bg-green-600">ศูนย์ยุติเรื่อง</span>
            <span v-if="item.type == 8" class="results-status type-6 bg-green-800">เสร็จสิ้น</span>

          </div>
        </div>
        <div class="mt-4 px-4 flex flex-wrap justify-end gap-2">
          <button type="button" v-if="item.type == 0 && user_level == 'root'" @click="retrunComplaint(item.id)" class="text-green-600 border border-green-700 text-sm px-4 py-2 rounded ">นำกลับไปใช้</button>
          <button type="button" v-if="item.type == 2 && user_level == 'root'" @click="editComplaint(item.id)" class="bg-cyan-600 text-white text-sm px-4 py-2 rounded hover:bg-cyan-700">แก้ไข</button>
          <button type="button" v-if="item.type == 3 && user_level == 'unit' && item.is_add == 2" @click="editComplaint(item.id)" class="bg-gray-700 text-white text-sm px-4 py-2 rounded hover:bg-gray-800">แก้ไข</button>
          <button type="button" v-if="item.type == 2 && user_level == 'root'" @click="sendComplaint(item.id)" class="bg-gray-700 text-white text-sm px-4 py-2 rounded hover:bg-gray-800">ส่งให้หน่วยดำเนินการ</button>
          <button type="button" v-if="item.type == 2 && user_level == 'root'" @click="cancelComplaint(item.id)" class="bg-red-600 text-white text-sm px-4 py-2 rounded hover:bg-red-700">ไม่ใช่/ยุติเรื่อง</button>

          <button type="button" v-if="item.type == 3 && user_level == 'unit'" @click="receiveComplaint(item.id)" class="bg-cyan-600 text-white text-sm px-4 py-2 rounded hover:bg-cyan-700">รับเรื่องร้องเรียน</button>
          <button type="button" v-if="(item.type == 4 || item.type == 6) && user_level == 'unit'" @click="answerComplaint(item.id)" class="bg-green-600 text-white text-sm px-4 py-2 rounded hover:bg-green-700">ตอบ/แก้ไขข้อร้องเรียน</button>

        </div>
        <div class="text-sm mt-4 px-4 border-t py-3 font-medium text-gray-700">
          <span v-if="item.process > 0">{{item.process}} การดำเนินการ</span>
          <span v-else>ยังไม่ดำเนินการ</span>
        </div>
      </div>
    </div>
    <div v-if="!isEdit && !isShow && items.length == 0" class="text-center text-red-500 text-lg mt-11"> -- ไม่มีข้อมูล --</div>
    <pagination 
      v-show="!isEdit && !isShow && items.length > 0"
      ref="pagination" 
      :limit="limit"
      @eventPage="eventPage"
    ></pagination>

    <div v-if="isEdit" class="flex gap-2">
      <div class="text-2xl text-[#EE7530] ml-11 mb-6">
        <i class="far fa-edit"></i> รายละเอียดเรื่องร้องเรียน
      </div>
      <div class="mt-2 text-[#007bff]">
        <i class="fas fa-angle-right"></i> แก้ไขข้อร้องเรียน
      </div>
    </div>
    
    <div v-show="isEdit"><InputComplaint ref="formedit" :user_level="user_level" type="edit" @backPage="handleClosedEdit"></InputComplaint></div>


    <div v-if="isShow" class="flex flex-col md:flex-row gap-2">
      <div v-html="title_name">
      </div>
      <div class="md:mt-2 -mt-4 text-[#007bff]">
        <i class="fas fa-angle-right"></i> แสดงรายละเอียด
      </div>
    </div>
    <div v-show="isShow">
      <ShowComplaint ref="formshow" :user_level="user_level"  @backPage="handleClosedEdit"></ShowComplaint>
    </div>


    <div
    v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="form" :validation-schema="schema" @submit="sendData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]">ยืนยันการ ส่งให้หน่วยดำเนินการ !</h2>
            <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-start">
                <div class="w-full md:w-3/12">ระดับเรื่อง :</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="complain_level" class="input" v-model="form.complain_level">
                    <option value="">-- กรุณาเลือก --</option>
                    <option value="1">เรื่องทั่วไป</option>
                    <option value="2">เรื่องลับ</option>
                  </Field>
                  <ErrorMessage name="complain_level" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">ระดับความรุนแรง :</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="severity_admin" class="input" v-model="form.severity_admin">
                    <option value="">-- กรุณาเลือก --</option>
                    <option value="1">ระดับ 1</option>
                    <option value="2">ระดับ 2</option>
                    <option value="3">ระดับ 3</option>
                    <option value="4">ระดับ 4</option>
                    <option value="5">ระดับ 5</option>
                  </Field>
                  <ErrorMessage name="severity_admin" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">หน่วยกำกับดูแล :</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="send_unit" class="input" v-model="form.send_unit">
                    <option value="">-- กรุณาเลือก --</option>
                    <option v-for="(item) in item_unit" :value="item.id">{{ item.name }}</option>
                  </Field>
                  <ErrorMessage name="send_unit" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">คำสั่งการ (ถ้ามี):</div>
                <div class="w-full md:w-9/12">
                  <Field name="send_comm" type="text" class="input" v-model="form.send_comm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">เอกสารคำสั่งการ (ถ้ามี):</div>
                  <div class="w-full md:w-9/12">
                  <div class="flex items-center gap-2">
                  <div @click="changeFile()" class="border-[#ccc] border rounded px-4 py-1 inline-block hover:cursor-pointer bg-[#e9ecef]">เลือกไฟล์</div>
                  <div v-if="file.name" class="text-[#007bff] underline">{{ file.name }}</div>
                </div>
                <div class="text-xs text-[#6c757d]">กรุณาเลือกไฟล์ pdf, หรือรูปภาพ</div>
                <input id="fileSelect" @change="slectFile()" ref="file" type="file" accept=".pdf,image/png,image/jpeg" style="display:none;" />  
              </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ส่งให้หน่วย ดำเนินการ
            </button>
        </div>
        </Form>
      </div>
    </div>


    <div
    v-if="formReceive.modal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="formReceive" :validation-schema="schemaReceive" @submit="receiveData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]"><i class="far fa-share-square"></i> รับเรื่อง ร้องเรียน !</h2>
            <i @click="formReceive.modal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-start mt-4">
                เจ้าหน้าที่ ที่รับผิดชอบ
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">ชื่อ :</div>
                <div class="w-full md:w-9/12">
                  <Field name="auth_fname" type="text" class="input" v-model="formReceive.auth_fname" />
                  <ErrorMessage name="auth_fname" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">นามสกุล :</div>
                <div class="w-full md:w-9/12">
                  <Field name="auth_lname" type="text" class="input" v-model="formReceive.auth_lname" />
                  <ErrorMessage name="auth_lname" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">โทรศัพท์ :</div>
                <div class="w-full md:w-9/12">
                  <input
                    type="text"
                    v-model="formReceive.auth_phone"
                    @input="formatPhone"
                    class="input"
                    placeholder="___-___-____"
                  />
                  <Field name="auth_phone" type="hidden" v-model="formReceive.auth_phone" />
                  <ErrorMessage name="auth_phone" class="text-red-500 text-sm" />
                </div>
              </div>
              <div class="flex flex-col md:flex-row items-start mt-4">
                <div class="w-full md:w-3/12">E-mail :</div>
                <div class="w-full md:w-9/12">
                  <Field name="auth_email" type="text" class="input" v-model="formReceive.auth_email" />
                  <ErrorMessage name="auth_email" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="formReceive.modal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ยืนยันการ รับเรื่อง
            </button>
        </div>
        </Form>
      </div>
    </div>

    <div
    v-if="showModalClose"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="formClose" :validation-schema="schemaClose" @submit="closeData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]">ยืนยันการ ยกเลิก/ยุติเรื่อง !</h2>
            <i @click="showModalClose = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="flex flex-col px-6 items-start mt-4">
              <div class="w-full">สาเหตุการยกเลิก/ยุติเรื่อง :</div>
                <div class="w-full mt-2">
                  <Field
                    as="textarea"
                    name="comm_cancel"
                    rows="3"
                    class="form-control w-full border rounded p-2 border-[#ccc]"
                    v-model="formClose.comm_cancel"
                  />
                  <ErrorMessage name="comm_cancel" class="text-red-500 text-sm" />
                </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="showModalClose = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ยืนยันการ ยกเลิก/ยุติเรื่อง
            </button>
        </div>
        </Form>
      </div>
    </div>

    <div
    v-if="formAnswer.modal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form :initial-values="formAnswer" :validation-schema="schemaAnswer" @submit="answerData">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]">ตอบกลับ / แก้ไข ข้อร้องเรียน !</h2>
            <i @click="formAnswer.modal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="flex flex-col px-6 items-start mt-4">
              <div class="w-full">การแก้ไขข้อร้องเรียน :</div>
                <div class="w-full mt-2">
                  <Field
                    as="textarea"
                    name="answer_detail"
                    rows="3"
                    class="form-control w-full border rounded p-2 border-[#ccc]"
                    v-model="formAnswer.answer_detail"
                  />
                  <ErrorMessage name="answer_detail" class="text-red-500 text-sm" />
                </div>
            </div>
            <div class="flex flex-col px-6 items-start mt-4">
                <div class="w-full md:w-3/12">เอกสารประกอบ (ถ้ามี):</div>
                  <div class="w-full md:w-9/12">
                  <div class="flex items-center gap-2">
                  <div @click="changeFile()" class="border-[#ccc] border rounded px-4 py-1 inline-block hover:cursor-pointer bg-[#e9ecef]">เลือกไฟล์</div>
                  <div v-if="file.name" class="text-[#007bff] underline">{{ file.name }}</div>
                </div>
                <div class="text-xs text-[#6c757d]">กรุณาเลือกไฟล์ pdf, หรือรูปภาพ</div>
                <input id="fileSelect" @change="slectFile()" ref="file" type="file" accept=".pdf,image/png,image/jpeg" style="display:none;" />  
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="formAnswer.model = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ตอบข้อร้องเรียน
            </button>
        </div>
        </Form>
      </div>
    </div>
    
  </div>
</template>
<script>
import pagination from '../Pagination.vue';
import InputComplaint from './InputComplaint.vue';
import ShowComplaint from './ShowComplaint.vue';
import axios from 'axios'
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
// import th from 'vue-datepicker-next/locale/lang/th';


import moment from 'moment'
import { ref } from 'vue'

export default{
  data(){
    return{
      showModal: false,
      showModalClose: false,
      isLoading: false,
      momentFormat: {
  // Date → String
  stringify: (date) => {
    // ถ้ามีค่า date ให้แปลงเป็น dd/MM/YY
    return date ? moment(date).format('DD/MM/YY') : ''
  },
  
  // String → Date
  parse: (value) => {
    // แปลงจาก dd/MM/YY กลับเป็น Date()
    return value ? moment(value, 'DD/MM/YY').toDate() : null
  },

  // get week number (optional)
  getWeek: (date) => {
    return moment(date).week() // ใช้ moment คำนวณเลขสัปดาห์
  }
},
      s_code: '',
      s_date: '',
      page: 1,
      limit: 20,
      items: [],
      item_unit: [],
      isEdit: false,
      isShow: false,
      id: '',
      form: {
        complain_level: '',
        severity_admin: '',
        send_unit: '',
        send_comm: '',
      },
      schema: yup.object({
        complain_level: yup.string().required('กรุณาเลือกระดับ'),
        severity_admin: yup.string().required('กรุณาเลือกระดับความรุนแรง'),
        send_unit: yup.string().required('กรุณาเลือกหน่วยกำกับดูแล'),
      }),
      formClose:{
        comm_cancel: '',
      },
      schemaClose: yup.object({
        comm_cancel: yup.string().required('กรุณากรอกเหตุผล'),
      }),
      formReceive: {
        modal: false,
        auth_fname: '',
        auth_lname: '',
        auth_email: '',
        auth_phone: '',
      },
      schemaReceive: yup.object({
        auth_fname: yup.string().required('กรุณากรอกชื่อ'),
        auth_lname: yup.string().required('กรุณากรอกนามสกุล'),
        auth_email: yup.string().email('รูปแบบอีเมลไม่ถูกต้อง').required('กรุณากรอกอีเมล'),
        auth_phone: yup.string().required('กรุณากรอกเบอร์โทรศัพท์'),
      }),
      formAnswer:{
        modal: false,
        answer_detail: '',
      },
      schemaAnswer: yup.object({
        answer_detail: yup.string().required('กรุณากรอก การแก้ไขข้อร้องเรียน'),
      }),
      file:{
        uri: '',
        name: '',
        type: '',
      },
    }
  },
  setup() {
    const selectedDate = ref(null)

    const momentFormat = {
      // Date → String (แสดงเป็น พ.ศ.)
      stringify: (date) => {
        if (!date) return ''
        const d = moment(date)
        const day = d.date().toString().padStart(2, '0')
        const month = (d.month() + 1).toString().padStart(2, '0')
        const yearBE = d.year() + 543
        return `${day}/${month}/${yearBE}`
      },

      // String → Date (แปลงจาก พ.ศ. → ค.ศ.)
      parse: (value) => {
        if (!value) return null
        const [day, month, yearBE] = value.split('/')
        const yearCE = Number(yearBE) - 543
        return moment(`${day}/${month}/${yearCE}`, 'DD/MM/YYYY').toDate()
      },

      // เลขสัปดาห์ (optional)
      getWeek: (date) => moment(date).week()
    }

    return { selectedDate, momentFormat }
  },
  props:['type','title_name','unit','user_level','is_search'],
  components:{
    pagination,
    InputComplaint,
    ShowComplaint,
    Form,
    Field,
    ErrorMessage,
    Swal,
    DatePicker,
  },
  created(){
    this.item_unit = JSON.parse(this.unit)
    console.log('is_search',this.is_search)
    console.log('type',this.type)
  },
  mounted() {
    this.showData();
  },
  methods: {
    sendComplaint(id){
      this.id = id
      this.showModal = true
    },
    cancelComplaint(id){
      this.id = id
      this.showModalClose = true
    },
    changeFile(){
      this.$refs.file.click();
    },
    slectFile(){
      const file = this.$refs.file?.files[0];
      this.file.name = file.name
      this.file.type = 'new'
    },
    showComplaint(id){
      this.isShow = true
      this.scrollToContent();
      setTimeout(() => {
        this.isLoading = false;
        this.$refs.formshow.loadComplaint(id)
      }, 800)
    },
    editComplaint(id){
      this.id = id
      this.isEdit = true
      this.isLoading = true;
      this.scrollToContent();
      setTimeout(() => {
        this.isLoading = false;
        this.$refs.formedit.loadComplaint(id)
      }, 800)
    },
    scrollToContent() {
      const target = this.$refs.pageContent
      if (target) {
        const y = target.offsetTop - 1000
        window.scrollTo({
          top: y,
          behavior: 'smooth'
        })
      }
    },
    handleClosedEdit(){
      // this.scrollToContent();
      this.isEdit = false;
      this.isShow = false;
      this.showData();
    },
    showData(){
      this.isLoading = true;
      axios.get('/get/complaint/type',{
        params:{
          page: this.page,
          limit: this.limit,
          type: this.type,
          s_code: this.s_code,
          s_date: this.selectedDate,
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.items = res.data.item;
          this.isLoading = false
          
          this.genPagination(res.data.pagination)
        }else{
          this.isLoading = false
          this.genPagination(null)
        }
      })
      .catch(err => {
        this.genPagination(null)
        console.error(err)
        this.isLoading = false;
      })
    },
    sendData(values){
      console.log(values);
      Swal.fire({
        title: 'ยืนยันส่งให้หน่วยดำเนินการ !',
        html: 'ยืนยันการส่งเรื่องร้องเรียนนี้ ใช่หรือไม่ ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          this.isLoading = true
          const formData = new FormData();
          formData.append('file', this.$refs.file?.files[0]);
          formData.append('complain_level', values.complain_level);
          formData.append('send_comm', values.send_comm);
          formData.append('send_unit', values.send_unit);
          formData.append('severity_admin', values.severity_admin);
          axios.post('/complaint/send/'+this.id, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          }) 
          .then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.$refs.file.value = null
              this.file.uri = '';
              this.file.name = '';
              this.file.type = '';
              this.form.complain_level = ''
              this.form.severity_admin = ''
              this.form.send_unit = ''
              this.form.send_comm = ''
              this.showModal = false
              this.showData()
            }else{
              this.isLoading = false
              Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
            }
          }).catch(err => {
            this.isLoading = false
            console.error(err)
            Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'ไม่สามารถเชื่อมต่อข้อมูลได้',
              confirmButtonText: 'ตกลง'
            })
          })
        }
      });
    },
    closeData(values){
      Swal.fire({
        title: 'ยืนยันการยุติ !',
        html: 'ยืนยันการยกเลิก/ยุติเรื่องร้องเรียนนี้ ใช่หรือไม่ ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
        this.isLoading = true 
        axios.post('/complaint/cancel/'+this.id,{
          comm_cancel: values.comm_cancel,
          }).then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.formClose.comm_cancel = ''
              this.showModalClose = false
              this.showData()
            }else{
              this.isLoading = false
              Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
            }
          }).catch(err => {
            this.isLoading = false
            console.error(err)
            Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'ไม่สามารถเชื่อมต่อข้อมูลได้',
              confirmButtonText: 'ตกลง'
            })
          })
        }
      })
    },
    receiveComplaint(id){
      this.id = id
      this.formReceive.modal = true
    },
    receiveData(values){
      Swal.fire({
        title: 'ยืนยันการรับเรื่อง !',
        html: 'ยืนยันการรับเรื่องร้องเรียนนี้ ใช่หรือไม่ ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          this.isLoading = true 
          axios.post('/complaint/receive/'+this.id,{
          auth_fname: values.auth_fname,
          auth_lname: values.auth_lname,
          auth_email: values.auth_email,
          auth_phone: values.auth_phone,
          }).then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.formReceive.modal = false
              this.showData()
            }else{
              this.isLoading = false
              Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
            }
          }).catch(err => {
            this.isLoading = false
            console.error(err)
            Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'ไม่สามารถเชื่อมต่อข้อมูลได้',
              confirmButtonText: 'ตกลง'
            })
          })
        }
      });
    },
    answerComplaint(id){
      this.id = id
      this.formAnswer.modal = true
    },
    answerData(values){
      Swal.fire({
        title: 'ยืนยันการตอบ/แก้ไข !',
        html: 'กรุณายืนยันการตอบ/แก้ไขข้อร้องเรียน อีกครั้ง',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
        this.isLoading = true 

        const formData = new FormData();
        formData.append('answer_file', this.$refs.file?.files[0]);
        formData.append('answer_detail', values.answer_detail);
        formData.append('id', this.id);

         axios.post('/complaint/save/answer/'+this.id, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          }).then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.$refs.file.value = null
              this.file.uri = '';
              this.file.name = '';
              this.file.type = '';
              this.formAnswer.answer_detail = ''
              this.formAnswer.modal = false
              this.showData()
            }else{
              this.isLoading = false
              Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
            }
          }).catch(err => {
            this.isLoading = false
            console.error(err)
            Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'ไม่สามารถเชื่อมต่อข้อมูลได้',
              confirmButtonText: 'ตกลง'
            })
          })
        }
      });
    },
    retrunComplaint(id){
      Swal.fire({
        title: 'ยืนยัน !',
        html: 'ยืนยันการนำเรื่องร้องเรียนกลับไปใช้ ใช่หรือไม่ ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          this.isLoading = true 
          axios.post('/complaint/reset/'+id).then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.formReceive.modal = false
              this.showData()
            }else{
              this.isLoading = false
              Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
            }
          }).catch(err => {
            this.isLoading = false
            console.error(err)
            Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'ไม่สามารถเชื่อมต่อข้อมูลได้',
              confirmButtonText: 'ตกลง'
            })
          })
        }
      });
    },
    genPagination(data){
      if(this.isEdit) return
      if(data != null){
        this.$refs.pagination.from = data.from; 
        this.$refs.pagination.to = data.to; 
        this.$refs.pagination.total = data.total; 
        this.$refs.pagination.currentpage = this.page
      }else{
        this.$refs.pagination.total = 0;
      }
      this.$refs.pagination.createPagination();
    },
    eventPage(page){
      this.page = page;
      this.showData();
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
    },
    formatPhone(event) {
      let raw = event.target.value.replace(/\D/g, '')
      if (raw.length > 10) raw = raw.slice(0, 10)

      const part1 = raw.slice(0, 3)
      const part2 = raw.slice(3, 6)
      const part3 = raw.slice(6, 10)

      const formatted = [part1, part2, part3].filter(Boolean).join('-')
      this.formReceive.auth_phone = formatted
    },
    formatBE(date) {
      if (!date) return ''
      const d = new Date(date)
      const day = String(d.getDate()).padStart(2, '0')
      const month = String(d.getMonth() + 1).padStart(2, '0')
      const year = d.getFullYear() + 543 // เพิ่ม 543 = พ.ศ.
      return `${day}/${month}/${year}`
    },
    // แปลงกลับจาก พ.ศ. → Date() ค.ศ.
    parseBE(value) {
      if (!value) return null
      const [day, month, year] = value.split('/')
      return new Date(Number(year) - 543, Number(month) - 1, Number(day))
    }
  }
}
</script>