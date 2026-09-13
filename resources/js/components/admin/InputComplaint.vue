<template>
  <div class="admin-complaint">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" color="#3fbbc0" />

    <Form :initial-values="form" :validation-schema="schema" @submit="confirmSave" @invalid-submit="invalidSubmit">
      <h2 class="section-title">ข้อมูลผู้ร้องเรียน</h2>
      <label class="concealed"><input v-model="form.concealed" type="checkbox"> ปกปิดชื่อและข้อมูลส่วนตัว</label>

      <div class="form-grid">
        <FormRow label="ชื่อผู้ร้องเรียน">
          <Field v-model="form.firstName" name="firstName" class="input" />
        </FormRow>
        <FormRow label="นามสกุล">
          <Field v-model="form.lastName" name="lastName" class="input" />
        </FormRow>
        <FormRow label="เพศ">
          <Field v-model="form.sex" as="select" name="sex" class="input">
            <option value="">-- กรุณาเลือก --</option>
            <option value="1">ชาย</option>
            <option value="2">หญิง</option>
            <option value="3">LGBTQ+</option>
          </Field>
        </FormRow>
        <FormRow label="อาชีพ">
          <Field v-model="form.work" name="work" class="input" />
        </FormRow>
        <FormRow label="ที่อยู่" wide>
          <Field v-model="form.address" name="address" class="input" />
        </FormRow>
        <FormRow label="จังหวัด">
          <Field v-model="form.province_id" as="select" name="province_id" class="input" @change="getDistrict">
            <option value="">-- กรุณาเลือก --</option>
            <option v-for="row in provinces" :key="row.id" :value="row.id">{{ row.name }}</option>
          </Field>
        </FormRow>
        <FormRow label="อำเภอ">
          <Field v-model="form.district_id" as="select" name="district_id" class="input" @change="getSubDistrict">
            <option value="">-- กรุณาเลือก --</option>
            <option v-for="row in districts" :key="row.id" :value="row.id">{{ row.name }}</option>
          </Field>
        </FormRow>
        <FormRow label="ตำบล">
          <Field v-model="form.subdistrict_id" as="select" name="subdistrict_id" class="input" @change="getZipcode">
            <option value="">-- กรุณาเลือก --</option>
            <option v-for="row in subdistricts" :key="row.id" :value="row.id">{{ row.name }}</option>
          </Field>
        </FormRow>
        <FormRow label="รหัสไปรษณีย์">
          <Field v-model="form.zipcode" name="zipcode" class="input" />
        </FormRow>
        <FormRow label="มือถือ">
          <Field v-model="form.phone" name="phone" class="input" />
        </FormRow>
        <FormRow label="โทรศัพท์">
          <Field v-model="form.tel" name="tel" class="input" />
        </FormRow>
        <FormRow label="อีเมล">
          <Field v-model="form.email" name="email" class="input" />
        </FormRow>
      </div>

      <h2 class="section-title">ข้อมูลเกี่ยวกับเรื่องร้องเรียน</h2>
      <div class="form-grid">
        <FormRow label="ช่องทางร้องเรียน">
          <Field v-model="form.method_id" as="select" name="method_id" class="input">
            <option value="">-- กรุณาเลือก --</option>
            <option v-for="row in item_methods" :key="row.id" :value="row.id">{{ row.name }}</option>
          </Field>
          <ErrorMessage name="method_id" class="error" />
        </FormRow>
        <FormRow label="ประเด็นร้องเรียน">
          <Field v-model="form.type_id" as="select" name="type_id" class="input">
            <option value="">-- กรุณาเลือก --</option>
            <option v-for="row in complaintTypes" :key="row.id" :value="row.id">{{ row.num }}. {{ row.name }}</option>
          </Field>
          <ErrorMessage name="type_id" class="error" />
        </FormRow>
        <FormRow label="เรื่องที่ร้องเรียน" wide>
          <Field v-model="form.name" name="name" class="input" />
          <ErrorMessage name="name" class="error" />
        </FormRow>
        <FormRow label="รายละเอียด" wide>
          <Field v-model="form.description" as="textarea" rows="4" name="description" class="input" />
          <ErrorMessage name="description" class="error" />
        </FormRow>
        <FormRow label="สิ่งที่ต้องการให้แก้ไข" wide>
          <Field v-model="form.improvement" as="textarea" rows="4" name="improvement" class="input" />
          <ErrorMessage name="improvement" class="error" />
        </FormRow>
      </div>

      <section class="upload-box">
        <div class="upload-heading">
          <div><strong>เอกสารประกอบ</strong>
            <p>รูปภาพไม่เกิน 10 รูป, PDF 1 ไฟล์, วิดีโอ 1 ไฟล์ และไม่เกิน 10 MB ต่อไฟล์</p>
          </div>
          <button type="button" @click="$refs.fileInput.click()">+ เลือกไฟล์</button>
          <input ref="fileInput" hidden multiple type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4,.mov"
            @change="selectFiles">
        </div>
        <p v-if="fileError" class="error">{{ fileError }}</p>

        <div v-if="allPreviewFiles.length" class="file-grid">
          <article v-for="file in allPreviewFiles" :key="file.key" class="file-card">
            <img v-if="file.type === 'image'" :src="file.url" :alt="file.name">
            <video v-else-if="file.type === 'video'" :src="file.url" controls preload="metadata"></video>
            <a v-else :href="file.url" target="_blank" class="pdf">PDF</a>
            <div class="file-meta"><span><b>{{ file.name }}</b><small>{{ file.sizeText }}</small></span><button
                type="button" @click="removeFile(file)">ลบ</button></div>
          </article>
        </div>
        <div v-else class="empty">ยังไม่มีไฟล์แนบ</div>
      </section>

      <div class="actions"><button type="button" class="secondary" @click="onBackPage()">ย้อนกลับ</button><button
          type="submit">{{ id ? 'บันทึกการแก้ไข' : 'เพิ่มเรื่องร้องเรียน' }}</button></div>
    </Form>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

