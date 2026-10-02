@extends('frontend.layouts.app')

@section('title', __('ui.admission.info_title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.admission.info_title'),
        'subtitle' => __('ui.admission.info_subtitle'),
        'crumbs' => [__('ui.nav.admission') => route('admission'), __('ui.admission.info_title') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid grid-2" style="gap:44px;align-items:start;">
                <div class="reveal">
                    <span class="eyebrow"><i class="bi bi-list-check"></i> {{ __('ui.admission.process_eyebrow') }}</span>
                    <h2 class="section-title" style="font-size:1.7rem;">{{ __('ui.admission.process_title') }}</h2>

                    <div class="stack" style="gap:16px;margin-top:24px;">
                        @foreach ([
                            [__('ui.admission.process_1'), __('ui.admission.process_1_text')],
                            [__('ui.admission.process_2'), __('ui.admission.process_2_text')],
                            [__('ui.admission.process_3'), __('ui.admission.process_3_text')],
                            [__('ui.admission.process_4'), __('ui.admission.process_4_text')],
                        ] as $i => $step)
                            <div class="card reveal" style="padding:22px 26px;display:flex;gap:18px;align-items:flex-start;">
                                <div class="icon-box" style="width:44px;height:44px;margin:0;font-size:1rem;flex-shrink:0;">{{ $i + 1 }}</div>
                                <div>
                                    <h4 style="font-size:.98rem;margin-bottom:5px;">{{ $step[0] }}</h4>
                                    <p class="muted" style="font-size:.85rem;">{{ $step[1] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="stack reveal" style="gap:20px;">
                    <div class="card" style="padding:30px;">
                        <h3 style="font-size:1.08rem;margin-bottom:16px;">{{ __('ui.admission.available_classes') }}</h3>
                        <div class="row" style="flex-wrap:wrap;gap:9px;">
                            @forelse ($classes as $class)
                                <span class="badge">{{ $class }}</span>
                            @empty
                                <span class="muted" style="font-size:.86rem;">{{ __('ui.admission.classes_empty') }}</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="card" style="padding:30px;">
                        <h3 style="font-size:1.08rem;margin-bottom:16px;">{{ __('ui.admission.required_docs') }}</h3>
                        <ul class="stack" style="gap:11px;">
                            @foreach ([
                                __('ui.admission.doc_birth'),
                                __('ui.admission.doc_records'),
                                __('ui.admission.doc_photos'),
                                __('ui.admission.doc_id'),
                                __('ui.admission.doc_transfer'),
                            ] as $doc)
                                <li class="muted" style="font-size:.87rem;display:flex;gap:9px;">
                                    <span style="color:var(--success);"><i class="bi bi-check-circle-fill"></i></span> {{ $doc }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card" style="padding:30px;background:var(--gradient);color:#fff;border:none;">
                        <h3 style="color:#fff;font-size:1.08rem;margin-bottom:10px;">{{ __('ui.admission.ready_title') }}</h3>
                        <p style="color:rgba(255,255,255,.8);font-size:.87rem;margin-bottom:18px;">{{ __('ui.admission.ready_text') }}</p>
                        <a href="{{ route('admission') }}" class="btn" style="background:#fff;color:var(--secondary);width:100%;">{{ __('ui.common.apply_now') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
