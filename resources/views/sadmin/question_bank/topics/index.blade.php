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
                        <input type="text" placeholder="{{ __('Search here...') }}" id="searchByTopic" />
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white" type="button" data-bs-toggle="modal" data-bs-target="#addTopicModal">
                        <i class="fa fa-plus"></i> {{ __('Add Topic') }}
                    </button>
                </div>
            </div>
            
            <table id="topicsDataTable" class="table zTable zTable-last-item-right">
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
                        <th class="all">
                            <div class="text-nowrap">{{ __('Chapter') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Topic Name') }}</div>
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

    <!-- Add Topic Modal -->
    <div class="modal fade" id="addTopicModal" tabindex="-1" aria-labelledby="addTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Topic') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.question-bank.topics.store') }}" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Chapter') }} <span class="text-danger">*</span></label>
                            <select name="chapter_id" class="sf-select-without-search">
                                <option value="">{{ __('Select Chapter') }}</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}">
                                        {{ $chapter->name }} 
                                        @if($chapter->subject)
                                            ({{ $chapter->subject->name }} - {{ $chapter->subject->academicClass ? $chapter->subject->academicClass->name : '' }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Topic Name') }} <span class="text-danger">*</span></label>
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

    <!-- Edit Topic Modal -->
    <div class="modal fade" id="editTopicModal" tabindex="-1" aria-labelledby="editTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Topic') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="editTopicForm" class="ajax reset" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Chapter') }} <span class="text-danger">*</span></label>
                            <select name="chapter_id" id="editTopicChapterId" class="sf-select-without-search form-control zForm-control">
                                <option value="">{{ __('Select Chapter') }}</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}">
                                        {{ $chapter->name }} 
                                        @if($chapter->subject)
                                            ({{ $chapter->subject->name }} - {{ $chapter->subject->academicClass ? $chapter->subject->academicClass->name : '' }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Topic Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editTopicName" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" id="editTopicOrder" class="form-control zForm-control">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="editTopicStatus" class="sf-select-without-search form-control zForm-control">
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

    <input type="hidden" id="topicsDataRoute" value="{{ route('super-admin.question-bank.topics.index') }}">
    <input type="hidden" id="topicInfoRoute" value="{{ route('super-admin.question-bank.topics.get-info') }}">
    <input type="hidden" id="topicUpdateBaseUrl" value="{{ url('sadmin/question-bank/topics/update') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/topics.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
