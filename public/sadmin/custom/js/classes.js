(function ($) {
    "use strict";

    $(document).ready(function () {
        classesDataTable();
    });

    function classesDataTable() {
        var table = $("#classesDataTable").DataTable({
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
                url: $('#classesDataRoute').val(),
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
                { "data": "order", "name": "order" },
                { "data": "status", "name": "status" },
                { "data": "action", searchable: false, responsivePriority: 2 },
            ],
            stateSave: true,
            "bDestroy": true
        });

        // Custom search input connection
        $('#searchByClass').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Edit Class Modal
    $(document).on('click', '.editClassBtn', function () {
        var id = $(this).data('id');
        var url = $('#classInfoRoute').val();
        
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    $('#editClassName').val(data.name);
                    $('#editClassOrder').val(data.order);
                    
                    $('#editClassStatus').val(data.status);
                    if ($('#editClassStatus').hasClass('select2-hidden-accessible')) {
                        $('#editClassStatus').trigger('change');
                    } else {
                        $('#editClassStatus').niceSelect('update');
                    }
                    
                    var updateUrl = $('#classUpdateBaseUrl').val() + '/' + id;
                    $('#editClassForm').attr('action', updateUrl);
                    
                    $('#editClassModal').modal('show');
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