const FormRow = { props: { label: String, wide: Boolean }, template: `<label :class="['form-row',{wide}]"><span>{{ label }}</span><div><slot /></div></label>` }

export default {
  name: 'AdminComplaintForm', components: { Form, Field, ErrorMessage, FormRow },
  props: { type: String, user_level: String, key_title: { type: String, default: '' } },
  emits: ['backPage', 'saved'],
  data() {
    return {
      id: '', isLoading: false, provinces: [], districts: [], subdistricts: [], units: [], complaintTypes: [], item_methods: [],
      existingFiles: [], newFiles: [], newPreviews: [], deletedFileIds: [], fileError: '', maxSize: 10 * 1024 * 1024,
      form: { concealed: false, firstName: '', lastName: '', idcard: '', sex: '', work: '', address: '', phone: '', tel: '', email: '', province_id: '', district_id: '', subdistrict_id: '', zipcode: '', unit_id: '', type_id: '', sub_id: '', person_id: '', method_id: '', name: '', description: '', improvement: '', method_id: '' },
      schema: yup.object({ method_id: yup.string().required('กรุณาเลือกช่องทางการร้องเรียน') , type_id: yup.string().required('กรุณาเลือกประเด็น'), name: yup.string().required('กรุณากรอกเรื่อง'), description: yup.string().required('กรุณากรอกรายละเอียด'), improvement: yup.string().required('กรุณากรอกสิ่งที่ต้องการให้แก้ไข') })
    }
  },
  computed: {
    allPreviewFiles() {
      const old = this.existingFiles.map(f => ({ key: `old-${f.id}`, id: f.id, isOld: true, type: f.file_type, name: f.original_name || f.file_name, url: f.file_url, sizeText: f.file_size_text || this.formatSize(f.file_size) }))
      return [...old, ...this.newPreviews]
    }
  },
  created() { this.getMasterData() },
  beforeUnmount() { this.clearPreviews() },
  methods: {
    async getMasterData() { this.isLoading = true; try { const { data } = await axios.get('/get/master/data'); this.provinces = data.province || []; this.units = data.unit || []; this.complaintTypes = data.type || []; this.item_methods = data.methods || []; } finally { this.isLoading = false } },
    async loadComplaint(id) {
      this.isLoading = true; this.id = id
      try {
        const { data } = await axios.get(`/get/complaint/by/${id}`); const i = data.item
        Object.assign(this.form, { concealed: !!i.concealed, firstName: i.fname || '', lastName: i.lname || '', idcard: i.idcard || '', sex: i.gender || '', work: i.work || '', address: i.address || '', phone: i.phone || '', tel: i.tel || '', email: i.email || '', province_id: i.province_id || '', district_id: i.district_id || '', subdistrict_id: i.subdistrict_id || '', zipcode: i.zipcode || '', unit_id: i.unit_id || '', type_id: i.type_id || '', sub_id: i.sub_id || '', person_id: i.person_id || '', method_id: i.method_id || '', name: i.name || '', description: i.description || '', improvement: i.improvement || '' })
        this.existingFiles = Array.isArray(i.files) ? i.files : []; this.deletedFileIds = []; this.newFiles = []; this.clearPreviews()
        if (i.province_id) await this.getDistrict(false); if (i.district_id) await this.getSubDistrict(false)
      } finally { this.isLoading = false }
    },
    extension(file) { return file.name.split('.').pop()?.toLowerCase() || '' },
    fileType(file) { const e = this.extension(file); if (['jpg', 'jpeg', 'png', 'webp'].includes(e)) return 'image'; if (e === 'pdf') return 'pdf'; if (['mp4', 'mov'].includes(e)) return 'video'; return null },
    key(file) { return `${file.name}-${file.size}-${file.lastModified}` },
    formatSize(size) { return Number(size) >= 1048576 ? `${(size / 1048576).toFixed(2)} MB` : `${(size / 1024).toFixed(2)} KB` },
    selectFiles(event) {
      const selected = Array.from(event.target.files || []); event.target.value = ''; this.fileError = ''; const next = [...this.newFiles]
      for (const file of selected) { if (!this.fileType(file)) return this.fileFail(`ไม่รองรับไฟล์ ${file.name}`); if (file.size > this.maxSize) return this.fileFail(`ไฟล์ ${file.name} มีขนาดเกิน 10 MB`); if (!next.some(f => this.key(f) === this.key(file))) next.push(file) }
      const types = [...this.existingFiles.map(f => f.file_type), ...next.map(f => this.fileType(f))]
      if (types.filter(t => t === 'image').length > 10) return this.fileFail('รูปภาพต้องไม่เกิน 10 รูป')
      if (types.filter(t => t === 'pdf').length > 1) return this.fileFail('PDF ต้องไม่เกิน 1 ไฟล์')
      if (types.filter(t => t === 'video').length > 1) return this.fileFail('วิดีโอต้องไม่เกิน 1 ไฟล์')
      this.newFiles = next; this.makePreviews()
    },
    fileFail(message) { this.fileError = message; Swal.fire('ไฟล์ไม่ถูกต้อง', message, 'warning') },
    makePreviews() { this.clearPreviews(); this.newPreviews = this.newFiles.map((f, i) => ({ key: `new-${this.key(f)}`, index: i, isOld: false, type: this.fileType(f), name: f.name, url: URL.createObjectURL(f), sizeText: this.formatSize(f.size) })) },
    clearPreviews() { this.newPreviews.forEach(f => URL.revokeObjectURL(f.url)); this.newPreviews = [] },
    removeFile(file) { if (file.isOld) { this.deletedFileIds.push(file.id); this.existingFiles = this.existingFiles.filter(f => f.id !== file.id) } else { this.newFiles.splice(file.index, 1); this.makePreviews() } this.fileError = '' },
    invalidSubmit() { Swal.fire('แจ้งเตือน', 'กรุณากรอกข้อมูลให้ครบ', 'warning') },
    async confirmSave(values) { const result = await Swal.fire({ title: 'ยืนยันการบันทึก', text: this.id ? 'ยืนยันการแก้ไขข้อมูลหรือไม่' : 'ยืนยันการเพิ่มข้อมูลหรือไม่', icon: 'question', showCancelButton: true, confirmButtonText: 'บันทึก', cancelButtonText: 'ยกเลิก' }); if (result.isConfirmed) await this.save(values) },
    onBackPage(){
      
        if(!this.id){
          window.location.assign('/admin')
        }

        this.$emit('backPage')
    },
    async save(values) {
      this.isLoading = true

      try {
        const formData = new FormData()

        const formValues = {
          id: this.id,
          key_title: this.key_title,
          concealed: values.concealed ? 1 : 0,
          fname: values.firstName,
          lname: values.lastName,
          idcard: values.idcard,
          gender: values.sex,
          work: values.work,
          address: values.address,
          phone: values.phone,
          tel: values.tel,
          email: values.email,
          province_id: values.province_id,
          district_id: values.district_id,
          subdistrict_id: values.subdistrict_id,
          zipcode: values.zipcode,
          unit_id: values.unit_id,
          type_id: values.type_id,
          sub_id: values.sub_id,
          person_id: values.person_id,
          method_id: values.method_id,
          name: values.name,
          description: values.description,
          improvement: values.improvement,
        }

        /*
         * เพิ่มข้อมูลทั่วไปลง FormData
         */
        Object.entries(formValues).forEach(([key, value]) => {
          formData.append(key, value ?? '')
        })

        /*
         * เพิ่มเฉพาะไฟล์ใหม่
         */
        this.newFiles.forEach((file) => {
          if (file instanceof File && file.size > 0) {
            formData.append('attachments[]', file)
          }
        })

        /*
         * ID ของไฟล์เดิมที่ต้องการลบ
         */
        this.deletedFileIds.forEach((fileId) => {
          formData.append('deleted_file_ids[]', fileId)
        })

        const response = await axios.post(
          '/complaint/store',
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
         * ปิด Loading ก่อนเปิด SweetAlert
         */
        this.isLoading = false

        await Swal.fire({
          title: 'สำเร็จ',
          text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#3085d6',
        })

        this.$emit('saved', data)
        this.$emit('backPage')
        if(!this.id){
          window.location.assign('/admin')
        }
        
      } catch (error) {
        console.error(error)

        /*
         * ปิด Loading ก่อนแสดง Error
         */
        this.isLoading = false

        const validationErrors =
          error.response?.data?.errors

        const message = validationErrors
          ? Object.values(validationErrors).flat()[0]
          : error.response?.data?.message ||
          error.message ||
          'บันทึกข้อมูลไม่สำเร็จ'

        await Swal.fire({
          title: 'ผิดพลาด',
          text: message,
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } finally {
        /*
         * ปิด Loading เสมอ ไม่ว่าจะสำเร็จหรือเกิด Error
         */
        this.isLoading = false
      }
    },
    async getDistrict(clear = true) { if (!this.form.province_id) return; const { data } = await axios.get(`/get/district/${this.form.province_id}`); this.districts = data.item || []; if (clear) { this.form.district_id = ''; this.form.subdistrict_id = '' } },
    async getSubDistrict(clear = true) { if (!this.form.district_id) return; const { data } = await axios.get(`/get/subdistrict/${this.form.district_id}`); this.subdistricts = data.item || []; if (clear) this.form.subdistrict_id = '' },
    getZipcode() { const row = this.subdistricts.find(i => String(i.id) === String(this.form.subdistrict_id)); this.form.zipcode = row?.zip_code || '' }
  }
}
</script>

<style scoped>
.admin-complaint {
  padding: 24px;
  background: #fff
}

.section-title {
  margin: 28px 0 18px;
  padding: 12px;
  border-radius: 10px;
  background:
                    radial-gradient(circle at 0% 100%, rgba(129, 227, 255, .22), transparent 28%),
                    radial-gradient(circle at 100% 0%, rgba(167, 160, 255, .18), transparent 26%),
                    linear-gradient(135deg, #18a4ff 0%, #0a78d3 38%, #0b63c9 68%, #124d96 100%);
                
  color: #fff;
  text-align: center;
  font-size: 18px
}

.concealed {
  display: block;
  margin: 15px;
  color: #f0643c
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px
}

.form-row {
  display: grid;
  grid-template-columns: 145px 1fr;
  align-items: start;
  gap: 10px
}

.form-row>span {
  text-align: right;
  padding-top: 10px
}

.form-row.wide {
  grid-column: 1/-1
}

.input {
  width: 100%;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 9px 11px
}

.error {
  display: block;
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px
}

.upload-box {
  margin-top: 25px;
  padding: 20px;
  border: 1px dashed #93c5fd;
  border-radius: 16px;
  background: #f8fbff
}

.upload-heading {
  display: flex;
  justify-content: space-between;
  gap: 15px;
  align-items: center
}

.upload-heading p {
  margin: 4px 0;
  color: #64748b;
  font-size: 12px
}

.upload-heading button,
.actions button {
  border: 0;
  border-radius: 9px;
  padding: 10px 18px;
  background: #0879dc;
  color: #fff;
  cursor: pointer
}

.file-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-top: 16px
}

.file-card {
  padding: 10px;
  border: 1px solid #dbeafe;
  border-radius: 12px;
  background: #fff
}

.file-card img,
.file-card video,
.file-card .pdf {
  width: 100%;
  height: 135px;
  object-fit: cover;
  border-radius: 8px
}

.pdf {
  display: grid;
  place-items: center;
  background: #fff1f2;
  color: #dc2626;
  font-size: 28px;
  font-weight: 800;
  text-decoration: none
}

.file-meta {
  display: flex;
  justify-content: space-between;
  gap: 8px;
  margin-top: 8px
}

.file-meta span {
  min-width: 0
}

.file-meta b,
.file-meta small {
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap
}

.file-meta small {
  color: #64748b
}

.file-meta button {
  border: 0;
  background: none;
  color: #ef4444
}

.empty {
  text-align: center;
  color: #94a3b8;
  padding: 20px
}

.actions {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 26px
}

.actions .secondary {
  background: #64748b
}

@media(max-width:768px) {
  .form-grid {
    grid-template-columns: 1fr
  }

  .form-row {
    grid-template-columns: 1fr
  }

  .form-row>span {
    text-align: left;
    padding: 0
  }

  .upload-heading {
    align-items: flex-start;
    flex-direction: column
  }

  .file-grid {
    grid-template-columns: 1fr 1fr
  }
}

@media(max-width:480px) {
  .file-grid {
    grid-template-columns: 1fr
  }
}
</style>
