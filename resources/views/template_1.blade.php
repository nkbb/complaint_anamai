<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ระบบอนามัย ข้อคิดเห็นข้อร้องเรียน</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { thai: ['Noto Sans Thai', 'sans-serif'] },
          colors: {
            brand: {
              50: '#ecf7ff', 100: '#d8efff', 200: '#b9e4ff', 300: '#85d4ff',
              400: '#45b8ff', 500: '#1495f0', 600: '#0a78d3', 700: '#0a61ab',
              800: '#0f528d', 900: '#124575'
            },
            health: '#008b58',
            mint: '#13c7a1',
            skyplus: '#55c6ff',
            violetplus: '#7b6dff',
            peach: '#ffb86b'
          },
          boxShadow: {
            soft: '0 20px 50px rgba(16, 72, 138, .12)',
            card: '0 14px 34px rgba(16, 72, 138, .10)',
            neon: '0 12px 30px rgba(20,149,240,.28)'
          },
          backgroundImage: {
            gridline: 'linear-gradient(rgba(255,255,255,.26) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.26) 1px, transparent 1px)'
          }
        }
      }
    }
  </script>
  <style>
    @font-face {
                font-family: 'sarabun';
                src: url('/fonts/th-sarabun/Sarabun-Light.ttf') format('truetype');
            }

            @font-face {
                font-family: 'sarabun';
                src: url('/fonts/th-sarabun/Sarabun-Bold.ttf') format('truetype');
                font-weight: bold;
            }
    html { scroll-behavior: smooth; }
    body { font-family: 'sarabun', sans-serif; }
    .bg-main {
      background:
        radial-gradient(circle at 10% 10%, rgba(83, 203, 255, .14), transparent 24%),
        radial-gradient(circle at 90% 15%, rgba(123, 109, 255, .12), transparent 22%),
        linear-gradient(180deg, #f5fbff 0%, #ffffff 18%, #f7fbff 48%, #ffffff 100%);
    }
    .hero-bg {
      background:
        radial-gradient(circle at 14% 16%, rgba(111, 216, 255, .28), transparent 22%),
        radial-gradient(circle at 86% 10%, rgba(123, 109, 255, .18), transparent 24%),
        radial-gradient(circle at 82% 72%, rgba(30, 196, 230, .20), transparent 28%),
        linear-gradient(110deg, rgba(255,255,255,.96) 0%, rgba(239,249,255,.96) 38%, rgba(217,240,255,.92) 72%, rgba(210,234,255,.88) 100%);
    }
    .wave-bg {
      background:
        radial-gradient(circle at 10% 10%, rgba(20,149,240,.10), transparent 22%),
        radial-gradient(circle at 92% 18%, rgba(123,109,255,.10), transparent 20%),
        linear-gradient(180deg, rgba(240,249,255,.92), rgba(255,255,255,1));
    }
    .pattern-dots {
      background-image: radial-gradient(rgba(20,149,240,.18) 1px, transparent 1px);
      background-size: 14px 14px;
    }
    .blue-panel {
      background:
        radial-gradient(circle at 0% 100%, rgba(129, 227, 255, .22), transparent 28%),
        radial-gradient(circle at 100% 0%, rgba(167, 160, 255, .18), transparent 26%),
        linear-gradient(135deg, #18a4ff 0%, #0a78d3 38%, #0b63c9 68%, #124d96 100%);
    }
    .glass {
      background: rgba(255,255,255,.72);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }
  </style>
</head>
<body class="bg-main text-slate-700">
  <header class="sticky top-0 z-50 border-b border-white/70 bg-white/80 backdrop-blur-xl">
    <div class="h-1 w-full bg-gradient-to-r from-health via-brand-500 to-violetplus"></div>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
      <a href="#" class="flex items-center gap-3">
        <div class="grid h-12 w-12 place-items-center rounded-full border-4 border-green-100 bg-health text-white shadow-lg shadow-green-200/60">
          <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 4v16M7 9h10M8.5 15.5c1.9-1.4 5.1-1.4 7 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M12 21c4.4-2.1 7-5.6 7-10.4V5.8L12 3 5 5.8v4.8C5 15.4 7.6 18.9 12 21Z" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </div>
        <div class="leading-tight">
          <p class="text-lg font-extrabold text-slate-900">ระบบอนามัย</p>
          <p class="text-sm font-semibold text-brand-700">ข้อคิดเห็นข้อร้องเรียน</p>
        </div>
      </a>

      <button id="menuBtn" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm md:hidden" aria-label="open menu">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <nav id="navMenu" class="hidden items-center gap-7 text-sm font-semibold text-slate-700 md:flex">
        <a class="rounded-full bg-brand-50 px-4 py-2 text-brand-700" href="#home">หน้าหลัก</a>
        <a class="transition hover:text-brand-600" href="#complaint">ร้องเรียน</a>
        <a class="transition hover:text-brand-600" href="#tracking">ติดตามเรื่องร้องเรียน</a>
        <a class="transition hover:text-brand-600" href="#info">ชม</a>
        <a class="transition hover:text-brand-600" href="#download">ดาวน์โหลด</a>
        <a class="transition hover:text-brand-600" href="#staff">สำหรับเจ้าหน้าที่</a>
      </nav>
    </div>

    <nav id="mobileMenu" class="hidden border-t border-slate-100 bg-white px-5 pb-4 text-sm font-semibold md:hidden">
      <a class="block py-2 text-brand-700" href="#home">หน้าหลัก</a>
      <a class="block py-2" href="#complaint">ร้องเรียน</a>
      <a class="block py-2" href="#tracking">ติดตามเรื่องร้องเรียน</a>
      <a class="block py-2" href="#info">ชม</a>
      <a class="block py-2" href="#download">ดาวน์โหลด</a>
      <a class="block py-2" href="#staff">สำหรับเจ้าหน้าที่</a>
    </nav>
  </header>

  <main id="home">
    <section class="hero-bg relative overflow-hidden border-b border-brand-100/80">
      <div class="absolute -left-20 top-14 h-56 w-56 rounded-full bg-cyan-200/40 blur-3xl"></div>
      <div class="absolute -right-16 top-6 h-72 w-72 rounded-full bg-violet-200/30 blur-3xl"></div>
      <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-r from-brand-100/0 via-brand-100/30 to-brand-200/10"></div>

      <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 md:grid-cols-2 md:px-8 lg:py-20">
        <div class="relative z-10">
          <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white/80 px-4 py-2 text-xs font-bold text-brand-700 shadow-sm backdrop-blur">
            <span class="h-2 w-2 rounded-full bg-health"></span>
            ระบบรับเรื่องร้องเรียนออนไลน์
          </div>
          <p class="mb-3 h-1.5 w-20 rounded-full bg-gradient-to-r from-health to-brand-500"></p>
          <h1 class="text-3xl font-extrabold leading-tight text-slate-900 md:text-5xl">
            ศูนย์รับเรื่องร้องเรียน<br>
            <span class="bg-gradient-to-r from-brand-600 via-sky-500 to-violetplus bg-clip-text text-transparent">และข้อคิดเห็นชมเชย</span>
          </h1>
          <p class="mt-3 text-xl font-bold text-slate-800">กรมอนามัย กระทรวงสาธารณสุข</p>
          <p class="mt-5 max-w-xl text-sm leading-7 text-slate-600 md:text-base">
            รับฟังทุกเสียง เพื่อพัฒนาการบริการและสุขภาพที่ดีของประชาชน พร้อมติดตามสถานะได้สะดวก ปลอดภัย และโปร่งใส
          </p>

          <div class="mt-8 flex flex-col gap-4 sm:flex-row">
            <a href="#complaint" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-700 px-7 py-3.5 font-bold text-white shadow-neon transition hover:-translate-y-0.5 hover:from-brand-600 hover:to-brand-800">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
              ยื่นเรื่องร้องเรียน
            </a>
            <a href="#tracking" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-300 bg-white/90 px-7 py-3.5 font-bold text-brand-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-brand-50">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/></svg>
              ติดตามสถานะ
            </a>
          </div>

          <div class="mt-7 flex flex-wrap gap-3 text-sm">
            <span class="rounded-full bg-health/10 px-4 py-2 font-semibold text-health">ปลอดภัย</span>
            <span class="rounded-full bg-brand-100 px-4 py-2 font-semibold text-brand-700">ติดตามง่าย</span>
            <span class="rounded-full bg-violet-100 px-4 py-2 font-semibold text-violet-700">โปร่งใส</span>
          </div>
        </div>

        <div class="relative min-h-[360px] lg:min-h-[440px]">
          <div class="absolute left-8 top-6 glass rounded-2xl border border-white/70 px-4 py-3 shadow-card">
            <p class="text-xs font-semibold text-slate-500">เปิดรับฟังความคิดเห็น</p>
            <p class="mt-1 text-lg font-extrabold text-brand-700">24/7 ออนไลน์</p>
          </div>

          <div class="absolute right-0 top-4 hidden rounded-3xl bg-white/55 p-4 shadow-soft backdrop-blur md:block">
            <div class="grid h-20 w-20 place-items-center rounded-2xl bg-gradient-to-br from-cyan-200 to-brand-400 text-white">
              <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 13 4 4L19 7"/></svg>
            </div>
          </div>

          <div class="absolute inset-x-0 bottom-0 mx-auto h-56 max-w-lg rounded-[3rem] bg-gradient-to-t from-brand-200/70 via-sky-100/35 to-transparent blur-sm"></div>

          <div class="absolute bottom-3 left-3 flex items-end gap-3 sm:left-10">
            <div class="hidden rounded-[2rem] bg-white/75 p-3 shadow-soft backdrop-blur sm:block">
              <div class="h-36 w-24 rounded-[1.5rem] bg-gradient-to-b from-cyan-50 via-cyan-100 to-cyan-300"></div>
            </div>
            <div class="rounded-[2rem] bg-white/80 p-3 shadow-soft backdrop-blur">
              <div class="h-48 w-28 rounded-[1.5rem] bg-gradient-to-b from-sky-50 via-sky-100 to-sky-400"></div>
            </div>
            <div class="relative rounded-[2.5rem] bg-white/90 p-4 shadow-soft backdrop-blur">
              <div class="mx-auto grid h-24 w-24 place-items-center rounded-full bg-gradient-to-br from-slate-200 to-slate-400">
                <div class="h-16 w-16 rounded-full bg-white/60"></div>
              </div>
              <div class="mt-3 h-40 w-36 rounded-[1.8rem] border border-brand-100 bg-gradient-to-b from-white via-brand-50 to-brand-100"></div>
              <div class="absolute -right-6 bottom-0 grid h-24 w-20 place-items-center rounded-3xl bg-gradient-to-b from-brand-500 to-brand-700 text-white shadow-lg shadow-brand-600/30">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m5 13 4 4L19 7"/></svg>
              </div>
            </div>
            <div class="hidden rounded-[2rem] bg-white/80 p-3 shadow-soft backdrop-blur md:block">
              <div class="h-44 w-28 rounded-[1.5rem] bg-gradient-to-b from-sky-50 via-violet-100 to-violet-300"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mx-auto -mt-1 max-w-7xl px-4 py-10 md:px-8">
      <div class="grid gap-5 md:grid-cols-3">
        <a id="complaint" href="#" class="group relative overflow-hidden rounded-3xl border border-brand-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
          <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-brand-500 via-cyan-400 to-sky-300"></div>
          <div class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-lg shadow-brand-500/25">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
          </div>
          <h2 class="text-xl font-extrabold text-slate-900">ยื่นเรื่องร้องเรียน</h2>
          <p class="mt-3 text-sm leading-7 text-slate-600">แจ้งเรื่องร้องเรียน ข้อคิดเห็น หรือปัญหาการให้บริการของหน่วยงาน</p>
          <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">คลิกเพื่อยื่นเรื่อง <span class="transition group-hover:translate-x-1">→</span></p>
        </a>

        <a href="#tracking" class="group relative overflow-hidden rounded-3xl border border-cyan-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
          <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-brand-500 to-violetplus"></div>
          <div class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-cyan-400 to-brand-600 text-white shadow-lg shadow-cyan-400/25">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/></svg>
          </div>
          <h2 class="text-xl font-extrabold text-slate-900">ติดตามเรื่องร้องเรียน</h2>
          <p class="mt-3 text-sm leading-7 text-slate-600">ตรวจสอบสถานะความคืบหน้าของเรื่องร้องเรียนที่คุณได้ยื่นไว้</p>
          <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">คลิกเพื่อติดตาม <span class="transition group-hover:translate-x-1">→</span></p>
        </a>

        <a id="download" href="#" class="group relative overflow-hidden rounded-3xl border border-violet-100 bg-white p-7 shadow-card transition hover:-translate-y-1 hover:shadow-soft">
          <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-violetplus via-brand-500 to-cyan-300"></div>
          <div class="mb-5 grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-violetplus to-brand-600 text-white shadow-lg shadow-violet-400/25">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
          </div>
          <h2 class="text-xl font-extrabold text-slate-900">คู่มือและดาวน์โหลด</h2>
          <p class="mt-3 text-sm leading-7 text-slate-600">ดาวน์โหลดคู่มือการใช้งาน เอกสารและแบบฟอร์มต่าง ๆ</p>
          <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600">คลิกเพื่อดาวน์โหลด <span class="transition group-hover:translate-x-1">→</span></p>
        </a>
      </div>
    </section>

    <section id="tracking" class="mx-auto max-w-7xl px-4 pb-14 md:px-8">
      <div class="relative overflow-hidden rounded-[2rem] border border-brand-100 bg-white p-6 shadow-soft md:p-10">
        <div class="absolute -left-10 top-0 h-40 w-40 rounded-full bg-brand-100/80 blur-2xl"></div>
        <div class="absolute bottom-0 right-0 h-44 w-44 rounded-full bg-cyan-100/90 blur-2xl"></div>
        <div class="absolute right-10 top-8 hidden h-20 w-20 rounded-3xl bg-gradient-to-br from-violet-200 to-cyan-100 opacity-70 md:block"></div>
        <div class="relative mx-auto max-w-3xl text-center">
          <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ติดตามเรื่องร้องเรียน</h2>
          <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
          <p class="mt-5 text-sm text-slate-600">กรุณากรอกข้อมูลเพื่อค้นหาสถานะเรื่องร้องเรียนของท่าน</p>
        </div>

        <form class="relative mx-auto mt-8 grid max-w-4xl gap-6 md:grid-cols-2">
          <label class="block">
            <span class="text-sm font-bold text-slate-800">กรุณากรอก เลขที่ร้องเรียน</span>
            <input type="text" placeholder="เช่น SEC000000" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100" />
            <span class="mt-2 block text-xs text-slate-500">รหัสอ้างอิงที่ได้รับเมื่อท่านยื่นเรื่องร้องเรียน</span>
          </label>
          <label class="block">
            <span class="text-sm font-bold text-slate-800">กรุณากรอก เบอร์มือถือ ผู้ร้อง 4 ตัวท้าย</span>
            <input type="text" maxlength="4" placeholder="เช่น 1234" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100" />
            <span class="mt-2 block text-xs text-slate-500">ตัวอย่าง 090-xxx-xxxx ให้กรอก 4 ตัวท้าย</span>
          </label>

          <div class="md:col-span-2 flex justify-center pt-2">
            <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-violetplus px-9 py-3.5 font-bold text-white shadow-neon transition hover:-translate-y-0.5">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/></svg>
              ค้นหาเรื่องร้องเรียน
            </button>
          </div>

          <p class="md:col-span-2 flex items-center justify-center gap-2 text-xs text-slate-500">
            <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 11.25h10.5A2.25 2.25 0 0 0 19.5 19.5v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            ข้อมูลของท่านจะถูกเก็บเป็นความลับและปลอดภัย
          </p>
        </form>
      </div>
    </section>

    <section id="info" class="wave-bg py-14">
      <div class="mx-auto max-w-7xl px-4 md:px-8">
        <div class="text-center">
          <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">ข้อมูลสำหรับประชาชน</h2>
          <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-gradient-to-r from-brand-500 to-violetplus"></div>
        </div>

        <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <a href="#" class="group rounded-3xl border border-brand-100 bg-gradient-to-b from-blue-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
            <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-brand-600 shadow-sm">
              <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5.25h6M9 9h6M9 12.75h3M6.75 3h10.5A2.25 2.25 0 0 1 19.5 5.25v13.5A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75V5.25A2.25 2.25 0 0 1 6.75 3Z"/></svg>
            </div>
            <h3 class="mt-4 font-extrabold text-slate-900">ขั้นตอนการร้องเรียน</h3>
            <p class="mt-3 text-xs leading-6 text-slate-500">ขั้นตอนการยื่นเรื่องร้องเรียนและการพิจารณาดำเนินการ</p>
            <p class="mt-4 text-sm font-bold text-brand-600">ดูขั้นตอน →</p>
          </a>

          <a href="#" class="group rounded-3xl border border-cyan-100 bg-gradient-to-b from-cyan-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
            <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-cyan-600 shadow-sm">
              <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 6v6l4 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <h3 class="mt-4 font-extrabold text-slate-900">ระยะเวลาดำเนินการ</h3>
            <p class="mt-3 text-xs leading-6 text-slate-500">ระยะเวลาในการพิจารณาตามประเภทของเรื่องร้องเรียน</p>
            <p class="mt-4 text-sm font-bold text-cyan-600">ดูรายละเอียด →</p>
          </a>

          <a href="#" class="group rounded-3xl border border-violet-100 bg-gradient-to-b from-violet-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
            <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-violet-600 shadow-sm">
              <svg class="h-14 w-14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.92 2.83 7.18 6.55 7.87V21l3.02-3.02A8.002 8.002 0 0 0 12 2Zm.05 12.75a1.1 1.1 0 1 1-.01-2.2 1.1 1.1 0 0 1 .01 2.2ZM13 11.25h-1.8c0-2.4 2.4-2.24 2.4-3.72 0-.82-.63-1.32-1.55-1.32-.93 0-1.58.52-1.72 1.43H8.45C8.64 5.82 10.1 4.6 12.1 4.6c2.1 0 3.45 1.15 3.45 2.84 0 2.2-2.36 2.35-2.55 3.81Z"/></svg>
            </div>
            <h3 class="mt-4 font-extrabold text-slate-900">คำถามที่พบบ่อย (FAQ)</h3>
            <p class="mt-3 text-xs leading-6 text-slate-500">รวมคำถามที่พบบ่อยและวิธีแก้ไขเบื้องต้น</p>
            <p class="mt-4 text-sm font-bold text-violet-600">ดูคำถามที่พบบ่อย →</p>
          </a>

          <a href="#" class="group rounded-3xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white p-7 text-center shadow-card transition hover:-translate-y-1 hover:shadow-soft">
            <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-white text-emerald-600 shadow-sm">
              <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M18 10.5V9a6 6 0 0 0-12 0v1.5m12 0A2.25 2.25 0 0 1 20.25 12.75v1.5A2.25 2.25 0 0 1 18 16.5h-1.5v-6H18Zm-12 0A2.25 2.25 0 0 0 3.75 12.75v1.5A2.25 2.25 0 0 0 6 16.5h1.5v-6H6Zm12 6v.75A3.75 3.75 0 0 1 14.25 21H12"/></svg>
            </div>
            <h3 class="mt-4 font-extrabold text-slate-900">ช่องทางการติดต่อ</h3>
            <p class="mt-3 text-xs leading-6 text-slate-500">ช่องทางการติดต่อและสอบถามข้อมูลเพิ่มเติมจากกรมอนามัย</p>
            <p class="mt-4 text-sm font-bold text-emerald-600">ดูช่องทางติดต่อ →</p>
          </a>
        </div>
      </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-8 md:px-8">
      <div class="blue-panel overflow-hidden rounded-[2rem] px-6 py-8 text-white shadow-soft md:px-10">
        <div class="absolute"></div>
        <div class="grid gap-8 md:grid-cols-[1.35fr_1fr_1fr_1fr] md:items-center">
          <div class="flex items-center gap-5 border-white/20 md:border-r md:pr-8">
            <div class="grid h-20 w-20 shrink-0 place-items-center rounded-full bg-white text-brand-600 shadow-lg">
              <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-9.75 6.75L2.25 6.75"/></svg>
            </div>
            <div>
              <p class="font-bold text-white/90">ช่องทางการติดต่อเรื่องร้องเรียน</p>
              <p class="mt-1 text-2xl font-extrabold md:text-3xl">4000@anamai.mail.go.th</p>
              <p class="mt-2 text-sm text-white/85">เราพร้อมรับฟังและดูแลทุกเรื่องร้องเรียน</p>
            </div>
          </div>

          <div class="text-center md:text-left">
            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75M12 15v2.25M6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25v-6a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6A2.25 2.25 0 0 0 6.75 21Z"/></svg>
            <h3 class="font-extrabold">ปลอดภัยและเป็นความลับ</h3>
            <p class="mt-2 text-xs leading-6 text-white/80">ข้อมูลของท่านได้รับการคุ้มครองตามกฎหมายคุ้มครองข้อมูลส่วนบุคคล</p>
          </div>

          <div class="text-center md:text-left">
            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0M18 9.75h3m-1.5-1.5v3"/></svg>
            <h3 class="font-extrabold">เป็นธรรม โปร่งใส</h3>
            <p class="mt-2 text-xs leading-6 text-white/80">ดำเนินการตรวจสอบอย่างเป็นธรรมและไม่เลือกปฏิบัติ</p>
          </div>

          <div class="text-center md:text-left">
            <svg class="mx-auto mb-3 h-12 w-12 md:mx-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12.75 2.25 2.25L15.75 9M21 12l-2.1 2.1.3 2.96-2.96.3L14.1 19.5 12 21l-2.1-1.5-2.14-2.14-2.96-.3.3-2.96L3 12l2.1-2.1-.3-2.96 2.96-.3L9.9 4.5 12 3l2.1 1.5 2.14 2.14 2.96.3-.3 2.96L21 12Z"/></svg>
            <h3 class="font-extrabold">มุ่งมั่นพัฒนา</h3>
            <p class="mt-2 text-xs leading-6 text-white/80">นำข้อร้องเรียนและข้อเสนอแนะไปพัฒนาบริการ</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-white/90">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 md:grid-cols-[1.4fr_1fr_1fr_1fr] md:px-8">
      <div>
        <div class="flex items-center gap-3">
          <div class="grid h-12 w-12 place-items-center rounded-full border-4 border-green-100 bg-health text-white shadow-lg shadow-green-100">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"><path d="M12 4v16M7 9h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 21c4.4-2.1 7-5.6 7-10.4V5.8L12 3 5 5.8v4.8C5 15.4 7.6 18.9 12 21Z" stroke="currentColor" stroke-width="1.5"/></svg>
          </div>
          <div>
            <p class="text-xl font-extrabold text-health">กรมอนามัย</p>
            <p class="text-xs font-bold text-slate-500">DEPARTMENT OF HEALTH</p>
          </div>
        </div>
        <p class="mt-5 max-w-sm text-sm leading-7 text-slate-600">
          ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมอนามัย<br>
          ที่อยู่ 88/22 ม.4 ต.ตลาดขวัญ อ.เมืองนนทบุรี จ.นนทบุรี 11000<br>
          โทรศัพท์ 0 2590 4000
        </p>
        <div class="mt-5 flex gap-3">
          <a class="grid h-9 w-9 place-items-center rounded-full bg-brand-600 text-white shadow-sm" href="#">f</a>
          <a class="grid h-9 w-9 place-items-center rounded-full bg-slate-800 text-white shadow-sm" href="#">▶</a>
          <a class="grid h-9 w-9 place-items-center rounded-full bg-green-500 text-white shadow-sm" href="#">◎</a>
          <a class="grid h-9 w-9 place-items-center rounded-full bg-violetplus text-white shadow-sm" href="#">🌐</a>
        </div>
      </div>

      <div>
        <h4 class="font-extrabold text-slate-900">ลิงก์ที่เกี่ยวข้อง</h4>
        <ul class="mt-4 space-y-3 text-sm text-slate-600">
          <li><a class="hover:text-brand-600" href="#">› หน้าหลัก</a></li>
          <li><a class="hover:text-brand-600" href="#">› ร้องเรียน</a></li>
          <li><a class="hover:text-brand-600" href="#">› ติดตามเรื่องร้องเรียน</a></li>
          <li><a class="hover:text-brand-600" href="#">› ชม</a></li>
          <li><a class="hover:text-brand-600" href="#">› ดาวน์โหลด</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-extrabold text-slate-900">นโยบายและความเป็นส่วนตัว</h4>
        <ul class="mt-4 space-y-3 text-sm text-slate-600">
          <li><a class="hover:text-brand-600" href="#">› นโยบายคุ้มครองข้อมูลส่วนบุคคล</a></li>
          <li><a class="hover:text-brand-600" href="#">› นโยบายการใช้งานเว็บไซต์</a></li>
          <li><a class="hover:text-brand-600" href="#">› Cookie Policy</a></li>
          <li><a class="hover:text-brand-600" href="#">› Website Security Policy</a></li>
        </ul>
      </div>

      <div id="staff">
        <h4 class="font-extrabold text-slate-900">สำหรับเจ้าหน้าที่</h4>
        <ul class="mt-4 space-y-3 text-sm text-slate-600">
          <li><a class="hover:text-brand-600" href="#">› เข้าสู่ระบบเจ้าหน้าที่</a></li>
        </ul>
      </div>
    </div>

    <div class="bg-gradient-to-r from-slate-900 via-brand-900 to-slate-900 py-4 text-white">
      <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 text-xs text-white/80 md:flex-row md:px-8">
        <p>Copyright © 2026 กรมอนามัย กระทรวงสาธารณสุข สงวนลิขสิทธิ์</p>
        <p>เวอร์ชัน 1.0.0</p>
      </div>
    </div>
  </footer>

  <script>
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
  </script>
</body>
</html>