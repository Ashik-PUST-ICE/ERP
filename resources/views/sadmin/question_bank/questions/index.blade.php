@extends('sadmin.layouts.app')

@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <h3 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h3>
        <div class="table-wrap-one">
            <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <div class="search-one flex-grow-1 max-w-207">
                        <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="" /></button>
                        <input type="text" placeholder="{{ __('Search here...') }}" id="searchByQuestion" />
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <a href="{{ route('super-admin.question-bank.questions.create') }}" class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">
                        <i class="fa fa-plus"></i> {{ __('Add Question') }}
                    </a>
                </div>
            </div>
            
            <table id="questionsDataTable" class="table zTable zTable-last-item-right">
                <thead>
                    <tr>
                        <th class="all">
                            <div class="text-nowrap">{{ __('SL') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Class') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Subject') }}</div>
                        </th>
                        <th class="desktop">
                            <div class="text-nowrap">{{ __('Type') }}</div>
                        </th>
                        <th class="desktop">
                            <div class="text-nowrap">{{ __('Question Preview') }}</div>
                        </th>
                        <th class="desktop">
                            <div class="text-nowrap">{{ __('Status') }}</div>
                        </th>
                        <th class="desktop">
                            <div class="text-nowrap">{{ __('Action') }}</div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-labelledby="questionPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="questionPreviewModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>

    <input type="hidden" id="questionsDataRoute" value="{{ route('super-admin.question-bank.questions.index') }}">
    <input type="hidden" id="questionsPreviewRoute" value="{{ route('super-admin.question-bank.questions.preview', '') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/questions.js') }}?ver=1.2"></script>
@endpush
