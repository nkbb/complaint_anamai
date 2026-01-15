<template>
  <!-- ปุ่มเปิด Modal -->
  <div
  v-if="showModal"
  class="fixed inset-0 bg-black bg-opacity-90 z-[999] flex items-center justify-center overflow-hidden"
  @click.self="showModal = false"
>
  <div class="relative w-full h-full flex items-center justify-center">

    <!-- ปุ่มปิด -->
    <button
      @click="showModal = false"
      class="absolute bottom-5 left-1/2 -translate-x-1/2 bg-white/80 hover:bg-white
             text-black px-4 py-2 rounded shadow"
    >
      ปิด
    </button>

    <!-- รูปภาพเต็มจอ -->
    <img
      :src="image_url"
      class="max-w-full max-h-full object-contain"
    />
  </div>
</div>





</template>
<script>
export default {
  data() {
    return {
      showModal: false,
      image_url: '',
    }
  },
  props:['image'],
  mounted() {
    const i = JSON.parse(this.image);
    const lastTime = localStorage.getItem('last_show_time');
    if (lastTime) {
      const last = parseInt(lastTime);     // เวลาเดิม (ms)
      const now = Date.now();              // เวลาปัจจุบัน (ms)
      const diffMs = now - last;           // ส่วนต่าง ms

      const diffMinutes = Math.floor(diffMs / 60000); // แปลงเป็นนาที
      const diffSeconds = Math.floor(diffMs / 1000);  // แปลงเป็นวินาที

      if (diffMs >= 600000) { // 10 นาที = 600000 ms
        if(i){
          this.showModal = true;
          this.image_url = i.image_url;
          localStorage.setItem('last_show_time', Date.now());
        }
      }
    }else{
      if(i){
        this.showModal = true;
        this.image_url = i.image_url;
        localStorage.setItem('last_show_time', Date.now());
      }
    }

    
  },
  methods: {
   
  }
}
</script>