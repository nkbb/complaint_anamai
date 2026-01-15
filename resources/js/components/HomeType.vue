<template>
  <div>
    <div class="flex justify-center mt-11 mb-10">
        <h1 class="border-b-[5px] border-[#3fbbc0] text-[#3fbbc0] text-[28px]">แจ้งเรื่องร้องเรียน</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] mb-[80px] justify-items-center gap-6 mt-11">
        <div @click="clickComplaint(1)" class="grid justify-items-center hover:cursor-pointer">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">การให้บริการ</div>
          <div class="text-sm mt-2 text-center">การกระทำหรือการดำเนินการอย่างใดอย่างหนึ่ง เพื่อตอบสนองความต้องการของบุคคล หรือองค์กรให้ได้รับความพึงพอใจ</div>
        </div>
        <div  @click="clickComplaint(2)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_2.jpg'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">การบริหารจัดการ</div>
          <div class="text-sm mt-2 text-center">การดำเนินงาน หรือการปฏิบัติงานใดๆ ของหน่วยงานของรัฐ และ/หรือ เจ้าหน้าที่ของรัฐ เช่น การบริหารทรัพยากรบุคคล</div>
        </div>
        <div  @click="clickComplaint(3)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">การรักษาผิดพลาด</div>
          <div class="text-sm mt-2 text-center">การรักษาพยาบาล หมายถึง รูปแบบการรักษาที่ตั้งอยู่บนการสันนิษฐานและการวินิจฉัยของแพทย์ ซึ่งผู้ร้องเรียนอาจเห็นว่าการักษาผิดขั้นตอน</div>
        </div>
        <div  @click="clickComplaint(4)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_4.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">พฤติกรรมส่วนตัว</div>
          <div class="text-sm mt-2 text-center">การกระทำ ของบุคคลในทุกลักษณะ ทั้งที่เป็นโดยธรรมชาติทางสรีระและที่จงใจกระทำ</div>
        </div>
        <div  @click="clickComplaint(5)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_5.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">ค่าตอบแทน</div>
          <div class="text-sm mt-2 text-center">ค่าใช้จ่ายต่างๆ ที่องค์การจ่ายให้แก่ผู้ปฏิบัติงาน ค่าใช้จ่ายนี้อาจจ่ายในรูปตัวเงินหรือมิใช่ตัวเงินก็ได้ เพื่อตอบแทนการปฏิบัติงานตามหน้าที่ความรับผิดชอบ </div>
        </div>
        <div  @click="clickComplaint(6)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_6.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">การทุจริตของเจ้าหน้าที่</div>
          <div class="text-sm mt-2 text-center">การแสวงหาประโยชน์ที่มิควรได้โดยชอบด้วยกฎหมาย สำหรับตนเองหรือผู้อื่น</div>
        </div>
        <div  @click="clickComplaint(7)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">วินัยข้าราชการ</div>
          <div class="text-sm mt-2 text-center">ข้อบัญญัติที่วางไว้เป็นหลักกำกับพฤติกรรม และมีมาตรการสำหรับควบคุมความประพฤติ และการกระทำของข้าราชการ</div>
        </div>
        <div  @click="clickComplaint(8)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">ประชาชนขัดแย้งกับเจ้าหน้าที่ของรัฐ</div>
          <div class="text-sm mt-2 text-center">การขัดแย้งหรือความไม่พอใจส่วนตัวของบุคคลใดบุคคลหนึ่ง ต่อเจ้าหน้าที่ของรัฐคนใดคนหนึ่ง</div>
        </div>
        <div  @click="clickComplaint(9)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">การคุ้มครองผู้บริโภค</div>
          <div class="text-sm mt-2 text-center">การปกป้องดูแลผู้บริโภค ให้ได้รับความปลอดภัย เป็นธรรม และประหยัด จากการบริโภคสินค้าและบริการ</div>
        </div>
        <div  @click="clickComplaint(10)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">สวัสดิการของรัฐ</div>
          <div class="text-sm mt-2 text-center">รัฐหรือประเทศที่รัฐบาลมีบทบาทสำคัญในการสนับสนุนส่งเสริม และจัดสวัสดิการสังคมให้แก่ประชาชน ในประเทศอย่างจริงจังและเป็นระบบ</div>
        </div>
        <div  @click="clickComplaint(11)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_1.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">ขอความอนุเคราะห์/ขอความช่วยเหลือ</div>
          <div class="text-sm mt-2 text-center">การขอความช่วยเหลือ, ความเอื้อเฟื้อ, ความเกื้อหนุน, ความเจือจุนด้านต่าง ๆ ตามความต้องการของบุคคลนั้น ๆ</div>
        </div>
        <div  @click="clickComplaint(12)" class="grid justify-items-center">
          <img :src="'/images/complaint_type_12.png'" class=" w-[150px] h-[150px] rounded-full object-cover" />
          <div class="text-lg mt-4 text-center">ข้อเสนอแนะและชมเชย</div>
          <div class="text-sm mt-2 text-center">เรื่องที่ผู้รับบริการหรือผู้มีส่วนได้ส่วนเสียมีข้อเสนอแนะ ข้อคิดเห็นหรือชมเชย เกี่ยวกับการปฏิบัติงาน</div>
        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'SettingUnitComponent',
  data() {
    return {
      isLoading: false,
    }
  },
  methods: {
    clickComplaint(type){
      sessionStorage.setItem('_complaint_type', type)
      window.location = '/complaint'
    }
  }
}
</script>