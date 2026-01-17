<template>
  <div class="py-6 px-2 md:px-7 bg-white my-5 mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md mt-[189px] mb-[80px]">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <div v-if="step == 1" class="text-center text-2xl text-[#3fbbc0]">ข้อตกลงหลักเกณฑ์ เรื่องร้องเรียน</div>
    
    <div v-if="step == 1" class="py-5 px-6">
      <div v-html="conditions"></div>

      <div class="text-center mt-6">
        <div class="flex flex-row gap-4">
          <div class="pl-11"><input type="checkbox" v-model="isChecked" class="custom-checkbox" /></div>
          <div @click="selApprove" class="hover:cursor-pointer pl-3 -mt-2 text-[14px]"> 
            <div> * ข้าพเจ้าขอรับรองว่าข้อเท็จจริงที่ได้ยื่นร้องเรียนต่อกรมอนามัยเป็นเรื่องที่เกิดขึ้นจริงทั้งหมดและขอรับผิดชอบต่อข้อเท็จจริงดังกล่าวข้างต้นทุกประการ</div>
            <div> * การนำความเท็จมาร้องเรียนต่อเจ้าหน้าที่ ซึ่งทำให้ผู้อื่นได้รับความเสียหายอาจเป็นความผิดฐานแจ้งความเท็จต่อเจ้าพนักงานตามประมวลกฎหมายอาญา</div>
          </div>
        </div>
      </div>
      <div class="text-center mt-3">
        <div @click="goToStep2()" class="inline-block px-4 py-2 text-white bg-[#1d684a] border border-[#1d684a] rounded-sm hover:cursor-pointer">ดำเนินการต่อไป</div>
      </div>
    </div>

    
    <template v-if="step == 2">
      <div class="text-center text-2xl text-[#3fbbc0]">ข้อมูลการร้องเรียน - ร้องทุกข์</div>
      <Form :initial-values="form" :validation-schema="schema" @submit="onSubmit">
        <div class="mt-5 mb-2 text-center text-white text-base bg-[#1d684a] rounded-sm py-3">ข้อมูลผู้ร้องเรียน</div>
        <div class="flex gap-2 flex-col">
          <div class="mt-5 pl-0 md:pl-8 lg:pl-[10%]">
            <input type="checkbox" v-model="form.concealed" class="custom-checkbox" />
              <span class="pl-3 -mt-2 text-[#FF7043]">ถ้าต้องการปกปิด ชื่อและข้อมูลส่วนตัว ให้คลิกที่นี่</span>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ชื่อ ผู้ร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="firstName" type="text" class="input" v-model="form.firstName" />
                <ErrorMessage name="firstName" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">นามสกุล ผู้ร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="lastName" type="text" class="input" v-model="form.lastName" />
                <ErrorMessage name="lastName" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อาชีพ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <!-- <input
                  type="text"
                  v-model="form.idcard"
                  @input="formatThaiIdCard"
                  class="input"
                  placeholder="_-____-______-__-_"
                />
                <Field name="idcard" v-model="form.idcard" type="hidden" />
                <ErrorMessage name="idcard" class="text-red-500 text-sm" /> -->
                <Field name="work" type="text" class="input" v-model="form.work" />
                <ErrorMessage name="work" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">เพศ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-6/12 lg:w-4/12">
                <Field as="select" name="sex" class="input" v-model="form.sex">
                  <option value="">-- กรุณาเลือก --</option>
                  <option value="1">ชาย</option>
                  <option value="2">หญิง</option>
                  <option value="3">LGBTQ+</option>
                </Field>
                <ErrorMessage name="sex" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <!-- <div class="flex flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อาชีพ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="work" type="text" class="input" v-model="form.work" />
                <ErrorMessage name="work" class="text-red-500 text-sm" />
              </div>
            </div>
          </div> -->
          <div class="flex flex-col gap-3">
            <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">ที่อยู่ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-10/12">
                <Field name="address" type="text" class="input" v-model="form.address" />
                <ErrorMessage name="address" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">จังหวัด <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="province_id" class="input" v-model="form.province_id" @change="getDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_province" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="province_id" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อำเภอ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="district_id" class="input" v-model="form.district_id" @change="getSubDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_district" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="district_id" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ตำบล <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="subdistrict_id" class="input" v-model="form.subdistrict_id" @change="getZipcode()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_subdistrict" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="subdistrict_id" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">รหัสไปรษณี <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="zipcode" type="text" class="input" v-model="form.zipcode" />
                <ErrorMessage name="zipcode" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">มือถือ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                 <input
                  type="text"
                  v-model="form.phone"
                  @input="formatPhone"
                  class="input"
                  placeholder="___-___-____"
                />
                <Field name="phone" type="hidden" v-model="form.phone" />
                <ErrorMessage name="phone" class="text-red-500 text-sm" />
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
              <div class="w-full md:w-4/12 text-left md:text-right">อีเมล <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="email" type="text" class="input" v-model="form.email" />
                <ErrorMessage name="email" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
        </div>
        <div class="mt-[44px] mb-2 text-center text-white text-base bg-[#1d684a] rounded-sm py-3">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
        <div class="flex gap-2 flex-col">
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
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ประเด็นการร้องเรียน <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="type_id" class="input" @change="selectType()" v-model="form.type_id">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_type" :value="item.id">{{ item.num }}. {{ item.name }}</option>
                </Field>
                <ErrorMessage name="type_id" class="text-red-500 text-sm" />
              </div>
            </div>
            <div v-if="form.type_id == 1 || form.type_id == 2" class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
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
            </div>
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
              <div class="w-full md:w-2/12 text-left md:text-right">สิ่งที่ต้องการให้แก้ไข ปรับปรุง <span class="text-[#ff0000]">*</span> :</div>
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
        <div class="flex justify-center mt-8">
          <button
            type="submit"
            class="inline-block mt-2 px-6 py-2 text-white bg-[#1d684a] border border-[#1d684a] rounded"
          >
          บันทึก
          </button>
        </div>
      </Form>
    </template>
    
    <template v-if="step == 3">
      <div class="text-center text-2xl text-[#3fbbc0]">กรมสุขภาพจิต<br/>ได้รับเรื่องร้องเรียนของท่านแล้ว</div>
      <div class="flex justify-center text-center my-11 flex-col">
        <div class="">
          <input v-model="code" type="text" class="text-lg inline-block border p-2 border-[#28a745] text-center" />
        </div>
        <div class="mt-4">
          กรุณา คัดลอก <span @click="copyToClipboard" class="hover:cursor-pointer text-[#dc3545]"><i class="far fa-copy"></i> รหัสร้องเรียนไว้เพื่อติดตาม การร้องเรียนของท่านได้</span>
        </div>
        <p v-if="copied" class="text-green-600 mt-2">คัดลอกเรียบร้อยแล้ว!</p>
        <div class="mt-1">
          ท่าน สามารถติดตามการร้องเรียนของท่านได้ที่ เมนู <a href="/complaint/follow">ติดตามเรื่องร้องเรียน</a>
        </div>
          

          
        <div class="mt-11">
          <a href="/" class="inline-block px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
        </div>
      </div>
    </template>

    <div
      v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative animate-fade-in-down ">
        <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
          <h2 class="text-lg text-[#3fbbc0]">ยืนยัน การร้องเรียน-ร้องทุกข์</h2>
          <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
        </div>
        <div class="overflow-y-auto max-h-[60vh]">
          <div class="flex justify-center bg-[#1d684a] text-white py-2">ข้อมูลเกี่ยวกับผู้ร้องเรียน</div>
          <div v-if="form.concealed" class="pl-11 my-3"><i class="far fa-window-close text-2xl"></i> ปกปิด เรื่องร้องเรียน <span class="text-red-500 text-sm">(หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียนจะไม่มีการรับทราบข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนแต่อย่างใด)</span></div>
          <div v-else class="pl-11 my-3"><i class="fas fa-check text-xl"></i> ไม่ปกปิด เรื่องร้องเรียน <span class="text-red-500 text-sm">(ข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนจะถูกเปิดเผยแก่หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียน)</span></div>

          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ชื่อ ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ form.firstName }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">นามสกุล ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ form.lastName }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">อาชีพ: <span class="text-[#6c757d] pl-2">{{ form.work }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">เพศ : 
              <span v-if="form.sex == 1" class="text-[#6c757d] pl-2">ชาย</span>
              <span v-if="form.sex == 2" class="text-[#6c757d] pl-2">หญิง</span>
              <span v-if="form.sex == 3" class="text-[#6c757d] pl-2">LGBTQ+</span>
            </div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll mt-2">ที่อยู่: <span class="text-[#6c757d] pl-2">{{ form.address }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ตำบล : <span class="text-[#6c757d] pl-2">{{ showTitle('subdistrict', form.subdistrict_id) }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">อำเภอ : <span class="text-[#6c757d] pl-2">{{ showTitle('district',form.district_id) }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">จังหวัด : <span class="text-[#6c757d] pl-2">{{ showTitle('province',form.province_id) }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">รหัสไปรษณี : <span class="text-[#6c757d] pl-2">{{ form.zipcode }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">เบอร์โทรศัพท์ : <span class="text-[#6c757d] pl-2">{{ form.tel }}</span></div>
            <div class="flex w-ufll md:w-1/2 mt-2">เบอร์มือถือ : <span class="text-[#6c757d] pl-2">{{ form.phone }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">email : <span class="text-[#6c757d] pl-2">{{ form.email }}</span></div>
          </div>

          <div class="flex justify-center bg-[#1d684a] text-white py-2 mt-5">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ร้องเรียนถึง : <span class="text-[#6c757d] pl-2">{{ showTitle('unit',form.unit_id) }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ประเด็นการร้องเรียน : <span class="text-[#6c757d] pl-2">{{ showTitle('type',form.type_id) }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">ร้องเรียนบุคคล : <span class="text-[#6c757d] pl-2">{{ showTitle('person',form.person_id) }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">เรื่องที่ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ form.name }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">สิ่งที่ต้องการให้แก้ไข ปรับปรุง : <span class="text-[#6c757d] pl-2">{{ form.description }}</span></div>
          </div>
          <div class="flex flex-col md:flex-row px-6 md:px-8">
            <div class="flex w-ufll md:w-1/2 mt-2">เอกสารประกอบ (ถ้ามี) : 
              <span v-if="file.name" class="text-[#6c757d] pl-2">{{ file.name }}</span>
              <span v-else class="text-[#6c757d] pl-2">ไม่มี</span>
            </div>
          </div>
          

        </div>
        <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-2">
          <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              แก้ไข
          </button>
          <button type="button" @click="sendData()" class="px-4 py-2 bg-[#28a745] text-white rounded ">
              ยืนยัน ส่งเรื่องร้องเรียน
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

import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import useClipboard from 'vue-clipboard3'

export default {
  name: 'ComplaintComponent',
  data() {
    return {
      isLoading: false,
      step:1,
      isChecked: false,
      showModal: false,
      code: '',
      copied: false,
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
      },
      schema: yup.object({
        firstName: yup.string().required('กรุณากรอกชื่อ'),
        lastName: yup.string().required('กรุณากรอกนามสกุล'),
        /*idcard: yup
          .string()
          .required('กรุณากรอกเลขบัตรประชาชน')
          .matches(/^\d{1}-\d{4}-\d{5}-\d{2}-\d{1}$/, 'รูปแบบไม่ถูกต้อง (เช่น 1-2345-67890-12-3)'),*/
        sex: yup.string().required('กรุณาเลือกเพศ'),
        work: yup.string().required('กรุณาเลือกอาชีพ'),
        address: yup.string().required('กรุณากรอกที่อยู่'),
        phone: yup
          .string()
          .required('กรุณากรอกเบอร์มือถือ')
          .matches(/^\d{3}-\d{3}-\d{4}$/, 'รูปแบบไม่ถูกต้อง (เช่น 090-123-4567)'),
        province_id: yup.string().required('กรุณาเลือก'),
        district_id: yup.string().required('กรุณาเลือก'),
        subdistrict_id: yup.string().required('กรุณาเลือก'),
        zipcode: yup.string().required('กรุณากรอก'),
        email: yup
          .string()
          .trim()
          .matches(
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            'กรุณากรอกอีเมลให้ถูกต้อง'
          )
          .required('กรุณากรอกอีเมล'),
        unit_id: yup.string().required('กรุณาเลือกหน่วยที่จะร้องเรียนถึง'),
        type_id: yup.string().required('กรุณาเลือกประเด็นการร้องเรียน'),
        person_id: yup.string().required('กรุณาเลือกร้องเรียนบุคคล'),
        name: yup.string().required('กรุณากรอกเรื่องที่ร้องเรียน'),
        improvement: yup.string().required('กรุณากรอกรายละเอีดยเพิ่มเติม'),
        description: yup.string().required('กรุณากรอกสิ่งที่ต้องการให้แก้ไข ปรับปรุง'),
        sub_id: yup.string().when('type_id', (type_id, schema) => {
          return type_id[0] == '1' || type_id[0] == '2'
            ? schema.required('กรุณาเลือกประเด็นย่อย')
            : schema.nullable();
        }), 
      }),
      input:[],
      file:{
        uri: '',
        name: '',
      },
      item_province: [],
      item_district: [],
      item_subdistrict: [],
      item_unit: [],
      item_type: [],
      item_sub: [],
      item_person: [],
      showEvaluation: false,
      item_question: [],
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
  props:['key_title','conditions','province','unit','sub','type','person'],
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
    useClipboard,
  },
  created(){
    this.item_province = JSON.parse(this.province)
    this.item_unit = JSON.parse(this.unit)
    this.item_type = JSON.parse(this.type)
    this.item_person = JSON.parse(this.person)
  },
  mounted() {
  },
  methods: {
    selApprove(){
      if(this.isChecked == false){
        this.isChecked = true;
      }else{
        this.isChecked = false;
      }
    },
    goToStep2(){
      if(!this.isChecked){
        Swal.fire({
          title: 'แจ้งเตือน!',
          text: 'กรุณา ยอมรับ ข้อตกลงหลักเกณฑ์ !',
          icon: 'warning',
          confirmButtonText: 'ตกลง'
        })
      }else{
        this.step = 2;
      }
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
          this.form.zipcode = ''
          this.form.subdistrict_id = ''
          this.isLoading = false;
        }else{
          this.item_district = [];
          this.form.zipcode = ''
          this.form.subdistrict_id = ''
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
          this.form.zipcode = ''
          this.isLoading = false;
        }else{
          this.item_subdistrict = [];
          this.form.zipcode = ''
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
    },
    showTitle(type, id){
      let title = '';
      if(type == 'province'){
        const found = this.item_province.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'district'){
        const found = this.item_district.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'subdistrict'){
        const found = this.item_subdistrict.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'unit'){
        const found = this.item_unit.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'type'){
        const found = this.item_type.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'sub'){
        const found = this.item_sub.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if(type == 'person'){
        const found = this.item_person.find(item => item.id === id);
        title = found ? found.name : '';
      }
      return title;
    },
    onSubmit(values) {
      this.showModal = true
    },
    sendData(){
      this.isLoading = true;

      const formData = new FormData();
      formData.append('file', this.$refs.file?.files[0]);
      formData.append('concealed', (this.form.concealed)? 1: 0);
      formData.append('fname', this.form.firstName);
      formData.append('lname', this.form.lastName);
      formData.append('idcard', this.form.idcard);
      formData.append('gender', this.form.sex);
      formData.append('work', this.form.work);
      formData.append('address', this.form.address);
      formData.append('phone', this.form.phone);
      formData.append('tel', this.form.tel);
      formData.append('province_id', this.form.province_id);
      formData.append('district_id', this.form.district_id);
      formData.append('subdistrict_id', this.form.subdistrict_id);
      formData.append('zipcode', this.form.zipcode);
      formData.append('unit_id', this.form.unit_id);
      formData.append('type_id', this.form.type_id);
      formData.append('sub_id', this.form.sub_id);
      formData.append('person_id', this.form.person_id);
      formData.append('name', this.form.name);
      formData.append('description', this.form.description);
      formData.append('improvement', this.form.improvement);
      formData.append('key_title', this.key_title);

      axios.post('/complaint', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.isLoading = false;
          this.showModal = false;
          this.code = res.data.code;
          Swal.fire({
            title: 'สำเร็จ !',
            html: 'ท่านได้ส่งคำร้องเรียน/ร้องทุกข์เรียบร้อยแล้ว<br/>กรุณาตอบแบบประเมินความพึงพอใจ <br/>เพื่อให้หน่วยงานนำข้อมูลไปใช้ในการพัฒนา<br/>และปรับปรุงการให้บริการ',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ตกลง',
          }).then((result) => {
            if (result.isConfirmed) {
              this.getQuestion();
              this.step++
            }else{
              this.getQuestion();
              this.step++
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
    },
    getQuestion(){
      this.isLoading = true;
      axios.get('/load/question') 
      .then(res => {
        if(res.data.status == 200){
          this.item_question = res.data.item
          this.isLoading = false;
          this.showEvaluation = true;
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
    async copyToClipboard() {
      const { toClipboard } = useClipboard()

      try {
        await toClipboard(this.code)
        this.copied = true

        setTimeout(() => {
          this.copied = false
        }, 2000)
      } catch (err) {
        console.error('คัดลอกไม่สำเร็จ:', err)
        alert('ไม่สามารถคัดลอกได้')
      }
    },
  },
    
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