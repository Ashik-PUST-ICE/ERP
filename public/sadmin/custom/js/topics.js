(function ($) {
    "use strict";

    $(document).ready(function () {
        topicsDataTable();
    });

    function topicsDataTable() {
        var table = $("#topicsDataTable").DataTable({
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
                url: $('#topicsDataRoute').val(),
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
                { "data": "chapter_name", "name": "chapter_name", orderable: false, searchable: false },
                { "data": "name", "name": "topics.name" },
                { "data": "order", "name": "topics.order" },
                { "data": "status", "name": "topics.status" },
                { "data": "action", searchable: false, responsivePriority: 2 },
            ],
            stateSave: true,
            "bDestroy": true
        });

        // Custom search input connection
        $('#searchByTopic').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Edit Topic Modal
    $(document).on('click', '.editTopicBtn', function () {
        var id = $(this).data('id');
        var url = $('#topicInfoRoute').val();
        
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    
                    $('#editTopicChapterId').val(data.chapter_id);
                    if ($('#editTopicChapterId').hasClass('select2-hidden-accessible')) {
                        $('#editTopicChapterId').trigger('change');
                    } else {
                        $('#editTopicChapterId').niceSelect('update');
                    }

                    $('#editTopicName').val(data.name);
                    $('#editTopicOrder').val(data.order);
                    
                    $('#editTopicStatus').val(data.status);
                    if ($('#editTopicStatus').hasClass('select2-hidden-accessible')) {
                        $('#editTopicStatus').trigger('change');
                    } else {
                        $('#editTopicStatus').niceSelect('update');
                    }
                    
                    var updateUrl = $('#topicUpdateBaseUrl').val() + '/' + id;
                    $('#editTopicForm').attr('action', updateUrl);
                    
                    $('#editTopicModal').modal('show');
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
