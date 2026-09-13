<template>
  <div class="mx-auto max-w-7xl px-4 pb-14 pt-8 md:px-8 md:pt-11">
    <div class="relative overflow-hidden rounded-[2rem] border border-brand-100 bg-white p-6 shadow-soft md:p-10">
      <div class="absolute -left-10 top-0 h-40 w-40 rounded-full bg-brand-100/80 blur-2xl"></div>
      <div class="absolute bottom-0 right-0 h-44 w-44 rounded-full bg-cyan-100/90 blur-2xl"></div>
      <div class="absolute right-10 top-8 hidden h-20 w-20 rounded-3xl bg-gradient-to-br from-violet-200 to-cyan-100 opacity-70 md:block"></div>

      <div class="relative mx-auto max-w-3xl text-center">
        <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ติดตามเรื่องร้องเรียน</h2>
        <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
        <p class="mt-5 text-sm text-slate-600">กรุณากรอกข้อมูลเพื่อค้นหาสถานะเรื่องร้องเรียนของท่าน</p>
      </div>

      <form class="relative mx-auto mt-8 grid max-w-4xl gap-6 md:grid-cols-2" @submit.prevent="searchComplaint">
        <label class="block">
          <span class="text-sm font-bold text-slate-800">
            เลขที่ร้องเรียน <span class="text-red-500">*</span>
          </span>
          <input
            v-model.trim="form.code"
            type="text"
            autocomplete="off"
            :placeholder="`เช่น ${keyTitle}6900001`"
            :class="['tracking-input', { 'tracking-input-error': errors.code }]"
            @input="clearError('code')"
          />
          <span v-if="errors.code" class="mt-2 block text-xs text-red-500">{{ errors.code }}</span>
          <span v-else class="mt-2 block text-xs text-slate-500">รหัสอ้างอิงที่ได้รับเมื่อท่านยื่นเรื่องร้องเรียน</span>
        </label>

        <label class="block">
          <span class="text-sm font-bold text-slate-800">
            เบอร์มือถือผู้ร้อง 4 ตัวท้าย <span class="text-red-500">*</span>
          </span>
          <input
            v-model="form.phoneLast4"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="4"
            autocomplete="off"
            placeholder="เช่น 1234"
            :class="['tracking-input', { 'tracking-input-error': errors.phoneLast4 }]"
            @input="handlePhoneInput"
          />
          <span v-if="errors.phoneLast4" class="mt-2 block text-xs text-red-500">{{ errors.phoneLast4 }}</span>
          <span v-else class="mt-2 block text-xs text-slate-500">ตัวอย่าง 090-xxx-1234 ให้กรอก 1234</span>
        </label>

        <div v-if="errors.general" class="md:col-span-2">
          <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <span>{{ errors.general }}</span>
          </div>
        </div>

        <div class="flex justify-center pt-2 md:col-span-2">
          <button
            type="submit"
            :disabled="isLoading"
            class="inline-flex min-w-[230px] items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-violetplus px-9 py-3.5 font-bold text-white shadow-neon transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
          >
            <i v-if="isLoading" class="fas fa-spinner fa-spin"></i>
            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
            </svg>
            {{ isLoading ? 'กำลังค้นหา...' : 'ค้นหาเรื่องร้องเรียน' }}
          </button>
        </div>

        <p class="flex items-center justify-center gap-2 text-xs text-slate-500 md:col-span-2">
          <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 11.25h10.5A2.25 2.25 0 0 0 19.5 19.5v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
          </svg>
          ข้อมูลของท่านจะถูกเก็บเป็นความลับและปลอดภัย
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  keyTitle: { type: String, default: 'AA' },
  apiUrl: { type: String, default: '/api/complaints/tracking/verify' },
  resultUrl: { type: String, default: '/tracking/result' },
})

const form = reactive({ code: '', phoneLast4: '' })
const errors = reactive({ code: '', phoneLast4: '', general: '' })
const isLoading = ref(false)

function clearErrors() {
  errors.code = ''
  errors.phoneLast4 = ''
  errors.general = ''
}

function clearError(field) {
  errors[field] = ''
  errors.general = ''
}

function handlePhoneInput(event) {
  form.phoneLast4 = event.target.value.replace(/\D/g, '').slice(0, 4)
  clearError('phoneLast4')
}

function validateForm() {
  clearErrors()
  if (!form.code) errors.code = 'กรุณากรอกเลขที่ร้องเรียน'
  if (!/^\d{4}$/.test(form.phoneLast4)) errors.phoneLast4 = 'กรุณากรอกตัวเลขมือถือ 4 ตัวท้าย'
  return !errors.code && !errors.phoneLast4
}

async function searchComplaint() {
    if (!validateForm() || isLoading.value) {
        return
    }

    //isLoading.value.value = true
    errors.general = ''

    try {
        const response = await axios.post(
            '/follow',
            {
                code: form.code.toUpperCase(),
                phone_last4: form.phoneLast4,
            },
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        )

        const data = response.data

        if (
            data.status !== 200 ||
            !data.complaint_id
        ) {
            errors.general =
                data.message ||
                'ไม่พบข้อมูลเรื่องร้องเรียน กรุณาตรวจสอบข้อมูลอีกครั้ง'

            return
        }

        localStorage.setItem(
            'complaint_tracking_id',
            String(data.complaint_id)
        )

        if (data.tracking_token) {
            localStorage.setItem(
                'complaint_tracking_token',
                data.tracking_token
            )
        }

        window.location.assign('/tracking/result')
    } catch (error) {
        if (error.response?.status === 422) {
            const validationErrors =
                error.response.data.errors || {}

            errors.code =
                validationErrors.code?.[0] || ''

            errors.phoneLast4 =
                validationErrors.phone_last4?.[0] || ''

            errors.general =
                error.response.data.message || ''
        } else if (error.response?.status === 404) {
            errors.general =
                error.response.data.message ||
                'ไม่พบข้อมูลเรื่องร้องเรียน'
        } else if (error.response?.status === 429) {
            errors.general =
                'ค้นหาบ่อยเกินไป กรุณารอสักครู่แล้วลองใหม่'
        } else {
            errors.general =
                'ระบบไม่สามารถตรวจสอบข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
        }

        console.error(error)
    } finally {
        isLoading.value = false
    }
}
</script>

<style scoped>
.tracking-input {
  display: block;
  width: 100%;
  margin-top: 0.5rem;
  padding: 0.75rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 1rem;
  background: rgba(255,255,255,.95);
  color: #334155;
  font-size: .875rem;
  outline: none;
  transition: border-color .2s ease, box-shadow .2s ease;
}
.tracking-input:focus { border-color: #1689db; box-shadow: 0 0 0 4px rgba(22,137,219,.12); }
.tracking-input-error { border-color: #ef4444; }
.tracking-input-error:focus { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,.1); }
</style>
