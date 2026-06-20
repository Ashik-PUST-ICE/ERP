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
                        <input type="text" placeholder="{{ __('Search here...') }}" id="searchByClass" />
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white" type="button" data-bs-toggle="modal" data-bs-target="#addClassModal">
                        <i class="fa fa-plus"></i> {{ __('Add Class') }}
                    </button>
                </div>
            </div>
            
            <table id="classesDataTable" class="table zTable zTable-last-item-right">
                <thead>
                    <tr>
                        <th class="all">
                            <div class="text-nowrap">{{ __('SL') }}</div>
                        </th>
                        <th class="all">
                            <div class="text-nowrap">{{ __('Name') }}</div>
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

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Class') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.question-bank.classes.store') }}" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Class Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" class="form-control zForm-control" value="0">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="sf-select-without-search">
                                <option value="{{ QB_STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                <option value="{{ QB_STATUS_INACTIVE }}">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Class') }}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="editClassForm" class="ajax reset" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Class Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editClassName" class="form-control zForm-control" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Order') }}</label>
                            <input type="number" name="order" id="editClassOrder" class="form-control zForm-control">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="editClassStatus" class="sf-select-without-search form-control zForm-control">
                                <option value="{{ QB_STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                <option value="{{ QB_STATUS_INACTIVE }}">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="classesDataRoute" value="{{ route('super-admin.question-bank.classes.index') }}">
    <input type="hidden" id="classInfoRoute" value="{{ route('super-admin.question-bank.classes.get-info') }}">
    <input type="hidden" id="classUpdateBaseUrl" value="{{ url('sadmin/question-bank/classes/update') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/classes.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
