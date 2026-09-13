<template>
  <section class="evaluation-page">
    <div class="evaluation-hero">
      <div class="hero-orb hero-orb-left"></div>
      <div class="hero-orb hero-orb-right"></div>

      <div class="hero-content">
        <div class="hero-badge">เสียงของคุณช่วยพัฒนาบริการ</div>
        <h1>แบบประเมินความพึงพอใจ</h1>
        <p>ใช้เวลาเพียง 1–2 นาที ข้อมูลของท่านจะถูกเก็บเป็นความลับ</p>

        <div class="hero-icon" aria-hidden="true">
          <svg viewBox="0 0 120 120" fill="none">
            <rect
              x="32"
              y="22"
              width="62"
              height="80"
              rx="12"
              fill="white"
              stroke="#3B82F6"
              stroke-width="5"
            />
            <rect x="48" y="14" width="30" height="16" rx="7" fill="#3B82F6" />
            <path
              d="M45 47H65M45 61H62M45 75H58"
              stroke="#93C5FD"
              stroke-width="5"
              stroke-linecap="round"
            />
            <circle cx="83" cy="76" r="25" fill="#2563EB" />
            <circle cx="75" cy="71" r="2.5" fill="white" />
            <circle cx="91" cy="71" r="2.5" fill="white" />
            <path
              d="M73 82C79 88 87 88 93 82"
              stroke="white"
              stroke-width="4"
              stroke-linecap="round"
            />
          </svg>
        </div>
      </div>
    </div>

    <div class="evaluation-content">
      <form class="evaluation-card" @submit.prevent="submitEvaluation">
        <!-- ส่วนที่ 1 -->
        <section class="form-section">
          <div class="section-title">
            <span class="section-icon">
              <i class="fas fa-user"></i>
            </span>
            <h2>ส่วนที่ 1 ข้อมูลของผู้ใช้บริการ</h2>
          </div>

          <p class="section-description">กรุณากรอกข้อมูลของท่านให้ครบถ้วน</p>

          <div class="profile-grid">
            <div class="form-group">
              <label>เพศ <span class="required-mark">*</span></label>

              <div class="gender-options">
                <label
                  v-for="option in genderOptions"
                  :key="option.value"
                  class="radio-label"
                >
                  <input
                    v-model="form.gender"
                    type="radio"
                    name="gender"
                    :value="option.value"
                  />
                  <span>{{ option.label }}</span>
                </label>
              </div>

              <p v-if="errors.gender" class="error-message">
                {{ errors.gender }}
              </p>
            </div>

            <div class="form-group">
              <label for="age">อายุ <span class="required-mark">*</span></label>
              <div class="input-with-suffix">
                <input
                  id="age"
                  v-model.number="form.age"
                  type="number"
                  min="1"
                  max="120"
                  placeholder="ระบุอายุ"
                />
                <span>ปี</span>
              </div>
              <p v-if="errors.age" class="error-message">{{ errors.age }}</p>
            </div>

            <div class="form-group">
              <label for="education">
                3. ระดับการศึกษา
                <span class="required-mark">*</span>
              </label>

              <select
                id="education"
                v-model="form.education"
                name="qualification"
              >
                <option value="">-- กรุณาเลือก --</option>

                <option
                  v-for="option in educationOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>

              <p v-if="errors.education" role="alert" class="error-message">
                {{ errors.education }}
              </p>
            </div>

            <div class="form-group">
              <label for="occupation">
                4. อาชีพ
                <span class="required-mark">*</span>
              </label>

              <select id="occupation" v-model="form.occupation" name="work">
                <option value="">-- กรุณาเลือก --</option>

                <option
                  v-for="option in occupationOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>

              <p v-if="errors.occupation" role="alert" class="error-message">
                {{ errors.occupation }}
              </p>
            </div>
          </div>
        </section>

        <!-- ส่วนที่ 2 -->
        <section class="form-section satisfaction-section">
          <div class="section-title">
            <span class="section-icon"><i class="fas fa-star"></i></span>
            <h2>ส่วนที่ 2 ความพึงพอใจของผู้ใช้บริการ</h2>
          </div>

          <p class="section-description">
            กรุณาเลือกระดับความพึงพอใจในแต่ละหัวข้อ
          </p>

          <div class="question-list">
            <article
              v-for="(question, index) in normalizedQuestions"
              :key="question.id"
              class="question-card"
            >
              <h3>{{ question.question }}</h3>
              <p v-if="question.description">{{ question.description }}</p>

              <div class="answer-grid">
                <label
                  :class="[
                    'answer-card',
                    'answer-negative',
                    { selected: answers[question.id] === 1 },
                  ]"
                >
                  <input
                    v-model="answers[question.id]"
                    type="radio"
                    :name="`question-${question.id}`"
                    :value="1"
                  />
                  <span class="answer-face">☹</span>
                  <span class="answer-text">ไม่พึงพอใจ</span>
                </label>

                <label
                  :class="[
                    'answer-card',
                    'answer-positive',
                    { selected: answers[question.id] === 3 },
                  ]"
                >
                  <input
                    v-model="answers[question.id]"
                    type="radio"
                    :name="`question-${question.id}`"
                    :value="3"
                  />
                  <span class="answer-face">☺</span>
                  <span class="answer-text">พึงพอใจมาก</span>
                </label>
              </div>

              <p v-if="errors[`question_${question.id}`]" class="error-message">
                {{ errors[`question_${question.id}`] }}
              </p>
            </article>
          </div>

          <div class="form-group suggestion-group">
            <label for="suggestion">ข้อเสนอแนะเพิ่มเติม (ถ้ามี)</label>
            <textarea
              id="suggestion"
              v-model.trim="form.suggestion"
              maxlength="500"
              rows="4"
              placeholder="กรุณาเขียนข้อเสนอแนะของท่านที่นี่..."
            ></textarea>
            <div class="character-count">{{ form.suggestion.length }}/500</div>
          </div>
        </section>

        <div class="privacy-message">
          <i class="fas fa-lock"></i>
          <div>
            <strong>ข้อมูลของท่านจะถูกเก็บเป็นความลับ</strong>
            <span>กรมอนามัยจะใช้ข้อมูลไปเพื่อการพัฒนาการให้บริการเท่านั้น</span>
          </div>
        </div>

        <div class="form-actions">
          <button
            type="button"
            class="cancel-button"
            :disabled="submitting"
            @click="cancelForm"
          >
            ยกเลิก
          </button>
          <button type="submit" class="submit-button" :disabled="submitting">
            <i v-if="submitting" class="fas fa-spinner fa-spin"></i>
            <i v-else class="far fa-paper-plane"></i>
            {{ submitting ? "กำลังส่งข้อมูล..." : "ส่งแบบประเมิน" }}
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { computed, reactive, ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
  questions: {
    type: Array,
    default: () => [
      {
        id: 1,
        question: "ความพึงพอใจด้านการให้บริการ (การตอบสนองข้อร้องเรียน)",
        description: "เช่น การให้ข้อมูล คำแนะนำ และการตอบข้อซักถาม",
      },
      {
        id: 2,
        question:
          "ความพึงพอใจต่อการใช้งานระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน",
        description:
          "เช่น การค้นหาข้อมูล การยื่นเรื่องร้องเรียน และการติดตามสถานะ",
      },
    ],
  },
  submitUrl: {
    type: String,
    default: "/evaluation",
  },
  cancelUrl: {
    type: String,
    default: "/",
  },
});

