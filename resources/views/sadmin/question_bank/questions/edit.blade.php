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
            <form class="ajax" action="{{ route('super-admin.question-bank.questions.update', $question->id) }}" method="post" enctype="multipart/form-data" data-handler="commonResponseRedirect" data-redirect-url="{{ route('super-admin.question-bank.questions.index') }}">
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
                                    <option value="{{ $class->id }}" {{ $question->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $question->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Chapter') }}</label>
                            <select name="chapter_id" id="chapter_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Chapter') }}</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}" {{ $question->chapter_id == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pb-15">
                            <label class="zForm-label">{{ __('Topic') }}</label>
                            <select name="topic_id" id="topic_id" class="sf-select-without-search form-control">
                                <option value="">{{ __('Select Topic') }}</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ $question->topic_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                @endforeach
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
                                        <option value="{{ $type['id'] }}" data-has-options="{{ $type['has_options'] }}" {{ $question->question_type_id == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="zForm-label">{{ __('Difficulty') }} <span class="text-danger">*</span></label>
                                <select name="difficulty" class="sf-select-without-search form-control">
                                    <option value="{{ QB_DIFFICULTY_EASY }}" {{ $question->difficulty == QB_DIFFICULTY_EASY ? 'selected' : '' }}>{{ __('Easy') }}</option>
                                    <option value="{{ QB_DIFFICULTY_MEDIUM }}" {{ $question->difficulty == QB_DIFFICULTY_MEDIUM ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="{{ QB_DIFFICULTY_HARD }}" {{ $question->difficulty == QB_DIFFICULTY_HARD ? 'selected' : '' }}>{{ __('Hard') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="zForm-label">{{ __('Marks') }} <span class="text-danger">*</span></label>
                                <input type="number" name="marks" class="form-control zForm-control" value="{{ $question->marks }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Question Text') }} <span class="text-danger">*</span></label>
                                <textarea name="question_text" class="form-control zForm-control summernote" rows="4" required>{{ $question->question_text }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Question Image') }}</label>
                                <input type="file" name="image" class="form-control zForm-control" accept="image/*">
                                @if($question->image)
                                    <div class="mt-10">
                                        <img src="{{ asset($question->image) }}" alt="Question Image" width="150" class="bd-ra-4">
                                    </div>
                                @endif
                            </div>

                            <!-- DYNAMIC SECTIONS CONTAINER -->
                            <div class="col-md-12 mt-10" id="dynamicSectionsContainer">
                                
                                <!-- MCQ OPTIONS SECTION -->
                                <div class="{{ $question->question_type_id == QB_QTYPE_MCQ ? '' : 'd-none' }} dynamic-section" id="section-mcq">
                                    <div class="bd-one bd-c-light-border bd-ra-4 p-15 bg-light mt-10">
                                        <div class="d-flex justify-content-between align-items-center mb-10">
                                            <h5 class="fs-14 fw-600 text-textBlack">{{ __('Question Options (MCQ)') }}</h5>
                                            <button type="button" id="addOptionBtn" class="py-5 px-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-12 text-white">
                                                <i class="fa fa-plus"></i> {{ __('Add Option') }}
                                            </button>
                                        </div>
                                        <div id="optionsContainer" class="row rg-10">
                                            <!-- Load Existing Options -->
                                            @foreach($question->options as $index => $option)
                                                <div class="col-md-12 option-row">
                                                    <div class="d-flex align-items-center g-10">
                                                        <input type="radio" name="is_correct_option" value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }} class="form-check-input mt-0" style="width: 20px; height: 20px;" title="Mark as correct answer">
                                                        <input type="text" name="options[{{ $index }}]" value="{{ $option->option_text }}" class="form-control zForm-control flex-grow-1" placeholder="Option text" required>
                                                        <button type="button" class="btn btn-sm btn-danger removeOptionBtn"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- TRUE/FALSE SECTION -->
                                <div class="{{ $question->question_type_id == QB_QTYPE_TRUE_FALSE ? '' : 'd-none' }} dynamic-section" id="section-true-false">
                                    <div class="bd-one bd-c-light-border bd-ra-4 p-15 bg-light mt-10">
                                        <h5 class="fs-14 fw-600 text-textBlack mb-10">{{ __('Select Correct Answer') }}</h5>
                                        <div class="d-flex g-20">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tf_answer" id="tf_true" value="True" {{ $question->correct_answer == 'True' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tf_true">{{ __('True') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tf_answer" id="tf_false" value="False" {{ $question->correct_answer == 'False' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tf_false">{{ __('False') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- FILL IN THE BLANKS SECTION -->
                                @php
                                    $optionsJson = json_decode($question->options_json, true) ?? [];
                                    $blanks = $optionsJson['blanks'] ?? [];
                                    $matches = $optionsJson['matches'] ?? [];
                                @endphp
                                <div class="{{ $question->question_type_id == QB_QTYPE_FILL_BLANK ? '' : 'd-none' }} dynamic-section" id="section-fill-blank">
                                    <div class="bd-one bd-c-light-border bd-ra-4 p-15 bg-light mt-10">
                                        <h5 class="fs-14 fw-600 text-textBlack mb-10">{{ __('Fill in the Blanks') }}</h5>
                                        <p class="text-para-text fs-12 mb-10">{{ __('Use [blank] in the question text above. Then provide the answers for the blanks below in order.') }}</p>
                                        <div id="blanksContainer" class="row rg-10">
                                            @if(count($blanks) > 0)
                                                @foreach($blanks as $index => $blank)
                                                    <div class="col-md-12 blank-row">
                                                        <div class="d-flex align-items-center g-10">
                                                            <span class="fw-500">{{ $index + 1 }}.</span>
                                                            <input type="text" name="blanks[{{ $index }}]" value="{{ $blank }}" class="form-control zForm-control flex-grow-1" placeholder="Answer for [blank]">
                                                            <button type="button" class="btn btn-sm btn-danger removeBlankBtn"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-12 blank-row">
                                                    <div class="d-flex align-items-center g-10">
                                                        <span class="fw-500">1.</span>
                                                        <input type="text" name="blanks[0]" class="form-control zForm-control flex-grow-1" placeholder="Answer for first [blank]">
                                                        <button type="button" class="btn btn-sm btn-success addBlankBtn"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- MATCHING SECTION -->
                                <div class="{{ $question->question_type_id == QB_QTYPE_MATCHING ? '' : 'd-none' }} dynamic-section" id="section-matching">
                                    <div class="bd-one bd-c-light-border bd-ra-4 p-15 bg-light mt-10">
                                        <div class="d-flex justify-content-between align-items-center mb-10">
                                            <h5 class="fs-14 fw-600 text-textBlack">{{ __('Matching Pairs') }}</h5>
                                            <button type="button" id="addMatchBtn" class="py-5 px-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-12 text-white">
                                                <i class="fa fa-plus"></i> {{ __('Add Pair') }}
                                            </button>
                                        </div>
                                        <div id="matchingContainer" class="row rg-10">
                                            @if(count($matches) > 0)
                                                @foreach($matches as $index => $match)
                                                    <div class="col-md-12 match-row">
                                                        <div class="d-flex align-items-center g-10">
                                                            <input type="text" name="match_left[]" value="{{ $match['left'] }}" class="form-control zForm-control flex-grow-1" placeholder="Left side text" required>
                                                            <input type="text" name="match_right[]" value="{{ $match['right'] }}" class="form-control zForm-control flex-grow-1" placeholder="Right side text (Correct match)" required>
                                                            <button type="button" class="btn btn-sm btn-danger removeMatchBtn"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- SHORT / LONG QUESTION (TEXTAREA) SECTION -->
                                <div class="{{ in_array($question->question_type_id, [QB_QTYPE_SHORT, QB_QTYPE_LONG]) ? '' : 'd-none' }} dynamic-section" id="section-text-answer">
                                    <div class="mt-10">
                                        <label class="zForm-label">{{ __('Model / Correct Answer') }} <span class="text-danger">*</span></label>
                                        <textarea name="correct_answer" id="correct_answer_text" class="form-control zForm-control" rows="3" placeholder="{{ __('Type the correct/model answer here...') }}">{{ $question->correct_answer }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <label class="zForm-label">{{ __('Explanation (Optional)') }}</label>
                                <textarea name="explanation" class="form-control zForm-control summernote" rows="3">{{ $question->explanation }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Board (Optional)') }}</label>
                                <select name="board_id" class="sf-select-without-search form-control">
                                    <option value="">{{ __('Select Board') }}</option>
                                    @foreach($boards as $board)
                                        <option value="{{ $board->id }}" {{ $question->board_id == $board->id ? 'selected' : '' }}>{{ $board->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Year (Optional)') }}</label>
                                <input type="number" name="year" class="form-control zForm-control" value="{{ $question->year }}" placeholder="e.g. 2023">
                            </div>

                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="sf-select-without-search form-control">
                                    <option value="{{ QB_QUESTION_STATUS_DRAFT }}" {{ $question->status == QB_QUESTION_STATUS_DRAFT ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                    <option value="{{ QB_QUESTION_STATUS_PUBLISHED }}" {{ $question->status == QB_QUESTION_STATUS_PUBLISHED ? 'selected' : '' }}>{{ __('Published') }}</option>
                                    <option value="{{ QB_QUESTION_STATUS_ARCHIVED }}" {{ $question->status == QB_QUESTION_STATUS_ARCHIVED ? 'selected' : '' }}>{{ __('Archived') }}</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-20">
                    <button type="submit" class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-16 fw-600">{{ __('Update Question') }}</button>
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
    <script src="{{ asset('sadmin/custom/js/question_form.js') }}?ver=1.1"></script>
    <script>
        window.QB_QTYPE_MCQ = {{ QB_QTYPE_MCQ }};
        window.QB_QTYPE_TRUE_FALSE = {{ QB_QTYPE_TRUE_FALSE }};
        window.QB_QTYPE_FILL_BLANK = {{ QB_QTYPE_FILL_BLANK }};
        window.QB_QTYPE_SHORT = {{ QB_QTYPE_SHORT }};
        window.QB_QTYPE_LONG = {{ QB_QTYPE_LONG }};
        window.QB_QTYPE_MATCHING = {{ QB_QTYPE_MATCHING }};

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
