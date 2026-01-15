<template>
  <Swiper
    :modules="[Autoplay, Navigation, Pagination]"
    :autoplay="{ delay: 5000 }"
    :loop="true"
    navigation
    pagination
    class="w-full mx-auto"
  >
  {{ images }}
    <SwiperSlide v-for="(item, index) in images" :key="index">
      <img :src="item.image_url" class="w-full h-[736px] object-cover rounded" />
    </SwiperSlide>
  </Swiper>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const images = ref([])

onMounted(async () => {
  try {
    const res = await fetch('/get/banner')   // ← URL API ของคุณ
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