const genderOptions = [
  { value: "1", label: "ชาย" },
  { value: "2", label: "หญิง" },
  { value: "3", label: "LGBTQ+" },
];

const educationOptions = [
  {
    value: 1,
    label: "ต่ำกว่าปริญญาตรี",
  },
  {
    value: 2,
    label: "ปริญญาตรี",
  },
  {
    value: 3,
    label: "ปริญญาโท",
  },
  {
    value: 4,
    label: "ปริญญาเอก",
  },
];

const occupationOptions = [
  {
    value: 1,
    label: "รับราชการ",
  },
  {
    value: 2,
    label: "พนักงานบริษัท/รัฐวิสาหกิจ",
  },
  {
    value: 3,
    label: "ธุรกิจส่วนตัว",
  },
  {
    value: 4,
    label: "รับจ้าง",
  },
  {
    value: 5,
    label: "นักเรียน/นักศึกษา",
  },
  {
    value: 6,
    label: "อื่น ๆ",
  },
];

const normalizedQuestions = computed(() =>
  props.questions.map((question, index) => ({
    id: question.id ?? index + 1,
    question: question.question || question.name || "",
    description: question.description || "",
  })),
);

const form = reactive({
  gender: "",
  age: "",
  education: "",
  occupation: "",
  suggestion: "",
});
const answers = reactive({});
const errors = reactive({});
const submitting = ref(false);

