<template>
  <section class="hero-carousel" aria-label="ข่าวประชาสัมพันธ์" style="padding: 0px !important;">
    <Swiper
      :modules="modules"
      :slides-per-view="1"
      :loop="true"
      :speed="700"
      effect="fade"
      :fade-effect="{ crossFade: true }"
      :autoplay="{
        delay: 7000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      }"
      :pagination="{
        clickable: true,
        dynamicBullets: false,
      }"
      :navigation="true"
      :keyboard="{
        enabled: true,
      }"
      :a11y="{
        enabled: true,
        prevSlideMessage: 'สไลด์ก่อนหน้า',
        nextSlideMessage: 'สไลด์ถัดไป',
        paginationBulletMessage: 'ไปยังสไลด์ที่ {{index}}',
      }"
      class="hero-swiper"
    >
      <!-- Slide 1: HTML ทั้งหน้า -->
     
      <SwiperSlide>
        <div class="hero-slide hero-html-slide">
          <div class="hero-decoration hero-decoration-left"></div>
          <div class="hero-decoration hero-decoration-right"></div>

          <div class="hero-container">
            <div class="hero-content">
              <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                ระบบรับเรื่องร้องเรียนออนไลน์
              </div>

              <h1>
                ทุกความคิดเห็นของคุณ
                <span>ช่วยพัฒนาบริการสุขภาพ</span>
                <strong>ให้ดียิ่งขึ้น</strong>
              </h1>

              <p>
                ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย
                พร้อมรับฟังทุกเสียง เพื่อให้บริการด้วยความใส่ใจ
                โปร่งใส และเป็นธรรม
              </p>

              <div class="hero-actions">
                <a href="/complaint" class="hero-button hero-button-primary">
                  <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m16.862 4.487 1.687-1.688a1.875 1.875
                         0 1 1 2.652 2.652L10.582 16.07
                         a4.5 4.5 0 0 1-1.897 1.13L6 18
                         l.8-2.685a4.5 4.5 0 0 1
                         1.13-1.897l8.932-8.931Z"
                    />
                  </svg>

                  ยื่นเรื่องร้องเรียน
                </a>

                <a
                  href="/#tracking"
                  class="hero-button hero-button-outline"
                >
                  <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-width="2"
                      d="m21 21-4.35-4.35M10.5 18
                         a7.5 7.5 0 1 1 0-15
                         7.5 7.5 0 0 1 0 15Z"
                    />
                  </svg>

                  ติดตามเรื่องร้องเรียน
                </a>
              </div>
            </div>

            <div class="hero-visual">
              <img :src="'/images/home/complaint-hero.png'" alt="ประชาชนใช้บริการระบบร้องเรียนกรมอนามัย" />
            </div>
          </div>
        </div>
      </SwiperSlide>

      <!-- Slide 2: รูปภาพเดียว 
      <SwiperSlide>
        <a
          href="/complaint"
          class="hero-slide hero-image-slide"
          aria-label="ยื่นเรื่องร้องเรียนออนไลน์"
        >
          <picture>
            <source
              media="(max-width: 767px)"
              :srcset="'/images/slides/complaint-mobile.jpg'"
            />

            <img
              :src="'/images/slides/complaint-desktop.jpg'"
              alt="ประชาสัมพันธ์ระบบรับเรื่องร้องเรียนออนไลน์"
            />
          </picture>
        </a>
      </SwiperSlide>
      -->
    
    </Swiper>
  </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'

import {
  A11y,
  Autoplay,
  EffectFade,
  Keyboard,
  Navigation,
  Pagination,
} from 'swiper/modules'

import 'swiper/css'
import 'swiper/css/a11y'
import 'swiper/css/effect-fade'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const modules = [
  A11y,
  Autoplay,
  EffectFade,
  Keyboard,
  Navigation,
  Pagination,
]
</script>

<style scoped>
.hero-carousel {
  --hero-height: clamp(480px, 43vw, 680px);
  position: relative;
  overflow: hidden;
  background: #dff3ff;
}

.hero-swiper {
  width: 100%;
  height: var(--hero-height);
}

.hero-slide {
  position: relative;
  display: block;
  width: 100%;
  height: var(--hero-height);
  overflow: hidden;
}

/* HTML slide */

.hero-html-slide {
  background:
    radial-gradient(
      circle at 85% 20%,
      rgba(79, 181, 255, 0.32),
      transparent 28%
    ),
    radial-gradient(
      circle at 10% 90%,
      rgba(255, 255, 255, 0.8),
      transparent 28%
    ),
    linear-gradient(
      115deg,
      #f2fbff 0%,
      #d9f1ff 50%,
      #bfe5ff 100%
    );
}

.hero-decoration {
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
}

.hero-decoration-left {
  left: -160px;
  bottom: -180px;
  width: 430px;
  height: 430px;
  background: rgba(255, 255, 255, 0.38);
}

