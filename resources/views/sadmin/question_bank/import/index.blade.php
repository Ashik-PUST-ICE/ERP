@extends('sadmin.layouts.app')

@push('title')
    {{ $title }}
@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h3 class="fs-18 fw-600 lh-18 text-textBlack pb-20">{{ $title }}</h3>

    <!-- AI Status Banner -->
    @if(!$aiConfigured)
    <div class="bd-one bd-c-warning bg-warning-light bd-ra-6 p-15 mb-20 d-flex align-items-center cg-10">
        <i class="fa-solid fa-triangle-exclamation text-warning fs-18"></i>
        <p class="fs-14 text-warning fw-500 mb-0">
            {{ __('AI is not configured. Please set your API key in') }}
            <a href="{{ route('admin.ai-agent.index') }}" class="text-main-color fw-700">{{ __('AI Agent Settings') }}</a>
            {{ __('to use the AI generation feature.') }}
        </p>
    </div>
    @else
    <div class="bd-one bd-c-success bg-success-light bd-ra-6 p-15 mb-20 d-flex align-items-center cg-10">
        <i class="fa-solid fa-circle-check text-success fs-18"></i>
        <p class="fs-14 text-success fw-500 mb-0">
            {{ __('AI Connected:') }} <strong>{{ strtoupper($aiProvider) }} — {{ $aiModel }}</strong>
        </p>
    </div>
    @endif

    <!-- Tabs -->
    <div class="bd-one bd-c-light-border bd-ra-6 overflow-hidden mb-20">
        <ul class="nav d-flex bg-light bd-b-one bd-c-light-border" id="importTabs">
            <li>
                <button class="tab-btn fs-14 fw-500 px-20 py-12 border-0 bg-transparent active" data-tab="excel-tab">
                    <i class="fa-solid fa-file-excel me-6 text-success"></i> {{ __('Excel Bulk Import') }}
                </button>
            </li>
            <li>
                <button class="tab-btn fs-14 fw-500 px-20 py-12 border-0 bg-transparent" data-tab="ai-tab">
                    <i class="fa-solid fa-robot me-6 text-main-color"></i> {{ __('AI Question Generator') }}
                </button>
            </li>
        </ul>

        <!-- EXCEL IMPORT TAB -->
        <div id="excel-tab" class="tab-content p-25 active">
            <div class="row rg-20">
                <!-- Download Template -->
                <div class="col-md-12">
                    <div class="bd-one bd-c-light-border bd-ra-6 p-20 d-flex align-items-center justify-content-between flex-wrap g-15">
                        <div>
                            <h5 class="fs-16 fw-600 text-textBlack mb-5">{{ __('Step 1: Download the Template') }}</h5>
                            <p class="fs-13 text-para-text mb-0">{{ __('Download the Excel template, fill in your questions, then upload it below.') }}</p>
                            <p class="fs-12 text-para-text mt-5">
                                <strong>{{ __('Columns:') }}</strong> class, subject, chapter, topic, question_type (MCQ / True/False / Short / Long / Matching / Fill in Blank), question_text, option_a, option_b, option_c, option_d, correct_answer (a/b/c/d or text), difficulty (easy/medium/hard), marks, year, explanation
                            </p>
                        </div>
                        <a href="{{ route('super-admin.question-bank.import.template') }}" class="py-10 px-20 bd-one bd-c-success bg-success bd-ra-4 fs-14 fw-500 text-white d-flex align-items-center cg-8">
                            <i class="fa fa-download"></i> {{ __('Download Template') }}
                        </a>
                    </div>
                </div>

                <!-- Upload -->
                <div class="col-md-12">
                    <div class="bd-one bd-c-light-border bd-ra-6 p-20">
                        <h5 class="fs-16 fw-600 text-textBlack mb-15">{{ __('Step 2: Upload Filled Template') }}</h5>
                        <form class="ajax" action="{{ route('super-admin.question-bank.import.excel') }}" method="post" enctype="multipart/form-data" data-handler="importExcelHandler" id="excelImportForm">
                            @csrf
                            <div class="d-flex align-items-center g-15 flex-wrap">
                                <input type="file" name="excel_file" id="excelFile" class="form-control zForm-control flex-grow-1" accept=".xlsx,.xls,.csv" required>
                                <button type="submit" class="py-12 px-20 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 text-white" id="importExcelBtn">
                                    <i class="fa fa-upload me-6"></i> {{ __('Import Questions') }}
                                </button>
                            </div>
                        </form>
                        <!-- Import Result -->
                        <div id="importResult" class="mt-15 d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI GENERATOR TAB -->
        <div id="ai-tab" class="tab-content p-25 d-none">
            <div class="row rg-20">
                <!-- STEP 1: Upload Book -->
                <div class="col-md-5">
                    <div class="bd-one bd-c-light-border bd-ra-6 p-20 h-100">
                        <h5 class="fs-15 fw-600 text-textBlack mb-15">
                            <span class="d-inline-flex justify-content-center align-items-center w-24 h-24 bg-main-color bd-ra-50 fs-12 fw-600 text-white me-8">1</span>
                            {{ __('Upload Book / PDF') }}
                        </h5>
                        <form id="uploadBookForm">
                            @csrf
                            <div class="row rg-15">
                                <div class="col-12">
                                    <label class="zForm-label">{{ __('Book Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="bookTitle" class="form-control zForm-control" placeholder="e.g. Class 10 Physics" required>
                                </div>
                                <div class="col-12">
                                    <label class="zForm-label">{{ __('File (PDF / DOCX / TXT)') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="book_file" id="bookFile" class="form-control zForm-control" accept=".pdf,.docx,.txt" required>
                                </div>
                                <div class="col-6">
                                    <label class="zForm-label">{{ __('Class (Optional)') }}</label>
                                    <select name="class_id" id="bookClassId" class="sf-select-without-search">
                                        <option value="">{{ __('Any Class') }}</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="zForm-label">{{ __('Subject (Optional)') }}</label>
                                    <select name="subject_id" id="bookSubjectId" class="sf-select-without-search">
                                        <option value="">{{ __('Any Subject') }}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="uploadBookBtn" class="w-100 py-12 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 text-white">
                                        <i class="fa fa-cloud-upload-alt me-6"></i> {{ __('Upload & Extract Text') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Uploaded Books List -->
                        @if($books->count())
                        <div class="mt-20 bd-t-one bd-c-light-border pt-15">
                            <h6 class="fs-13 fw-600 text-textBlack mb-10">{{ __('Previously Uploaded Books') }}</h6>
                            <div class="d-flex flex-column rg-8">
                                @foreach($books as $book)
                                <div class="d-flex align-items-center justify-content-between bd-one bd-c-light-border bd-ra-4 px-10 py-8 book-item" data-id="{{ $book->id }}">
                                    <div>
                                        <p class="fs-13 fw-500 text-textBlack mb-0">{{ $book->title }}</p>
                                        <p class="fs-11 text-para-text mb-0">{{ strtoupper($book->file_type) }} · {{ $book->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="d-flex g-8">
                                        <button type="button" class="btn btn-xs btn-primary selectBookBtn" data-id="{{ $book->id }}" data-title="{{ $book->title }}" data-class="{{ $book->class_id }}" data-subject="{{ $book->subject_id }}">
                                            <i class="fa fa-check"></i> {{ __('Use') }}
                                        </button>
                                        <button type="button" class="btn btn-xs btn-danger deleteBookBtn" data-id="{{ $book->id }}" data-url="{{ route('super-admin.question-bank.import.book.delete', $book->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- STEP 2: Configure & Generate -->
                <div class="col-md-7">
                    <div class="bd-one bd-c-light-border bd-ra-6 p-20">
                        <h5 class="fs-15 fw-600 text-textBlack mb-15">
                            <span class="d-inline-flex justify-content-center align-items-center w-24 h-24 bg-main-color bd-ra-50 fs-12 fw-600 text-white me-8">2</span>
                            {{ __('Configure & Generate') }}
                        </h5>
                        <input type="hidden" id="selectedBookId" value="">
                        <div id="selectedBookInfo" class="d-none mb-15 bd-one bd-c-main-color bg-main-color-light bd-ra-4 px-15 py-10">
                            <p class="fs-13 fw-600 text-main-color mb-0"><i class="fa fa-book me-6"></i> <span id="selectedBookTitle"></span></p>
                        </div>

                        <div class="row rg-15">
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Class') }} <span class="text-danger">*</span></label>
                                <select name="class_id" id="genClassId" class="sf-select-without-search">
                                    <option value="">{{ __('Select Class') }}</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                                <select name="subject_id" id="genSubjectId" class="sf-select-without-search">
                                    <option value="">{{ __('Select Subject') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Chapter (Optional)') }}</label>
                                <select name="chapter_id" id="genChapterId" class="sf-select-without-search">
                                    <option value="">{{ __('Select Chapter') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Topic (Optional)') }}</label>
                                <select name="topic_id" id="genTopicId" class="sf-select-without-search">
                                    <option value="">{{ __('Select Topic') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Question Type') }} <span class="text-danger">*</span></label>
                                <select id="genQType" class="sf-select-without-search">
                                    @foreach(getQuestionTypes() as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Difficulty') }}</label>
                                <select id="genDifficulty" class="sf-select-without-search">
                                    <option value="{{ QB_DIFFICULTY_EASY }}">{{ __('Easy') }}</option>
                                    <option value="{{ QB_DIFFICULTY_MEDIUM }}" selected>{{ __('Medium') }}</option>
                                    <option value="{{ QB_DIFFICULTY_HARD }}">{{ __('Hard') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="zForm-label">{{ __('Number of Questions') }} <span class="text-danger">*</span></label>
                                <input type="number" id="genCount" class="form-control zForm-control" value="5" min="1" max="30">
                            </div>
                            <div class="col-12">
                                <button type="button" id="generateBtn" class="py-12 px-20 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-600 text-white w-100" {{ !$aiConfigured ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-wand-magic-sparkles me-8"></i>
                                    {{ __('Generate Questions with AI') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Preview & Save Generated Questions -->
                <div class="col-md-12 d-none" id="previewSection">
                    <div class="bd-one bd-c-main-color bd-ra-6 p-20">
                        <div class="d-flex align-items-center justify-content-between mb-15 flex-wrap g-10">
                            <h5 class="fs-15 fw-600 text-textBlack mb-0">
                                <span class="d-inline-flex justify-content-center align-items-center w-24 h-24 bg-main-color bd-ra-50 fs-12 fw-600 text-white me-8">3</span>
                                {{ __('Review & Save Generated Questions') }}
                            </h5>
                            <div class="d-flex g-10">
                                <button type="button" id="saveAllBtn" class="py-10 px-20 bd-one bd-c-success bg-success bd-ra-4 fs-14 fw-500 text-white">
                                    <i class="fa fa-save me-6"></i> {{ __('Save All to Question Bank') }}
                                </button>
                                <button type="button" id="clearPreviewBtn" class="py-10 px-15 bd-one bd-c-danger bg-danger bd-ra-4 fs-14 fw-500 text-white">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div id="questionsPreview" class="row rg-15">
                            <!-- AI questions rendered here via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="aiLoadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(0,0,0,0.6); z-index:9999;">
    <div class="text-center text-white">
        <div class="spinner-border text-white mb-15" style="width:50px;height:50px;"></div>
        <h5 class="fs-18 fw-600">{{ __('AI is generating questions...') }}</h5>
        <p class="fs-13">{{ __('This may take 10-30 seconds.') }}</p>
    </div>
</div>

<input type="hidden" id="apiGetSubjectsUrl" value="{{ route('super-admin.question-bank.questions.api.subjects') }}">
<input type="hidden" id="apiGetChaptersUrl" value="{{ route('super-admin.question-bank.questions.api.chapters') }}">
<input type="hidden" id="apiGetTopicsUrl" value="{{ route('super-admin.question-bank.questions.api.topics') }}">
<input type="hidden" id="uploadBookUrl" value="{{ route('super-admin.question-bank.import.book.upload') }}">
<input type="hidden" id="generateUrl" value="{{ route('super-admin.question-bank.import.ai.generate') }}">
<input type="hidden" id="saveGeneratedUrl" value="{{ route('super-admin.question-bank.import.ai.save') }}">
<input type="hidden" id="csrfToken" value="{{ csrf_token() }}">
<input type="hidden" id="QB_QTYPE_MCQ" value="{{ QB_QTYPE_MCQ }}">
@endsection

@push('script')
<script>
$(document).ready(function () {
    const QB_QTYPE_MCQ = parseInt($('#QB_QTYPE_MCQ').val());
    let generatedData = null;

    // Tab switching
    $('.tab-btn').on('click', function () {
        $('.tab-btn').removeClass('active');
        $('.tab-content').addClass('d-none').removeClass('active');
        $(this).addClass('active');
        $('#' + $(this).data('tab')).removeClass('d-none').addClass('active');
    });

    function refreshSelectPicker(selector) {
        if ($(selector).hasClass('select2-hidden-accessible')) {
            $(selector).trigger('change');
        } else if ($.fn.niceSelect) {
            $(selector).niceSelect('update');
        }
    }

    // Cascade dropdowns for AI generator
    function loadSubjects(classId, subjectSelect) {
        $(subjectSelect).html('<option value="">Select Subject</option>');
        refreshSelectPicker(subjectSelect);
        if (!classId) return;
        $.get($('#apiGetSubjectsUrl').val(), { class_id: classId }, function (res) {
            if (res.success && res.data) {
                $.each(res.data, function (k, v) {
                    $(subjectSelect).append('<option value="' + v.id + '">' + v.name + '</option>');
                });
                refreshSelectPicker(subjectSelect);
            }
        });
    }

    function loadChapters(subjectId) {
        $('#genChapterId').html('<option value="">Select Chapter</option>');
        $('#genTopicId').html('<option value="">Select Topic</option>');
        refreshSelectPicker('#genChapterId');
        refreshSelectPicker('#genTopicId');
        if (!subjectId) return;
        $.get($('#apiGetChaptersUrl').val(), { subject_id: subjectId }, function (res) {
            if (res.success && res.data) {
                $.each(res.data, function (k, v) {
                    $('#genChapterId').append('<option value="' + v.id + '">' + v.name + '</option>');
                });
                refreshSelectPicker('#genChapterId');
            }
        });
    }

    function loadTopics(chapterId) {
        $('#genTopicId').html('<option value="">Select Topic</option>');
        refreshSelectPicker('#genTopicId');
        if (!chapterId) return;
        $.get($('#apiGetTopicsUrl').val(), { chapter_id: chapterId }, function (res) {
            if (res.success && res.data) {
                $.each(res.data, function (k, v) {
                    $('#genTopicId').append('<option value="' + v.id + '">' + v.name + '</option>');
                });
                refreshSelectPicker('#genTopicId');
            }
        });
    }

    $('#genClassId').on('change', function () { loadSubjects($(this).val(), '#genSubjectId'); });
    $('#genSubjectId').on('change', function () { loadChapters($(this).val()); });
    $('#genChapterId').on('change', function () { loadTopics($(this).val()); });
    $('#bookClassId').on('change', function () { loadSubjects($(this).val(), '#bookSubjectId'); });

    // Upload book
    $('#uploadBookBtn').on('click', function () {
        const formData = new FormData($('#uploadBookForm')[0]);
        formData.set('_token', $('#csrfToken').val());
        const file = $('#bookFile')[0].files[0];
        if (!$('#bookTitle').val() || !file) {
            toastr.error('Please provide a title and file.');
            return;
        }
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-6"></i> Uploading...');
        $.ajax({
            url: $('#uploadBookUrl').val(),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    $('#selectedBookId').val(res.data.book_id);
                    $('#selectedBookTitle').text($('#bookTitle').val());
                    $('#selectedBookInfo').removeClass('d-none');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () { toastr.error('Upload failed.'); },
            complete: function () {
                $('#uploadBookBtn').prop('disabled', false).html('<i class="fa fa-cloud-upload-alt me-6"></i> Upload & Extract Text');
            }
        });
    });

    // Select an existing book
    $(document).on('click', '.selectBookBtn', function () {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const classId = $(this).data('class');
        const subjectId = $(this).data('subject');
        $('#selectedBookId').val(id);
        $('#selectedBookTitle').text(title);
        $('#selectedBookInfo').removeClass('d-none');
        if (classId) {
            $('#genClassId').val(classId);
            loadSubjects(classId, '#genSubjectId');
            setTimeout(() => { if (subjectId) $('#genSubjectId').val(subjectId); }, 500);
        }
        toastr.info('Book "' + title + '" selected. Configure settings and generate questions.');
    });

    // Delete book
    $(document).on('click', '.deleteBookBtn', function () {
        const url = $(this).data('url');
        if (!confirm('Delete this book?')) return;
        $.post(url, { _token: $('#csrfToken').val() }, function (res) {
            if (res.status) { toastr.success(res.message); location.reload(); }
            else { toastr.error(res.message); }
        });
    });

    // Generate questions
    $('#generateBtn').on('click', function () {
        const bookId = $('#selectedBookId').val();
        const classId = $('#genClassId').val();
        const subjectId = $('#genSubjectId').val();
        if (!bookId) { toastr.warning('Please upload or select a book first.'); return; }
        if (!classId) { toastr.warning('Please select a Class.'); return; }
        if (!subjectId) { toastr.warning('Please select a Subject.'); return; }

        $('#aiLoadingOverlay').removeClass('d-none').css('display', 'flex');

        $.ajax({
            url: $('#generateUrl').val(),
            type: 'POST',
            data: {
                _token: $('#csrfToken').val(),
                book_id: bookId,
                class_id: classId,
                subject_id: subjectId,
                chapter_id: $('#genChapterId').val() || '',
                topic_id: $('#genTopicId').val() || '',
                question_type: $('#genQType').val(),
                difficulty: $('#genDifficulty').val(),
                count: $('#genCount').val(),
            },
            success: function (res) {
                if (res.status && res.data && res.data.questions) {
                    generatedData = res.data;
                    renderPreview(res.data.questions, parseInt(res.data.question_type));
                    $('#previewSection').removeClass('d-none');
                    $('html, body').animate({ scrollTop: $('#previewSection').offset().top - 100 }, 400);
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message || 'Generation failed.');
                }
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Generation failed.');
            },
            complete: function () {
                $('#aiLoadingOverlay').addClass('d-none').css('display', 'none');
            }
        });
    });

    function renderPreview(questions, questionType) {
        let html = '';
        questions.forEach(function (q, i) {
            html += `<div class="col-md-12 preview-question-item" data-index="${i}">
                <div class="bd-one bd-c-light-border bd-ra-6 p-15">
                    <div class="d-flex align-items-start justify-content-between mb-10">
                        <span class="fs-13 fw-600 text-main-color">#${i + 1}</span>
                        <button type="button" class="btn btn-xs btn-danger removePreviewBtn" data-index="${i}"><i class="fa fa-trash"></i></button>
                    </div>
                    <label class="zForm-label">Question Text</label>
                    <textarea class="form-control zForm-control mb-10 q-text" rows="2">${q.question_text || ''}</textarea>`;

            if (questionType === QB_QTYPE_MCQ) {
                const letters = ['a', 'b', 'c', 'd'];
                letters.forEach(function (l) {
                    html += `<div class="d-flex align-items-center g-10 mb-8">
                        <span class="fw-700 fs-13 text-textBlack" style="min-width:20px">${l.toUpperCase()}.</span>
                        <input type="text" class="form-control zForm-control flex-grow-1 opt-${l}" value="${q['option_' + l] || ''}">
                        <input type="radio" name="correct_${i}" value="${l}" ${(q.correct_answer || '').toLowerCase() === l ? 'checked' : ''} title="Correct Answer">
                    </div>`;
                });
            } else {
                html += `<label class="zForm-label">Correct Answer</label>
                    <textarea class="form-control zForm-control mb-10 q-answer" rows="2">${q.correct_answer || ''}</textarea>`;
            }

            html += `<label class="zForm-label mt-5">Explanation (Optional)</label>
                    <input type="text" class="form-control zForm-control q-explanation" value="${q.explanation || ''}">
                </div>
            </div>`;
        });
        $('#questionsPreview').html(html);
    }

    // Remove a question from preview
    $(document).on('click', '.removePreviewBtn', function () {
        $(this).closest('.preview-question-item').remove();
    });

    // Clear preview
    $('#clearPreviewBtn').on('click', function () {
        $('#previewSection').addClass('d-none');
        $('#questionsPreview').html('');
        generatedData = null;
    });

    // Save all generated questions
    $('#saveAllBtn').on('click', function () {
        if (!generatedData) return;

        const questionsToSave = [];
        const questionType = generatedData.question_type;

        $('#questionsPreview .preview-question-item').each(function () {
            const q = {
                question_text: $(this).find('.q-text').val(),
                explanation: $(this).find('.q-explanation').val(),
            };
            if (questionType === QB_QTYPE_MCQ) {
                q.option_a = $(this).find('.opt-a').val();
                q.option_b = $(this).find('.opt-b').val();
                q.option_c = $(this).find('.opt-c').val();
                q.option_d = $(this).find('.opt-d').val();
                q.correct_answer = $(this).find('input[type=radio]:checked').val() || 'a';
            } else {
                q.correct_answer = $(this).find('.q-answer').val();
            }
            if (q.question_text.trim()) questionsToSave.push(q);
        });

        if (!questionsToSave.length) { toastr.warning('No questions to save.'); return; }

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-6"></i> Saving...');

        $.ajax({
            url: $('#saveGeneratedUrl').val(),
            type: 'POST',
            data: {
                _token: $('#csrfToken').val(),
                questions: questionsToSave,
                class_id: generatedData.class_id,
                subject_id: generatedData.subject_id,
                chapter_id: generatedData.chapter_id || '',
                topic_id: generatedData.topic_id || '',
                question_type: generatedData.question_type,
                difficulty: generatedData.difficulty,
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    $('#previewSection').addClass('d-none');
                    $('#questionsPreview').html('');
                    generatedData = null;
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) { toastr.error(xhr.responseJSON?.message || 'Save failed.'); },
            complete: function () {
                $('#saveAllBtn').prop('disabled', false).html('<i class="fa fa-save me-6"></i> Save All to Question Bank');
            }
        });
    });

    // Excel import handler
    window.importExcelHandler = function (res) {
        let html = '';
        if (res.status) {
            html += `<div class="bd-one bd-c-success bg-success-light bd-ra-4 p-12 text-success fw-500">
                <i class="fa fa-check-circle me-6"></i> ${res.data.imported} question(s) imported successfully!
            </div>`;
        }
        if (res.data && res.data.errors && res.data.errors.length) {
            html += `<div class="bd-one bd-c-warning bg-warning-light bd-ra-4 p-12 mt-10">
                <p class="fw-600 text-warning mb-8"><i class="fa fa-triangle-exclamation me-6"></i>Errors:</p>
                <ul class="mb-0 ps-20">`;
            res.data.errors.forEach(e => { html += `<li class="fs-12 text-warning">${e}</li>`; });
            html += '</ul></div>';
        }
        $('#importResult').html(html).removeClass('d-none');
    };
});
</script>
<style>
.tab-btn.active { border-bottom: 3px solid var(--main-color); color: var(--main-color); background: #fff; }
.tab-btn { transition: all .2s; color: #666; }
.tab-btn:hover { background: #f5f5f5; }
</style>
@endpush
