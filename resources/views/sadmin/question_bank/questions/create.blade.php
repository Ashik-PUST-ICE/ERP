@extends('sadmin.layouts.app')

@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <div class="d-flex justify-content-between align-items-center pb-20">
            <h3 class="fs-18 fw-600 lh-18 text-textBlack">{{ $title }}</h3>
            <a href="{{ route('super-admin.question-bank.questions.index') }}" class="py-12 px-20 bd-one bd-c-light-border bg-white bd-ra-4 fs-14 fw-500 lh-14 text-textBlack">
                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
        
        <div class="bg-white bd-one bd-c-light-border bd-ra-8 p-sm-30 p-15">
            <form class="ajax" action="{{ route('super-admin.question-bank.questions.store') }}" method="post" enctype="multipart/form-data" data-handler="commonResponseRedirect" data-redirect-url="{{ route('super-admin.question-bank.questions.index') }}">
                @csrf
                <div class="row rg-20">
                    
                    <!-- Hierarchy Left Column -->
                    <div class="col-lg-4 col-md-6 border-end pb-20">
                        <h4 class="fs-16 fw-600 lh-16 text-textBlack pb-15">{{ __('1. Question Hierarchy') }}</h4>
                        
                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Class') }} <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Class') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Subject') }}</option>
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Chapter') }}</label>
                            <select name="chapter_id" id="chapter_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Chapter') }}</option>
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Topic') }}</label>
                            <select name="topic_id" id="topic_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Topic') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Question Details Right Column -->
                    <div class="col-lg-8 col-md-6">
                        <h4 class="fs-16 fw-600 lh-16 text-textBlack pb-15">{{ __('2. Question Content') }}</h4>

                        <div class="row rg-15">
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Question Type') }} <span class="text-danger">*</span></label>
                                <select name="question_type_id" id="question_type_id" class="sf-select-without-search form-control">
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach($questionTypes as $type)
                                        <option value="{{ $type->id }}" data-has-options="{{ $type->has_options }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="zForm-label">{{ __('Difficulty') }} <span class="text-danger">*</span></label>
                                <select name="difficulty" class="sf-select-without-search form-control">
                                    <option value="1">{{ __('Easy') }}</option>
                                    <option value="2">{{ __('Medium') }}</option>
                                    <option value="3">{{ __('Hard') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="zForm-label">{{ __('Marks') }} <span class="text-danger">*</span></label>
                                <input type="number" name="marks" class="form-control zForm-control" value="1" required>
                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Question Text') }} <span class="text-danger">*</span></label>
                                <textarea name="question_text" class="form-control zForm-control summernote" rows="4" required></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Question Image') }}</label>
                                <input type="file" name="image" class="form-control zForm-control" accept="image/*">
                            </div>

                            <!-- DYNAMIC OPTIONS SECTION -->
                            <div class="col-md-12 d-none" id="dynamicOptionsSection">
                                <div class="bd-one bd-c-light-border bd-ra-4 p-15 bg-light mt-10">
                                    <div class="d-flex justify-content-between align-items-center mb-10">
                                        <h5 class="fs-14 fw-600 text-textBlack">{{ __('Question Options') }}</h5>
                                        <button type="button" id="addOptionBtn" class="py-5 px-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-12 text-white">
                                            <i class="fa fa-plus"></i> {{ __('Add Option') }}
                                        </button>
                                    </div>
                                    <div id="optionsContainer" class="row rg-10">
                                        <!-- Options will be appended here via JS -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 d-none" id="correctAnswerTextSection">
                                <label class="zForm-label">{{ __('Correct Answer') }} <span class="text-danger">*</span></label>
                                <textarea name="correct_answer" id="correct_answer_text" class="form-control zForm-control" rows="2" placeholder="{{ __('Type the correct answer here...') }}"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Explanation (Optional)') }}</label>
                                <textarea name="explanation" class="form-control zForm-control summernote" rows="3"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Board (Optional)') }}</label>
                                <input type="text" name="board" class="form-control zForm-control" placeholder="e.g. Dhaka Board">
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Year (Optional)') }}</label>
                                <input type="number" name="year" class="form-control zForm-control" placeholder="e.g. 2023">
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="sf-select-without-search form-control">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="2">{{ __('Inactive') }}</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-20">
                    <button type="submit" class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-16 fw-600">{{ __('Save Question') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- API Routes -->
    <input type="hidden" id="apiGetSubjects" value="{{ route('super-admin.question-bank.questions.api.subjects') }}">
    <input type="hidden" id="apiGetChapters" value="{{ route('super-admin.question-bank.questions.api.chapters') }}">
    <input type="hidden" id="apiGetTopics" value="{{ route('super-admin.question-bank.questions.api.topics') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/question_form.js') }}?ver={{ env('VERSION', 0) }}"></script>
    <script>
        // Custom form handler for Redirect
        window.commonResponseRedirect = function(response) {
            if (response.status) {
                toastr.success(response.message);
                setTimeout(function() {
                    window.location.href = $('.ajax').data('redirect-url');
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        };
    </script>
@endpush
