<template>
  <div
    class="py-6 px-2 md:px-7  my-5 mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] mb-[80px] rounded-3xl border border-slate-100 bg-white shadow-soft">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'"
      :width="64" :height="64" />

    <div v-if="step == 1" class="text-center font-bold text-2xl text-brand-600">ข้อตกลงหลักเกณฑ์ เรื่องร้องเรียน</div>

    <div v-if="step == 1" class="py-5 px-6">
      <div v-html="conditions" class="leading-7"></div>

      <div class="text-center mt-6">
        <div class="flex flex-row gap-4">
          <div class="pl-11"><input type="checkbox" v-model="isChecked" class="custom-checkbox" /></div>
          <div @click="selApprove" class="hover:cursor-pointer pl-3 -mt-2 text-[14px] text-blue-400 font-bold">
            <div> *
              ข้าพเจ้าขอรับรองว่าข้อเท็จจริงที่ได้ยื่นร้องเรียนต่อกรมอนามัยเป็นเรื่องที่เกิดขึ้นจริงทั้งหมดและขอรับผิดชอบต่อข้อเท็จจริงดังกล่าวข้างต้นทุกประการ
            </div>
            <div> * การนำความเท็จมาร้องเรียนต่อเจ้าหน้าที่
              ซึ่งทำให้ผู้อื่นได้รับความเสียหายอาจเป็นความผิดฐานแจ้งความเท็จต่อเจ้าพนักงานตามประมวลกฎหมายอาญา</div>
          </div>
        </div>
      </div>
      <div class="text-center mt-5">
        <div @click="goToStep2()"
          class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-7 py-3.5 font-bold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700 hover:cursor-pointer">
          ดำเนินการต่อไป</div>
      </div>
    </div>


    <template v-if="step == 2">
      <div class="text-center font-bold text-2xl text-brand-600">ข้อมูลการร้องเรียน - ร้องทุกข์</div>
      <Form v-slot="{ errors }" :initial-values="form" :validation-schema="schema" @submit="onSubmit"
        @invalid-submit="onInvalidSubmit">
        <div class="blue-panel overflow-hidden rounded-md mt-5 mb-2 py-3 text-white shadow-soft text-center">
          ข้อมูลผู้ร้องเรียน</div>
        <div class="flex gap-2 flex-col">
          <div class="mt-5 pl-0 md:pl-8 lg:pl-[10%]">
            <input type="checkbox" v-model="form.concealed" class="custom-checkbox" />
            <span class="pl-3 -mt-2 text-[#FF7043]">ถ้าต้องการปกปิด ชื่อและข้อมูลส่วนตัว ให้คลิกที่นี่</span>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">ชื่อ ผู้ร้องเรียน <span
                  class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="firstName" type="text" :class="['input', { 'error-border': errors.firstName }]"
                  v-model="form.firstName" />
                <ErrorMessage name="firstName" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">นามสกุล ผู้ร้องเรียน <span
                  class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="lastName" type="text" :class="['input', { 'error-border': errors.lastName }]"
                  v-model="form.lastName" />
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
                <Field name="work" type="text" :class="['input', { 'error-border': errors.work }]"
                  v-model="form.work" />
                <ErrorMessage name="work" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">เพศ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-6/12 lg:w-4/12">
                <Field as="select" name="sex" :class="['input', { 'error-border': errors.sex }]" v-model="form.sex">
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
              <div class="w-full md:w-2/12 text-left md:text-right">ที่อยู่ <span class="text-[#ff0000]">*</span> :
              </div>
              <div class="w-full md:w-10/12">
                <Field name="address" type="text" :class="['input', { 'error-border': errors.address }]"
                  v-model="form.address" />
                <ErrorMessage name="address" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">จังหวัด <span class="text-[#ff0000]">*</span> :
              </div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="province_id" :class="['input', { 'error-border': errors.province_id }]"
                  v-model="form.province_id" @change="getDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_province" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="province_id" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">เขต / อำเภอ <span class="text-[#ff0000]">*</span> :
              </div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="district_id" :class="['input', { 'error-border': errors.district_id }]"
                  v-model="form.district_id" @change="getSubDistrict()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_district" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="district_id" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">แขวง / ตำบล <span class="text-[#ff0000]">*</span> :
              </div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="subdistrict_id" :class="['input', { 'error-border': errors.subdistrict_id }]"
                  v-model="form.subdistrict_id" @change="getZipcode()">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_subdistrict" :value="item.id">{{ item.name }}</option>
                </Field>
                <ErrorMessage name="subdistrict_id" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">รหัสไปรษณี <span class="text-[#ff0000]">*</span> :
              </div>
              <div class="w-full md:w-8/12">
                <Field name="zipcode" type="text" :class="['input', { 'error-border': errors.zipcode }]"
                  v-model="form.zipcode" />
                <ErrorMessage name="zipcode" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">มือถือ <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <input type="text" v-model="form.phone" @input="formatPhone"
                  :class="['input', { 'error-border': errors.phone }]" placeholder="___-___-____" />
                <Field name="phone" type="hidden" v-model="form.phone" />
                <ErrorMessage name="phone" class="text-red-500 text-sm" />
              </div>
            </div>
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">โทรศัพท์ (ถ้ามี) :</div>
              <div class="w-full md:w-8/12">
                <input type="text" v-model="form.tel" @input="formatTel" class="input" placeholder="__-____-____" />
                <Field name="tel" type="hidden" v-model="form.tel" />
              </div>
            </div>
          </div>
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-1/2 gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-4/12 text-left md:text-right">อีเมล <span class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="email" type="text" :class="['input', { 'error-border': errors.email }]"
                  v-model="form.email" />
                <ErrorMessage name="email" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
        </div>

        <div class="blue-panel overflow-hidden rounded-md mt-[44px] mb-2 py-3 text-white shadow-soft text-center">
          ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>

        <div class="flex gap-2 flex-col">
          <div class="flex md:flex-row flex-col gap-3">
            <div class="flex flex-col w-full md:flex-row md:w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">ประเด็นการร้องเรียน <span
                  class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="select" name="type_id" :class="['input', { 'error-border': errors.type_id }]"
                  @change="selectType()" v-model="form.type_id">
                  <option value="">-- กรุณาเลือก --</option>
                  <option v-for="(item) in item_type" :value="item.id">{{ item.num }}. {{ item.name }}</option>
                </Field>
                <ErrorMessage name="type_id" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">เรื่องที่ร้องเรียน <span
                  class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field name="name" type="text" :class="['input', { 'error-border': errors.name }]"
                  v-model="form.name" />
                <ErrorMessage name="name" class="text-red-500 text-sm" />
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">รายละเอียดเรื่องที่ร้องเรียน <span
                  class="text-[#ff0000]">*</span> :</div>
              <div class="w-full md:w-8/12">
                <Field as="textarea" name="improvement" rows="3"
                  class="form-control w-full rounded border p-2 outline-none transition-colors" :class="errors.improvement
                    ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500'
                    : 'border-[#ccc] focus:border-brand-600 focus:ring-1 focus:ring-brand-600'"
                  v-model="form.improvement" />
                <ErrorMessage name="improvement" class="mt-1 block text-sm text-red-500" />
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col md:flex-row w-full gap-2 items-center mt-4 md:mt-2">
              <div class="w-full md:w-2/12 text-left md:text-right">สิ่งที่ต้องการให้แก้ไข ปรับปรุง (ถ้ามี) :</div>
              <div class="w-full md:w-8/12">
                <Field as="textarea" name="description" rows="3"
                  class="form-control w-full border rounded p-2 border-[#ccc]" v-model="form.description" />
              </div>
            </div>
          </div>
          <div class="flex flex-col md:flex-row w-full gap-2 mt-4 md:mt-2">
            <div class="w-full md:w-2/12 text-left md:text-right">
              เอกสารประกอบ (ถ้ามี) :
            </div>

            <div class="w-full md:w-8/12">
              <button type="button" class="rounded border border-[#ccc] bg-[#e9ecef] px-4 py-2 hover:bg-gray-200"
                @click="$refs.attachmentInput.click()">
                เลือกไฟล์
              </button>

              <input ref="attachmentInput" type="file" multiple class="hidden"
                accept=".pdf,.mp4,.mov,.jpg,.jpeg,.png,.webp" @change="selectAttachments" />

              <div class="mt-2 text-xs text-[#6c757d]">
                วิดีโอไม่เกิน 1 ไฟล์, PDF ไม่เกิน 1 ไฟล์ และรูปภาพไม่เกิน
                10 รูป โดยแต่ละไฟล์ต้องมีขนาดไม่เกิน 10 MB
              </div>

              <!-- Video -->
              <div v-if="videoFile" class="mt-4">
                <div class="font-medium">วิดีโอ</div>

                <div class="mt-1 flex items-center justify-between rounded border p-2">
                  <span class="break-all text-blue-600">
                    {{ videoFile.name }} ({{ formatFileSize(videoFile.size) }})
                  </span>

                  <button type="button" class="ml-3 text-red-500" @click="removeVideo">
                    ลบ
                  </button>
                </div>
              </div>

              <!-- PDF -->
              <div v-if="pdfFile" class="mt-4">
                <div class="font-medium">เอกสาร PDF</div>

                <div class="mt-1 flex items-center justify-between rounded border p-2">
                  <span class="break-all text-blue-600">
                    {{ pdfFile.name }} ({{ formatFileSize(pdfFile.size) }})
                  </span>

                  <button type="button" class="ml-3 text-red-500" @click="removePdf">
                    ลบ
                  </button>
                </div>
              </div>

              <!-- Images -->
              <div v-if="imageFiles.length" class="mt-4">
                <div class="font-medium">
                  รูปภาพ ({{ imageFiles.length }}/10)
                </div>

                <div v-for="(image, index) in imageFiles" :key="fileKey(image)"
                  class="mt-1 flex items-center justify-between rounded border p-2">
                  <span class="break-all text-blue-600">
                    {{ index + 1 }}. {{ image.name }}
                    ({{ formatFileSize(image.size) }})
                  </span>

                  <button type="button" class="ml-3 text-red-500" @click="removeImage(index)">
                    ลบ
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-center mt-8">
          <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-7 py-3.5 font-bold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700 hover:cursor-pointer">
            บันทึก
          </button>
        </div>
      </Form>
    </template>

    <template v-if="step == 3">
      <div class="text-center font-bold text-2xl text-brand-600">ศูนย์รับข้อร้องเรียนและข้อชมเชย
        กรมอนามัย<br />ได้รับเรื่องร้องเรียนของท่านแล้ว</div>
      <div class="flex justify-center text-center my-11 flex-col">
        <div class="">
          <input v-model="code" type="text" class="text-lg inline-block border p-2 border-[#28a745] text-center" />
        </div>
        <div class="mt-4">
          กรุณา คัดลอก <span @click="copyToClipboard" class="hover:cursor-pointer text-[#dc3545]"><i
              class="far fa-copy"></i> รหัสร้องเรียนไว้เพื่อติดตาม การร้องเรียนของท่านได้</span>
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

    <div v-if="showModal" class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]">
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative animate-fade-in-down ">
        <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
          <h2 class="text-lg text-brand-600">
            {{ confirmationStep === 1 ? 'ยืนยันการร้องเรียน-ร้องทุกข์' : 'แบบประเมินความพึงพอใจ' }}
          </h2>
          <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
        </div>
        <div class="overflow-y-auto max-h-[60vh]">
          <template v-if="confirmationStep === 1">
            <div class="flex justify-center blue-panel overflow-hidden text-white py-2">ข้อมูลเกี่ยวกับผู้ร้องเรียน
            </div>
            <div v-if="form.concealed" class="pl-11 my-3"><i class="far fa-window-close text-2xl"></i> ปกปิด
              เรื่องร้องเรียน <span
                class="text-red-500 text-sm">(หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียนจะไม่มีการรับทราบข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนแต่อย่างใด)</span>
            </div>
            <div v-else class="pl-11 my-3"><i class="fas fa-check text-xl"></i> ไม่ปกปิด เรื่องร้องเรียน <span
                class="text-red-500 text-sm">(ข้อมูลเกี่ยวกับตัวตนของผู้ร้องเรียนจะถูกเปิดเผยแก่หน่วยงานที่รับผิดชอบและผู้ถูกร้องเรียน)</span>
            </div>

            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">ชื่อ ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{
                  form.firstName }}</span></div>
              <div class="flex w-ufll md:w-1/2 mt-2">นามสกุล ผู้ร้องเรียน : <span class="text-[#6c757d] pl-2">{{
                  form.lastName }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">อาชีพ: <span class="text-[#6c757d] pl-2">{{ form.work }}</span>
              </div>
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
              <div class="flex w-ufll md:w-1/2 mt-2">ตำบล : <span class="text-[#6c757d] pl-2">{{
                showTitle('subdistrict', form.subdistrict_id) }}</span></div>
              <div class="flex w-ufll md:w-1/2 mt-2">อำเภอ : <span class="text-[#6c757d] pl-2">{{
                showTitle('district', form.district_id) }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">จังหวัด : <span class="text-[#6c757d] pl-2">{{
                showTitle('province', form.province_id) }}</span></div>
              <div class="flex w-ufll md:w-1/2 mt-2">รหัสไปรษณี : <span class="text-[#6c757d] pl-2">{{ form.zipcode
                  }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">เบอร์โทรศัพท์ : <span class="text-[#6c757d] pl-2">{{ form.tel
                  }}</span></div>
              <div class="flex w-ufll md:w-1/2 mt-2">เบอร์มือถือ : <span class="text-[#6c757d] pl-2">{{ form.phone
                  }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">email : <span class="text-[#6c757d] pl-2">{{ form.email }}</span>
              </div>
            </div>

            <div class="flex justify-center blue-panel overflow-hidden text-white py-2 mt-5">
              ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">ประเด็นการร้องเรียน : <span class="text-[#6c757d] pl-2">{{
                showTitle('type',form.type_id) }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">เรื่องที่ร้องเรียน : <span class="text-[#6c757d] pl-2">{{ form.name
                  }}</span></div>
            </div>
            <div class="flex flex-col md:flex-row px-6 md:px-8">
              <div class="flex w-ufll md:w-1/2 mt-2">สิ่งที่ต้องการให้แก้ไข ปรับปรุง : <span
                  class="text-[#6c757d] pl-2">{{ form.description }}</span></div>
            </div>
            <div class="px-6 md:px-8">
              <div class="mt-4 font-medium">
                เอกสารประกอบ (ถ้ามี)
              </div>

              <div v-if="!videoFile && !pdfFile && imageFiles.length === 0" class="mt-2 text-[#6c757d]">
                ไม่มีไฟล์แนบ
              </div>

              <!-- Preview วิดีโอ -->
              <div v-if="videoFile" class="mt-4">
                <div class="mb-2 font-medium text-[#1d684a]">
                  วิดีโอ
                </div>

                <div class="max-w-2xl rounded-lg border p-3">
                  <video v-if="videoPreviewUrl" :src="videoPreviewUrl" controls preload="metadata"
                    class="max-h-[400px] w-full rounded bg-black">
                    เบราว์เซอร์ของคุณไม่รองรับการแสดงวิดีโอ
                  </video>

                  <div class="mt-2 break-all text-sm text-[#6c757d]">
                    {{ videoFile.name }}
                    ({{ formatFileSize(videoFile.size) }})
                  </div>
                </div>
              </div>

              <!-- Preview PDF -->
              <div v-if="pdfFile" class="mt-4">
                <div class="mb-2 font-medium text-[#1d684a]">
                  เอกสาร PDF
                </div>

                <div class="max-w-4xl rounded-lg border p-3">
                  <iframe v-if="pdfPreviewUrl" :src="pdfPreviewUrl" title="ตัวอย่างเอกสาร PDF"
                    class="h-[500px] w-full rounded border"></iframe>

                  <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                    <span class="break-all text-sm text-[#6c757d]">
                      {{ pdfFile.name }}
                      ({{ formatFileSize(pdfFile.size) }})
                    </span>

                    <a v-if="pdfPreviewUrl" :href="pdfPreviewUrl" target="_blank" rel="noopener noreferrer"
                      class="text-sm text-blue-600 underline">
                      เปิด PDF ในหน้าต่างใหม่
                    </a>
                  </div>
                </div>
              </div>

              <!-- Preview รูปภาพ -->
              <div v-if="imagePreviews.length" class="mt-4">
                <div class="mb-2 font-medium text-[#1d684a]">
                  รูปภาพ ({{ imagePreviews.length }} รูป)
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                  <div v-for="(preview, index) in imagePreviews" :key="preview.key"
                    class="overflow-hidden rounded-lg border bg-white p-2">
                    <a :href="preview.url" target="_blank" rel="noopener noreferrer">
                      <img :src="preview.url" :alt="preview.file.name" class="h-40 w-full rounded object-cover" />
                    </a>

                    <div class="mt-2 truncate text-sm text-[#6c757d]">
                      {{ index + 1 }}. {{ preview.file.name }}
                    </div>

                    <div class="text-xs text-[#6c757d]">
                      {{ formatFileSize(preview.file.size) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <Form v-if="confirmationStep === 2" id="evaluationForm" v-slot="{ errors: evaluationErrors }"
            :initial-values="formEvaluation" :validation-schema="schemaEvaluation"
            @submit="submitComplaintWithEvaluation">
            <p class="px-6 py-4 text-xs text-red-500">
              * กรุณากรอกแบบประเมินความพึงพอใจ เพื่อนำผลไปใช้ปรับปรุงการให้บริการ
            </p>
            <div class="flex justify-center blue-panel overflow-hidden text-white py-2">ส่วนที่ 1 ข้อมูลของผู้ใช้บริการ
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-3/12">1. เพศ</div>
                <div class="w-full md:w-9/12">
                  <div class="flex flex-row justify-between">
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="1" /> ชาย
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="2" /> หญิง
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="3" /> LGBTQ+
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
                  <Field name="age" type="text" :class="['input', { 'error-border': evaluationErrors.age }]"
                    v-model="formEvaluation.age" />
                  <ErrorMessage name="age" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12">3. ระดับการศึกษา</div>
                <div class="w-full md:w-4/12">
                  <Field as="select" name="qualification"
                    :class="['input', { 'error-border': evaluationErrors.qualification }]"
                    v-model="formEvaluation.qualification">
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
                <div class="w-full md:w-4/12">
                  <Field as="select" name="work" :class="['input', { 'error-border': evaluationErrors.work }]"
                    v-model="formEvaluation.work">
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
                  <Field name="workDis" type="text" :class="['input', { 'error-border': evaluationErrors.workDis }]"
                    v-model="formEvaluation.workDis" />
                  <ErrorMessage name="workDis" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="flex justify-center blue-panel overflow-hidden text-white py-2 mt-4">ส่วนที่ 2
              ความพึงพอใจของผู้ใช้บริการ</div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1" v-for="(item, i) in item_question">
              <div class="flex flex-col">
                <div class="w-full">{{ i + 1 }}. {{ item.name }}</div>
                <div class="w-full mt-1">
                  <div class="grid grid-cols-2 sm:grid-cols-3 justify-items-stretch">
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel' + item.id" :id="'sel' + item.id + '_1'"
                        v-model="item.sel" value="1" :checked="item.checked_1">
                      <label class="hover:cursor-pointer" :for="'sel' + item.id + '_1'">
                        ไม่พึงพอใจ
                      </label>
                    </div>
                    <!-- <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel'+item.id" :id="'sel'+item.id+'_2'" v-model="item.sel" value="2" :checked="item.checked_2">
                      <label class="hover:cursor-pointer" :for="'sel'+item.id+'_2'">
                        พึงพอใจ
                      </label>
                    </div> -->
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel' + item.id" :id="'sel' + item.id + '_3'"
                        v-model="item.sel" value="3" :checked="item.checked_3">
                      <label class="hover:cursor-pointer" :for="'sel' + item.id + '_3'">
                        พึงพอใจมาก
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Form>
        </div>
        <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-2">
          <template v-if="confirmationStep === 1">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              กลับไปแก้ไข
            </button>
            <button type="button" :disabled="isLoading" @click="goToEvaluationStep"
              class="px-4 py-2 bg-brand-600 text-white rounded ">
              ถัดไป: ทำแบบประเมิน
            </button>
          </template>
          <template v-else>
            <button type="button" :disabled="isLoading" @click="confirmationStep = 1"
              class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ย้อนกลับ
            </button>
            <button type="submit" form="evaluationForm" class="px-4 py-2 bg-[#28a745] text-white rounded ">
              {{ isLoading ? 'กำลังส่งข้อมูล...' : 'ส่งเรื่องร้องเรียน' }}
            </button>
          </template>
        </div>
      </div>
    </div>

    <div v-if="showEvaluation"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]">
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <Form v-slot="{ errors: evaluationErrors }" :initial-values="formEvaluation"
          :validation-schema="schemaEvaluation" @submit="sendEvaluation">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#3fbbc0]">แบบประเมินความพึงพอใจ สำหรับผู้ใช้บริการ</h2>
            <i @click="showEvaluation = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">
            <div class="px-6 md:px-8 mt-2 flex flex-col text-xs text-[#dc3545]">*กรุณากรอกแบบประเมินความพึงพอใจ
              เพื่อนำผลไปใช้ในการปรับปรุงการให้บริการ จึงขอความร่วมมือจากทุกท่านที่ใช้งานระบบรับเรื่องร้องเรียน
              กรมสุขภาพจิต
            </div>
            <div class="mx-6 px-3 py-2 mt-4 flex flex-col bg-[#1d684a] text-white">ส่วนที่ 1 ข้อมูลของผู้ใช้บริการ</div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-3/12">1. เพศ</div>
                <div class="w-full md:w-9/12">
                  <div class="flex flex-row justify-between">
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="1" /> ชาย
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="2" /> หญิง
                    </label>
                    <label class="flex items-center gap-2">
                      <Field type="radio" name="gender" :class="{ 'error-radio': evaluationErrors.gender }"
                        v-model="formEvaluation.gender" value="3" /> LGBTQ+
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
                  <Field name="age" type="text" :class="['input', { 'error-border': evaluationErrors.age }]"
                    v-model="formEvaluation.age" />
                  <ErrorMessage name="age" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-3/12">3. ระดับการศึกษา</div>
                <div class="w-full md:w-9/12">
                  <Field as="select" name="qualification"
                    :class="['input', { 'error-border': evaluationErrors.qualification }]"
                    v-model="formEvaluation.qualification">
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
                  <Field as="select" name="work" :class="['input', { 'error-border': evaluationErrors.work }]"
                    v-model="formEvaluation.work">
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
                  <Field name="workDis" type="text" :class="['input', { 'error-border': evaluationErrors.workDis }]"
                    v-model="formEvaluation.workDis" />
                  <ErrorMessage name="workDis" class="text-red-500 text-sm" />
                </div>
              </div>
            </div>
            <div class="mx-6 px-3 py-2 mt-6 flex flex-col bg-[#1d684a] text-white">ส่วนที่ 2 ความพึงพอใจของผู้ใช้บริการ
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1" v-for="(item, i) in item_question">
              <div class="flex flex-col">
                <div class="w-full">{{ i + 1 }}. {{ item.name }}</div>
                <div class="w-full mt-1">
                  <div class="grid grid-cols-2 sm:grid-cols-3 justify-items-stretch">
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel' + item.id" :id="'sel' + item.id + '_1'"
                        v-model="item.sel" value="1" :checked="item.checked_1">
                      <label class="hover:cursor-pointer" :for="'sel' + item.id + '_1'">
                        ไม่พึงพอใจ
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel' + item.id" :id="'sel' + item.id + '_2'"
                        v-model="item.sel" value="2" :checked="item.checked_2">
                      <label class="hover:cursor-pointer" :for="'sel' + item.id + '_2'">
                        พึงพอใจ
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="hover:cursor-pointer" type="radio" :name="'sel' + item.id" :id="'sel' + item.id + '_3'"
                        v-model="item.sel" value="3" :checked="item.checked_3">
                      <label class="hover:cursor-pointer" :for="'sel' + item.id + '_3'">
                        พึงพอใจมาก
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-2">
            <button type="button" @click="showEvaluation = false"
              class="px-4 py-2 bg-[#6c757d] text-white rounded border">
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
      step: 1,
      confirmationStep: 1,
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
        type_id: yup.string().required('กรุณาเลือกประเด็นการร้องเรียน'),
        name: yup.string().required('กรุณากรอกเรื่องที่ร้องเรียน'),
        improvement: yup.string().required('กรุณากรอกรายละเอีดยเพิ่มเติม'),
      }),
      input: [],
      file: {
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
      }),
      videoFile: null,
      pdfFile: null,
      imageFiles: [],
      videoPreviewUrl: '',
      pdfPreviewUrl: '',
      imagePreviews: [],
      maxFileSize: 10 * 1024 * 1024,
    }
  },
  props: ['key_title', 'conditions', 'province', 'unit', 'sub', 'type', 'person', 'sel_id'],
  components: {
    Form,
    Field,
    ErrorMessage,
    Swal,
    useClipboard,
  },
  created() {
    this.item_province = JSON.parse(this.province)
    this.item_unit = JSON.parse(this.unit)
    this.item_type = JSON.parse(this.type)
    this.item_person = JSON.parse(this.person)

  },
  mounted() {
    if (this.sel_id) {
      this.form.type_id = this.sel_id;
    }
  },
  methods: {
    selApprove() {
      if (this.isChecked == false) {
        this.isChecked = true;
      } else {
        this.isChecked = false;
      }
    },
    goToStep2() {
      if (!this.isChecked) {
        Swal.fire({
          title: 'แจ้งเตือน!',
          text: 'กรุณา ยอมรับ ข้อตกลงหลักเกณฑ์ !',
          icon: 'warning',
          confirmButtonText: 'ตกลง'
        })
      } else {
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
    selectType() {
      this.form.sub_id = '';
    },
    getDistrict() {
      if (!this.form.province_id) {
        this.form.district_id = ''
        this.form.subdistrict_id = ''
        this.form.zipcode = ''
        return false;
      }
      this.isLoading = true;
      axios.get('/get/district/' + this.form.province_id)
        .then(res => {
          if (res.data.status == 200) {
            this.item_district = res.data.item
            this.form.zipcode = ''
            this.form.subdistrict_id = ''
            this.isLoading = false;
          } else {
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
    getSubDistrict() {
      if (!this.form.district_id) {
        this.form.subdistrict_id = ''
        this.form.zipcode = ''
        return false;
      }
      this.isLoading = true;
      axios.get('/get/subdistrict/' + this.form.district_id)
        .then(res => {
          if (res.data.status == 200) {
            this.item_subdistrict = res.data.item
            this.form.zipcode = ''
            this.isLoading = false;
          } else {
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
    getZipcode() {
      if (!this.form.subdistrict_id) {
        this.form.zipcode = ''
        return false;
      }

      const zipcode = this.item_subdistrict.find(i => i.id === this.form.subdistrict_id);
      if (zipcode.zip_code != undefined) {
        this.form.zipcode = zipcode.zip_code
      }
    },
    changeFile() {
      this.$refs.file.click();
    },
    slectFile() {
      const file = this.$refs.file?.files[0];
      this.file.name = file.name
    },
    showTitle(type, id) {
      let title = '';
      if (type == 'province') {
        const found = this.item_province.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'district') {
        const found = this.item_district.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'subdistrict') {
        const found = this.item_subdistrict.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'unit') {
        const found = this.item_unit.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'type') {
        const found = this.item_type.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'sub') {
        const found = this.item_sub.find(item => item.id === id);
        title = found ? found.name : '';
      }
      if (type == 'person') {
        const found = this.item_person.find(item => item.id === id);
        title = found ? found.name : '';
      }
      return title;
    },
    onInvalidSubmit() {
      Swal.fire({
        title: 'แจ้งเตือน!',
        text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
        icon: 'warning',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: 'rgb(10, 120, 211)',
      })
    },
    onSubmit(values) {
      this.showModal = true
    },
    showUploadError(message) {
      Swal.fire({
        title: 'ไฟล์ไม่ถูกต้อง',
        text: message,
        icon: 'warning',
        confirmButtonText: 'ตกลง',
      })
    },

    formatFileSize(bytes) {
      return `${(bytes / (1024 * 1024)).toFixed(2)} MB`
    },

    fileKey(file) {
      return `${file.name}-${file.size}-${file.lastModified}`
    },

    getFileExtension(file) {
      return file.name.split('.').pop()?.toLowerCase() || ''
    },

    selectAttachments(event) {
      const selectedFiles = Array.from(event.target.files || [])

      // ล้าง input เพื่อให้สามารถเลือกไฟล์เดิมซ้ำได้
      event.target.value = ''

      if (!selectedFiles.length) {
        return
      }

      // ใช้ค่าชั่วคราว เพื่อไม่แก้ข้อมูลเดิมถ้ามีไฟล์ใดไม่ผ่าน
      let nextVideo = this.videoFile
      let nextPdf = this.pdfFile
      const nextImages = [...this.imageFiles]

      const videoExtensions = ['mp4', 'mov']
      const imageExtensions = ['jpg', 'jpeg', 'png', 'webp']

      for (const file of selectedFiles) {
        const extension = this.getFileExtension(file)

        if (file.size > this.maxFileSize) {
          this.showUploadError(
            `ไฟล์ ${file.name} มีขนาดเกิน 10 MB`
          )
          return
        }

        if (videoExtensions.includes(extension)) {
          if (nextVideo) {
            this.showUploadError('สามารถเลือกวิดีโอได้ไม่เกิน 1 ไฟล์')
            return
          }

          nextVideo = file
          continue
        }

        if (extension === 'pdf') {
          if (nextPdf) {
            this.showUploadError('สามารถเลือกไฟล์ PDF ได้ไม่เกิน 1 ไฟล์')
            return
          }

          nextPdf = file
          continue
        }

        if (imageExtensions.includes(extension)) {
          const duplicate = nextImages.some(
            (image) => this.fileKey(image) === this.fileKey(file)
          )

          if (!duplicate) {
            nextImages.push(file)
          }

          continue
        }

        this.showUploadError(
          `ไม่รองรับไฟล์ ${file.name} กรุณาเลือกวิดีโอ, PDF หรือรูปภาพ`
        )
        return
      }

      if (nextImages.length > 10) {
        this.showUploadError('สามารถเลือกรูปภาพได้ไม่เกิน 10 รูป')
        return
      }

      this.videoFile = nextVideo
      this.pdfFile = nextPdf
      this.imageFiles = nextImages

      this.createAttachmentPreviews()
    },

    appendAttachments(formData) {
  if (
    this.videoFile instanceof File &&
    this.videoFile.size > 0
  ) {
    formData.append(
      'attachments[]',
      this.videoFile
    )
  }

  if (
    this.pdfFile instanceof File &&
    this.pdfFile.size > 0
  ) {
    formData.append(
      'attachments[]',
      this.pdfFile
    )
  }

  this.imageFiles.forEach((file) => {
    if (file instanceof File && file.size > 0) {
      formData.append(
        'attachments[]',
        file
      )
    }
  })
},

    removeVideo() {
      this.videoFile = null
      this.createAttachmentPreviews()
    },

    removePdf() {
      this.pdfFile = null
      this.createAttachmentPreviews()
    },

    removeImage(index) {
      this.imageFiles.splice(index, 1)
      this.createAttachmentPreviews()
    },
    createAttachmentPreviews() {
      this.clearAttachmentPreviews()

      if (this.videoFile) {
        this.videoPreviewUrl = URL.createObjectURL(this.videoFile)
      }

      if (this.pdfFile) {
        this.pdfPreviewUrl = URL.createObjectURL(this.pdfFile)
      }

      this.imagePreviews = this.imageFiles.map((file) => ({
        file,
        url: URL.createObjectURL(file),
        key: `${file.name}-${file.size}-${file.lastModified}`,
      }))
    },

    clearAttachmentPreviews() {
      if (this.videoPreviewUrl) {
        URL.revokeObjectURL(this.videoPreviewUrl)
      }

      if (this.pdfPreviewUrl) {
        URL.revokeObjectURL(this.pdfPreviewUrl)
      }

      this.imagePreviews.forEach((preview) => {
        URL.revokeObjectURL(preview.url)
      })

      this.videoPreviewUrl = ''
      this.pdfPreviewUrl = ''
      this.imagePreviews = []
    },
    async goToEvaluationStep() {
      this.isLoading = true
      try {
        if (!this.item_question.length) {
          const { data } = await axios.get('/load/question')
          if (data.status !== 200) throw new Error()
          this.item_question = data.item
        }
        this.confirmationStep = 2
      } catch { Swal.fire('ผิดพลาด', 'ไม่สามารถโหลดแบบประเมินได้', 'error') }
      finally { this.isLoading = false }
    },
    sendData() {
      this.isLoading = true

      const formData = new FormData()

      this.appendAttachments(formData)

      formData.append('concealed', this.form.concealed ? 1 : 0)
      formData.append('fname', this.form.firstName)
      formData.append('lname', this.form.lastName)
      formData.append('idcard', this.form.idcard || '')
      formData.append('gender', this.form.sex)
      formData.append('work', this.form.work)
      formData.append('address', this.form.address)
      formData.append('phone', this.form.phone)
      formData.append('tel', this.form.tel || '')
      formData.append('email', this.form.email || '')
      formData.append('province_id', this.form.province_id)
      formData.append('district_id', this.form.district_id)
      formData.append('subdistrict_id', this.form.subdistrict_id)
      formData.append('zipcode', this.form.zipcode)
      formData.append('type_id', this.form.type_id)
      formData.append('name', this.form.name)
      formData.append('description', this.form.description || '')
      formData.append('improvement', this.form.improvement)
      formData.append('key_title', this.key_title)

      axios.post('/complaint', formData, {
        headers: {
          Accept: 'application/json',
        },
      })
        .then(({ data }) => {
          if (data.status !== 200) {
            throw new Error(data.message || 'ไม่สามารถบันทึกข้อมูลได้')
          }

          this.showModal = false
          this.code = data.code
          this.step = 3

          Swal.fire({
            title: 'สำเร็จ!',
            text: 'ส่งเรื่องร้องเรียนเรียบร้อยแล้ว',
            icon: 'success',
            confirmButtonText: 'ตกลง',
          })
        })
        .catch((error) => {
          const message =
            error.response?.data?.message ||
            error.message ||
            'ไม่สามารถทำรายการได้'

          Swal.fire({
            title: 'ผิดพลาด!',
            text: message,
            icon: 'error',
            confirmButtonText: 'ตกลง',
          })
        })
        .finally(() => {
          this.isLoading = false
        })
    },
    async submitComplaintWithEvaluation(values) {
  /*
   * ตรวจสอบว่าตอบคำถามครบหรือยัง
   */
  const unanswered = this.item_question.find(
    (item) =>
      item.sel === null ||
      item.sel === undefined ||
      item.sel === ''
  )

  if (unanswered) {
    await Swal.fire({
      title: 'กรุณาตอบแบบประเมินให้ครบ',
      text: unanswered.name,
      icon: 'warning',
      confirmButtonText: 'ตกลง',
    })

    return
  }

  this.isLoading = true

  try {
    const formData = new FormData()

    /*
     * ข้อมูลผู้ร้องเรียน
     */
    const complaintData = {
      concealed: this.form.concealed ? 1 : 0,
      fname: this.form.firstName,
      lname: this.form.lastName,
      idcard: this.form.idcard,
      gender: this.form.sex,
      work: this.form.work,
      address: this.form.address,
      phone: this.form.phone,
      tel: this.form.tel,
      email: this.form.email,
      province_id: this.form.province_id,
      district_id: this.form.district_id,
      subdistrict_id: this.form.subdistrict_id,
      zipcode: this.form.zipcode,
      unit_id: this.form.unit_id,
      type_id: this.form.type_id,
      sub_id: this.form.sub_id,
      person_id: this.form.person_id,
      name: this.form.name,
      description: this.form.description,
      improvement: this.form.improvement,
      key_title: this.key_title,
    }

    Object.entries(complaintData).forEach(
      ([key, value]) => {
        formData.append(key, value ?? '')
      }
    )

    /*
     * สำคัญ: ส่งไฟล์ทั้งหมดด้วย attachments[]
     */
    this.appendAttachments(formData)

    /*
     * ข้อมูลแบบประเมิน
     */
    formData.append(
      'questions',
      JSON.stringify(
        this.item_question.map((item) => ({
          id: item.id,
          sel: Number(item.sel),
        }))
      )
    )

    formData.append(
      'eva_gender',
      this.formEvaluation.gender ?? ''
    )

    formData.append(
      'eva_age',
      this.formEvaluation.age ?? ''
    )

    formData.append(
      'eva_qualification',
      this.formEvaluation.qualification ?? ''
    )

    formData.append(
      'eva_work',
      this.formEvaluation.work ?? ''
    )

    formData.append(
      'eva_workDis',
      this.formEvaluation.workDis ?? ''
    )

    /*
     * ใช้ตรวจสอบระหว่างพัฒนา
     */
    for (const [key, value] of formData.entries()) {
      if (value instanceof File) {
        console.log(key, {
          name: value.name,
          type: value.type,
          size: value.size,
        })
      } else {
        console.log(key, value)
      }
    }

    /*
     * ไม่ต้องกำหนด Content-Type เอง
     * Axios จะสร้าง multipart boundary ให้อัตโนมัติ
     */
    const response = await axios.post(
      '/complaint',
      formData,
      {
        headers: {
          Accept: 'application/json',
        },
      }
    )

    const data = response.data

    if (data.status !== 200) {
      throw new Error(
        data.message || 'ไม่สามารถบันทึกข้อมูลได้'
      )
    }

    /*
     * ปิด Loading ก่อนแสดง SweetAlert
     */
    this.isLoading = false
    this.showModal = false
    this.code = data.code
    this.step = 3

    await Swal.fire({
      title: 'สำเร็จ!',
      html: `
        ส่งเรื่องร้องเรียนเรียบร้อยแล้ว<br>
        เลขที่ร้องเรียน:
        <strong>${data.code}</strong>
      `,
      icon: 'success',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'ตกลง',
    })

    /*
     * ล้าง Preview หลังบันทึกสำเร็จ
     */
    this.clearAttachmentPreviews()
  } catch (error) {
    console.error(error)

    const validationErrors =
      error.response?.data?.errors

    let message =
      error.response?.data?.message ||
      error.message ||
      'ไม่สามารถบันทึกข้อมูลได้'

    if (validationErrors) {
      message =
        Object.values(validationErrors).flat()[0]
        || message
    }

    await Swal.fire({
      title: 'ผิดพลาด!',
      text: message,
      icon: 'error',
      confirmButtonText: 'ตกลง',
    })
  } finally {
    this.isLoading = false
  }
},
    getQuestion() {
      this.isLoading = true;
      axios.get('/load/question')
        .then(res => {
          if (res.data.status == 200) {
            this.item_question = res.data.item
            this.isLoading = false;
            this.showEvaluation = true;
          } else {
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
    sendEvaluation(values) {
      this.isLoading = true;
      axios.post('/question', {
        data: values,
        question: this.item_question,
      })
        .then(res => {
          if (res.data.status == 200) {
            this.isLoading = false;
            this.showEvaluation = false;
            Swal.fire({ title: 'สำเร็จ !', html: 'ส่งแบบประเมินความพึงพอใจ สำเร็จ! <br/>ขอบคุณที่ ทำแบบประเมินความพีงพอใจ', icon: 'success', confirmButtonText: 'ตกลง' });
            this.formEvaluation.gender = '';
            this.formEvaluation.age = '';
            this.formEvaluation.qualification = '';
            this.formEvaluation.work = '';
            this.formEvaluation.workDis = '';
          } else {
            this.isLoading = false;
            Swal.fire({ title: 'ผิดพลาด !', text: 'ไม่สามารถทำรายการได้ !', icon: 'error', confirmButtonText: 'ตกลง' });
          }
        })
        .catch(err => {
          console.error(err)
          this.isLoading = false;
          Swal.fire({ title: 'ผิดพลาด !', text: 'ไม่สามารถทำรายการได้ !', icon: 'error', confirmButtonText: 'ตกลง' });
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
  beforeUnmount() {
    this.clearAttachmentPreviews()
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
  background-color: rgb(8, 118, 209);
  border-color: rgb(8, 118, 209);
}

.custom-checkbox:checked::after {
  content: '✔';
  color: white;
  font-size: 16px;
  position: absolute;
  top: 0px;
  left: 5px;
}

.error-border {
  border-color: #ef4444 !important;
  box-shadow: 0 0 0 1px #ef4444;
}

.error-border:focus {
  border-color: #ef4444 !important;
  box-shadow: 0 0 0 1px #ef4444;
  outline: none;
}

.error-radio {
  accent-color: #ef4444;
  outline: 1px solid #ef4444;
  outline-offset: 1px;
}
</style>
