@extends('layouts.app')

@section('pageTitle', 'ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')
    <div class="-mt-11 ">


        <image-carouse-component id="home" isload="true" :initial-banners="{{ Illuminate\Support\Js::from($banners) }}"></image-carouse-component>


        <!-- <home-type-component></home-type-component> -->



        <!-- <image-carouse-component isload="true"></image-carouse-component> -->
        <!-- <image-carouse-banner></image-carouse-banner> -->

        <!-- <div class="grid justify-items-center py-[68px] bg-[#13849c]">
                                        <h1 class="flex justify-center"><div class="border-b-[2px] pb-3 border-[#ffffff] text-[#ffffff] text-[28px]">รับเรื่องร้องเรียน</div></h1>
                                        
                                        <div class="text-center text-[#ffffff] text-base mt-8">ช่องทางการติดต่อ</div>
                                        <div class="flex flex-row gap-2 justify-center mt-6">
                                            <div class="bg-[#ffffff] rounded-[50%] py-[5px] px-[10px]"><i class="far fa-envelope text-[#1d684a] text-[24px]"></i></div>
                                            <div class="text-[#ffffff] text-xl" >4000@anamai.mail.go.th</div>
                                        </div>
                                    </div> -->


        <!-- <home-manual-component></home-manual-component> -->
        <!-- <home-agreement-component></home-agreement-component> -->
        <!-- <home-evaluation-component></home-evaluation-component> -->
        <!-- <home-cookie-component></home-cookie-component> -->
        <!-- <home-comments-component></home-comments-component> -->

        {{-- <section id="home" class="hero -mt-11">
            <div class="container hero-grid">
                <div class="hero-copy"><span class="eyebrow">● ระบบรับเรื่องร้องเรียนออนไลน์</span>
                    <h1>ทุกความคิดเห็นของคุณ<br>ช่วยพัฒนาบริการสุขภาพ<br><em>ให้ดียิ่งขึ้น</em></h1>
                    <p>ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย พร้อมรับฟังทุกเสียง เพื่อให้บริการด้วยความใส่ใจ โปร่งใส
                        และเป็นธรรม</p>
                    <div class="hero-actions"><a class="btn btn-primary" href="/complaint">✎ ยื่นเรื่องร้องเรียน</a><a
                            class="btn btn-outline" href="#tracking">⌕ ติดตามสถานะ</a></div>
                </div>
                <div class="hero-visual">
                    <div class="photo-fallback">👨‍👩‍👦‍👦</div><img class="hero-photo"
                        src="/images/home/complaint-hero.png" alt="ประชาชนใช้บริการระบบร้องเรียน"
                        onerror="this.style.display='none'">
                </div>
            </div>
            <div class="container">
                <div class="trust">
                    <div class="trust-item">
                        <div class="round-icon">♢</div>
                        <div><strong>ปลอดภัย</strong><small>ข้อมูลได้รับการคุ้มครอง</small></div>
                    </div>
                    <div class="trust-item">
                        <div class="round-icon">⟳</div>
                        <div><strong>ติดตามได้</strong><small>ตรวจสอบสถานะได้ตลอด 24 ชั่วโมง</small></div>
                    </div>
                    <div class="trust-item">
                        <div class="round-icon">◉</div>
                        <div><strong>โปร่งใส</strong><small>กระบวนการตรวจสอบชัดเจน</small></div>
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- <section id="services" class="services section-blue">
            <div class="container">
                <div class="mx-auto -mt-1 max-w-7xl px-4 py-10 md:px-8">
                    <div class="grid gap-5 md:grid-cols-3">
                        <a id="complaint" href="#"
                            class="group relative overflow-hidden rounded-3xl border border-brand-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                            <div
                                class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-brand-500 via-cyan-400 to-sky-300">
                            </div>
                            <div
                                class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-lg shadow-brand-500/25">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">ยื่นเรื่องร้องเรียน</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">แจ้งเรื่องร้องเรียน ข้อคิดเห็น
                                หรือปัญหาการให้บริการของหน่วยงาน</p>
                            <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">
                                คลิกเพื่อยื่นเรื่อง
                                <span class="transition group-hover:translate-x-1">→</span>
                            </p>
                        </a>

                        <a href="#tracking"
                            class="group relative overflow-hidden rounded-3xl border border-cyan-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                            <div
                                class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-brand-500 to-violetplus">
                            </div>
                            <div
                                class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-cyan-400 to-brand-600 text-white shadow-lg shadow-cyan-400/25">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-width="2"
                                        d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">ติดตามเรื่องร้องเรียน</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                ตรวจสอบสถานะความคืบหน้าของเรื่องร้องเรียนที่คุณได้ยื่นไว้</p>
                            <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">คลิกเพื่อติดตาม
                                <span class="transition group-hover:translate-x-1">→</span>
                            </p>
                        </a>

                        <a id="download" href="#"
                            class="group relative overflow-hidden rounded-3xl border border-violet-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                            <div
                                class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-violetplus via-brand-500 to-cyan-300">
                            </div>
                            <div
                                class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-violetplus to-brand-600 text-white shadow-lg shadow-violet-400/25">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <!-- กระดาษแบบประเมิน -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5.25h6M9 9h6M9 12.75h3
                                        M6.75 3h10.5a2.25 2.25 0 0 1 2.25 2.25v13.5
                                        A2.25 2.25 0 0 1 17.25 21H6.75
                                        A2.25 2.25 0 0 1 4.5 18.75V5.25
                                        A2.25 2.25 0 0 1 6.75 3Z" />
                                    <!-- เครื่องหมายถูก -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m9 17 1.5 1.5L14 15" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">แบบประเมิมความพึงพอใจ</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                ช่วยให้เราทราบความคิดเห็นของท่านเกี่ยวกับบริการของเรา</p>
                            <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">
                                คลิกเพื่อทำแบบประเมิน
                                <span class="transition group-hover:translate-x-1">→</span>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </section> --}}

        <section id="topics" class="topics section-blue">
            <div class="container">
                <div class="heading md:mt-16 mt-14">
                    <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ขั้นตอนง่าย ๆ เพียง 3 ขั้นตอน</h2>
                    <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div>
                            <h3>ยื่นเรื่องร้องเรียน/ส่งคำชมเชย</h3>
                            <p>กรอกข้อมูลให้ครบถ้วนเพื่อประโยชน์ในการตรวจสอบ</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div>
                            <h3>ตรวจสอบและดำเนินการ</h3>
                            <p>เจ้าหน้าที่ตรวจสอบข้อมูลและดำเนินการตามขั้นตอน</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div>
                            <h3>แจ้งผลการดำเนินการ</h3>
                            <p>แจ้งผลผ่านช่องทางที่ท่านระบุไว้</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-b border-[#a9d9ff] my-[72px]"></div>
            <div class="container">
                <div class="heading mt-[65px]">
                    <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ประเด็นการร้องเรียน</h2>
                    <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
                    <p>เลือกประเภทเรื่องที่ต้องการร้องเรียน</p>
                </div>
                <div class="topics-container">
                    <div class="topic-grid">
                        <a class="topic-card is-featured" href="/complaint?type_id=1">
                        <div class="topic-image-wrap">
                            <img src="images/topic/01-public-health-law.jpg" alt="เอกสารกฎหมายด้านการสาธารณสุข" loading="lazy">
                            <span class="topic-number">01</span>
                        </div>
                        <div class="topic-content">
                            <h2>พ.ร.บ. การสาธารณสุข</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=2">
                        <div class="topic-image-wrap">
                            <img src="images/topic/02-infant-formula.jpg" alt="การเตรียมนมสำหรับทารกอย่างปลอดภัย" loading="lazy">
                            <span class="topic-number">02</span>
                        </div>
                        <div class="topic-content">
                            <h2>พ.ร.บ. นมผง</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=3">
                        <div class="topic-image-wrap">
                            <img src="images/topic/03-teen-pregnancy.jpg" alt="เจ้าหน้าที่ให้คำปรึกษาวัยรุ่น" loading="lazy">
                            <span class="topic-number">03</span>
                        </div>
                        <div class="topic-content">
                            <h2>การตั้งครรภ์ในวัยรุ่น</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=4">
                        <div class="topic-image-wrap">
                            <img src="images/topic/04-public-nuisance.jpg" alt="ประชาชนพูดคุยเรื่องปัญหาในชุมชน" loading="lazy">
                            <span class="topic-number">04</span>
                        </div>
                        <div class="topic-content">
                            <h2>เหตุเดือดร้อนรำคาญ</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=5">
                        <div class="topic-image-wrap">
                            <img src="images/topic/05-food-water-sanitation.jpg" alt="ตลาดอาหารสะอาดและน้ำดื่มปลอดภัย" loading="lazy">
                            <span class="topic-number">05</span>
                        </div>
                        <div class="topic-content">
                            <h2>สุขาภิบาลอาหารและน้ำ</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=6">
                        <div class="topic-image-wrap">
                            <img src="images/topic/06-health-impact.jpg" alt="แพทย์ให้คำปรึกษาเรื่องผลกระทบต่อสุขภาพ" loading="lazy">
                            <span class="topic-number">06</span>
                        </div>
                        <div class="topic-content">
                            <h2>ผลกระทบต่อสุขภาพ</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=7">
                        <div class="topic-image-wrap">
                            <img src="images/topic/07-all-ages-health.jpg" alt="ครอบครัวทุกช่วงวัยออกกำลังกายร่วมกัน" loading="lazy">
                            <span class="topic-number">07</span>
                        </div>
                        <div class="topic-content">
                            <h2>ส่งเสริมสุขภาพทุกกลุ่มวัย</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=8">
                        <div class="topic-image-wrap">
                            <img src="images/topic/08-staff-complaint.jpg" alt="เจ้าหน้าที่ให้บริการประชาชน" loading="lazy">
                            <span class="topic-number">08</span>
                        </div>
                        <div class="topic-content">
                            <h2>ร้องเรียนเจ้าหน้าที่กรมอนามัย</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=9">
                        <div class="topic-image-wrap">
                            <img src="images/topic/09-feedback-compliment.jpg" alt="ประชาชนให้ข้อเสนอแนะและคำชมเชย" loading="lazy">
                            <span class="topic-number">09</span>
                        </div>
                        <div class="topic-content">
                            <h2>ข้อเสนอแนะและข้อชมเชย</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>

                        <a class="topic-card" href="/complaint?type_id=10">
                        <div class="topic-image-wrap">
                            <img src="images/topic/10-other.jpg" alt="แบบฟอร์มสอบถามเรื่องอื่น ๆ" loading="lazy">
                            <span class="topic-number">10</span>
                        </div>
                        <div class="topic-content">
                            <h2>อื่น ๆ</h2>
                            <span class="topic-link">ดูรายละเอียด <b>→</b></span>
                        </div>
                        </a>
                    </div>
                </div>
                <div id="tracking"></div>
            </div>
        </section>

        <section class="tracking section-blue">
            <home-follow-component></home-follow-component>
            <!-- Satisfaction Evaluation -->
            <section class="evaluation-section">
                <div class="evaluation-card">
                    <!-- Illustration -->
                    <div class="evaluation-illustration" aria-hidden="true">
                        <svg
                            viewBox="0 0 120 120"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="60" cy="60" r="54" fill="#EEF2FF" />

                            <rect
                                x="31"
                                y="27"
                                width="54"
                                height="69"
                                rx="10"
                                fill="white"
                                stroke="#6366F1"
                                stroke-width="4"
                            />

                            <rect
                                x="47"
                                y="20"
                                width="23"
                                height="14"
                                rx="6"
                                fill="#6366F1"
                            />

                            <rect
                                x="42"
                                y="45"
                                width="9"
                                height="9"
                                rx="2"
                                fill="#DBEAFE"
                                stroke="#3B82F6"
                                stroke-width="2"
                            />

                            <path
                                d="M44 49L47 52L52 46"
                                stroke="#2563EB"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M59 49H75"
                                stroke="#94A3B8"
                                stroke-width="3"
                                stroke-linecap="round"
                            />

                            <rect
                                x="42"
                                y="62"
                                width="9"
                                height="9"
                                rx="2"
                                fill="#DBEAFE"
                                stroke="#3B82F6"
                                stroke-width="2"
                            />

                            <path
                                d="M44 66L47 69L52 63"
                                stroke="#2563EB"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M59 66H75"
                                stroke="#94A3B8"
                                stroke-width="3"
                                stroke-linecap="round"
                            />

                            <rect
                                x="42"
                                y="79"
                                width="9"
                                height="9"
                                rx="2"
                                fill="#DBEAFE"
                                stroke="#3B82F6"
                                stroke-width="2"
                            />

                            <path
                                d="M44 83L47 86L52 80"
                                stroke="#2563EB"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M59 83H75"
                                stroke="#94A3B8"
                                stroke-width="3"
                                stroke-linecap="round"
                            />

                            <circle
                                cx="87"
                                cy="83"
                                r="20"
                                fill="url(#evaluationGradient)"
                            />

                            <circle cx="80" cy="79" r="2" fill="white" />
                            <circle cx="94" cy="79" r="2" fill="white" />

                            <path
                                d="M79 87C83 92 91 92 95 87"
                                stroke="white"
                                stroke-width="3"
                                stroke-linecap="round"
                            />

                            <defs>
                                <linearGradient
                                    id="evaluationGradient"
                                    x1="67"
                                    y1="63"
                                    x2="107"
                                    y2="103"
                                    gradientUnits="userSpaceOnUse"
                                >
                                    <stop stop-color="#38BDF8" />
                                    <stop offset="1" stop-color="#6366F1" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="evaluation-content">
                        <div class="evaluation-label">
                            <span class="evaluation-label-dot"></span>
                            เสียงของคุณมีความหมาย
                        </div>

                        <h2>ช่วยเราพัฒนาบริการให้ดียิ่งขึ้น</h2>

                        <p>
                            ร่วมประเมินความพึงพอใจในการใช้บริการ
                            ใช้เวลาเพียง 1–2 นาที
                        </p>
                    </div>

                    <!-- Button -->
                    <div class="evaluation-action">
                        <a
                            href="/evaluation"
                            class="evaluation-button"
                            aria-label="ทำแบบประเมินความพึงพอใจ"
                        >
                            <span>ทำแบบประเมินความพึงพอใจ</span>

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M5 12H19M19 12L13 6M19 12L13 18"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
            {{-- <div class="border-b border-[#a9d9ff] my-[72px]"></div>
            <div class="mx-auto max-w-7xl px-4 pb-14 md:px-8">
                <div class="survey-box">
                    <div class="survey-copy">
                        <div class="survey-face">☺</div>
                        <div>
                            <h2>ช่วยเราพัฒนาการให้บริการ</h2>
                            <p>ทุกความคิดเห็นของท่านมีความหมาย ร่วมประเมินเพื่อให้กรมอนามัยให้บริการที่ดียิ่งขึ้น</p>
                        </div>
                    </div><a class="btn" href="/evaluation"><i class="fas fa-clipboard-check text-[42px]"></i>
                        ทำแบบประเมิน<br />ความพึงพอใจ <i class="fas fa-angle-right"></i></a>
                </div>
            </div> --}}
        </section>

        <section  class="wave-bg section-blue py-14">
            <div class="mx-auto max-w-7xl px-4 md:px-8">
                <div class="text-center">
                    <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ข้อมูลสำหรับประชาชน</h2>
                    <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
                </div>

                <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="#"
                        class="group rounded-3xl border border-brand-100 bg-gradient-to-b from-blue-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                        <div
                            class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-brand-600 shadow-sm">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M9 5.25h6M9 9h6M9 12.75h3M6.75 3h10.5A2.25 2.25 0 0 1 19.5 5.25v13.5A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75V5.25A2.25 2.25 0 0 1 6.75 3Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-extrabold text-slate-900">ขั้นตอนการร้องเรียน</h3>
                        <p class="mt-3 text-xs leading-6 text-slate-500">
                            ขั้นตอนการยื่นเรื่องร้องเรียนและการพิจารณาดำเนินการ</p>
                        <p class="mt-4 text-sm font-bold text-brand-600">ดูขั้นตอน →</p>
                    </a>

                    <a href="#"
                        class="group rounded-3xl border border-cyan-100 bg-gradient-to-b from-cyan-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                        <div
                            class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-cyan-600 shadow-sm">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M12 6v6l4 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-extrabold text-slate-900">ระยะเวลาดำเนินการ</h3>
                        <p class="mt-3 text-xs leading-6 text-slate-500">ระยะเวลาในการพิจารณาตามประเภทของเรื่องร้องเรียน
                        </p>
                        <p class="mt-4 text-sm font-bold text-cyan-600">ดูรายละเอียด →</p>
                    </a>

                    <a href="#"
                        class="group rounded-3xl border border-violet-100 bg-gradient-to-b from-violet-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                        <div
                            class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-violet-600 shadow-sm">
                            <svg class="h-14 w-14" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2a8 8 0 0 0-8 8c0 3.92 2.83 7.18 6.55 7.87V21l3.02-3.02A8.002 8.002 0 0 0 12 2Zm.05 12.75a1.1 1.1 0 1 1-.01-2.2 1.1 1.1 0 0 1 .01 2.2ZM13 11.25h-1.8c0-2.4 2.4-2.24 2.4-3.72 0-.82-.63-1.32-1.55-1.32-.93 0-1.58.52-1.72 1.43H8.45C8.64 5.82 10.1 4.6 12.1 4.6c2.1 0 3.45 1.15 3.45 2.84 0 2.2-2.36 2.35-2.55 3.81Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-extrabold text-slate-900">คำถามที่พบบ่อย (FAQ)</h3>
                        <p class="mt-3 text-xs leading-6 text-slate-500">รวมคำถามที่พบบ่อยและวิธีแก้ไขเบื้องต้น</p>
                        <p class="mt-4 text-sm font-bold text-violet-600">ดูคำถามที่พบบ่อย →</p>
                    </a>

                    <a href="/#contact"
                        class="group rounded-3xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
                        <div
                            class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-emerald-600 shadow-sm">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M18 10.5V9a6 6 0 0 0-12 0v1.5m12 0A2.25 2.25 0 0 1 20.25 12.75v1.5A2.25 2.25 0 0 1 18 16.5h-1.5v-6H18Zm-12 0A2.25 2.25 0 0 0 3.75 12.75v1.5A2.25 2.25 0 0 0 6 16.5h1.5v-6H6Zm12 6v.75A3.75 3.75 0 0 1 14.25 21H12" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-extrabold text-slate-900">ช่องทางการติดต่อ</h3>
                        <p class="mt-3 text-xs leading-6 text-slate-500">
                            ช่องทางการติดต่อและสอบถามข้อมูลเพิ่มเติมจากกรมอนามัย</p>
                        <p class="mt-4 text-sm font-bold text-emerald-600">ดูช่องทางติดต่อ →</p>
                    </a>
                </div>
            </div>
            <div class="md:border-b border-none border-[#a9d9ff] my-11 md:my-[72px]"></div>
            <div class="mx-auto  max-w-7xl px-4 pb-8 md:px-8">
                <div class="blue-panel overflow-hidden rounded-[2rem] px-6 py-8 text-white shadow-soft md:px-10">
                    <div class="absolute"></div>
                    <div class="grid gap-8 md:grid-cols-[1.35fr_1fr_1fr_1fr] md:items-center">
                        <div class="flex items-center gap-5 border-white/20 md:border-r md:pr-8">
                            <div
                                class="grid h-20 w-20 shrink-0 place-items-center rounded-full bg-white text-brand-600 shadow-lg">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-9.75 6.75L2.25 6.75" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-white/90">ช่องทางการติดต่อเรื่องร้องเรียน</p>
                                <p class="mt-1 text-2xl font-extrabold md:text-3xl">4000@anamai.mail.go.th</p>
                                <p class="mt-2 text-sm text-white/85">เราพร้อมรับฟังและดูแลทุกเรื่องร้องเรียน</p>
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75M12 15v2.25M6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25v-6a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6A2.25 2.25 0 0 0 6.75 21Z" />
                            </svg>
                            <h3 class="font-extrabold">ปลอดภัยและเป็นความลับ</h3>
                            <p class="mt-2 text-xs leading-6 text-white/80">
                                ข้อมูลของท่านได้รับการคุ้มครองตามกฎหมายคุ้มครองข้อมูลส่วนบุคคล</p>
                        </div>

                        <div class="text-center md:text-left">
                            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0M18 9.75h3m-1.5-1.5v3" />
                            </svg>
                            <h3 class="font-extrabold">เป็นธรรม โปร่งใส</h3>
                            <p class="mt-2 text-xs leading-6 text-white/80">ดำเนินการตรวจสอบอย่างเป็นธรรมและไม่เลือกปฏิบัติ
                            </p>
                        </div>

                        <div class="text-center md:text-left">
                            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="m9 12.75 2.25 2.25L15.75 9M21 12l-2.1 2.1.3 2.96-2.96.3L14.1 19.5 12 21l-2.1-1.5-2.14-2.14-2.96-.3.3-2.96L3 12l2.1-2.1-.3-2.96 2.96-.3L9.9 4.5 12 3l2.1 1.5 2.14 2.14 2.96.3-.3 2.96L21 12Z" />
                            </svg>
                            <h3 class="font-extrabold">มุ่งมั่นพัฒนา</h3>
                            <p class="mt-2 text-xs leading-6 text-white/80">นำข้อร้องเรียนและข้อเสนอแนะไปพัฒนาบริการ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="contact"> </div>

        </section>

        <div  class="contact -mb-11">
            <div class="container contact-row">
                <div>
                    <i class="fas fa-headset text-2xl"></i>
                    <div><strong>สายด่วนกรมอนามัย</strong><small>1478</small></div>
                </div>
                <div>
                    <i class="far fa-envelope text-2xl"></i>
                    <div><strong>อีเมล</strong><small>4000@anamai.mail.go.th</small></div>
                </div>
                <div>
                    <i class="far fa-clock text-2xl"></i>
                    <div><strong>เวลาทำการ</strong><small>จันทร์–ศุกร์ 08.30–16.30 น.</small></div>
                </div>
            </div>
        </div>

    </div>
@endsection
