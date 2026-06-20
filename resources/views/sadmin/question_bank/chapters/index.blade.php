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
                        <input type="text" placeholder="{{ __('Search here...') }}" id="searchByChapter" />
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white" type="button" data-bs-toggle="modal" data-bs-target="#addChapterModal">
                        <i class="fa fa-plus"></i> {{ __('Add Chapter') }}
                    </button>
                </div>
            </div>
            
            <table id="chaptersDataTable" class="table zTable zTable-last-item-right">
                <thead>
                    <tr>
                        <th class="all">
                            <div class="text-nowrap">{{ __('SL') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Class Name') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Subject Name') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Chapter Name') }}</div>
                        </th>
                        <th class="desktop">
                            <div class="text-nowrap">{{ __('Order') }}</div>
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

    <!-- Add Chapter Modal -->
    <div class="modal fade" id="addChapterModal" tabindex="-1" aria-labelledby="addChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Chapter') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.question-bank.chapters.store') }}" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                            <select name="subject_id" class="sf-select-without-search">
                                <option value="">{{ __('Select Subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }} 
                                        @if($subject->academicClass)
                                            ({{ $subject->academicClass->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Chapter Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" class="form-control zForm-control" value="0">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="sf-select-without-search">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="2">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Chapter Modal -->
    <div class="modal fade" id="editChapterModal" tabindex="-1" aria-labelledby="editChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Chapter') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="editChapterForm" class="ajax reset" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                            <select name="subject_id" id="editChapterSubjectId" class="sf-select-without-search form-control zForm-control">
                                <option value="">{{ __('Select Subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }} 
                                        @if($subject->academicClass)
                                            ({{ $subject->academicClass->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Chapter Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editChapterName" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" id="editChapterOrder" class="form-control zForm-control">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="editChapterStatus" class="sf-select-without-search form-control zForm-control">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="2">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="chaptersDataRoute" value="{{ route('super-admin.question-bank.chapters.index') }}">
    <input type="hidden" id="chapterInfoRoute" value="{{ route('super-admin.question-bank.chapters.get-info') }}">
    <input type="hidden" id="chapterUpdateBaseUrl" value="{{ url('sadmin/question-bank/chapters/update') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/chapters.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
