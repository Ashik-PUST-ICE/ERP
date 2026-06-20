@extends('sadmin.layouts.app')

@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="d-flex flex-wrap align-items-center justify-content-between g-10 pb-22">
            <h4 class="fs-24 fw-600 lh-29 text-textBlack mb-0">{{ $title }}</h4>
            <a href="{{ route('super-admin.question-bank.classes.index') }}" class="py-10 px-16 bd-one bd-c-stroke bd-ra-8 fs-13 fw-500 text-textBlack text-decoration-none">
                <i class="fa-solid fa-arrow-left text-main-color"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
            <form action="{{ route('super-admin.question-bank.classes.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Class Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="2">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">{{ __('Save') }}</button>
            </form>
        </div>
    </div>
@endsection
