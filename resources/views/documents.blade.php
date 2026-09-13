@extends('layouts.app')

@section('pageTitle', 'ดาวน์โหลด/คู่มือการใช้งาน')

@section('content')
@php
    $categoryLabels = [
        'manual'   => 'คู่มือการใช้งาน',
        'form'     => 'แบบฟอร์ม',
        'document' => 'เอกสารเผยแพร่',
        'other'    => 'อื่น ๆ',
    ];

    $fileColors = [
        'pdf'  => 'background:#fff1f2;color:#e11d48;',
        'doc'  => 'background:#eff6ff;color:#2563eb;',
        'docx' => 'background:#eff6ff;color:#2563eb;',
        'xls'  => 'background:#ecfdf5;color:#059669;',
        'xlsx' => 'background:#ecfdf5;color:#059669;',
        'zip'  => 'background:#fffbeb;color:#d97706;',
    ];

    $formatFileSize = function ($bytes) {
        if (!$bytes) {
            return '-';
        }

        return $bytes < 1048576
            ? number_format($bytes / 1024, 1) . ' KB'
            : number_format($bytes / 1048576, 1) . ' MB';
    };
@endphp

<section class="document-public-page">
    {{-- Hero --}}
    <div class="document-hero">
        <div class="hero-circle hero-circle-right"></div>
        <div class="hero-circle hero-circle-left"></div>

        <div class="mx-auto max-w-7xl px-4 py-14 md:px-8 md:py-20">
            <div class="relative z-10 mx-auto max-w-3xl text-center">
                <div class="hero-label">
                    <i class="far fa-folder-open"></i>
                    ศูนย์รวมเอกสารสำหรับประชาชน
                </div>

                <h1>
                    ดาวน์โหลดเอกสารและ
                    <span>คู่มือการใช้งาน</span>
                </h1>

                <p>
                    เอกสาร คู่มือ และแบบฟอร์มที่เกี่ยวข้อง
                    เพื่ออำนวยความสะดวกในการใช้บริการ
                    ระบบรับเรื่องร้องเรียนและข้อเสนอแนะของกรมอนามัย
                </p>

                <div class="hero-document-art" aria-hidden="true">
                    <div class="art-file art-file-left">
                        <i class="far fa-file-alt"></i>
                    </div>

                    <div class="art-folder">
                        <i class="fas fa-folder-open"></i>
                    </div>

                    <div class="art-file art-file-right">
                        <i class="far fa-file-pdf"></i>
                    </div>

                    <div class="art-download">
                        <i class="fas fa-download"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="document-content">
        <div class="mx-auto max-w-7xl px-4 pb-20 md:px-8">
            {{-- Search --}}
            <div class="document-search-box">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>

                    <input
                        id="documentSearch"
                        type="search"
                        placeholder="ค้นหาชื่อเอกสารหรือคู่มือ..."
                    />
                </div>

                <div class="category-select-wrapper">
                    <i class="fas fa-filter"></i>

                    <select id="documentCategory">
                        <option value="">ทุกประเภทเอกสาร</option>

                        @foreach ($categoryLabels as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
            </div>

            {{-- Heading --}}
            <div class="document-heading">
                <div>
                    <h2>รายการเอกสาร</h2>

                    <p>
                        พบ
                        <strong id="documentCount">
                            {{ $documents->count() }}
                        </strong>
                        รายการ
                    </p>
                </div>

                <div class="safe-file">
                    <i class="fas fa-shield-alt"></i>
                    ไฟล์จากระบบกรมอนามัย
                </div>
            </div>

            {{-- Document cards --}}
            <div id="documentGrid" class="document-grid">
                @forelse ($documents as $document)
                    @php
                        $extension = strtolower(
                            $document->file_extension
                            ?: pathinfo($document->file_name, PATHINFO_EXTENSION)
                        );

                        $fileColor = $fileColors[$extension]
                            ?? 'background:#f1f5f9;color:#475569;';
                    @endphp

                    <article
                        class="document-card"
                        data-document-card
                        data-name="{{ Str::lower(
                            $document->name . ' ' . $document->description
                        ) }}"
                        data-category="{{ $document->category }}"
                    >
                        <div class="document-main">
                            <div
                                class="file-icon"
                                style="{{ $fileColor }}"
                            >
                                @if ($extension === 'pdf')
                                    <i class="far fa-file-pdf"></i>
                                @elseif (in_array($extension, ['doc', 'docx']))
                                    <i class="far fa-file-word"></i>
                                @elseif (in_array($extension, ['xls', 'xlsx']))
                                    <i class="far fa-file-excel"></i>
                                @elseif ($extension === 'zip')
                                    <i class="far fa-file-archive"></i>
                                @else
                                    <i class="far fa-file"></i>
                                @endif

                                <span>{{ strtoupper($extension ?: 'FILE') }}</span>
                            </div>

                            <div class="document-info">
                                <div class="document-labels">
                                    <span class="category-label">
                                        {{ $categoryLabels[$document->category] ?? 'อื่น ๆ' }}
                                    </span>

                                    @if ($document->is_new)
                                        <span class="new-label">NEW</span>
                                    @endif
                                </div>

                                <h3>{{ $document->name }}</h3>

                                @if ($document->description)
                                    <p class="document-description">
                                        {{ $document->description }}
                                    </p>
                                @endif

                                <div class="document-meta">
                                    <span>
                                        <i class="far fa-file-alt"></i>
                                        {{ $formatFileSize($document->file_size) }}
                                    </span>

                                    <span>
                                        <i class="fas fa-download"></i>
                                        {{ number_format($document->download_count) }}
                                        ครั้ง
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{ optional($document->updated_at)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a
                            href="{{ route('documents.download', $document) }}"
                            class="download-button"
                            aria-label="ดาวน์โหลด {{ $document->name }}"
                        >
                            <i class="fas fa-download"></i>
                            ดาวน์โหลด
                        </a>
                    </article>
                @empty
                    <div class="empty-state">
                        <i class="far fa-folder-open"></i>
                        <h3>ยังไม่มีเอกสารเผยแพร่</h3>
                        <p>กรุณากลับมาตรวจสอบอีกครั้งในภายหลัง</p>
                    </div>
                @endforelse
            </div>

            {{-- Search empty result --}}
            <div id="searchEmptyState" class="empty-state hidden">
                <i class="fas fa-search"></i>
                <h3>ไม่พบเอกสารที่ค้นหา</h3>
                <p>ลองเปลี่ยนคำค้นหาหรือเลือกประเภทเอกสารอื่น</p>
            </div>

            {{-- Contact --}}
            <div class="document-contact">
                <div class="contact-icon">
                    <i class="far fa-file-alt"></i>
                    <span>?</span>
                </div>

                <div class="contact-content">
                    <span>ต้องการความช่วยเหลือ?</span>
                    <h3>ติดต่อสอบถามเกี่ยวกับเอกสาร</h3>
                    <p>
                        หากมีข้อสงสัยเกี่ยวกับเอกสารหรือคู่มือการใช้งาน
                        สามารถติดต่อเจ้าหน้าที่ได้ในวันและเวลาราชการ
                    </p>
                </div>

                <a href="/#contact" class="contact-button">
                    <i class="far fa-comment"></i>
                    ติดต่อเจ้าหน้าที่
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('documentSearch');
        const category = document.getElementById('documentCategory');
        const cards = Array.from(
            document.querySelectorAll('[data-document-card]')
        );

        const count = document.getElementById('documentCount');
        const grid = document.getElementById('documentGrid');
        const empty = document.getElementById('searchEmptyState');

        function filterDocuments() {
            const keyword = (search?.value || '')
                .trim()
                .toLocaleLowerCase('th');

            const selectedCategory = category?.value || '';
            let visibleCount = 0;

            cards.forEach(function (card) {
                const matchedName =
                    !keyword ||
                    (card.dataset.name || '').includes(keyword);

                const matchedCategory =
                    !selectedCategory ||
                    card.dataset.category === selectedCategory;

                const visible = matchedName && matchedCategory;

                card.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            if (count) {
                count.textContent = visibleCount;
            }

            if (grid) {
                grid.classList.toggle(
                    'hidden',
                    visibleCount === 0 && cards.length > 0
                );
            }

            if (empty) {
                empty.classList.toggle(
                    'hidden',
                    visibleCount > 0 || cards.length === 0
                );
            }
        }

        search?.addEventListener('input', filterDocuments);
        category?.addEventListener('change', filterDocuments);
    });
</script>
@endsection
