(function ($) {
    "use strict";

    $(document).ready(function () {
        
        // --- 1. Cascading Dropdowns ---

        // On Class Change -> Load Subjects
        $('#class_id').on('change', function () {
            var class_id = $(this).val();
            var url = $('#apiGetSubjects').val();
            
            $('#subject_id').html('<option value="">Select Subject</option>');
            $('#chapter_id').html('<option value="">Select Chapter</option>');
            $('#topic_id').html('<option value="">Select Topic</option>');
            
            if (class_id) {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: { class_id: class_id },
                    success: function (response) {
                        if (response.success && response.data) {
                            var options = '<option value="">Select Subject</option>';
                            $.each(response.data, function (key, value) {
                                options += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            $('#subject_id').html(options);
                            refreshSelectPicker('#subject_id');
                            refreshSelectPicker('#chapter_id');
                            refreshSelectPicker('#topic_id');
                        }
                    }
                });
            } else {
                refreshSelectPicker('#subject_id');
                refreshSelectPicker('#chapter_id');
                refreshSelectPicker('#topic_id');
            }
        });

        // On Subject Change -> Load Chapters
        $('#subject_id').on('change', function () {
            var subject_id = $(this).val();
            var url = $('#apiGetChapters').val();
            
            $('#chapter_id').html('<option value="">Select Chapter</option>');
            $('#topic_id').html('<option value="">Select Topic</option>');
            
            if (subject_id) {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: { subject_id: subject_id },
                    success: function (response) {
                        if (response.success && response.data) {
                            var options = '<option value="">Select Chapter</option>';
                            $.each(response.data, function (key, value) {
                                options += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            $('#chapter_id').html(options);
                            refreshSelectPicker('#chapter_id');
                            refreshSelectPicker('#topic_id');
                        }
                    }
                });
            } else {
                refreshSelectPicker('#chapter_id');
                refreshSelectPicker('#topic_id');
            }
        });

        // On Chapter Change -> Load Topics
        $('#chapter_id').on('change', function () {
            var chapter_id = $(this).val();
            var url = $('#apiGetTopics').val();
            
            $('#topic_id').html('<option value="">Select Topic</option>');
            
            if (chapter_id) {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: { chapter_id: chapter_id },
                    success: function (response) {
                        if (response.success && response.data) {
                            var options = '<option value="">Select Topic</option>';
                            $.each(response.data, function (key, value) {
                                options += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            $('#topic_id').html(options);
                            refreshSelectPicker('#topic_id');
                        }
                    }
                });
            } else {
                refreshSelectPicker('#topic_id');
            }
        });

        function refreshSelectPicker(selector) {
            if ($(selector).hasClass('select2-hidden-accessible')) {
                $(selector).trigger('change');
            } else if ($.fn.niceSelect) {
                $(selector).niceSelect('update');
            }
        }

        // --- 2. Dynamic Option Builder ---

        var optionCount = $('#optionsContainer .option-row').length || 0;
        var blankCount = $('#blanksContainer .blank-row').length || 1;
        var matchCount = $('#matchingContainer .match-row').length || 0;

        $('#question_type_id').on('change', function () {
            var selectedType = parseInt($(this).val());
            
            // Hide all dynamic sections first
            $('.dynamic-section').addClass('d-none');
            
            // Remove required attributes from correct answers to avoid validation issues on hidden elements
            $('#correct_answer_text').removeAttr('required');

            if (selectedType === window.QB_QTYPE_MCQ) {
                $('#section-mcq').removeClass('d-none');
                if ($('#optionsContainer .option-row').length === 0) {
                    addOptionRow(); addOptionRow(); addOptionRow(); addOptionRow();
                }
            } 
            else if (selectedType === window.QB_QTYPE_TRUE_FALSE) {
                $('#section-true-false').removeClass('d-none');
            }
            else if (selectedType === window.QB_QTYPE_FILL_BLANK) {
                $('#section-fill-blank').removeClass('d-none');
            }
            else if (selectedType === window.QB_QTYPE_MATCHING) {
                $('#section-matching').removeClass('d-none');
                if ($('#matchingContainer .match-row').length === 0) {
                    addMatchRow(); addMatchRow();
                }
            }
            else if (selectedType === window.QB_QTYPE_SHORT || selectedType === window.QB_QTYPE_LONG) {
                $('#section-text-answer').removeClass('d-none');
                $('#correct_answer_text').attr('required', 'required');
            }
        });

        $('#addOptionBtn').on('click', function () {
            addOptionRow();
        });

        $(document).on('click', '.removeOptionBtn', function () {
            $(this).closest('.option-row').remove();
            reindexOptions();
        });
        
        $(document).on('click', '.addBlankBtn', function () {
            addBlankRow();
        });
        
        $(document).on('click', '.removeBlankBtn', function () {
            $(this).closest('.blank-row').remove();
            reindexBlanks();
        });

        $('#addMatchBtn').on('click', function () {
            addMatchRow();
        });

        $(document).on('click', '.removeMatchBtn', function () {
            $(this).closest('.match-row').remove();
            reindexMatches();
        });

        function addOptionRow() {
            var html = `
                <div class="col-md-12 option-row">
                    <div class="d-flex align-items-center g-10">
                        <input type="radio" name="is_correct_option" value="${optionCount}" class="form-check-input mt-0" style="width: 20px; height: 20px;" title="Mark as correct answer">
                        <input type="text" name="options[${optionCount}]" class="form-control zForm-control flex-grow-1" placeholder="Option text" required>
                        <button type="button" class="btn btn-sm btn-danger removeOptionBtn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#optionsContainer').append(html);
            optionCount++;
            reindexOptions();
        }
        
        function addBlankRow() {
            var html = `
                <div class="col-md-12 blank-row mt-10">
                    <div class="d-flex align-items-center g-10">
                        <span class="fw-500 blank-number"></span>
                        <input type="text" name="blanks[]" class="form-control zForm-control flex-grow-1" placeholder="Answer for [blank]" required>
                        <button type="button" class="btn btn-sm btn-danger removeBlankBtn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#blanksContainer').append(html);
            reindexBlanks();
        }

        function addMatchRow() {
            var html = `
                <div class="col-md-12 match-row">
                    <div class="d-flex align-items-center g-10">
                        <input type="text" name="match_left[]" class="form-control zForm-control flex-grow-1" placeholder="Left side text" required>
                        <input type="text" name="match_right[]" class="form-control zForm-control flex-grow-1" placeholder="Right side text (Correct match)" required>
                        <button type="button" class="btn btn-sm btn-danger removeMatchBtn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#matchingContainer').append(html);
            reindexMatches();
        }

        function reindexOptions() {
            $('#optionsContainer .option-row').each(function(index) {
                $(this).find('input[type="radio"]').val(index);
                $(this).find('input[type="text"]').attr('name', 'options[' + index + ']');
            });
            optionCount = $('#optionsContainer .option-row').length;
        }

        function reindexBlanks() {
            $('#blanksContainer .blank-row').each(function(index) {
                $(this).find('.blank-number').text((index + 1) + '.');
                $(this).find('input[type="text"]').attr('name', 'blanks[' + index + ']');
            });
        }
        
        function reindexMatches() {
            // Nothing to strictly reindex if we just use arrays for matches
        }


    });

})(jQuery);
