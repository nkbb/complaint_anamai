<template>
  <div
    class="flex flex-col mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] mb-[80px] mt-11"
  >
    <Swiper
      v-if="isload"
      :modules="[Autoplay, Navigation, Pagination]"
      :autoplay="{ delay: 2000 }"
      :loop="true"
      :slides-per-view="3"
      :space-between="16"
      :breakpoints="{
        0: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }"
      navigation
      pagination
      class="w-full mx-auto"
    >
      <SwiperSlide
        v-for="(item, index) in banners"
        :key="index"
      >
        <a :href="item.link">
          <img
            :src="item.img"
            class="w-full h-auto object-contain rounded"
          />
        </a>
      </SwiperSlide>
    </Swiper>

    <!-- ตัวนี้จะแสดงได้ปกติ -->
    ฟหกดหฟกดห
  </div>
</template>
<script>
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

export default {
  components: {
    Swiper,
    SwiperSlide
  },
  props: {
    isload: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      banners: [
        { img: '/images/banner/baner01.jpg', link: '#' },
        { img: '/images/banner/baner02.jpg', link: '#' },
        { img: '/images/banner/baner03.jpg', link: '#' },
        { img: '/images/banner/baner04.jpg', link: '#' }
      ]
    }
  }
}
</script>
