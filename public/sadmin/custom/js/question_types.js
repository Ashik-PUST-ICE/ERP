(function ($) {
    "use strict";

    $(document).ready(function () {
        questionTypesDataTable();
    });

    function questionTypesDataTable() {
        var table = $("#questionTypesDataTable").DataTable({
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
                url: $('#questionTypesDataRoute').val(),
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
                { "data": "name", "name": "name" },
                { "data": "has_options", "name": "has_options" },
                { "data": "action", searchable: false, responsivePriority: 2 },
            ],
            stateSave: true,
            "bDestroy": true
        });

        // Custom search input connection
        $('#searchByQuestionType').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Edit Question Type Modal
    $(document).on('click', '.editQuestionTypeBtn', function () {
        var id = $(this).data('id');
        var url = $('#questionTypeInfoRoute').val();
        
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;

                    $('#editQuestionTypeName').val(data.name);
                    
                    $('#editQuestionTypeHasOptions').val(data.has_options);
                    if ($('#editQuestionTypeHasOptions').hasClass('select2-hidden-accessible')) {
                        $('#editQuestionTypeHasOptions').trigger('change');
                    } else {
                        $('#editQuestionTypeHasOptions').niceSelect('update');
                    }
                    
                    var updateUrl = $('#questionTypeUpdateBaseUrl').val() + '/' + id;
                    $('#editQuestionTypeForm').attr('action', updateUrl);
                    
                    $('#editQuestionTypeModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (error) {
                toastr.error('Something went wrong!');
            }
        });
    });

})(jQuery);
