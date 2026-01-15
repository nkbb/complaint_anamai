<template>
  <div class="px-6 mt-4 w-full">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />
    <div class="flex flex-col md:flex-row md:gap-6 gap-2 ml-0 md:ml-4">
      <div class="flex flex-row gap-3">
        <div>รหัสเรื่องร้องเรียน :</div>
        <div>{{ item?.code }}</div>
      </div>
      <div class="flex flex-row gap-3">
         <div>สถานะ :</div>
        <div>
            <span v-if="item?.type == 0" class="results-status type-1 bg-red-600">ยุติเรื่อง-ไม่ใช่</span>
            <span v-if="item?.type == 2" class="results-status type-1 bg-orange-600">รอศูนย์รับเรื่อง</span>
            <span v-if="item?.type == 3 && user_level == 'root'" class="results-status type-2 bg-yellow-600">ศูนย์รับเรื่อง - รอหน่วยรับเรื่อง</span>
            <span v-if="item?.type == 3 && user_level == 'unit'" class="results-status type-2 bg-yellow-600">รอรับเรื่อง</span>
            <span v-if="item?.type == 4 && user_level == 'root'" class="results-status type-3 bg-blue-400">หน่วย รับเรื่อง - กำลังดำเนินการ</span>
            <span v-if="item?.type == 4 && user_level == 'unit'" class="results-status type-3 bg-blue-800">รับเรื่องแล้ว - กำลังดำเนินการ</span>
            <span v-if="item?.type == 5" class="results-status type-4 bg-green-600">หน่วย ยุติเรื่อง</span>
            <span v-if="item?.type == 6 && user_level == 'root'" class="results-status type-4 bg-red-400">ส่งกลับให้หน่วย ดำเนินการแก้ไข</span>
            <span v-if="item?.type == 6 && user_level == 'unit'" class="results-status type-4 bg-red-400">ศูนย์ส่งกับ - ให้แก้ไข</span>
            <span v-if="item?.type == 7" class="results-status type-5 bg-green-600">ศูนย์ยุติเรื่อง</span>
            <span v-if="item?.type == 8" class="results-status type-6 bg-green-800">เสร็จสิ้น</span>
        </div>
      </div>
    </div>
    <div class="flex mt-2 flex-col md:flex-row md:gap-6 gap-2 ml-0 md:ml-4">
      <div class="text-sm text-gray-500 mt-1 flex gap-2 md:flex-row flex-col">
        <div class="flex flex-row gap-2">
          <i class="fas fa-clock"></i>
          <div v-if="item?.created_at">{{ formatThaiDate(item?.created_at) }}</div>
        </div>
        <div class="flex flex-row gap-2">
          <i class="far fa-paper-plane"></i>
          <div v-if="item?.user_add">{{ item?.user_add }}</div>
        </div>
      </div>
    </div>
    <div class="mt-4 text-center text-lg text-white bg-[#1d684a] -mx-7 md:mx-0 py-2">ข้อมูลเกี่ยวกับผู้ร้องเรียน</div>
    <div class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">การเปิดเผยข้อมูล :</div>
      <div v-if="item?.concealed" class="w-full md:w-10/12 text-[#dc3545] text-xl">ปกปิด ข้อมูลผู้ร้องเรียน</div>
      <div v-else class="w-full md:w-10/12 text-green-500 text-xl">ไม่ปกปิด ข้อมูลผู้ร้องเรียน</div>
    </div>
    <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">ชื่อ-นามสกุล :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.fname }} {{ item?.lname }}</div>
    </div>
    <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">เพศ :</div>
      <div class="w-full md:w-10/12 text-sm">
        <span v-if="item?.gender == 1">ชาย</span>
        <span v-if="item?.gender == 2">หญิง</span>
        <span v-if="item?.gender == 3">LGBTQ+</span>
      </div>
    </div>
    <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">อาชีพ :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.work }}</div>
    </div>
    <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">ที่อยู่ :</div>
      <div class="w-full md:w-10/12 text-sm"  v-if="item?.province_id">{{ item?.address }}</div>
    </div>
    <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">เบอร์โทร :</div>
      <div class="w-full md:w-4/12 text-sm">{{ item?.tel }}</div>
      <div class="w-full md:w-2/12">เบอร์โทรศัพท์ :</div>
      <div class="w-full md:w-4/12 text-sm">{{ item?.phone }}</div>
    </div>
     <div v-if="!item?.concealed || user_level == 'root'" class="flex flex-col md:flex-row mt-4 items-center gap-3">
      <div class="w-full md:w-2/12">Email :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.email }}</div>
    </div>
    <div class="my-4 text-center text-lg text-white bg-[#1d684a] -mx-7 md:mx-0 py-2">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">ร้องเรียนถึง :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.unit_name }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">ช่องทางที่ร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.method_name }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">ประเด็นเรื่องร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.type_name }} <span v-if="item?.sub_name">({{ item?.sub_name }})</span></div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">บุคคลที่ร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.person_name }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">หัวข้อเรื่องร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.name }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">วันที่ร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm">{{ formatThaiDate(item?.created_at) }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">รายละเอียดเรื่องร้องเรียน :</div>
      <div class="w-full md:w-10/12 text-sm" v-html="item?.description"></div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">สิ่งที่ต้องการแก้ไข :</div>
      <div class="w-full md:w-10/12 text-sm" v-html="item?.improvement"></div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">เอกสารประกอบ :</div>
      <div class="w-full md:w-10/12 text-sm" v-if="item?.file">
        <a class="text-blue-500" :href="item?.file_url" target="_blank">{{ item?.file }}</a>
      </div>
    </div>
    <template v-if="item?.type  >= 3 && (item?.is_add == 1 || item?.is_add ==3)">
    <div class="my-4 text-center text-lg text-white bg-[#1d684a] -mx-7 md:mx-0 py-2 mt-11">คำสั่งการจากศูนย์รับเรื่อง (สำนักเลขานุการกรม)</div>

    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">ระดับเรื่อง :</div>
      <div class="w-full md:w-4/12 text-sm">
        <span v-if="item?.complain_level == 1">เรื่องทั่วไป</span>
        <span v-if="item?.complain_level == 2">เรื่องลับ</span>
      </div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">ระดับความรุนแรง :</div>
      <div class="w-full md:w-10/12 text-sm">
        <span v-if="item?.severity_admin == 1">ระดับ 1</span>
        <span v-if="item?.severity_admin == 2">ระดับ 2</span>
        <span v-if="item?.severity_admin == 3">ระดับ 3</span>
        <span v-if="item?.severity_admin == 4">ระดับ 4</span>
        <span v-if="item?.severity_admin == 5">ระดับ 5</span>
        {{ item?.severity_name }}
      </div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-2/3 md:w-4/12">ระดับความรวดเร็วในการตอบสนองข้อคิดเห็น :</div>
      <div class="w-1/3 md:w-6/12 text-sm">
        ภายใน {{ item?.severity_time }} วัน
      </div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">คำสั่งการ :</div>
      <div class="w-full md:w-10/12 text-sm">{{ item?.send_comm }}</div>
    </div>
    <div class="flex flex-col md:flex-row mt-4 gap-3">
      <div class="w-full md:w-2/12">เอกสารประกอบ :</div>
      <div class="w-full md:w-10/12 text-sm" v-if="item?.send_files"><a class="text-blue-500" :href="item?.send_files_url" target="_blank">{{ item?.send_files }}</a></div>
    </div>
    </template>

    <template v-if="item?.type  >= 3">
      <div class="my-4  text-lg text-white text-center bg-[#1d684a] py-2 mt-11">ข้อความการตอบ-แก้ไข ข้อร้องเรียน (หน่วยกำกับดูแล)</div>
      <div class="flex flex-col w-full py-6" v-if="user_level == 'root'">
        <h2 v-if="item?.type == 3" class="text-red-400 text-center">อยู่ระหว่างดำเนินการ</h2>
        <h2 v-if="item?.type == 4 || item?.type == 6" class="text-red-400 text-center">รอหน่วยดำเนินการ ตอบ-แก้ไขข้อร้องเรียน</h2>

        <div class="flex flex-col md:flex-row mt-4 gap-3">
          <div class="w-full md:w-2/12">การแก้ไขข้อร้องเรียน :</div>
          <div class="w-full md:w-10/12 text-sm" v-html="item?.answer_detail"></div>
        </div>
        <div class="flex flex-col md:flex-row mt-4 gap-3">
          <div class="w-full md:w-2/12">เอกสารประกอบ :</div>
          <div class="w-full md:w-10/12 text-sm" v-if="item?.answer_file"><a class="text-blue-500" :href="item?.answer_file_url" target="_blank">{{ item?.answer_file }}</a></div>
        </div>

        <div class="flex flex-col mt-6 md:flex-row justify-center gap-3" v-if="item?.type == 5">
          <div class="text-center">
            <button type="button" @click="sendError()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ส่งกลับให้หน่วยแก้ไขอีกครั้ง 
            </button>
          </div>
          <div class="text-center">
            <button type="button" @click="sendOk()"  class="px-4 py-2 bg-green-600 text-white rounded hover:bg-gree-700">
              บันทึกรับทราบ ยุติเรื่อง
            </button>
          </div>
        </div>

      </div>
      <template v-if="user_level == 'unit' && (item?.type == 4 || item?.type == 5 || item?.type == 6)" >
        <Form :initial-values="formAnswer"  :validation-schema="schemaAnswer" @submit="answerData">
          <div class="flex flex-col md:flex-row mt-4 gap-3">
            <div class="w-full md:w-2/12">การแก้ไขข้อร้องเรียน :</div>
            <div class="w-full md:w-8/12 text-sm">
              <Field
                  as="textarea"
                  name="answer_detail"
                  rows="6"
                  class="form-control w-full border rounded p-2 border-[#ccc]"
                  v-model="formAnswer.answer_detail"
                />
              <ErrorMessage name="answer_detail" class="text-red-500 text-sm" />
            </div>
          </div>
          <div class="flex flex-col md:flex-row mt-4 gap-3">
            <div class="w-full md:w-2/12">เอกสารประกอบ (ถ้ามี) :</div>
            <div class="w-full md:w-8/12 text-sm">
                <div @click="changeFile()" class="border-[#ccc] border rounded px-4 py-1 inline-block hover:cursor-pointer bg-[#e9ecef]">เลือกไฟล์</div>
                <div v-if="file.name" class="text-[#007bff] underline">{{ file.name }}</div>
                <div v-if="!file.name && item?.answer_file" class="text-[#007bff]">
                  <a class="text-blue-500" :href="item?.answer_file_url" target="_blank">{{ item?.answer_file }}</a>
                </div>
            
            <div class="text-xs text-[#6c757d]">กรุณาเลือกไฟล์ pdf, หรือรูปภาพ</div>
            <input id="fileSelect" @change="slectFile()" ref="file" type="file" accept=".pdf,image/png,image/jpeg" style="display:none;" />  
            </div>
          </div>
          
          <div class="flex flex-col w-full text-center mt-6">
            <div class="text-center">
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ตอบข้อร้องเรียน
            </button>
            </div>
          </div>
        </Form>
      </template>
    </template>


    <template v-if="user_level == 'root'">
      <div class="my-4 text-center text-lg text-white bg-[#1d684a] -mx-7 md:mx-0 py-2 mt-11">แสดงกระบวนการ สำหรับผู้ร้อง</div>
      <Form :initial-values="formTrace"  @submit="traceData">
        <div class="flex flex-col md:flex-row mt-4 gap-3">
          <div class="w-full md:w-2/12">สถานะ :</div>
          <div class="w-full md:w-3/12 text-sm">
             <Field as="select" name="trace_approve" class="input" v-model="formTrace.trace_approve">
                <option value="1">กำลังดำเนินการ</option>
                <option value="2">ดำเนินการเรียบร้อย</option>
                <option value="3">ผู้ร้องรับทราบ</option>
              </Field>
          </div>
        </div>
        <div class="flex flex-col md:flex-row mt-4 gap-3">
          <div class="w-full md:w-2/12">ข้อความที่ต้องการแสดง :</div>
          <div class="w-full md:w-8/12 text-sm">
            <Field
                as="textarea"
                name="trace_show"
                rows="3"
                class="form-control w-full border rounded p-2 border-[#ccc]"
                v-model="formTrace.trace_show"
              />
          </div>
        </div>
        <div class="flex flex-col w-full text-center mt-6">
          <div class="text-center">
           <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
             บันทึก
          </button>
          </div>
        </div>
      </Form>
    </template>


    <div class="mt-11"> {{ item?.log?.length }} การดำเนินการ</div>
    <template v-for="(log) in item?.log">
      <div class="border mt-3 border-gray-200 p-3 -mx-7 md:mx-0">
        <div class="text-blue-500">
          {{ log.user_id }}
          <span class="pl-3 text-gray-900"><small><i class="far fa-clock"></i> : {{ formatThaiDate(log.date_time) }}</small></span>
        </div>

        <div v-if="log.type == 1">
          <span>แจ้งกลับ/ยกเลิก ข้อร้องเรียน <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
          <div class="pl-3">สาเหตุการยกเลิก/ยุติเรื่อง : {{ item?.del_comm }}</div>
        </div>

        <div v-if="log.type == 2" class="flex flex-col lg:flex-row">
          <div class="w-full">
            <span>ส่งข้อร้องเรียนให้ หน่วยกำกับดูแล ชื่อหน่วย {{ item?.unit_name }} <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
            <div class="pl-3">คำสั่งการ : {{ item?.send_comm }}</div>
            <div class="pl-3">เอกสารสั่งการ : 
              <a v-if="item?.send_files " class="text-blue-500" :href="item?.send_files_url" target="_blank">{{ item?.send_files }}</a>
              <span v-else>ไม่มีเอกสาร</span>
            </div>
          </div>
        </div>

        <div v-if="log.type == 3" class="flex flex-col md:flex-row items-end">
          <div class="w-full md:w-1/2">
            <span>หน่วยกำกับดูแลรับเรื่องร้องเรียน</span>
            <div class="pl-3 text-[14px]">ผู้รับผิดชอบ : {{ item?.auth_fname }} {{ item?.auth_lname }}</div>
            <div class="pl-3 text-[14px]">เบอร์ติดต่อ : {{ item?.auth_phone }} Email: {{ item?.auth_email }}</div>
           </div>
           <div class="w-full md:w-1/2 text-center md:text-right mt-3">
            <button type="button" v-if="user_level == 'root'" @click="addComment(item?.id)" class="bg-cyan-600 text-white text-[12px] px-4 py-1.5 rounded hover:bg-cyan-700">สอบถามการดำเนินการ</button>
            </div>
        </div>

        <div v-if="log.type == 4" class="flex flex-col md:flex-row items-end">
          <div class="w-full md:w-1/2">
            <span>หน่วยกำกับดูแล  ตอบ-แก้ไขข้อร้องเรียน</span>
           </div>
        </div>

        <div v-if="log.type == 5" class="flex flex-col md:flex-row items-end">
          <div class="w-full">
           <span>ศูนย์รับเรื่อง ส่งเรื่องร้องเรียน กลับให้หน่วย ตอบ-แก้ไขข้อร้องเรียน ใหม่อีกครั้ง <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
           </div>
        </div>

        <div v-if="log.type == 6" class="flex flex-col md:flex-row items-end">
          <div class="w-full md:w-2/3">
            <span>ศูนย์รับเรื่อง สอบถามการดำเนินการ <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
            
            <div class="pl-3 text-[14px] text-[#17a2b8]"><i class="fas fa-question-circle"></i> ข้อซักถาม : <span class="text-black">{{ log?.comment_ask }}</span></div>
            <div class="pl-3">
              <small><i class="far fa-clock"></i> : {{ formatThaiDate(log.date_time) }}</small>
              <small class="pl-3"><i class="far fa-user"></i> : {{ log?.user_ask }}</small>
            </div>
            <div class="pl-3 text-[14px] text-[#17a2b8]"><i class="fas fa-university"></i> คำตอบจากหน่วย : 
              <span v-if="log.comment_type == 1" class="text-red-400">หน่วยยังไม่ตอบข้อซักถาม...</span>
              <span v-if="log.comment_type == 2" class="text-black">{{ log?.comment_com }}</span>
            </div>
            <div class="pl-3" v-if="log.comment_date">
              <small><i class="far fa-clock"></i> : {{ formatThaiDate(log.comment_date) }}</small>
              <small class="pl-3"><i class="far fa-user"></i> : {{ log?.user_com }}</small>
            </div>
          </div>
          <div class="w-full md:w-1/3 text-center md:text-right mt-3">
            <button type="button" v-if="user_level == 'root' && log.comment_type == 1" @click="delComment(log?.id)" class="bg-red-600 text-white text-[12px] px-4 py-1.5 rounded hover:bg-red-700">ยกเลิกข้อซักถาม</button>
            <button type="button" v-if="user_level == 'unit' && item?.type <= 7" @click="replyComment(log)" class="bg-[#17a2b8] text-white text-[12px] px-4 py-1.5 rounded hover:bg-[#17a2b8]">ตอบข้อซักถาม</button>
          </div>
        </div>

        <div v-if="log.type == 7" class="flex flex-col md:flex-row items-end">
          <div class="w-full">
           <span>ยุติเรื่องร้องเรียน <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
           </div>
        </div>

        <div v-if="log.type == 8" class="flex flex-col md:flex-row items-end">
          <div class="w-full">
           <span>นำข้อร้องเรียน กลับมาใช้ <small> (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)</small></span>
           </div>
        </div>

      </div>
    </template>

    <div class="py-6 text-center w-full">
      <button @click="backPage()"  type="button" class="inline-block mt-2 px-6 py-2  border rounded"><i class="fas fa-long-arrow-alt-left"></i> ย้อนกลับ</button>
    </div>


    <div
    v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]"><i class="far fa-bell"></i> ติดตาม กระบวนการดำเนินงาน !</h2>
            <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">

            <div class="text-center text-white bg-[#1d684a] py-2 mx-4 mt-6">
              ติดตามกระบวนการ ดำเนินงานเรื่องร้องเรียน รหัสเรื่อง <strong>{{ item?.code }}</strong>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col items-start">
                <div class="w-full">ข้อซักถาม :</div>
                <div class="w-full">
                  <textarea rows="4" v-model="input.comm" class="w-full border rounded px-3 py-2" placeholder="กรุณากรอกข้อซักถาม"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="button" @click="saveCommend()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ติดตามกระบวนการ
            </button>
          </div>
      </div>
    </div>

    <div
    v-if="formReply.modal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
          <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
            <h2 class="text-lg text-[#EE7530]"><i class="far fa-bell"></i> ติดตาม กระบวนการดำเนินงาน !</h2>
            <i @click="formReply.modal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
          </div>
          <div class="overflow-y-auto max-h-[60vh]">

            <div class="text-center text-white bg-[#1d684a] py-2 mx-4 mt-6">
              ติดตามกระบวนการ ดำเนินงานเรื่องร้องเรียน รหัสเรื่อง <strong>{{ item?.code }}</strong>
            </div>
            <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
              <div class="flex flex-col md:flex-row items-start">
                <div class="w-full md:w-1/4">ข้อซักถาม :</div>
                <div class="w-full md:w-3/4">{{ formReply.comment_ask }}</div>
              </div>
              <div class="flex flex-row  w-full items-start">
                <small><i class="far fa-clock"></i> : {{ formatThaiDate(formReply.date_ask) }}</small>
                <small class="pl-3"><i class="far fa-user"></i> : {{ formReply.user_ask }}</small>
              </div>
              <div class="flex flex-col items-start mt-4">
                <div class="w-full">คำตอบ :</div>
                <div class="w-full">
                  <textarea rows="4" v-model="formReply.comment_com" class="w-full border rounded px-3 py-2" placeholder="กรุณากรอกคำตอบ"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8 gap-4">
            <button type="button" @click="formReply.modal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ยกเลิก
            </button>
            <button type="button" @click="saveReplay()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
              ตอบข้อซักถาม
            </button>
          </div>
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

    <div class="fixed top-[25%] right-4 z-50 flex flex-col gap-3" v-if="item?.id">
      <!-- ปุ่มพิมพ์ -->
      <button
        type="button"
        @click="print()"
        aria-label="พิมพ์หน้านี้"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-blue-800"
      >
        <!-- ไอคอนเครื่องพิมพ์ (SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2M6 18v3h12v-3" />
        </svg>
        <span>พิมพ์</span>
      </button>

      <button
        v-if="item?.type == 3 && user_level == 'unit'"
        type="button"
        @click="receiveComplaint()"
        aria-label="รับเรื่องร้องเรียน"
        class="group flex items-center gap-2 px-4 py-2 rounded-xl shadow-lg bg-white/90 backdrop-blur-sm border border-gray-200 hover:scale-105 transform transition
              text-sm font-medium text-cyan-600"
      >
        
        <span>รับเรื่องร้องเรียน</span>
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


  </div>
</template>
<script>
import Swal from 'sweetalert2'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import axios from 'axios'

export default {
  name: 'showComplaint',
  data(){
    return {
      showModal: false,
      id: '',
      item: {},
      isLoading: false,
      input:{
        comm: ''
      },
      formTrace: {
        trace_approve: 1,
        trace_show: '',
      },
      formReply:{
        modal: false,
        comment_id: '',
        comment_ask: '',
        comment_com: '',
        comment_date: '',
        complaint_id: '',
        date_ask: '',
        user_ask: '',
        user_com: '',
        type: '',
      },
      formReport:{
        report_detail: '',
        report_file: '',
      },
      schemaReport: yup.object().shape({
        report_detail: yup.string().required('กรุณากรอก ข้อความรายงานผู้บริหาร'),
      }),
      formAnswer:{
        answer_detail: '',
        answer_file: '',
      },
      schemaAnswer: yup.object().shape({
        answer_detail: yup.string().required('กรุณากรอก การแก้ไขข้อร้องเรียน'),
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
      file:{
        uri: '',
        name: '',
        type: '',
      },
    }
  },
  props:["user_level"],
  components:{
    Swal,
    Form,
    Field,
    ErrorMessage,
  },
  methods:{
    backPage(){
      this.$emit('backPage')
    },
    async loadComplaint(id){
      this.isLoading = true;
      this.id = id
      await axios.get('/get/complaint/by/'+id,{
        params:{
          type: 'show'
        }
      }) 
      .then(res => {
        if(res.data.status == 200){
          this.item = res.data.item;
          
          if(this.item?.trace_approve){
            this.formTrace.trace_approve = this.item?.trace_approve
          }
          if(this.item?.trace_show){
            this.formTrace.trace_show = this.item?.trace_show
          }

          if(this.item?.answer_detail){
            
            this.formAnswer.answer_detail = this.item?.answer_detail
            
            
          }
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
    addComment(){
      this.showModal = true;
    },
    saveCommend(){
      if(!this.input.comm){
        Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'กรุณากรอก ข้อซักถาม',
              confirmButtonText: 'ตกลง'
            })
            return;
      }

      this.isLoading = true 
      axios.post('/complaint/add/comment/'+this?.item?.id,{
        comment: this.input.comm,
        }).then(res => {
          if(res.data.status == 200){
            this.isLoading = false
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              text: res.data.message,
              confirmButtonText: 'ตกลง'
            })
            this.showModal = false
            this.loadComplaint(this?.item?.id)
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
        });
    },
    delComment(id){
      if(id){
        Swal.fire({
        title: 'ลบข้อซักถาม?',
        text: 'ยืนยันการลบข้อซักถาม อีกครั้ง!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ดำเนินการต่อ',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          this.isLoading = true;
          axios.delete('/complaint/delete/comment',
            {
              params:{
                id: id,
              }
            }
          )
          .then(res => {
            if(res.data.status == 200){
              this.isLoading = false;
              Swal.fire({title: 'สำเร็จ !',text: 'ลบข้อมูลสำเร็จ !',icon: 'success',confirmButtonText: 'ตกลง'});
              this.loadComplaint(this?.item?.id)
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
      })
      }
    },
    traceData(values){
      console.log(values);
      this.isLoading = true 
        axios.post('/complaint/save/trace/'+this?.item?.id,{
          data: values,
          }).then(res => {
            if(res.data.status == 200){
              this.isLoading = false
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: res.data.message,
                confirmButtonText: 'ตกลง'
              })
              this.showModal = false
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
          });
      // Implement trace data submission logic here
    },
    replyComment(log){
      this.formReply.modal = true;
      this.formReply.comment_id = log.comment_id;
      this.formReply.comment_ask = log.comment_ask;
      this.formReply.user_ask = log.user_ask;
      this.formReply.comment_com = log.comment_com;
      this.formReply.user_com = log.user_com;
      this.formReply.type = log.comment_type;
      this.formReply.date_ask = log.date_time;

    },
    saveReplay(){
      if(!this.formReply.comment_com){
        Swal.fire({
              icon: 'error',
              title: 'ผิดพลาด',
              text: 'กรุณากรอก คำตอบ',
              confirmButtonText: 'ตกลง'
            })
            return;
      }

      this.isLoading = true 
      axios.post('/complaint/add/reply/'+this?.item?.id,{
        comment: this.formReply.comment_com,
        id: this.formReply.comment_id,
        type: 2,
        }).then(res => {
          if(res.data.status == 200){
            this.isLoading = false
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              text: res.data.message,
              confirmButtonText: 'ตกลง'
            })
            this.formReply.modal = false
            this.loadComplaint(this?.item?.id)
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
        });
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
          const formData = new FormData();
          formData.append('answer_file', this.$refs.file?.files[0]);
          formData.append('answer_detail', values.answer_detail);
          formData.append('id', this.item?.id);

          this.isLoading = true 
          axios.post('/complaint/save/answer/'+this?.item?.id, formData, {
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
              this.loadComplaint(this?.item?.id)
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
          });
        }
          
      });
    },
    reportData(values){
      console.log('vaaaaa')
      Swal.fire({
        title: 'ยืนยันข้อมูล !',
        html: 'กรุณายืนยันการรายงานผู้บริหาร อีกครั้ง',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('report_file', this.$refs.file?.files[0]);
          formData.append('report_detail', values.answer_detail);
          formData.append('id', this.item?.id);

          this.isLoading = true 
          axios.post('/complaint/save/report/'+this?.item?.id, formData, {
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
              this.loadComplaint(this?.item?.id)
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
          });
        }
          
      });
    },
    changeFile(){
      this.$refs.file.click();
    },
    slectFile(){
      const file = this.$refs.file?.files[0];
      this.file.name = file.name
      this.file.type = 'new'
    },
    print(){
      window.open('/complaint/print/'+this.item?.code, '_blank');
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
    sendError(){
      Swal.fire({
        title: 'ยืนยันข้อมูล !',
        html: 'ส่งเรื่องกลับไปให้หน่วยงาน <br/>แก้ไขข้อร้องเรียน อีกครั้ง',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
      this.isLoading = true 
        axios.post('/complaint/send/reunit/'+this?.item?.id,{
        }).then(res => {
          if(res.data.status == 200){
            this.isLoading = false
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              text: res.data.message,
              confirmButtonText: 'ตกลง'
            })
            this.loadComplaint(this?.item?.id)
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
        });
      }
    });
    },
    sendOk(){
      Swal.fire({
        title: 'ยืนยันข้อมูล !',
        html: 'รับทราบ ตอบ-แก้ไขเรื่องร้องเรียน',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
      this.isLoading = true 
        axios.post('/complaint/send/step/'+this?.item?.id,{
        }).then(res => {
          if(res.data.status == 200){
            this.isLoading = false
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              text: res.data.message,
              confirmButtonText: 'ตกลง'
            })
            this.loadComplaint(this?.item?.id)
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
        });
      }
    });
    },
    receiveComplaint(){
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
        axios.post('/complaint/receive/'+this?.item?.id,{
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
              this.loadComplaint(this?.item?.id)
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
  }
}
</script>