function clearErrors() {
  Object.keys(errors).forEach((key) => delete errors[key]);
}

function validateForm() {
  clearErrors();
  if (!form.gender) errors.gender = "กรุณาเลือกเพศ";
  if (!form.age || form.age < 1 || form.age > 120)
    errors.age = "กรุณาระบุอายุให้ถูกต้อง";
  if (!form.education) errors.education = "กรุณาเลือกระดับการศึกษา";
  if (!form.occupation) errors.occupation = "กรุณาเลือกอาชีพ";

  normalizedQuestions.value.forEach((question) => {
    if (![1, 3].includes(answers[question.id])) {
      errors[`question_${question.id}`] = "กรุณาเลือกคำตอบ";
    }
  });

  return Object.keys(errors).length === 0;
}

async function submitEvaluation() {
  if (!validateForm()) {
    await Swal.fire({
      title: "ข้อมูลไม่ครบ",
      text: "กรุณากรอกข้อมูลและตอบคำถามให้ครบถ้วน",
      icon: "warning",
      confirmButtonText: "ตกลง",
    });

    document.querySelector(".error-message")?.scrollIntoView({
      behavior: "smooth",
      block: "center",
    });

    return;
  }

  const payload = {
    gender: form.gender,
    age: Number(form.age),
    qualification: Number(form.education),
    work: Number(form.occupation),
    suggestion: form.suggestion || null,

    questions: normalizedQuestions.value.map((question) => ({
      id: question.id,
      sel: answers[question.id],
    })),
  };

  submitting.value = true;

  try {
    const response = await axios.post(props.submitUrl, payload, {
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });

    if (response.data.status !== 200) {
      throw new Error(response.data.message || "ไม่สามารถส่งแบบประเมินได้");
    }

    await Swal.fire({
      title: "ขอบคุณสำหรับความคิดเห็น",
      text: "ระบบได้รับแบบประเมินของท่านเรียบร้อยแล้ว",
      icon: "success",
      confirmButtonText: "ตกลง",
    });

    window.location.href = props.cancelUrl;
  } catch (error) {
    console.error(error);

    await Swal.fire({
      title: "เกิดข้อผิดพลาด",
      text:
        error.response?.data?.message ||
        error.message ||
        "ไม่สามารถส่งแบบประเมินได้",
      icon: "error",
      confirmButtonText: "ตกลง",
    });
  } finally {
    submitting.value = false;
  }
}

function cancelForm() {
  window.location.href = props.cancelUrl;
}
</script>

