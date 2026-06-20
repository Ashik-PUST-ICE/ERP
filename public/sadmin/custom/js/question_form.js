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

        $('#question_type_id').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var hasOptions = selectedOption.data('has-options');

            if (hasOptions == 1) {
                $('#dynamicOptionsSection').removeClass('d-none');
                $('#correctAnswerTextSection').addClass('d-none');
                $('#correct_answer_text').removeAttr('required');
                
                // If it's a new form and no options exist, add 4 by default
                if ($('#optionsContainer .option-row').length === 0) {
                    addOptionRow();
                    addOptionRow();
                    addOptionRow();
                    addOptionRow();
                }
            } else if (hasOptions == 0) {
                $('#dynamicOptionsSection').addClass('d-none');
                $('#correctAnswerTextSection').removeClass('d-none');
                $('#correct_answer_text').attr('required', 'required');
            } else {
                $('#dynamicOptionsSection').addClass('d-none');
                $('#correctAnswerTextSection').addClass('d-none');
                $('#correct_answer_text').removeAttr('required');
            }
        });

        $('#addOptionBtn').on('click', function () {
            addOptionRow();
        });

        $(document).on('click', '.removeOptionBtn', function () {
            $(this).closest('.option-row').remove();
            reindexOptions();
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

        function reindexOptions() {
            $('#optionsContainer .option-row').each(function(index) {
                $(this).find('input[type="radio"]').val(index);
                $(this).find('input[type="text"]').attr('name', 'options[' + index + ']');
            });
            optionCount = $('#optionsContainer .option-row').length;
        }

    });

})(jQuery);
