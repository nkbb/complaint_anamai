<template>
  <div>
    <div @click="showModal=!showModal" class="z-[91] hover:h-[110px] fixed left-0 top-[65%] -translate-y-1/2 origin-left rotate-90 text-white border border-white bg-[#00ACC1] px-2 py-1 rounded text-lg pb-[36px] hover:cursor-pointer">
        ข้อตกลงการให้บริการ
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-[999] flex items-start justify-center bg-black bg-opacity-50 pt-[5%]"
    >
      <!-- กล่องเนื้อหา -->
      <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative animate-fade-in-down ">
        <div class="flex flex-row px-6 md:px-8 pt-6 pb-4 border-b justify-between">
          <h2 class="text-lg text-[#3fbbc0]">ข้อตกลงระดับการให้บริการ</h2>
          <i @click="showModal = false" class="fas fa-times hover:cursor-pointer -mt-2 -mr-2"></i>
        </div>
        <div class="overflow-y-auto max-h-[60vh]">
          <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
            <table class=" w-full border border-[#dee2e6] text-left text-sm">
              <tbody>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6] text-[#007bff]">งานที่ให้บริการ</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">การจัดการข้อคิดเห็นข้อร้องเรียน</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6] text-[#007bff]">หน่วยงานที่รับผดิชอบ</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">งานข้อคิดเห็นข้อร้องเรียนสํานกังานเลขานุการกรม</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-6 md:px-8 mt-5 flex flex-col gap-1">
            <table class=" w-full border border-[#dee2e6] text-left text-sm">
              <tbody>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6] text-[#007bff]" colspan="2">งานที่ให้บริการ</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]" colspan="2">งานข้อคิดเห็นข้อร้องเรียนสํานกังานเลขานุการกรม</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]">1. ทางโทรศัพท์ 02 590 8000, 02 149 5555</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">วันจันทร์ ถึงวนั ศุกร์ ตั้งแต่เวลา 08.30 – 16.30 น. (ยกเว้นวันหยุดที่ทางราชการกําหนด)</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]">2.ตู้รับข้อคิดเห็น</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">	วันจันทร์ ถึงวนั ศุกร์ตั้งแต่เวลา 08.30 – 16.30 น. (ยกเว้นวันหยุดที่ทางราชการกําหนด))</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]">3. โทรสาร 02 149 5512</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">วันจันทร์ ถึงวนั ศุกร์ตั้งแต่เวลา 08.30 – 16.30 น. (ยกเว้นวันหยุดที่ทางราชการกําหนด)</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]">4. งานข้อคิดเห็นข้อร้องเรียน สํานักงานเลขานุการกรม</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">วันจันทร์ ถึงวนั ศุกร์ ตั้งแต่เวลา 08.30 – 16.30 น. (ยกเว้นวันหยุดที่ทางราชการกําหนด)</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]">5. ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต (E-Complain)</td>
                  <td class="px-6 py-2 border border-[#dee2e6]">วันจันทร์ ถึงวนั ศุกร์ ตั้งแต่เวลา 08.30 – 16.30 น. (ยกเว้นวันหยุดที่ทางราชการกําหนด)</td>
                </tr>
                <tr class="hover:bg-[#efefef]">
                  <td class="px-6 py-2 border border-[#dee2e6]" colspan="2">หมายเหตุ ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิตเปิดให้บริการทุกวัน ตลอด 24 ชั่วโมง แต่เจ้าหน้าที่ดําเนินการเรื่องร้องทุกข์เฉพาะวันและเวลาราชการ</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-6 md:px-8 mt-6 flex flex-col gap-1">
            <div class="text-[#007bff]">ระดับการให้บริการ (ระยะเวลา) :</div>
            <div>1. ช่องทางโทรศัพท์ ใช้ระยะเวลาทั้งสิ้น ไม่เกิน 2 ชั่วโมง</div>
            <div>2. ช่องทางตู้รับข้อคิดเห็น ใช้ระยะเวลาทั้งสิ้น ไม่เกิน 2 วันทําการ</div>
            <div>3. ช่องทางโทรสาร ใช้ระยะเวลาทั้งสิ้น ไม่เกิน 2 วนั ทําการ</div>
            <div>4. ช่องทางงานข้อคิดเห็นข้อร้องเรียน สํานักงานเลขานุการกรม ใช้ระยะเวลาทั้งสิ้น ไม่เกิน 2 วนั ทํากา</div>
            <div>5. ช่องทางระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต (E-Complain) ใช้ระยะเวลาทั้งสิ้น ไม่เกิน 2 วันทําการ</div>
          </div>
          <div class="px-6 md:px-8 mt-6 flex flex-col gap-1">
            <div class="text-[#007bff]">เริ่มนับระยะเวลาเมื่อ :</div>
            <div class="indent-8">เจ้าหน้าที่บันทึกข้อมูลข้อคิดเห็นข้อร้องเรียนเข้าสู่ระบบบริหารจัดการข้อคิดเห็น ข้อร้องเรียน กรมสุขภาพจิต (E-Complain)</div>
          </div>
          <div class="px-6 md:px-8 mt-6 flex flex-col gap-1">
            <div class="text-[#007bff]">สิ้นสุดระยะเวลาเมื่อ :</div>
            <div class="indent-8">เจ้าหน้าที่ได้บนั ทึกข้อมูลการดําเนิน การในระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต (E-Complain) ว่าได้มีการประสานส่งข้อคิดเห็นข้อร้องเรียน หรือชี้แจง หรือให้คําแนะนําแก่ผู้ร้อง หรือขอข้อมูลเพิ่มเติมจากผู้ร้อง หรือระงับเรื่อง หรือยุติเรื่อง หรือรวมเรื่องแล้วแต่กรณี</div>
          </div>
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