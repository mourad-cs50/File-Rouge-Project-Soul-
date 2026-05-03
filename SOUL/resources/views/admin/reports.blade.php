@extends('layouts.admin')

@section('title', 'Report to Manager')
@section('breadcrumb', 'Resolution Center')
@section('page-title', 'Report to Manager')

@php $activePage = 'reports'; @endphp

@section('content')

    <script>
        // ── Category selector ─────────────────────────────────────────────────────
        const categories = ['security', 'user_conduct', 'technical', 'other'];

        function selectCategory(value) {
            // Update hidden input
            document.getElementById('category-input').value = value;

            // Reset all buttons
            categories.forEach(cat => {
                const btn = document.getElementById('cat-' + cat);
                btn.classList.remove('border-primary', 'bg-primary-container/10', 'text-primary');
                btn.classList.add('border-transparent', 'bg-surface-container', 'text-on-surface-variant');
            });

            // Highlight selected
            const selected = document.getElementById('cat-' + value);
            selected.classList.remove('border-transparent', 'bg-surface-container', 'text-on-surface-variant');
            selected.classList.add('border-primary', 'bg-primary-container/10', 'text-primary');
        }

        // ── Attachment label update ───────────────────────────────────────────────
        function updateAttachmentLabel(input) {
            const label = document.getElementById('attachment-label');
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                label.classList.add('text-primary');
            } else {
                label.textContent = 'Attach supporting evidence';
                label.classList.remove('text-primary');
            }
        }
    </script>

    {{-- ── Flash messages ──────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div id="flash-success"
             class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
            <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto">

        {{-- ── PAGE HEADER ──────────────────────────────────────────────────── --}}
        <div class="mb-10">
            <h2 class="text-4xl font-extrabold text-on-surface font-['Plus_Jakarta_Sans'] tracking-tight leading-tight">
                Report to Manager
            </h2>
            <p class="mt-3 text-on-surface-variant font-medium leading-relaxed">
                Submit critical complaints or operational issues directly to the management dashboard for immediate review.
            </p>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             REPORT FORM
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-surface-container-lowest rounded-3xl p-8 relative overflow-hidden editorial-shadow">

            {{-- Decorative blur --}}
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

            <form method="POST"
                  action="{{ route('admin.reports.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-8 relative z-10"
                  id="report-form">
                @csrf

                {{-- ── Category Selector ──────────────────────────────────── --}}
                <div class="space-y-3">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-on-surface-variant ml-1 font-['Manrope']">
                        Complaint Category <span class="text-error">*</span>
                    </label>

                    {{-- Hidden input carries the selected value --}}
                    <input type="hidden" name="category" id="category-input" value="{{ old('category', '') }}" required/>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            ['value' => 'security',     'icon' => 'security',    'label' => 'Security'],
                            ['value' => 'user_conduct', 'icon' => 'person_off',  'label' => 'User Conduct'],
                            ['value' => 'technical',    'icon' => 'cloud_off',   'label' => 'Technical'],
                            ['value' => 'other',        'icon' => 'more_horiz',  'label' => 'Other'],
                        ] as $cat)
                            <button type="button"
                                    onclick="selectCategory('{{ $cat['value'] }}')"
                                    id="cat-{{ $cat['value'] }}"
                                    class="category-btn flex items-center gap-3 p-4 rounded-xl border-2 transition-all font-bold text-sm
                                           {{ old('category') === $cat['value']
                                               ? 'border-primary bg-primary-container/10 text-primary'
                                               : 'border-transparent bg-surface-container hover:bg-surface-container-high text-on-surface-variant' }}">
                                <span class="material-symbols-outlined">{{ $cat['icon'] }}</span>
                                {{ $cat['label'] }}
                            </button>
                        @endforeach
                    </div>

                    @error('category')
                        <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Description ─────────────────────────────────────────── --}}
                <div class="space-y-3">
                    <label for="description"
                           class="text-[12px] font-bold uppercase tracking-widest text-on-surface-variant ml-1 font-['Manrope']">
                        Detailed Description <span class="text-error">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        required
                        placeholder="Explain the situation with as much detail as possible including timestamps and relevant IDs..."
                        class="w-full bg-surface-container-highest border-none rounded-xl p-5 text-on-surface focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline font-['Manrope'] text-sm resize-none"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-on-surface-variant ml-1">Minimum 20 characters.</p>
                </div>

                {{-- ── Attachment ───────────────────────────────────────────── --}}
                <div>
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-2xl border border-dashed border-outline-variant/30 cursor-pointer hover:bg-surface-container transition-colors"
                         onclick="document.getElementById('attachment').click()">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">attach_file</span>
                            <div>
                                <p class="text-sm font-semibold text-on-surface-variant" id="attachment-label">
                                    Attach supporting evidence
                                </p>
                                <p class="text-xs text-outline">JPG, PNG, PDF, MP4, DOC — max 10MB</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-primary">Upload File</span>
                    </div>
                    <input
                        id="attachment"
                        name="attachment"
                        type="file"
                        class="hidden"
                        accept=".jpg,.jpeg,.png,.pdf,.mp4,.mov,.doc,.docx"
                        onchange="updateAttachmentLabel(this)"
                    />
                    @error('attachment')
                        <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Submit ───────────────────────────────────────────────── --}}
                <button type="submit"
                        class="w-full py-5 px-8 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-extrabold text-lg shadow-[0_20px_40px_rgba(39,44,81,0.06)] hover:scale-[1.02] active:scale-95 transition-all flex justify-center items-center gap-3">
                    <span class="material-symbols-outlined">send</span>
                    Submit Report
                </button>
            </form>
        </div>

        {{-- ── Disclaimer note ──────────────────────────────────────────────── --}}
        <div class="mt-6 flex items-start gap-3 p-4 rounded-2xl bg-secondary-container/20 border border-secondary-container/30">
            <span class="material-symbols-outlined text-secondary text-sm mt-0.5">info</span>
            <p class="text-xs text-on-secondary-container font-medium leading-relaxed italic">
                All reports are timestamped and logged under your admin ID. Misuse of the reporting system may lead to curator status review.
            </p>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             PAST REPORTS
        ══════════════════════════════════════════════════════════════════ --}}
        @if($adminReports->isNotEmpty())
            <div class="mt-12">
                <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-on-surface mb-5">Your Previous Reports</h3>
                <div class="space-y-4">
                    @foreach($adminReports as $report)
                        <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/10 editorial-shadow">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-primary text-xl">{{ $report->categoryIcon() }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-on-surface">{{ $report->categoryLabel() }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $report->created_at->format('M d, Y · H:i') }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $report->statusBadgeClass() }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>

                            <p class="text-sm text-on-surface-variant leading-relaxed line-clamp-3">
                                {{ $report->description }}
                            </p>

                            @if($report->attachment_filename)
                                <div class="mt-3 flex items-center gap-2 text-xs text-primary font-semibold">
                                    <span class="material-symbols-outlined text-sm">attach_file</span>
                                    {{ $report->attachment_filename }}
                                </div>
                            @endif

                            @if($report->manager_notes)
                                <div class="mt-4 p-3 bg-surface-container-low rounded-xl border-l-4 border-primary">
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-primary mb-1">Manager Response</p>
                                    <p class="text-sm text-on-surface">{{ $report->manager_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    // ── Auto-dismiss flash ────────────────────────────────────────────────────
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => flash.remove(), 4000);

    // ── Restore selected category on validation error ─────────────────────────
    const savedCategory = document.getElementById('category-input').value;
    if (savedCategory) selectCategory(savedCategory);
</script>
@endpush