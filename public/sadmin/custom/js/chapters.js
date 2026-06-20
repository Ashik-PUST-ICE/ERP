(function ($) {
    "use strict";

    $(document).ready(function () {
        chaptersDataTable();
    });

    function chaptersDataTable() {
        var table = $("#chaptersDataTable").DataTable({
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
                url: $('#chaptersDataRoute').val(),
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
                { "data": "name", "name": "chapters.name" },
                { "data": "order", "name": "chapters.order" },
                { "data": "status", "name": "chapters.status" },
                { "data": "action", searchable: false, responsivePriority: 2 },
            ],
            stateSave: true,
            "bDestroy": true
        });

        // Custom search input connection
        $('#searchByChapter').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Edit Chapter Modal
    $(document).on('click', '.editChapterBtn', function () {
        var id = $(this).data('id');
        var url = $('#chapterInfoRoute').val();
        
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    
                    $('#editChapterSubjectId').val(data.subject_id);
                    if ($('#editChapterSubjectId').hasClass('select2-hidden-accessible')) {
                        $('#editChapterSubjectId').trigger('change');
                    } else {
                        $('#editChapterSubjectId').niceSelect('update');
                    }

                    $('#editChapterName').val(data.name);
                    $('#editChapterOrder').val(data.order);
                    
                    $('#editChapterStatus').val(data.status);
                    if ($('#editChapterStatus').hasClass('select2-hidden-accessible')) {
                        $('#editChapterStatus').trigger('change');
                    } else {
                        $('#editChapterStatus').niceSelect('update');
                    }
                    
                    var updateUrl = $('#chapterUpdateBaseUrl').val() + '/' + id;
                    $('#editChapterForm').attr('action', updateUrl);
                    
                    $('#editChapterModal').modal('show');
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
