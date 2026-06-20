@extends('sadmin.layouts.app')
@push('title') {{ $title }} @endpush
@section('content')

<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <h4 class="fs-18 fw-500 lh-20 text-textBlack pb-16">{{ __('Frontend Settings') }}</h4>
    <div class="row rg-20">
        <!-- Sidebar -->
        <div class="col-xl-3">
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                @include('sadmin.frontend.partials.sidebar')
            </div>
        </div>
        <!-- Content -->
        <div class="col-xl-9">
            <div class="bg-white bd-one bd-c-stroke bd-ra-8 p-sm-25 p-15">
                <div class="d-flex align-items-center justify-content-between pb-16 mb-20 border-bottom">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __($title) }}</h4>
                </div>

                <div class="row rg-20">
                    @foreach ($sections as $section)
                    <div class="col-lg-6">
                        <div class="bd-one bd-c-stroke bd-ra-8 p-20" style="background: #fafafa;">
                            <div class="d-flex align-items-center justify-content-between pb-12 mb-12" style="border-bottom:1px solid #eee">
                                <div>
                                    <h5 class="fs-15 fw-600 text-textBlack mb-2">{{ __(ucwords(str_replace('_', ' ', $section->section_key))) }}</h5>
                                    <small class="text-para-text fs-11">{{ $section->section_key }}</small>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input section-status-toggle" type="checkbox" role="switch"
                                           data-id="{{ $section->id }}"
                                           {{ $section->status == STATUS_ACTIVE ? 'checked' : '' }}>
                                </div>
                            </div>
                            <form class="section-form" data-id="{{ $section->id }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row rg-12">
                                    <div class="col-md-6">
                                        <label class="zForm-label">{{ __('Page Title') }}</label>
                                        <input type="text" name="page_title" value="{{ $section->page_title }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="zForm-label">{{ __('Section Title') }}</label>
                                        <input type="text" name="title" value="{{ $section->title }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="zForm-label">{{ __('Description') }}</label>
                                        <textarea name="description" rows="2" class="form-control zForm-control">{{ $section->description }}</textarea>
                                    </div>
                                    @if (in_array($section->section_key, ['hero_area', 'demo_ection']))
                                    <div class="col-12">
                                        <label class="zForm-label">{{ __('Banner Image') }}</label>
                                        @if ($section->banner_image)
                                            <div class="mb-8">
                                                <img src="{{ asset($section->banner_image) }}" alt="" style="max-height:60px;border-radius:4px;">
                                            </div>
                                        @endif
                                        <input type="file" name="banner_image" class="form-control zForm-control" accept="image/*">
                                    </div>
                                    @endif
                                    <div class="col-12 text-end">
                                        <button type="submit" class="py-10 px-20 bd-ra-4 bg-main-color text-white fw-500 fs-14 border-0 section-save-btn">
                                            <i class="fa fa-save me-1"></i> {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
$(function () {
    $(document).on('change', '.section-status-toggle', function () {
        const id     = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        const form   = new FormData();
        form.append('_token', '{{ csrf_token() }}');
        form.append('status', status);
        $.ajax({
            url  : '{{ route("super-admin.frontend.sections.update", "") }}/' + id,
            type : 'POST',
            data : form,
            processData: false,
            contentType: false,
            success: function (res) { toastr.success(res.message ?? '{{ __("Updated") }}'); },
            error  : function ()    { toastr.error('{{ __("Failed") }}'); },
        });
    });

    $(document).on('submit', '.section-form', function (e) {
        e.preventDefault();
        const id  = $(this).data('id');
        const btn = $(this).find('.section-save-btn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url         : '{{ route("super-admin.frontend.sections.update", "") }}/' + id,
            type        : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            success: function (res) {
                toastr.success(res.message ?? '{{ __("Saved") }}');
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> {{ __("Save") }}');
            },
            error: function () {
                toastr.error('{{ __("Something went wrong") }}');
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> {{ __("Save") }}');
            },
        });
    });
});
</script>
@endpush
@endsection