<style scoped>
.evaluation-page {
  min-height: 100vh;
  margin: -2.5rem 0;
  font-family: "Sarabun", sans-serif;
  background: #e9f7ff;
  color: #334155;
}
.evaluation-hero {
  position: relative;
  overflow: hidden;
  min-height: 330px;
  background: linear-gradient(120deg, #f4fcff 0%, #d9f2ff 52%, #bfe6ff 100%);
}
.hero-orb {
  position: absolute;
  border-radius: 999px;
}
.hero-orb-left {
  bottom: -150px;
  left: -120px;
  width: 330px;
  height: 330px;
  background: rgba(255, 255, 255, 0.65);
}
.hero-orb-right {
  top: -130px;
  right: -70px;
  width: 340px;
  height: 340px;
  background: rgba(56, 189, 248, 0.15);
}
.hero-content {
  position: relative;
  z-index: 1;
  max-width: 850px;
  margin: auto;
  padding: 45px 20px 0;
  text-align: center;
}
.hero-badge {
  display: inline-flex;
  padding: 7px 14px;
  border: 1px solid #bfdbfe;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.8);
  color: #1670d2;
  font-size: 13px;
  font-weight: 700;
}
.hero-content h1 {
  margin: 18px 0 6px;
  color: #09205c;
  font-size: clamp(32px, 4vw, 48px);
  font-weight: 800;
}
.hero-content h1::first-letter {
  color: inherit;
}
.hero-content p {
  margin: 0;
  color: #526580;
}
.hero-icon {
  width: 125px;
  height: 125px;
  margin: 12px auto 0;
}
.evaluation-content {
  position: relative;
  z-index: 5;
  display: block !important;
  width: 100%;
  max-width: 1060px;
  margin: -24px auto 0;
  padding: 0 18px 70px;
}

/* Reset ค่า .evaluation-card จาก CSS หน้า Home ที่ใช้ Grid 3 คอลัมน์ */
.evaluation-card {
  position: relative;
  display: block;
  width: 100%;
  min-height: 0;
  margin: 0;
  padding: 22px;
  overflow: visible;
  grid-template-columns: none !important;
  align-items: initial;
  gap: 0 !important;
  border: 1px solid rgba(186, 230, 253, 0.9);
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.98);
  box-shadow: 0 22px 50px rgba(14, 116, 144, 0.13);
}

.evaluation-card::before,
.evaluation-card::after {
  display: none;
  content: none;
}

