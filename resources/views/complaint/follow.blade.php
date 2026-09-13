@extends('layouts.app')

@section('pageTitle', 'ผลการติดตามเรื่องร้องเรียน')

@push('styles')
<style>
    .tracking-page{background:#eaf7ff;padding:48px 16px;color:#0f274f;font-family:"Noto Sans Thai",sans-serif}
    .tracking-wrap{max-width:1120px;margin:auto}.tracking-head{text-align:center;margin-bottom:28px}
    .tracking-head h1{font-size:clamp(26px,4vw,38px);font-weight:800;margin:0}.tracking-head p{color:#64748b;margin:8px 0 0}
    .found{display:inline-flex;align-items:center;gap:8px;margin-top:14px;padding:8px 16px;border-radius:999px;background:#dcfce7;color:#15803d;font-weight:700}
    .card{background:#fff;border:1px solid #cce8fa;border-radius:24px;box-shadow:0 15px 40px rgba(15,82,140,.08)}
    .summary{display:grid;grid-template-columns:1fr auto;gap:30px;padding:28px}.label{display:block;color:#64748b;font-size:13px;margin-bottom:5px}
    .code{font-size:30px;color:#0564d8}.summary-info{display:flex;gap:35px;margin-top:20px}.summary-info div{display:flex;gap:10px}.summary-info strong{display:block}
    .status{display:inline-flex;align-items:center;gap:8px;border-radius:999px;padding:10px 16px;font-weight:800}.status-1{background:#eff6ff;color:#2563eb}.status-2{background:#fff7ed;color:#ea580c}.status-3{background:#ecfdf5;color:#059669}
    .print-btn{display:block;width:100%;margin-top:14px;border:0;border-radius:12px;padding:11px 16px;background:#0b78e3;color:#fff;font-weight:700;cursor:pointer}
    .timeline{margin:22px 0;padding:28px 44px 24px;overflow:hidden}
    .timeline-steps{display:grid;grid-template-columns:repeat(3,1fr)}
    .step{position:relative;text-align:center;min-width:0}.step:not(:last-child)::after{content:"";position:absolute;z-index:0;top:21px;left:calc(50% + 24px);width:calc(100% - 48px);height:4px;border-radius:999px;background:#d7e0ec}.step.done:not(:last-child)::after{background:#25b77a}
    .dot{position:relative;z-index:1;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;margin:0 auto;background:#9eacc1;color:#fff;border:5px solid #fff;box-shadow:0 0 0 3px #e0e7f0;font-size:14px;font-weight:900}
    .step.done .dot{background:#25ae73;box-shadow:0 0 0 3px #caefdf,0 4px 12px rgba(37,174,115,.25)}.step.active .dot{background:#2778ed;box-shadow:0 0 0 3px #d4e5ff,0 4px 12px rgba(39,120,237,.3)}
    .step strong{display:block;margin-top:12px;color:#7b8ba4;font-size:15px;line-height:1.35}.step.done strong,.step.active strong{color:#102b55}
    .grid{display:grid;grid-template-columns:1fr 1.35fr;gap:22px}.info-card{padding:25px}.card-title{display:flex;align-items:center;gap:10px;margin:0 0 18px;font-size:19px}.card-title span{width:38px;height:38px;display:grid;place-items:center;border-radius:12px;background:#e0f2fe;color:#0369a1}
    .details{display:grid;gap:0}.row{display:grid;grid-template-columns:145px 1fr;gap:15px;padding:11px 0;border-bottom:1px dashed #dbe4ee}.row:last-child{border-bottom:0}.row span{color:#64748b}.row strong{overflow-wrap:anywhere}
    .privacy{padding:16px;border-radius:15px;background:#eff6ff;color:#1e40af}.attachments{margin-top:18px;padding-top:16px;border-top:1px solid #e2e8f0}.attachment-title{display:flex;align-items:center;justify-content:space-between;gap:10px}.attachment-count{padding:3px 9px;border-radius:999px;background:#e0f2fe;color:#0369a1;font-size:12px}.attachment-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:12px}.attachment-card{overflow:hidden;border:1px solid #dbeafe;border-radius:14px;background:#fff}.attachment-preview{display:flex;width:100%;height:150px;align-items:center;justify-content:center;background:#eff8ff;text-decoration:none}.attachment-preview img,.attachment-preview video{display:block;width:100%;height:100%;object-fit:cover}.attachment-preview video{background:#0f172a}.attachment-pdf{flex-direction:column;gap:6px;background:#fff1f2;color:#dc2626}.attachment-pdf b{font-size:28px}.attachment-meta{padding:11px}.attachment-name{display:block;overflow:hidden;color:#1e293b;font-size:13px;font-weight:700;text-overflow:ellipsis;white-space:nowrap}.attachment-bottom{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:6px}.attachment-size{color:#64748b;font-size:11px}.attachment-open{color:#0879dc;font-size:12px;font-weight:700;text-decoration:none}.attachment-empty{margin:10px 0 0;padding:14px;border-radius:10px;background:#f8fafc;color:#64748b;text-align:center}
    .latest{margin-top:22px;padding:25px}.latest-grid{display:grid;grid-template-columns:1fr 1fr 1.4fr;gap:18px}.latest-item{padding:15px;border-radius:15px;background:#f5faff}.latest-item span{display:block;color:#64748b;font-size:13px}.latest-item strong{display:block;margin-top:5px}
    .notice{display:flex;gap:12px;margin-top:22px;padding:17px 20px;border-radius:16px;background:#dff4ff;color:#075985}.actions{display:flex;justify-content:center;gap:12px;margin-top:25px}.action{border:1px solid #0b78e3;border-radius:13px;padding:11px 22px;background:#fff;color:#075cba;text-decoration:none;font-weight:700}.action.primary{background:#0b78e3;color:#fff}
    @media(max-width:768px){.tracking-page{padding:28px 12px}.summary,.grid,.latest-grid{grid-template-columns:1fr}.summary-info{flex-direction:column;gap:12px}.timeline{padding:24px 4px 20px}.dot{width:38px;height:38px;border-width:4px}.step:not(:last-child)::after{top:18px;left:calc(50% + 21px);width:calc(100% - 42px)}.step strong{font-size:11px;padding:0 3px}.row{grid-template-columns:1fr;gap:3px}.actions{flex-direction:column}.action{text-align:center}}
    @media(max-width:480px){.attachment-grid{grid-template-columns:1fr}}
    @media print{
        @page{size:A4;margin:12mm}header,footer,nav,.no-print{display:none!important}body,.tracking-page{background:#fff!important}.tracking-page{padding:0;color:#000}.tracking-wrap{max-width:none}.card{box-shadow:none;border:1px solid #bbb;break-inside:avoid}.tracking-head{margin-bottom:12px}.tracking-head h1{font-size:24px}.summary{padding:18px}.timeline{margin:12px 0;padding:18px 30px}.grid{gap:12px}.info-card,.latest{padding:16px}.notice{border:1px solid #bbb;background:#fff}.code{color:#000}.status{border:1px solid #999;background:#fff;color:#000}.attachment-grid{grid-template-columns:repeat(2,1fr)}.attachment-card{break-inside:avoid}.attachment-open{display:none}.attachment-preview video{display:none}.attachment-preview:has(video){height:auto;min-height:60px}.attachment-preview:has(video)::after{content:"ไฟล์วิดีโอ"}
    }
</style>
@endpush

@section('content')
@php
    $statusValue = max(1, min(3, (int) data_get($item, 'trace_approve', 1)));
    $statuses = [
        1 => ['label' => 'รับเรื่องร้องเรียน', 'detail' => 'ระบบได้รับเรื่องร้องเรียนแล้ว'],
        2 => ['label' => 'กำลังดำเนินการ', 'detail' => 'หน่วยงานกำลังตรวจสอบและดำเนินการ'],
        3 => ['label' => 'ดำเนินการเรียบร้อย', 'detail' => 'หน่วยงานดำเนินการเรื่องร้องเรียนเรียบร้อยแล้ว'],
    ];
    $thaiDate = function ($date) {
        if (!$date) return '-';
        $d = \Illuminate\Support\Carbon::parse($date)->locale('th');
        return $d->translatedFormat('j F').' '.($d->year + 543).' เวลา '.$d->format('H:i').' น.';
    };
    $files = collect(data_get($item, 'files', []));
@endphp

<section class="tracking-page">
    <div class="tracking-wrap">
        <div class="tracking-head">
            <h1>ผลการติดตามเรื่องร้องเรียน</h1>
            <p>ตรวจสอบสถานะและความคืบหน้าของเรื่องร้องเรียน</p>
            <span class="found">✓ พบข้อมูลเรื่องร้องเรียน</span>
        </div>

        <article class="card summary">
            <div>
                <span class="label">เลขที่ร้องเรียน</span>
                <strong class="code">{{ data_get($item, 'code', '-') }}</strong>
                <div class="summary-info">
                    <div><span>📅</span><p><span class="label">วันที่ยื่นเรื่อง</span><strong>{{ $thaiDate(data_get($item, 'created_at')) }}</strong></p></div>
                    <div><span>📄</span><p><span class="label">เรื่องที่ร้องเรียน</span><strong>{{ data_get($item, 'name', '-') }}</strong></p></div>
                </div>
            </div>
            <div>
                <span class="label">สถานะปัจจุบัน</span>
                <span class="status status-{{ $statusValue }}">● {{ $statuses[$statusValue]['label'] }}</span>
                {{-- <button type="button" class="print-btn no-print" onclick="window.print()">🖨 พิมพ์เอกสาร</button> --}}
            </div>
        </article>

        {{-- <article class="card timeline" aria-label="สถานะการดำเนินการ">
            <div class="timeline-steps">
                @foreach($statuses as $number => $status)
                    <div class="step {{ $number < $statusValue ? 'done' : '' }} {{ $number === $statusValue ? 'active' : '' }}">
                        <div class="dot">
                            @if($number < $statusValue)
                                ✓
                            @elseif($number === $statusValue)
                                {{ $number === 2 ? '⚙' : '✓' }}
                            @else
                                •••
                            @endif
                        </div>
                        <strong>{{ $status['label'] }}</strong>
                    </div>
                @endforeach
            </div>
        </article> --}}

        <div class="grid mt-8">
            <article class="card info-card">
                <h2 class="card-title"><span>👤</span>ข้อมูลผู้ร้องเรียน</h2>
                @if(data_get($item, 'concealed'))
                    <div class="privacy"><strong>🔒 ปกปิดข้อมูลผู้ร้องเรียน</strong><br>ข้อมูลส่วนบุคคลถูกเก็บเป็นความลับ</div>
                @else
                    <div class="details">
                        <div class="row"><span>ชื่อ–นามสกุล</span><strong>{{ trim(data_get($item, 'fname_masked').' '.data_get($item, 'lname_masked')) ?: '-' }}</strong></div>
                        <div class="row"><span>เพศ</span><strong>{{ ['1'=>'ชาย','2'=>'หญิง','3'=>'LGBTQ+'][data_get($item, 'gender')] ?? '-' }}</strong></div>
                        <div class="row"><span>อาชีพ</span><strong>{{ data_get($item, 'work_name', data_get($item, 'work', '-')) }}</strong></div>
                        <div class="row"><span>โทรศัพท์</span><strong>{{ data_get($item, 'phone_masked', data_get($item, 'phone', '-')) }}</strong></div>
                    </div>
                @endif
            </article>

            <article class="card info-card">
                <h2 class="card-title"><span>📋</span>ข้อมูลเรื่องร้องเรียน</h2>
                <div class="details">
                    <div class="row"><span>หน่วยงานกำกับดูแล</span><strong>{{ data_get($item, 'unit_name', '-') }}</strong></div>
                    <div class="row"><span>ประเด็นร้องเรียน</span><strong>{{ data_get($item, 'type_name', '-') }}</strong></div>
                    <div class="row"><span>เรื่องที่ร้องเรียน</span><strong>{{ data_get($item, 'name', '-') }}</strong></div>
                    <div class="row"><span>รายละเอียด</span><strong>{!! nl2br(e(data_get($item, 'description', '-'))) !!}</strong></div>
                    <div class="row"><span>สิ่งที่ต้องการให้แก้ไข</span><strong>{!! nl2br(e(data_get($item, 'improvement', '-'))) !!}</strong></div>
                </div>
                <div class="attachments">
                    <div class="attachment-title">
                        <strong>เอกสารประกอบ</strong>
                        @if($files->isNotEmpty())
                            <span class="attachment-count">{{ $files->count() }} ไฟล์</span>
                        @endif
                    </div>

                    @if($files->isNotEmpty())
                        <div class="attachment-grid">
                            @foreach($files as $file)
                                @php
                                    $fileType = data_get($file, 'file_type');
                                    $fileUrl = data_get($file, 'file_url', '#');
                                    $fileName = data_get($file, 'original_name', data_get($file, 'file_name', 'เอกสารประกอบ'));
                                    $fileSize = data_get($file, 'file_size_text', '');
                                @endphp

                                <article class="attachment-card">
                                    @if($fileType === 'image')
                                        <a class="attachment-preview" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ $fileUrl }}" alt="{{ $fileName }}" loading="lazy">
                                        </a>
                                    @elseif($fileType === 'video')
                                        <div class="attachment-preview">
                                            <video controls preload="metadata">
                                                <source src="{{ $fileUrl }}" type="{{ data_get($file, 'mime_type', 'video/mp4') }}">
                                            </video>
                                        </div>
                                    @elseif($fileType === 'pdf')
                                        <a class="attachment-preview attachment-pdf" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">
                                            <b>PDF</b><span>เปิดเอกสาร</span>
                                        </a>
                                    @else
                                        <a class="attachment-preview" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">📎 เปิดไฟล์แนบ</a>
                                    @endif

                                    <div class="attachment-meta">
                                        <span class="attachment-name" title="{{ $fileName }}">{{ $fileName }}</span>
                                        <div class="attachment-bottom">
                                            <span class="attachment-size">{{ $fileSize }}</span>
                                            <a class="attachment-open no-print" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">เปิดไฟล์ ↗</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @elseif(data_get($item, 'file'))
                        <div class="attachment-grid">
                            <article class="attachment-card">
                                <a class="attachment-preview" href="{{ data_get($item, 'file_url', '/storage/files/'.data_get($item, 'file')) }}" target="_blank" rel="noopener noreferrer">📎 เปิดไฟล์แนบเดิม</a>
                                <div class="attachment-meta"><span class="attachment-name">{{ data_get($item, 'file') }}</span></div>
                            </article>
                        </div>
                    @else
                        <p class="attachment-empty">ไม่มีเอกสารประกอบ</p>
                    @endif
                </div>
            </article>
        </div>

        <article class="card latest">
            <h2 class="card-title"><span>🕘</span>ความคืบหน้าล่าสุด</h2>
            <div class="latest-grid">
                <div class="latest-item"><span>วันที่อัปเดต</span><strong>{{ $thaiDate(data_get($item, 'trace_updated_at', data_get($item, 'updated_at'))) }}</strong></div>
                <div class="latest-item"><span>หน่วยงานรับผิดชอบ</span><strong>{{ data_get($item, 'responsible_unit_name', data_get($item, 'unit_name', '-')) }}</strong></div>
                <div class="latest-item"><span>รายละเอียดความคืบหน้า</span><strong>{{ data_get($item, 'trace_show', $statuses[$statusValue]['detail']) }}</strong></div>
            </div>
        </article>

        <div class="notice">🔒 <div><strong>ข้อมูลของท่านถูกเก็บเป็นความลับ</strong><br>เอกสารนี้ใช้สำหรับติดตามเรื่องร้องเรียนเท่านั้น</div></div>
        <div class="actions no-print">
            <a href="{{ url('/') }}" class="action">กลับหน้าหลัก</a>
            <a href="{{ url('/#tracking') }}" class="action primary">ค้นหาเรื่องอื่น</a>
        </div>
    </div>
</section>
@endsection
