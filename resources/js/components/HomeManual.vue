<template>
  <div>
    <div @click="showModal=!showModal" class="z-[90] fixed left-0 hover:h-[110px] top-[50%] -translate-y-1/2 origin-left rotate-90 text-white border border-white bg-[#00ACC1] px-2 py-1 rounded text-lg pb-[36px] hover:cursor-pointer">
        คู่มือการใช้งาน
    </div>
    <div
      v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down">
        <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
          <h2 class="text-lg text-[#3fbbc0]">คู่มือการใช้งาน</h2>
          <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
        </div>
        <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
          <div class="text-[#007bff]">ขั้นตอนการร้องเรียน-ร้องทุกข์</div>
          <div>1. กดเลือกที่แทบเมนู ร้องเรียน-ร้องทุกข์ ด้านบน หรือกดเลือกจากเนื้อหา การแจ้งร้องเรียนได้จากหน้าแรก</div>
          <div>2. อ่านเงื่อนไขการให้บริการและกดยอมรับ</div>
          <div>3. กรอกข้อมมูลในการร้องเรียน กรณีที่ไม่ต้องการเปิดเผยข้อมูลส่วนตัว สามารถซ่อนได้ โดยการเลือกที่ (ปกปิด)</div>
          <div>5. กดปุ่มบันทึกและยืนยันการส่งข้อมูลร้องเรียน</div>
          <div class="text-center mt-2">คู่มือวิธีการร้องเรียน - ร้องทุกข์ คลิก >> <a href="/manual/appeal" target="_blank" class="hover:cursor-pointer text-red-600"><i class="fas fa-file-pdf"></i></a></div>
        </div>
        <div class="px-6 md:px-8 mt-6 flex flex-col gap-1">
          <div class="text-[#007bff]">ขั้นตอนการติดตามเรื่องร้องเรียน</div>
          <div>1. กดเลือกที่แทบเมนู ติดตามเรื่องร้องเรียน</div>
          <div>2. กรอกข้อมมูลหมายเลขการร้องเรียน ซึ่งจะได้มาตอนที่แจ้งครั้งแรก</div>
          <div>3. กดปุ่มค้นหา เพื่อทำการค้นหาข้อมูลการร้องเรียน</div>
          <div>4. ข้อมูลการร้องเรียนจะแสดงเนื้อเกี่ยวกับการดำเนินการ ทั้งนี้ผู้ใช้บริการสามารถตรวจสอบความคืบหน้าในการดำเนินการได้ทันที</div>
          <div class="text-center mt-2">คู่มือวิธีการติดตามเรื่องร้องเรียน คลิก >> <a href="/manual/trace" target="_blank" class="hover:cursor-pointer text-red-600"><i class="fas fa-file-pdf"></i></a></div>
        </div>
        <div class="flex px-6 md:px-8 pt-6 pb-4 border-t justify-end mt-8">
          <button type="button" @click="showModal = false" class="px-4 py-2 bg-[#6c757d] text-white rounded border">
              ปิด
            </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>


export default {
  data() {
    return {
      showModal: false,
    }
  },
}
</script>