.evaluation-card > .form-section,
.evaluation-card > .privacy-message,
.evaluation-card > .form-actions {
  position: relative;
  z-index: 1;
  width: 100%;
  grid-column: auto;
}
.form-section + .form-section {
  margin-top: 24px;
}
.section-title {
  display: flex;
  padding: 13px 17px;
  align-items: center;
  gap: 12px;
  border-radius: 14px;
  background: linear-gradient(100deg, #e4f3ff, #eef6ff);
}
.section-icon {
  display: grid;
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  place-items: center;
  border-radius: 50%;
  background: linear-gradient(135deg, #1689ff, #145be1);
  color: white;
}
.section-title h2 {
  margin: 0;
  color: #102b6a;
  font-size: 22px;
  font-weight: 800;
}
.section-description {
  margin: 9px 16px 15px;
  color: #64748b;
  font-size: 13px;
}
.profile-grid {
  display: grid;
  padding: 0 16px;
  grid-template-columns: 1fr 1fr;
  gap: 18px 28px;
}
.form-group label {
  display: block;
  margin-bottom: 7px;
  color: #172554;
  font-size: 14px;
  font-weight: 700;
}
.required-mark {
  color: #ef4444;
}
.radio-label span {
  color: #172554;
}
.form-group input[type="number"],
.form-group select,
.form-group textarea {
  width: 100%;
  border: 1px solid #cbd5e1;
  border-radius: 11px;
  background: white;
  color: #334155;
  outline: none;
}
.form-group input[type="number"],
.form-group select {
  height: 45px;
  padding: 0 13px;
}
.form-group textarea {
  padding: 12px 14px;
  resize: vertical;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
}
.gender-options {
  display: flex;
  min-height: 45px;
  align-items: center;
  gap: 30px;
}
.radio-label {
  display: inline-flex !important;
  margin: 0 !important;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-weight: 500 !important;
}
.radio-label input {
  width: 19px;
  height: 19px;
  accent-color: #1478f2;
}
.input-with-suffix {
  display: flex;
  overflow: hidden;
  border: 1px solid #cbd5e1;
  border-radius: 11px;
}
.input-with-suffix:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
}
.input-with-suffix input {
  flex: 1;
  border: 0 !important;
  box-shadow: none !important;
}
.input-with-suffix > span {
  display: grid;
  width: 45px;
  place-items: center;
  background: #eff6ff;
  color: #64748b;
}
.question-list {
  display: grid;
  gap: 13px;
}
.question-card {
  padding: 16px;
  border: 1px solid #cfe5fb;
  border-radius: 14px;
  background: linear-gradient(110deg, #f8fcff, #fff);
}
.question-card h3 {
  margin: 0;
  color: #102b6a;
  font-size: 15px;
  font-weight: 800;
}
.question-card > p {
  margin: 4px 0 0;
  color: #64748b;
  font-size: 12px;
}
.answer-grid {
  display: grid;
  margin-top: 12px;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}
.answer-card {
  position: relative;
  display: grid !important;
  min-height: 82px;
  margin: 0 !important;
  padding: 10px 14px;
  grid-template-columns: 42px 1fr;
  align-items: center;
  justify-content: center;
  gap: 10px;
  border: 1.5px solid;
  border-radius: 12px;
  cursor: pointer;
  transition:
    transform 0.15s ease,
    box-shadow 0.15s ease;
}
.answer-card:hover {
  transform: translateY(-1px);
}
.answer-card input {
  position: absolute;
  width: 1px;
  height: 1px;
  overflow: hidden;
  opacity: 0;
  pointer-events: none;
}
.answer-card:has(input:focus-visible) {
  outline: 3px solid rgba(20, 120, 242, 0.3);
  outline-offset: 2px;
}
.answer-negative {
  border-color: #fecaca;
  background: #fff8f8;
}
.answer-positive {
  border-color: #bbf7d0;
  background: #f7fff9;
}
.answer-card.selected {
  border-color: #1478f2;
  box-shadow: 0 0 0 3px rgba(20, 120, 242, 0.12);
}
.answer-face {
  display: grid;
  width: 42px;
  height: 42px;
  place-items: center;
  border-radius: 50%;
  font-size: 29px;
}
.answer-negative .answer-face {
  background: #fee2e2;
  color: #dc2626;
}
.answer-positive .answer-face {
  background: #d1fae5;
  color: #059669;
}
.answer-text {
  color: #172554 !important;
  font-size: 14px;
  font-weight: 800;
}
.suggestion-group {
  position: relative;
  margin-top: 18px;
}
.character-count {
  position: absolute;
  right: 11px;
  bottom: 8px;
  color: #64748b;
  font-size: 11px;
}
.error-message {
  margin: 5px 0 0 !important;
  color: #dc2626 !important;
  font-size: 12px !important;
}
.privacy-message {
  display: flex;
  margin-top: 20px;
  padding: 12px;
  align-items: center;
  justify-content: center;
  gap: 12px;
  border-radius: 12px;
  background: #eef4ff;
  color: #1747b0;
  text-align: left;
}
.privacy-message i {
  font-size: 21px;
}
.privacy-message strong,
.privacy-message span {
  display: block;
}
.privacy-message strong {
  font-size: 13px;
}
.privacy-message span {
  margin-top: 2px;
  color: #64748b;
  font-size: 10px;
}
.form-actions {
  display: flex;
  margin-top: 16px;
  justify-content: space-between;
  gap: 14px;
}
.form-actions button {
  min-width: 180px;
  min-height: 47px;
  padding: 10px 20px;
  border-radius: 11px;
  font-weight: 700;
  cursor: pointer;
}
.form-actions button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}
.cancel-button {
  border: 1px solid #94a3b8;
  background: white;
  color: #64748b;
}
.submit-button {
  border: 0;
  background: linear-gradient(135deg, #1496ff, #0758e8);
  color: white;
  box-shadow: 0 10px 22px rgba(7, 88, 232, 0.22);
}
.submit-button i {
  margin-right: 7px;
}

@media (max-width: 700px) {
  .evaluation-hero {
    min-height: 290px;
  }
  .hero-content {
    padding-top: 35px;
  }
  .evaluation-card {
    padding: 12px;
    border-radius: 18px;
  }
  .section-title {
    padding: 11px 12px;
  }
  .section-title h2 {
    font-size: 17px;
  }
  .profile-grid,
  .answer-grid {
    grid-template-columns: 1fr;
  }
  .profile-grid {
    padding: 0 5px;
    gap: 15px;
  }
  .gender-options {
    flex-wrap: wrap;
    gap: 14px 22px;
  }
  .answer-grid {
    gap: 9px;
  }
  .answer-card {
    min-height: 70px;
  }
  .form-actions {
    flex-direction: column-reverse;
  }
  .form-actions button {
    width: 100%;
  }
}
</style>
