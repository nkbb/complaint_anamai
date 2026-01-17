<template>
  <div>
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3" v-if="id && type=='edit'">
      <!-- ปุ่มพิมพ์ -->
      <button
        type="button"
        @click="saveData()"
        aria-label="บันทึก"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-[#1d684a]"
      >
        <i class="far fa-save text-[20px]"></i>
        <span>บันทึก</span>
      </button>

      <button
        type="button"
        @click="removeData()"
        v-if="(user_level == 'root' && is_add == 3) || (user_level == 'unit' && is_add == 2)"
        aria-label="ลบ"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-red-500"
      >
        <i class="fas fa-trash-alt text-[18px]"></i>
        <span>ลบ</span>
      </button>

      <!-- ปุ่มย้อนกลับ -->
      <button
        type="button"
        @click="backPage()"
        aria-label="ย้อนกลับ"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-gray-800"
      >
        <!-- ไอคอนย้อนกลับ (SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>ย้อนกลับ</span>
      </button>
    </div>


    <div v-if="code" class="ml-11 ">
      <span class="text-[#FF7043]">รหัสเรื่อง</span> : {{ code }} 
    </div>
    <Form :initial-values="form" :validation-schema="schema" @submit="onSubmit" @invalid-submit="onInvalidSubmit">
      <div v-if="!code" class="text-[#FF7043] my-2 pl-11">*** กรณีไม่มีข้อมูล ผู้ร้องเรียนไม่ต้องกรอกข้อมูล</div>
      <div class="mt-5 mb-2 text-center text-white text-base bg-[#1d684a] rounded-sm py-3">ข้อมูลผู้ร้องเรียน</div>
      <div class="flex gap-2 flex-col">
          <div class="mt-5 pl-0 md:pl-8 lg:pl-[10%]">
            <input type="checkbox" v-model="form.concealed" class="custom-checkbox" />
              <span class="pl-3 -mt-2 text-[#FF7043]">ถ้าต้องการปกปิด ชื่อและข้อมูลส่วนตัว ให้คลิกที่นี่</span>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ชื่อ ผู้ร้องเรียน :</div>
              <div class="w-full md:w-8/12">
                <Field name="firstName" type="text" class="input" v-model="form.firstName" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">นามสกุล ผู้ร้องเรียน :</div>
              <div class="w-full md:w-8/12">
                <Field name="lastName" type="text" class="input" v-model="form.lastName" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <!-- <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">เลขบัตรประชาชน :</div>
              <div class="w-full md:w-8/12">
                <input
                  type="text"
                  v-model="form.idcard"
                  @input="formatThaiIdCard"
                  class="input"
                  placeholder="_-____-______-__-_"
                />
                <Field name="idcard" v-model="form.idcard" type="hidden" />
              </div>
            </div> -->
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">เพศ :</div>
              <div class="w-full md:w-6/12 lg:w-4/12">
                <Field as="select" name="sex" class="input" v-model="form.sex">
                  <option value="">-- กรุณาเลือก --</option>
                  <option value="1">ชาย</option>
                  <option value="2">หญิง</option>
                  <option value="3">LGBTQ+</option>
                </Field>
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อาชีพ :</div>
              <div class="w-full md:w-8/12">
                <Field name="work" type="text" class="input" v-model="form.work" />
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">ที่อยู่ :</div>
              <div class="w-full md:w-10/12">
                <Field name="address" type="text" class="input" v-model="form.address" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">จังหวัด :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="province_id" class="input" v-model="form.province_id" @change="getDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_province" :value="item.id">{{ item.name }}</option>
                </Field>
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อำเภอ :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="district_id" class="input" v-model="form.district_id" @change="getSubDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_district" :value="item.id">{{ item.name }}</option>
                </Field>
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ตำบล :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="subdistrict_id" class="input" v-model="form.subdistrict_id" @change="getZipcode()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_subdistrict" :value="item.id">{{ item.name }}</option>
                </Field>
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">รหัสไปรษณี :</div>
              <div class="w-full md:w-8/12">
                <Field name="zipcode" type="text" class="input" v-model="form.zipcode" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">มือถือ (ถ้ามี) :</div>
              <div class="w-full md:w-8/12">
                 <input
                  type="text"
                  v-model="form.phone"
                  @input="formatPhone"
                  class="input"
                  placeholder="___-___-____"
                />
                <Field name="phone" type="hidden" v-model="form.phone" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">โทรศัพท์ (ถ้ามี) :</div>
              <div class="w-full md:w-8/12">
                <input
                  type="text"
                  v-model="form.tel"
                  @input="formatTel"
                  class="input"
                  placeholder="__-____-____"
                />
                <Field name="tel" type="hidden" v-model="form.tel" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อีเมล (ถ้ามี) :</div>
              <div class="w-full md:w-8/12">
                <Field name="email" type="text" class="input" v-model="form.email" />
              </div>
            </div>
          </div>
      </div>
      <div class="mt-[44px] mb-2 text-center text-white text-base bg-[#1d684a] rounded-sm py-3">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
      <div class="flex gap-2 flex-col">
        <div class="flex md:flex-row flex-col gap-3">
          <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-4/12 text-left md:text-right">ช่องทางร้องเรียน : <span class="text-[#ff0000]">*</span></div>
            <div class="w-full md:w-8/12">
              <Field as="select" name="method_id" class="input" v-model="form.method_id">
                <option value="">-- กรุณาเลือก --</option>
                <option v-for="(item) in item_methods" :value="item.id">{{ item.name }}</option>
              </Field>
              <ErrorMessage name="method_id" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex md:flex-row flex-col gap-3">
          <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-4/12 text-left md:text-right">ร้องเรียนถึง : <span class="text-[#ff0000]">*</span></div>
            <div class="w-full md:w-8/12">
              <Field as="select" name="unit_id" class="input" v-model="form.unit_id">
                <option value="">-- กรุณาเลือก --</option>
                <option v-for="(item) in item_unit" :value="item.id">{{ item.name }}</option>
              </Field>
              <ErrorMessage name="unit_id" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex md:flex-row flex-col gap-3">
          <div class="flex flex-col w-full md:flex-row md:w-full gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">ประเด็นการร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
            <div class="w-full md:w-8/12">
              <Field as="select" name="type_id" class="input" @change="selectType()" v-model="form.type_id">
                <option value="">-- กรุณาเลือก --</option>
                <option v-for="(item) in item_type" :value="item.id">{{ item.num }}. {{ item.name }}</option>
              </Field>
              <ErrorMessage name="type_id" class="text-red-500 text-sm" />
            </div>
          </div>
          <!-- <div v-if="form.type_id == 1 || form.type_id == 2" class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
            <div  class="w-full md:w-4/12 text-left md:text-right">ประเด็นย่อย : <span class="text-[#ff0000]">*</span></div>
            <div class="w-full md:w-8/12">
              <Field as="select" name="sub_id" class="input" v-model="form.sub_id">
                <option value="">-- กรุณาเลือก --</option>
                <option v-if="form.type_id == 1" value="1">1.1 ด้านความรวดเร็ว/ตรงต่อเวลา</option>
                <option v-if="form.type_id == 1" value="2">1.2 ด้านพฤติกรรมบริการ</option>
                <option v-if="form.type_id == 1" value="3">1.3 ด้านสิ่งอำนวยความสะดวก/ความเสมอภาค</option>
                <option v-if="form.type_id == 1" value="4">1.4 ด้านการบำบัด รักษา</option>
                <option v-if="form.type_id == 1" value="5">1.5 ด้านการให้ข้อมูล/คำแนะนำ</option>
                <option v-if="form.type_id == 2" value="6">2.1 การบริหารพัสดุ</option>
                <option v-if="form.type_id == 2" value="7">2.2 การบริหารงบประมาณ</option>
                <option v-if="form.type_id == 2" value="8">2.3 การบริหารงานบุคคล</option>
                <option v-if="form.type_id == 2" value="9">2.4 การบริหารงานทั่วไป</option>
              </Field>
              <ErrorMessage name="sub_id" class="text-red-500 text-sm" />
            </div>
          </div> -->
        </div>
        <div class="flex md:flex-row flex-col gap-3">
          <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-4/12 text-left md:text-right">ร้องเรียนบุคคล : <span class="text-[#ff0000]">*</span></div>
            <div class="w-full md:w-8/12">
              <Field as="select" name="person_id" class="input" v-model="form.person_id">
                <option value="">-- กรุณาเลือก --</option>
                <option v-for="(item) in item_person" :value="item.id">{{ item.name }}</option>
              </Field>
              <ErrorMessage name="person_id" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">เรื่องที่ร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
            <div class="w-full md:w-8/12">
              <Field name="name" type="text" class="input" v-model="form.name" />
              <ErrorMessage name="name" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">รายละเอียดเรื่องที่ร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
            <div class="w-full md:w-8/12">
              <Field
                as="textarea"
                name="description"
                rows="3"
                class="form-control w-full border rounded p-2 border-[#ccc]"
                v-model="form.description"
              />
              <ErrorMessage name="description" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">สิ่งที่ต้องการให้แก้ไข ปรับปรุง <span class="text-[#ff0000]">*</span> :</div>
            <div class="w-full md:w-8/12">
              <Field
                as="textarea"
                name="improvement"
                rows="3"
                class="form-control w-full border rounded p-2 border-[#ccc]"
                v-model="form.improvement"
              />
              <ErrorMessage name="improvement" class="text-red-500 text-sm" />
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-3">
          <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">เอกสารประกอบ (ถ้ามี) : </div>
            <div class="w-full md:w-8/12">
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
      <div class="flex justify-center gap-3 mt-8">
        <button
          type="submit"
          ref="btnSubmit"
          class="inline-block mt-2 px-6 py-2 text-white bg-[#1d684a] border border-[#1d684a] rounded"
        >
        บันทึก
        </button>
        <button @click="backPage()" v-if="type=='edit'" type="button" class="inline-block mt-2 px-6 py-2  border rounded"><i class="fas fa-long-arrow-alt-left"></i> ย้อนกลับ</button>
      </div>
    </Form>
  </div>
</template>
<script>
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import useClipboard from 'vue-clipboard3'

export default {
  name: 'InputComplaint',
  data(){
    return {
      id: '',
      code: '',
      is_add: '',
      isLoading: false,
      form: {
        concealed: false,
        firstName: '',
        lastName: '',
        idcard: '',
        sex: '',
        work: '',
        address: '',
        phone: '',
        tel: '',
        province_id: '',
        district_id: '',
        subdistrict_id: '',
        zipcode: '',
        unit_id: '',
        type_id: '',
        sub_id: '',
        person_id: '',
        name: '',
        description: '',
        improvement: '',
        method_id: '',
      },
      schema: yup.object({
        method_id: yup.string().required('กรุณาเลือกหน่วยที่จะร้องเรียนถึง'),
        unit_id: yup.string().required('กรุณาเลือกหน่วยที่จะร้องเรียนถึง'),
        type_id: yup.string().required('กรุณาเลือกประเด็นการร้องเรียน'),
        person_id: yup.string().required('กรุณาเลือกร้องเรียนบุคคล'),
        name: yup.string().required('กรุณากรอกเรื่องที่ร้องเรียน'),
        improvement: yup.string().required('กรุณากรอกรายละเอีดยเพิ่มเติม'),
        description: yup.string().required('กรุณากรอกสิ่งที่ต้องการให้แก้ไข ปรับปรุง'),
        // sub_id: yup.string().when('type_id', (type_id, schema) => {
        //   return type_id[0] == '1' || type_id[0] == '2'
        //     ? schema.required('กรุณาเลือกประเด็นย่อย')
        //     : schema.nullable();
        // }), 
      }),
      item_province: [],
      item_district: [],
      item_subdistrict: [],
      item_unit: [],
      item_type: [],
      item_sub: [],
      item_person: [],
      item_methods: [],
      file:{
        uri: '',
        name: '',
        type: '',
      },
      show:{
        district_id: '',
        subdistrict_id: '',
      }
    }
  },
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
    useClipboard,
  },
  props:['type','user_level'],
  created(){
    this.getMasterData();
  },
  mounted(){
  },
  methods:{
    backPage(){
      this.$emit('backPage')
    },
    saveData(){
      this.$refs.btnSubmit.click();
    },
    async loadComplaint(id){
      this.isLoading = true;
      this.id = id
      await axios.get('/get/complaint/by/'+id) 
      .then(res => {
        if(res.data.status == 200){
          const item = res.data.item;
          this.form.concealed = (item.concealed) ? true : false;
          this.form.sex = (item.gender) ? item.gender : '';
          this.form.province_id = item.province_id
          if(item.province_id) this.getDistrict()
          this.show.district_id = item.district_id
          this.show.subdistrict_id = item.subdistrict_id
          this.form.zipcode = item.zipcode
          this.form.type_id = item.type_id
          this.form.sub_id = item.sub_id
          this.form.person_id = item.person_id
          this.form.unit_id = item.unit_id
          this.form.method_id = item.method_id
          this.form.firstName = item.fname;
          this.form.lastName = item.lname;
          this.form.address = item.address;
          this.form.work = item.work;
          this.form.phone = item.phone;
          this.form.tel = item.tel;
          this.form.email = item.email;
          this.form.name = item.name
          this.form.improvement = item.improvement
          this.form.description = item.description
          this.form.idcard = item.idcard
          this.code = item.code
          this.file.name = item.file
          this.file.type = 'old'
          this.isLoading = false
          this.is_add = item.is_add
        }else{
          this.isLoading = false
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    onInvalidSubmit(){
      Swal.fire({title: 'แจ้งเตือน !',text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง !',icon: 'warning',confirmButtonText: 'ตกลง'});
    },
    onSubmit(values){
      let uri = 'กรุณายืนยัน การเพิ่ม ข้อร้องเรียน อีกครั้ง';
      if(this.id) uri = 'กรุณายืนยัน การแก้ไข ข้อร้องเรียน อีกครั้ง';
      Swal.fire({
        title: 'ยืนยันการบันทีก !',
        html: uri,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('file', this.$refs.file?.files[0]);
          formData.append('concealed', (values.concealed)? 1: 0);
          formData.append('fname', values.firstName);
          formData.append('lname', values.lastName);
          formData.append('idcard', values.idcard);
          formData.append('gender', values.sex);
          formData.append('work', values.work);
          formData.append('address', values.address);
          formData.append('phone', values.phone);
          formData.append('tel', values.tel);
          formData.append('province_id', values.province_id);
          formData.append('district_id', values.district_id);
          formData.append('subdistrict_id', values.subdistrict_id);
          formData.append('zipcode', values.zipcode);
          formData.append('unit_id', values.unit_id);
          formData.append('type_id', values.type_id);
          formData.append('sub_id', values.sub_id);
          formData.append('person_id', values.person_id);
          formData.append('name', values.name);
          formData.append('description', values.description);
          formData.append('improvement', values.improvement);
          formData.append('method_id', values.method_id);
          formData.append('id', this.id);
          this.isLoading = true;
          axios.post('/complaint/store', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          }) 
          .then(res => {
            if(res.data.status == 200){
              this.isLoading = false;
              Swal.fire({
                title: 'สำเร็จ !',
                html: 'บันทึก ข้อร้องเรียน เรียบร้อยแล้ว',
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง',
              }).then((result) => {
                if (result.isConfirmed) {
                  if(this.id){
                    this.backPage();
                  }else{
                    window.location = '/dashboard'
                  }
                }
              });
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
      });

    },
    removeData(){
      if(this.id){
        Swal.fire({
          title: 'ยืนยันการลบ !',
          html: "กรุณายืนยันการลบ ข้อร้องเรียน อีกครั้ง",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'ตกลง',
          cancelButtonText: 'ยกเลิก',
        }).then((result) => {
          if (result.isConfirmed) {

            this.isLoading = true;
            axios.delete('/admin/complaint',
              {
                params:{
                  id: this.id,
                }
              }
            )
            .then(res => {
              if(res.data.status == 200){
                this.isLoading = false;
                Swal.fire({
                  title: 'สำเร็จ !',
                  html: "ลบ ข้อร้องเรียน สำเร็จ",
                  icon: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'ตกลง',
                }).then((result) => {
                  if (result.isConfirmed) {
                    this.backPage();
                  }
                });
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
        });
      }
    },
    getMasterData(){
      this.isLoading = true;
      axios.get('/get/master/data') 
      .then(res => {
        if(res.data.status == 200){
          this.item_province = res.data.province;
          this.item_unit = res.data.unit;
          this.item_person = res.data.person;
          this.item_sub = res.data.sub;
          this.item_type = res.data.type;
          this.item_methods = res.data.methods;
          this.isLoading = false
        }else{
          this.isLoading = false
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
    formatThaiIdCard(event) {
      let raw = event.target.value.replace(/\D/g, '')
      if (raw.length > 13) raw = raw.slice(0, 13)

      const part1 = raw.slice(0, 1)
      const part2 = raw.slice(1, 5)
      const part3 = raw.slice(5, 10)
      const part4 = raw.slice(10, 12)
      const part5 = raw.slice(12, 13)

      const formatted = [part1, part2, part3, part4, part5].filter(Boolean).join('-')
      this.form.idcard = formatted
    },
    formatTel(event) {
      let raw = event.target.value.replace(/\D/g, '')
      if (raw.length > 10) raw = raw.slice(0, 10)

      const part1 = raw.slice(0, 2)
      const part2 = raw.slice(2, 6)
      const part3 = raw.slice(6, 10)

      const formatted = [part1, part2, part3].filter(Boolean).join('-')
      this.form.tel = formatted
    },
    formatPhone(event) {
      let raw = event.target.value.replace(/\D/g, '')
      if (raw.length > 10) raw = raw.slice(0, 10)

      const part1 = raw.slice(0, 3)
      const part2 = raw.slice(3, 6)
      const part3 = raw.slice(6, 10)

      const formatted = [part1, part2, part3].filter(Boolean).join('-')
      this.form.phone = formatted
    },
    selectType(){
      this.form.sub_id = '';
    },
    getDistrict(){
      if(!this.form.province_id){
        this.form.district_id = ''
        this.form.subdistrict_id = ''
        this.form.zipcode = ''
        return false;
      }
      this.isLoading = true;
      axios.get('/get/district/'+this.form.province_id) 
      .then(res => {
        if(res.data.status == 200){
          this.item_district = res.data.item
          this.isLoading = false;
          if(this.show.district_id){
            this.form.district_id = this.show.district_id
            this.getSubDistrict();
          }
        }else{
          this.item_district = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        this.item_district = [];
        console.error(err)
        this.isLoading = false;
      })
    },
    getSubDistrict(){
      if(!this.form.district_id){
        this.form.subdistrict_id = ''
        this.form.zipcode = ''
        return false;
      }
      this.isLoading = true;
      axios.get('/get/subdistrict/'+this.form.district_id) 
      .then(res => {
        if(res.data.status == 200){
          this.item_subdistrict = res.data.item
          this.isLoading = false;
          if(this.show.subdistrict_id) this.form.subdistrict_id = this.show.subdistrict_id
        }else{
          this.item_subdistrict = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        this.item_subdistrict = [];
        console.error(err)
        this.isLoading = false;
      })
    },
    getZipcode(){
      if(!this.form.subdistrict_id){
        this.form.zipcode = ''
        return false;
      }
      const zipcode = this.item_subdistrict.find(i => i.id === this.form.subdistrict_id);
      if(zipcode.zip_code != undefined){
        this.form.zipcode = zipcode.zip_code
      }
    },
    changeFile(){
      this.$refs.file.click();
    },
    slectFile(){
      const file = this.$refs.file?.files[0];
      this.file.name = file.name
      this.file.type = 'new'
    },
  }
}
</script>
<style scoped>
.custom-checkbox {
  width: 24px;
  height: 24px;
  appearance: none;
  border: 2px solid #ccc;
  border-radius: 4px;
  position: relative;
  cursor: pointer;
  transition: all 0.2s ease;
}

.custom-checkbox:checked {
  background-color: #de864f;
  border-color: #de864f;
}

.custom-checkbox:checked::after {
  content: '✔';
  color: white;
  font-size: 16px;
  position: absolute;
  top: 0px;
  left: 5px;
}
</style>