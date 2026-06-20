(function ($) {
    "use strict";

    $(document).ready(function () {
        questionsDataTable();
    });

    function questionsDataTable() {
        if ($("#questionsDataTable").length) {
            var table = $("#questionsDataTable").DataTable({
                pageLength: 10,
                ordering: false,
                serverSide: true,
                processing: true,
                searching: true,
                responsive: {
                    breakpoints: [
                        { name: "desktop", width: Infinity },
                        { name: "tablet", width: 1400 },
                        { name: "fablet", width: 768 },
                        { name: "phone", width: 480 },
                    ],
                },
                ajax: {
                    url: $('#questionsDataRoute').val(),
                },
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    searchPlaceholder: "Search",
                    search: ""
                },
                dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
                columns: [
                    { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
                    { "data": "class_name", "name": "class_name", orderable: false, searchable: false },
                    { "data": "subject_name", "name": "subject_name", orderable: false, searchable: false },
                    { "data": "type_name", "name": "type_name", orderable: false, searchable: false },
                    { "data": "question_preview", "name": "questions.question_text" },
                    { "data": "status", "name": "questions.status" },
                    { "data": "action", searchable: false, responsivePriority: 2 },
                ],
                stateSave: true,
                "bDestroy": true
            });

            $('#searchByQuestion').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Preview Modal
            $(document).on('click', '.view-preview-btn', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = $('#questionsPreviewRoute').val() + '/' + id;
                
                $('#questionPreviewModalContent').html('<div class="p-20 text-center"><i class="fa fa-spinner fa-spin fa-2x text-main-color"></i></div>');
                $('#questionPreviewModal').modal('show');
                
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        $('#questionPreviewModalContent').html(response);
                    },
                    error: function () {
                        $('#questionPreviewModalContent').html('<div class="p-20 text-center text-danger">Failed to load preview.</div>');
                    }
                });
            });
        }
    }

})(jQuery);
