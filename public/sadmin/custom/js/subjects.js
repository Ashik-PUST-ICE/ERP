(function ($) {
    "use strict";

    $(document).ready(function () {
        subjectsDataTable();
    });

    function subjectsDataTable() {
        var table = $("#subjectsDataTable").DataTable({
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
                url: $('#subjectsDataRoute').val(),
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
                { "data": "name", "name": "subjects.name" },
                { "data": "order", "name": "subjects.order" },
                { "data": "status", "name": "subjects.status" },
                { "data": "action", searchable: false, responsivePriority: 2 },
            ],
            stateSave: true,
            "bDestroy": true
        });

        // Custom search input connection
        $('#searchBySubject').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Edit Subject Modal
    $(document).on('click', '.editSubjectBtn', function () {
        var id = $(this).data('id');
        var url = $('#subjectInfoRoute').val();
        
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    
                    $('#editSubjectClassId').val(data.class_id);
                    if ($('#editSubjectClassId').hasClass('select2-hidden-accessible')) {
                        $('#editSubjectClassId').trigger('change');
                    } else {
                        $('#editSubjectClassId').niceSelect('update');
                    }

                    $('#editSubjectName').val(data.name);
                    $('#editSubjectOrder').val(data.order);
                    
                    $('#editSubjectStatus').val(data.status);
                    if ($('#editSubjectStatus').hasClass('select2-hidden-accessible')) {
                        $('#editSubjectStatus').trigger('change');
                    } else {
                        $('#editSubjectStatus').niceSelect('update');
                    }
                    
                    var updateUrl = $('#subjectUpdateBaseUrl').val() + '/' + id;
                    $('#editSubjectForm').attr('action', updateUrl);
                    
                    $('#editSubjectModal').modal('show');
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