.hero-decoration-right {
  right: -100px;
  top: -150px;
  width: 420px;
  height: 420px;
  background: rgba(53, 163, 244, 0.14);
}

.hero-container {
  position: relative;
  z-index: 2;
  display: grid;
  width: min(1280px, calc(100% - 48px));
  height: 100%;
  margin: 0 auto;
  grid-template-columns: 1.05fr 0.95fr;
  align-items: center;
  gap: 40px;
}

.hero-content {
  font-family: "sarabun", sans-serif;
  padding-bottom: 35px;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  padding: 9px 16px;
  border: 1px solid rgba(8, 119, 239, 0.22);
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.76);
  color: #075bd8;
  font-size: 13px;
  font-weight: 700;
  backdrop-filter: blur(10px);
}

.hero-badge-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #0ca678;
  box-shadow: 0 0 0 4px rgba(12, 166, 120, 0.12);
}

.hero-content h1 {
  margin: 21px 0 17px;
  color: #09205c;
  font-size: clamp(37px, 4vw, 61px);
  font-weight: 800;
  letter-spacing: -1.5px;
  line-height: 1.16;
}

.hero-content h1 span,
.hero-content h1 strong {
  display: block;
}

.hero-content h1 strong {
  color: #10845f;
}

.hero-content p {
  max-width: 590px;
  margin: 0;
  color: #425678;
  font-size: 16px;
  line-height: 1.8;
}

.hero-actions {
  display: flex;
  gap: 14px;
  margin-top: 28px;
}

.hero-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
  min-height: 50px;
  padding: 12px 23px;
  border-radius: 13px;
  font-weight: 700;
  text-decoration: none;
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.hero-button:hover {
  transform: translateY(-2px);
}

.hero-button svg {
  width: 20px;
  height: 20px;
}

.hero-button-primary {
  background: linear-gradient(135deg, #0877ef, #0752cc);
  color: #fff;
  box-shadow: 0 12px 28px rgba(7, 91, 216, 0.28);
}

.hero-button-outline {
  border: 1.5px solid #0877ef;
  background: rgba(255, 255, 255, 0.82);
  color: #075bd8;
}

.hero-visual {
  align-self: end;
  height: 92%;
}

.hero-visual img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: center bottom;
  filter: drop-shadow(0 18px 28px rgba(20, 80, 130, 0.18));
}

/* Image-only slide */

.hero-image-slide picture,
.hero-image-slide img {
  display: block;
  width: 100%;
  height: 100%;
}

.hero-image-slide img {
  object-fit: cover;
  object-position: center;
}

/* Swiper controls */

.hero-swiper :deep(.swiper-button-prev),
.hero-swiper :deep(.swiper-button-next) {
  width: 45px;
  height: 45px;
  border: 1px solid rgba(255, 255, 255, 0.7);
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.88);
  color: #075bd8;
  box-shadow: 0 8px 24px rgba(5, 67, 140, 0.16);
  backdrop-filter: blur(10px);
}

.hero-swiper :deep(.swiper-button-prev) {
  left: 22px;
}

.hero-swiper :deep(.swiper-button-next) {
  right: 22px;
}

.hero-swiper :deep(.swiper-button-prev::after),
.hero-swiper :deep(.swiper-button-next::after) {
  font-size: 16px;
  font-weight: 800;
}

.hero-swiper :deep(.swiper-pagination) {
  bottom: 18px;
}

.hero-swiper :deep(.swiper-pagination-bullet) {
  width: 9px;
  height: 9px;
  background: #5b83ac;
  opacity: 0.5;
  transition: width 0.2s ease;
}

.hero-swiper :deep(.swiper-pagination-bullet-active) {
  width: 30px;
  border-radius: 99px;
  background: #0877ef;
  opacity: 1;
}

@media (max-width: 900px) {
  .hero-carousel,
  .hero-swiper,
  .hero-slide {
    --hero-height: 610px;
  }

  .hero-container {
    grid-template-columns: 1fr;
  }

  .hero-content {
    max-width: 660px;
    padding: 55px 20px 80px;
  }

  .hero-visual {
    display: none;
  }
}

@media (max-width: 640px) {
  .hero-carousel,
  .hero-swiper,
  .hero-slide {
    --hero-height: 570px;
  }

  .hero-container {
    width: calc(100% - 30px);
  }

  .hero-content {
    padding: 43px 5px 75px;
  }

  .hero-content h1 {
    font-size: 38px;
  }

  .hero-content p {
    font-size: 14px;
  }

  .hero-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .hero-swiper :deep(.swiper-button-prev),
  .hero-swiper :deep(.swiper-button-next) {
    display: none;
  }
}

@media (prefers-reduced-motion: reduce) {
  .hero-button {
    transition: none;
  }
}
</style>