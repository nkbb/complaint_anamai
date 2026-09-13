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

    <!-- Floating actions -->
    <div class="fixed right-4 top-[25%] z-40 flex flex-col gap-3">
      <button
        type="button"
        aria-label="เพิ่มแบนเนอร์"
        class="action-button flex items-center gap-2 rounded-xl border border-gray-200 bg-white/90 px-4 py-2 text-sm font-medium text-brand-600 shadow-lg backdrop-blur-sm transition hover:scale-105"
        @click="addData"
      >
        <i class="fas fa-plus"></i>
        <span>เพิ่ม</span>
      </button>

      <button
        type="button"
        aria-label="ย้อนกลับ"
        class="action-button flex items-center gap-2 rounded-xl border border-gray-200 bg-white/90 px-4 py-2 text-sm font-medium text-gray-800 shadow-lg backdrop-blur-sm transition hover:scale-105"
        @click="backPage"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="1.5"
          aria-hidden="true"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>ย้อนกลับ</span>
      </button>
    </div>

    <button
      type="button"
      class="ml-[60px] inline-flex items-center gap-2 rounded-md border border-brand-600 px-4 py-2 text-brand-600 hover:bg-brand-50"
      @click="addData"
    >
      <i class="fas fa-plus"></i>
      เพิ่มแบนเนอร์
    </button>

    <!-- Banner list -->
    <div class="mt-11 overflow-x-auto ">
      
      <table class="w-full border border-[#dee2e6] text-left text-sm">
        <thead>
          <tr class="bg-gray-50">
            <th class="w-[6%] border border-[#dee2e6] px-4 py-2 text-center">#</th>
            <th class="w-[32%] border border-[#dee2e6] px-4 py-2 text-center">
              รูป Desktop
            </th>
            <th class="w-[22%] border border-[#dee2e6] px-4 py-2 text-center">
              รูป Mobile
            </th>
            <th class="w-[25%] border border-[#dee2e6] px-4 py-2 text-center">
              Link
            </th>
            <th class="w-[15%] border border-[#dee2e6] px-4 py-2 text-center">
              จัดการ
            </th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(item, index) in items"
            :key="item.id"
            class="hover:bg-[#efefef]"
          >
            <td class="border border-[#dee2e6] px-4 py-2 text-center">
              {{ index + 1 }}
            </td>

            <td class="border border-[#dee2e6] px-4 py-2">
              <img
                v-if="getDesktopUrl(item)"
                :src="getDesktopUrl(item)"
                alt="รูปแบนเนอร์ Desktop"
                class="mx-auto aspect-[8/3] w-full max-w-[320px] rounded-lg border bg-gray-100 object-cover"
              />
              <span v-else class="block text-center text-gray-400">ไม่มีรูป</span>
            </td>

            <td class="border border-[#dee2e6] px-4 py-2">
              <img
                v-if="getMobileUrl(item)"
                :src="getMobileUrl(item)"
                alt="รูปแบนเนอร์ Mobile"
                class="mx-auto aspect-[4/5] h-32 rounded-lg border bg-gray-100 object-cover"
              />
              <span v-else class="block text-center text-gray-400">ไม่มีรูป</span>
            </td>

            <td class="break-all border border-[#dee2e6] px-4 py-2">
              {{ item.uri || '-' }}
            </td>

            <td class="border border-[#dee2e6] px-4 py-2 text-center">
              <div class="flex justify-center gap-4">
                <button
                  type="button"
                  class="text-blue-600 hover:text-blue-800"
                  title="แก้ไข"
                  @click="viewData(index)"
                >
                  <i class="far fa-edit"></i>
                </button>

                <button
                  type="button"
                  class="text-red-600 hover:text-red-800"
                  title="ลบ"
                  @click="removeData(item.id)"
                >
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="!items.length && !isLoading">
            <td colspan="5" class="border border-[#dee2e6] px-4 py-10 text-center text-gray-500">
              ไม่พบข้อมูลแบนเนอร์
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-11 flex justify-end">
      <a href="/admin/setting" class="rounded border px-4 py-2 hover:bg-gray-50">
        ย้อนกลับ
      </a>
    </div>

    <!-- Add/Edit modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 px-4 py-8"
      role="dialog"
      aria-modal="true"
      aria-labelledby="banner-modal-title"
      @click.self="closeModal"
    >
      <div class="relative w-full max-w-3xl rounded-xl bg-white p-6 shadow-xl">
        <h2 id="banner-modal-title" class="text-xl font-semibold text-brand-600">
          {{ form.id ? 'แก้ไขแบนเนอร์' : 'เพิ่มแบนเนอร์' }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
          เตรียมรูปแยกสำหรับ Desktop และ Mobile เพื่อให้แสดงผลได้พอดีกับหน้าจอ
        </p>

        <form class="mt-6" @submit.prevent="saveData">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Desktop upload -->
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                รูปสำหรับ Desktop <span class="text-red-500">*</span>
              </label>

              <div class="rounded-xl border-2 border-dashed border-gray-300 p-4 text-center transition hover:border-brand-500">
                <div class="mb-3 flex aspect-[8/3] w-full items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                  <img
                    v-if="desktopPreview"
                    :src="desktopPreview"
                    alt="ตัวอย่างรูป Desktop"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-sm text-gray-400">ยังไม่ได้เลือกรูป</span>
                </div>

                <button
                  type="button"
                  class="rounded-lg border bg-gray-100 px-4 py-2 text-sm hover:bg-gray-200"
                  @click="$refs.fileDesktop.click()"
                >
                  <i class="far fa-image mr-1"></i>
                  เลือกรูป Desktop
                </button>

                <p class="mt-2 text-xs text-gray-500">
                  แนะนำ 1920 × 720 px · JPG, PNG, WebP · ไม่เกิน 10 MB
                </p>
                <p v-if="files.desktop.name" class="mt-1 break-all text-xs text-blue-600">
                  {{ files.desktop.name }}
                </p>

                <input
                  ref="fileDesktop"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  class="hidden"
                  @change="selectImage($event, 'desktop')"
                />
              </div>
            </div>

            <!-- Mobile upload -->
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                รูปสำหรับ Mobile <span class="text-red-500">*</span>
              </label>

              <div class="rounded-xl border-2 border-dashed border-gray-300 p-4 text-center transition hover:border-brand-500">
                <div class="mx-auto mb-3 flex aspect-[4/5] h-[250px] items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                  <img
                    v-if="mobilePreview"
                    :src="mobilePreview"
                    alt="ตัวอย่างรูป Mobile"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="px-4 text-sm text-gray-400">ยังไม่ได้เลือกรูป</span>
                </div>

                <button
                  type="button"
                  class="rounded-lg border bg-gray-100 px-4 py-2 text-sm hover:bg-gray-200"
                  @click="$refs.fileMobile.click()"
                >
                  <i class="far fa-image mr-1"></i>
                  เลือกรูป Mobile
                </button>

                <p class="mt-2 text-xs text-gray-500">
                  แนะนำ 1080 × 1350 px · JPG, PNG, WebP · ไม่เกิน 10 MB
                </p>
                <p v-if="files.mobile.name" class="mt-1 break-all text-xs text-blue-600">
                  {{ files.mobile.name }}
                </p>

                <input
                  ref="fileMobile"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  class="hidden"
                  @change="selectImage($event, 'mobile')"
                />
              </div>
            </div>
          </div>

          <div class="mt-5">
            <label for="bannerUrl" class="mb-1 block text-sm font-medium text-gray-700">
              Link (ถ้ามี)
            </label>
            <input
              id="bannerUrl"
              v-model.trim="form.url"
              type="text"
              class="input w-full"
              placeholder="เช่น /complaint หรือ https://example.com"
            />
          </div>

          <div class="mt-6 flex justify-end gap-2">
            <button
              type="button"
              class="rounded border bg-white px-4 py-2 hover:bg-gray-50"
              @click="closeModal"
            >
              ปิด
            </button>

            <button
              type="submit"
              :disabled="isLoading"
              class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-800 disabled:cursor-not-allowed disabled:opacity-60"
            >
              {{ form.id ? 'บันทึกการแก้ไข' : 'เพิ่มแบนเนอร์' }}
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

const emptyFileState = () => ({
  file: null,
  name: '',
  preview: '',
})

const emptyForm = () => ({
  id: null,
  url: '',
  image_desktop: '',
  image_mobile: '',
  image_desktop_url: '',
  image_mobile_url: '',
})

export default {
  name: 'BannerSettingComponent',

  data() {
    return {
      isLoading: false,
      items: [],
      showModal: false,
      form: emptyForm(),
      files: {
        desktop: emptyFileState(),
        mobile: emptyFileState(),
      },
    }
  },

  computed: {
    desktopPreview() {
      return this.files.desktop.preview || this.form.image_desktop_url || ''
    },

    mobilePreview() {
      return this.files.mobile.preview || this.form.image_mobile_url || ''
    },
  },

  mounted() {
    this.showData()
  },

  beforeUnmount() {
    this.clearPreviewUrls()
  },

  methods: {
    backPage() {
      window.location.href = '/admin/setting'
    },

    getDesktopUrl(item) {
      return item.image_desktop_url || item.image_url || ''
    },

    getMobileUrl(item) {
      return item.image_mobile_url || item.image_desktop_url || item.image_url || ''
    },

    addData() {
      this.resetForm()
      this.showModal = true
    },

    viewData(index) {
      const item = this.items[index]

      this.resetForm()
      this.form = {
        id: item.id,
        url: item.uri || '',
        image_desktop: item.image_desktop || item.image || '',
        image_mobile: item.image_mobile || '',
        image_desktop_url: this.getDesktopUrl(item),
        image_mobile_url: this.getMobileUrl(item),
      }
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.resetForm()
    },

    resetForm() {
      this.clearPreviewUrls()
      this.form = emptyForm()
      this.files = {
        desktop: emptyFileState(),
        mobile: emptyFileState(),
      }

      if (this.$refs.fileDesktop) this.$refs.fileDesktop.value = ''
      if (this.$refs.fileMobile) this.$refs.fileMobile.value = ''
    },

    clearPreviewUrls() {
      ;['desktop', 'mobile'].forEach((device) => {
        const preview = this.files?.[device]?.preview
        if (preview) URL.revokeObjectURL(preview)
      })
    },

    selectImage(event, device) {
      const selectedFile = event.target.files?.[0]
      if (!selectedFile) return

      const allowedTypes = ['image/jpeg', 'image/png', 'image/webp']
      const maximumSize = 10 * 1024 * 1024

      if (!allowedTypes.includes(selectedFile.type)) {
        event.target.value = ''
        Swal.fire({
          title: 'ไฟล์ไม่ถูกต้อง',
          text: 'รองรับเฉพาะไฟล์ JPG, PNG และ WebP',
          icon: 'warning',
          confirmButtonText: 'ตกลง',
        })
        return
      }

      if (selectedFile.size > maximumSize) {
        event.target.value = ''
        Swal.fire({
          title: 'ไฟล์มีขนาดใหญ่เกินไป',
          text: 'รูปภาพแต่ละไฟล์ต้องมีขนาดไม่เกิน 10 MB',
          icon: 'warning',
          confirmButtonText: 'ตกลง',
        })
        return
      }

      if (this.files[device].preview) {
        URL.revokeObjectURL(this.files[device].preview)
      }

      this.files[device] = {
        file: selectedFile,
        name: selectedFile.name,
        preview: URL.createObjectURL(selectedFile),
      }
    },

    async showData() {
      this.isLoading = true

      try {
        const response = await axios.get('/admin/setting/banner/load', {
          params: { type: 1 },
        })

        this.items = response.data.status === 200
          ? (response.data.item || [])
          : []
      } catch (error) {
        console.error(error)
        this.items = []
      } finally {
        this.isLoading = false
      }
    },

    async saveData() {
      const isEditing = Boolean(this.form.id)
      const hasDesktopImage = Boolean(
        this.files.desktop.file || this.form.image_desktop || this.form.image_desktop_url
      )
      const hasMobileImage = Boolean(
        this.files.mobile.file || this.form.image_mobile || this.form.image_mobile_url
      )

      if (!hasDesktopImage || !hasMobileImage) {
        Swal.fire({
          title: 'ข้อมูลไม่ครบ',
          text: 'กรุณาเลือกรูปสำหรับ Desktop และ Mobile',
          icon: 'warning',
          confirmButtonText: 'ตกลง',
        })
        return
      }

      const formData = new FormData()
      if (this.form.id) formData.append('id', this.form.id)
      if (this.files.desktop.file) {
        formData.append('image_desktop', this.files.desktop.file)
      }
      if (this.files.mobile.file) {
        formData.append('image_mobile', this.files.mobile.file)
      }
      formData.append('uri', this.form.url || '')
      formData.append('type', '1')

      this.isLoading = true

      try {
        const response = await axios.post('/admin/setting/banner', formData)

        if (response.data.status !== 200) {
          throw new Error(response.data.message || 'บันทึกข้อมูลไม่สำเร็จ')
        }

        this.showModal = false
        this.resetForm()
        await this.showData()

        await Swal.fire({
          title: 'สำเร็จ',
          text: isEditing ? 'แก้ไขแบนเนอร์สำเร็จ' : 'เพิ่มแบนเนอร์สำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
        })
      } catch (error) {
        console.error(error)
        Swal.fire({
          title: 'ผิดพลาด',
          text: error.response?.data?.message || error.message || 'ไม่สามารถบันทึกข้อมูลได้',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } finally {
        this.isLoading = false
      }
    },

    removeData(id) {
      if (!id) return

      Swal.fire({
        title: 'ลบข้อมูล?',
        text: 'ยืนยันการลบข้อมูลอีกครั้ง',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ดำเนินการต่อ',
        cancelButtonText: 'ยกเลิก',
      }).then(async (result) => {
        if (!result.isConfirmed) return

        this.isLoading = true

        try {
          const response = await axios.delete('/admin/setting/banner', {
            params: { id },
          })

          if (response.data.status !== 200) {
            throw new Error(response.data.message || 'ลบข้อมูลไม่สำเร็จ')
          }

          await this.showData()
          Swal.fire({
            title: 'สำเร็จ',
            text: 'ลบข้อมูลสำเร็จ',
            icon: 'success',
            confirmButtonText: 'ตกลง',
          })
        } catch (error) {
          console.error(error)
          Swal.fire({
            title: 'ผิดพลาด',
            text: error.response?.data?.message || 'ไม่สามารถลบข้อมูลได้',
            icon: 'error',
            confirmButtonText: 'ตกลง',
          })
        } finally {
          this.isLoading = false
        }
      })
    },
  },
}
</script>
