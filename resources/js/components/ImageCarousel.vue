<template>
  <div class="flex flex-col mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] mb-[80px] mt-11  justify-items-center ">
  <Swiper
    v-if="isload"
    :modules="[Autoplay, Navigation, Pagination]"
    :autoplay="{ delay: 2000 }"
    :loop="true"
    navigation
    pagination
    class="w-full mx-auto"
  >
    <SwiperSlide  v-for="(item, index) in images" :key="index">
      <img
      :src="item.image_url"
      class="w-full h-auto object-contain rounded"
    />
    </SwiperSlide>
  </Swiper>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const images = ref([])
defineProps({
  isload: {
    type: Boolean,
    default: false
  }
})

onMounted(async () => {
  try {
    const res = await fetch('/get/banner') 
    const data = await res.json()

    if (data.status === 200) {
      images.value = data.banners
    }
   // data ต้องมี image_url
  } catch (error) {
    console.error('Load banner failed:', error)
  }
})
</script>
