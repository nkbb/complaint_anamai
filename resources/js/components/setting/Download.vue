<template>
  <div class="mb-11 mt-6">
    <loading
      :active="isLoading"
      :can-cancel="false"
      :is-full-page="true"
      color="#3fbbc0"
      loader="spinner"
      :width="64"
      :height="64"
    />

    <div class="fixed right-4 top-[25%] z-40 flex flex-col gap-3">
      <button type="button" class="action-button text-brand-600" @click="addData">
        <i class="fas fa-plus"></i>
        <span>เพิ่มเอกสาร</span>
      </button>

      <button type="button" class="action-button text-gray-800" @click="backPage">
        <i class="fas fa-chevron-left"></i>
        <span>ย้อนกลับ</span>
      </button>
    </div>

    <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">เอกสารสำหรับประชาชน</h1>
        <p class="mt-1 text-sm text-slate-500">
          จัดการคู่มือ แบบฟอร์ม และเอกสารที่เปิดให้ประชาชนดาวน์โหลด
        </p>
      </div>

      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700"
        @click="addData"
      >
        <i class="fas fa-plus"></i>
        เพิ่มเอกสาร
      </button>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-3 rounded-xl border bg-white p-4 md:grid-cols-[1fr_220px_180px]">
      <input
        v-model.trim="search.keyword"
        type="search"
        class="input w-full"
        placeholder="ค้นหาชื่อเอกสาร..."
      />

      <select v-model="search.category" class="input w-full">
        <option value="">ทุกประเภท</option>
        <option v-for="option in categoryOptions" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>

      <select v-model="search.status" class="input w-full">
        <option value="">ทุกสถานะ</option>
        <option value="1">เผยแพร่</option>
        <option value="0">ไม่เผยแพร่</option>
      </select>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full min-w-[980px] text-left text-sm">
        <thead class="bg-slate-50 text-slate-700">
          <tr>
            <th class="w-[6%] border-b px-4 py-3 text-center">ลำดับ</th>
            <th class="w-[33%] border-b px-4 py-3">ชื่อเอกสาร</th>
            <th class="w-[13%] border-b px-4 py-3 text-center">ประเภท</th>
            <th class="w-[18%] border-b px-4 py-3">ไฟล์</th>
            <th class="w-[10%] border-b px-4 py-3 text-center">ดาวน์โหลด</th>
            <th class="w-[10%] border-b px-4 py-3 text-center">สถานะ</th>
            <th class="w-[10%] border-b px-4 py-3 text-center">จัดการ</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item, index) in filteredItems" :key="item.id" class="hover:bg-slate-50">
            <td class="border-b px-4 py-3 text-center">{{ index + 1 }}</td>
            <td class="border-b px-4 py-3">
              <div class="flex items-start gap-2">
                <div class="min-w-0">
                  <div class="font-semibold text-slate-800">{{ item.name }}</div>
                  <p v-if="item.description" class="mt-1 line-clamp-2 text-xs text-slate-500">
                    {{ item.description }}
                  </p>
                </div>
                <span v-if="Number(item.is_new) === 1" class="new-badge">NEW</span>
              </div>
            </td>
            <td class="border-b px-4 py-3 text-center">
              <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">
                {{ categoryLabel(item.category) }}
              </span>
            </td>
            <td class="border-b px-4 py-3">
              <a
                v-if="item.file_url"
                :href="item.file_url"
                target="_blank"
                rel="noopener"
                class="inline-flex max-w-[230px] items-center gap-2 text-blue-600 hover:underline"
              >
                <i :class="fileIcon(item.file_name)"></i>
                <span class="truncate">{{ item.file_name || 'เปิดเอกสาร' }}</span>
              </a>
              <span v-else class="text-slate-400">ไม่มีไฟล์</span>
            </td>
            <td class="border-b px-4 py-3 text-center">
              {{ Number(item.download_count || 0).toLocaleString() }}
            </td>
            <td class="border-b px-4 py-3 text-center">
              <span :class="Number(item.status) === 1 ? 'status-published' : 'status-hidden'">
                {{ Number(item.status) === 1 ? 'เผยแพร่' : 'ไม่เผยแพร่' }}
              </span>
            </td>
            <td class="border-b px-4 py-3 text-center">
              <div class="flex justify-center gap-4">
                <button type="button" class="text-blue-600 hover:text-blue-800" title="แก้ไข" @click="viewData(item)">
                  <i class="far fa-edit"></i>
                </button>
                <button type="button" class="text-red-600 hover:text-red-800" title="ลบ" @click="removeData(item.id)">
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="!filteredItems.length && !isLoading">
            <td colspan="7" class="px-4 py-12 text-center text-slate-500">
              ไม่พบข้อมูลเอกสาร
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 px-4 py-8"
      role="dialog"
      aria-modal="true"
      aria-labelledby="document-modal-title"
      @click.self="closeModal"
    >
      <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 id="document-modal-title" class="text-xl font-bold text-brand-700">
              {{ form.id ? 'แก้ไขเอกสาร' : 'เพิ่มเอกสาร' }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">รองรับไฟล์ PDF, Word, Excel และ ZIP ขนาดไม่เกิน 20 MB</p>
          </div>
          <button type="button" class="text-2xl leading-none text-slate-400 hover:text-slate-700" @click="closeModal">×</button>
        </div>

        <form class="mt-6 space-y-5" @submit.prevent="saveData">
          <div>
            <label class="form-label">ชื่อเอกสาร <span class="text-red-500">*</span></label>
            <input v-model.trim="form.name" type="text" class="input w-full" placeholder="ระบุชื่อเอกสารหรือคู่มือ" />
            <p v-if="errors.name" class="error-message">{{ errors.name }}</p>
          </div>

          <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
              <label class="form-label">ประเภทเอกสาร <span class="text-red-500">*</span></label>
              <select v-model="form.category" class="input w-full">
                <option value="">-- กรุณาเลือก --</option>
                <option v-for="option in categoryOptions" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </select>
              <p v-if="errors.category" class="error-message">{{ errors.category }}</p>
            </div>

            <div>
              <label class="form-label">ลำดับการแสดง</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="input w-full" />
            </div>
          </div>

          <div>
            <label class="form-label">รายละเอียด</label>
            <textarea v-model.trim="form.description" rows="3" class="input w-full resize-y" placeholder="คำอธิบายสั้น ๆ เกี่ยวกับเอกสาร"></textarea>
          </div>

          <div>
            <label class="form-label">
              ไฟล์เอกสาร <span v-if="!form.id" class="text-red-500">*</span>
            </label>

            <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 text-center hover:border-brand-500">
              <input
                ref="documentFile"
                type="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                class="hidden"
                @change="selectFile"
              />

              <i class="fas fa-cloud-upload-alt text-4xl text-brand-500"></i>
              <p class="mt-2 text-sm font-medium text-slate-700">
                {{ selectedFile.name || form.file_name || 'ยังไม่ได้เลือกไฟล์' }}
              </p>
              <p v-if="selectedFile.size" class="mt-1 text-xs text-slate-500">
                {{ formatFileSize(selectedFile.size) }}
              </p>
              <button type="button" class="mt-3 rounded-lg border bg-white px-4 py-2 text-sm hover:bg-slate-100" @click="$refs.documentFile.click()">
                เลือกไฟล์
              </button>
            </div>
            <p v-if="errors.file" class="error-message">{{ errors.file }}</p>
          </div>

          <div class="grid grid-cols-1 gap-4 rounded-xl bg-slate-50 p-4 sm:grid-cols-2">
            <label class="flex cursor-pointer items-center justify-between gap-4">
              <div>
                <div class="text-sm font-semibold text-slate-700">แสดงป้าย NEW</div>
                <div class="text-xs text-slate-500">เน้นว่าเป็นเอกสารเพิ่มใหม่</div>
              </div>
              <input v-model="form.is_new" type="checkbox" class="h-5 w-5 accent-blue-600" />
            </label>

            <label class="flex cursor-pointer items-center justify-between gap-4">
              <div>
                <div class="text-sm font-semibold text-slate-700">เผยแพร่</div>
                <div class="text-xs text-slate-500">ให้ประชาชนมองเห็นและดาวน์โหลด</div>
              </div>
              <input v-model="form.status" type="checkbox" class="h-5 w-5 accent-emerald-600" />
            </label>
          </div>

          <div class="flex justify-end gap-2 border-t pt-5">
            <button type="button" class="rounded-lg border bg-white px-5 py-2.5 hover:bg-slate-50" @click="closeModal">ปิด</button>
            <button type="submit" :disabled="isLoading" class="rounded-lg bg-brand-600 px-5 py-2.5 font-semibold text-white hover:bg-brand-800 disabled:opacity-50">
              {{ form.id ? 'บันทึกการแก้ไข' : 'เพิ่มเอกสาร' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

const emptyForm = () => ({
  id: null,
  name: '',
  description: '',
  category: '',
  sort_order: 0,
  is_new: true,
  status: true,
  file_name: '',
  file_url: '',
})

export default {
  name: 'DocumentDownloadSetting',

  data() {
    return {
      isLoading: false,
      showModal: false,
      items: [],
      form: emptyForm(),
      selectedFile: { file: null, name: '', size: 0 },
      errors: {},
      search: { keyword: '', category: '', status: '' },
      categoryOptions: [
        { value: 'manual', label: 'คู่มือ' },
        { value: 'form', label: 'แบบฟอร์ม' },
        { value: 'document', label: 'เอกสารเผยแพร่' },
        { value: 'other', label: 'อื่น ๆ' },
      ],
    }
  },

  computed: {
    filteredItems() {
      const keyword = this.search.keyword.toLowerCase()
      return this.items.filter((item) => {
        const matchedKeyword = !keyword || String(item.name || '').toLowerCase().includes(keyword)
        const matchedCategory = !this.search.category || item.category === this.search.category
        const matchedStatus = this.search.status === '' || Number(item.status) === Number(this.search.status)
        return matchedKeyword && matchedCategory && matchedStatus
      })
    },
  },

  mounted() {
    this.showData()
  },

  methods: {
    backPage() {
      window.location.href = '/admin/setting'
    },

    categoryLabel(value) {
      return this.categoryOptions.find((option) => option.value === value)?.label || 'อื่น ๆ'
    },

    fileIcon(fileName = '') {
      const extension = fileName.split('.').pop()?.toLowerCase()
      if (extension === 'pdf') return 'far fa-file-pdf text-red-500'
      if (['doc', 'docx'].includes(extension)) return 'far fa-file-word text-blue-500'
      if (['xls', 'xlsx'].includes(extension)) return 'far fa-file-excel text-green-600'
      if (extension === 'zip') return 'far fa-file-archive text-amber-600'
      return 'far fa-file text-slate-500'
    },

    formatFileSize(bytes) {
      if (!bytes) return '0 KB'
      if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
      return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
    },

    resetForm() {
      this.form = emptyForm()
      this.selectedFile = { file: null, name: '', size: 0 }
      this.errors = {}
      if (this.$refs.documentFile) this.$refs.documentFile.value = ''
    },

    addData() {
      this.resetForm()
      this.showModal = true
    },

    viewData(item) {
      this.resetForm()
      this.form = {
        id: item.id,
        name: item.name || '',
        description: item.description || '',
        category: item.category || '',
        sort_order: Number(item.sort_order || 0),
        is_new: Number(item.is_new) === 1,
        status: Number(item.status) === 1,
        file_name: item.file_name || '',
        file_url: item.file_url || '',
      }
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.resetForm()
    },

    selectFile(event) {
      const file = event.target.files?.[0]
      if (!file) return

      const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip']
      const extension = file.name.split('.').pop()?.toLowerCase()
      const maximumSize = 20 * 1024 * 1024

      if (!allowedExtensions.includes(extension)) {
        event.target.value = ''
        Swal.fire('ไฟล์ไม่ถูกต้อง', 'รองรับ PDF, Word, Excel และ ZIP เท่านั้น', 'warning')
        return
      }

      if (file.size > maximumSize) {
        event.target.value = ''
        Swal.fire('ไฟล์ใหญ่เกินไป', 'ไฟล์ต้องมีขนาดไม่เกิน 20 MB', 'warning')
        return
      }

      this.selectedFile = { file, name: file.name, size: file.size }
      this.errors.file = ''
    },

    validateForm() {
      this.errors = {}
      if (!this.form.name) this.errors.name = 'กรุณาระบุชื่อเอกสาร'
      if (!this.form.category) this.errors.category = 'กรุณาเลือกประเภทเอกสาร'
      if (!this.form.id && !this.selectedFile.file) this.errors.file = 'กรุณาเลือกไฟล์เอกสาร'
      if (this.form.id && !this.selectedFile.file && !this.form.file_name) this.errors.file = 'กรุณาเลือกไฟล์เอกสาร'
      return Object.keys(this.errors).length === 0
    },

    async showData() {
      this.isLoading = true
      try {
        const response = await axios.get('/admin/setting/document/load')
        this.items = response.data.status === 200 ? (response.data.item || []) : []
      } catch (error) {
        console.error(error)
        this.items = []
        Swal.fire('ผิดพลาด', 'ไม่สามารถโหลดข้อมูลเอกสารได้', 'error')
      } finally {
        this.isLoading = false
      }
    },

    async saveData() {
      if (!this.validateForm()) return

      const isEditing = Boolean(this.form.id)
      const formData = new FormData()

      if (this.form.id) formData.append('id', this.form.id)
      formData.append('name', this.form.name)
      formData.append('description', this.form.description || '')
      formData.append('category', this.form.category)
      formData.append('sort_order', String(this.form.sort_order || 0))
      formData.append('is_new', this.form.is_new ? '1' : '0')
      formData.append('status', this.form.status ? '1' : '0')
      if (this.selectedFile.file) formData.append('file', this.selectedFile.file)

      this.isLoading = true
      try {
        const response = await axios.post('/admin/setting/document', formData)
        if (response.data.status !== 200) throw new Error(response.data.message || 'บันทึกข้อมูลไม่สำเร็จ')

        this.showModal = false
        this.resetForm()
        await this.showData()
        Swal.fire('สำเร็จ', isEditing ? 'แก้ไขเอกสารสำเร็จ' : 'เพิ่มเอกสารสำเร็จ', 'success')
      } catch (error) {
        console.error(error)
        Swal.fire('ผิดพลาด', error.response?.data?.message || error.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error')
      } finally {
        this.isLoading = false
      }
    },

    async removeData(id) {
      const result = await Swal.fire({
        title: 'ลบเอกสาร?',
        text: 'ไฟล์เอกสารจะถูกลบออกจากระบบ',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ลบข้อมูล',
        cancelButtonText: 'ยกเลิก',
      })

      if (!result.isConfirmed) return

      this.isLoading = true
      try {
        const response = await axios.delete('/admin/setting/document', { params: { id } })
        if (response.data.status !== 200) throw new Error(response.data.message || 'ลบข้อมูลไม่สำเร็จ')
        await this.showData()
        Swal.fire('สำเร็จ', 'ลบเอกสารสำเร็จ', 'success')
      } catch (error) {
        console.error(error)
        Swal.fire('ผิดพลาด', error.response?.data?.message || 'ไม่สามารถลบเอกสารได้', 'error')
      } finally {
        this.isLoading = false
      }
    },
  },
}
</script>

<style scoped>
.form-label { display: block; margin-bottom: .375rem; color: #334155; font-size: .875rem; font-weight: 600; }
.error-message { margin-top: .25rem; color: #ef4444; font-size: .75rem; }
.new-badge { flex-shrink: 0; border-radius: 999px; background: linear-gradient(135deg, #ef4444, #f97316); padding: 2px 7px; color: white; font-size: 10px; font-weight: 800; letter-spacing: .04em; box-shadow: 0 3px 8px rgba(239,68,68,.2); }
.status-published, .status-hidden { display: inline-flex; border-radius: 999px; padding: 4px 9px; font-size: 11px; font-weight: 700; }
.status-published { background: #dcfce7; color: #15803d; }
.status-hidden { background: #f1f5f9; color: #64748b; }
</style>
