@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Products List </h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="block block-rounded">
            <div class="block-header">
            </div>
            <div class="block-content">
                <button type="button" class="btn btn-primary" id="add-product-btn" data-toggle="modal"
                    data-target="#product_modal">
                    <i class="fa fa-plus mr-1"></i> Add Product (F1)
                </button>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table style="font-size: 12px;" id="product_table"
                        class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th style="font-size: 12px;"> </th>
                                <th style="font-size: 12px;">SKU #</th>
                                <th style="font-size: 12px;">BARCODE</th>
                                <th style="font-size: 12px;">NAME</th>
                                <th style="font-size: 12px;">TYPE</th>
                                <th style="font-size: 12px;">WAREHOUSE</th>
                                <th style="font-size: 12px;">ENTRY PERSON</th>
                                <th style="font-size: 12px;">DATE ENTRY</th>
                                <th style="font-size: 12px;">LAST DATE MODIFIED</th>
                                <th style="font-size: 12px;">REMARKS</th>
                                <th style="font-size: 12px;">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!-- END Your Block -->
        <!-- Add New Product Modal -->
        <div class="modal fade" id="product_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary text-white">
                            <h3 class="block-title">Add New Product</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form id="product_form" >
                                @csrf
                                <!-- Stepper Progress Bar -->
                                <div class="stepper-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" id="step-progress" style="width: 25%;">
                                        </div>
                                    </div>
                                    <ul class="stepper-steps">
                                        <li class="step active" data-step="1">Name</li>
                                        <li class="step" data-step="2">Product Details</li>
                                        <li class="step" data-step="3">Additional Info</li>
                                        <li class="step" data-step="4">Finalize</li>
                                    </ul>
                                </div>

                                <!-- Stepper Body -->
                                <div class="stepper-body">
                                    <!-- Step 1: Name -->
                                    <div class="step-content active" data-step="1">
                                        <div class="form-group">
                                            <label for="product_fullname">Name of Product</label>
                                            <input type="text" class="form-control" id="product_fullname"
                                                name="product_fullname" placeholder="Enter the full product name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_shortname">Shortcut Name (optional)</label>
                                            <input type="text" class="form-control" id="product_shortname"
                                                name="product_shortname" placeholder="Enter shortcut name (if any)">
                                        </div>
                                        <div class="form-group">
                                            <label for="jda_systemname">JDA System Name (optional)</label>
                                            <input type="text" class="form-control" id="jda_systemname"
                                                name="jda_systemname" placeholder="Enter JDA system name (if any)">
                                        </div>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 2: Product Details -->
                                    <div class="step-content" data-step="2">
                                        <div class="form-group">
                                            <label for="product_sku">SKU #</label>
                                            <input type="text" class="form-control" id="product_sku"
                                                name="product_sku" placeholder="000000001" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_barcode">BARCODE</label>
                                            <input type="text" class="form-control" id="product_barcode"
                                                name="product_barcode" placeholder="000000001" required>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 3: Additional Info -->
                                    <div class="step-content" data-step="3">
                                        <div class="form-group">
                                            <label for="product_type">TYPE</label>
                                            <select class="form-control" id="product_type" name="product_type" required>
                                                <option value="">Select Type</option>
                                                <option value="Returnable" class="text-success">Returnable</option>
                                                <option value="Non Returnable" class="text-danger">Non Returnable
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_warehouse">WAREHOUSE</label>
                                            <input type="text" class="form-control" id="product_warehouse"
                                                name="product_warehouse" placeholder="Enter warehouse name">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_entryperson">Entry Person</label>
                                            <input type="text" class="form-control" id="product_entryperson"
                                                name="product_entryperson" placeholder="Enter entry person name" required>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 4: Finalize -->
                                    <div class="step-content" data-step="4">
                                        <div class="form-group">
                                            <label for="product_remarks">REMARKS</label>
                                            <textarea class="form-control" id="product_remarks" name="product_remarks" placeholder="Enter remarks"></textarea>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button id="add_product" type="button" class="btn btn-alt-primary">
                                            <i class="fa fa-plus mr-1"></i> Add Product
                                        </button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Add New Product Modal -->
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="edit_product" tabindex="-1" role="dialog"
            aria-labelledby="modal-block-small" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary text-white">
                            <h3 class="block-title">Add New Product</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form id="edit_product_form">
                                @csrf
                                <input type="hidden" name="product_id" id="product_id">
                                <!-- Stepper Progress Bar -->
                                <div class="stepper-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" id="step-progress" style="width: 25%;">
                                        </div>
                                    </div>
                                    <ul class="stepper-steps">
                                        <li class="step active" data-step="1">Name</li>
                                        <li class="step" data-step="2">Product Details</li>
                                        <li class="step" data-step="3">Additional Info</li>
                                        <li class="step" data-step="4">Finalize</li>
                                    </ul>
                                </div>

                                <!-- Stepper Body -->
                                <div class="stepper-body">
                                    <!-- Step 1: Name -->
                                    <div class="step-content active" data-step="1">
                                        <div class="form-group">
                                            <label for="product_fullname">Name of Product</label>
                                            <input type="text" class="form-control" id="product_fullname"
                                                name="product_fullname" placeholder="Enter the full product name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_shortname">Shortcut Name (optional)</label>
                                            <input type="text" class="form-control" id="product_shortname"
                                                name="product_shortname" placeholder="Enter shortcut name (if any)"
                                                >
                                        </div>
                                        <div class="form-group">
                                            <label for="jda_systemname">JDA System Name (optional)</label>
                                            <input type="text" class="form-control" id="jda_systemname"
                                                name="jda_systemname" placeholder="Enter JDA system name (if any)"
                                                >
                                        </div>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 2: Product Details -->
                                    <div class="step-content" data-step="2">
                                        <div class="form-group">
                                            <label for="product_sku">SKU #</label>
                                            <input type="text" class="form-control" id="product_sku"
                                                name="product_sku" placeholder="000000001" required
                                                >
                                        </div>
                                        <div class="form-group">
                                            <label for="product_barcode">BARCODE</label>
                                            <input type="text" class="form-control" id="product_barcode"
                                                name="product_barcode" placeholder="000000001" required
                                                >
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 3: Additional Info -->
                                    <div class="step-content" data-step="3">
                                        <div class="form-group">
                                            <label for="product_type">TYPE</label>
                                            <select class="form-control" id="product_type" name="product_type" required>
                                                <option value="">Select Type</option>
                                                <option value="1" class="text-success">Returnable</option>
                                                <option value="2" class="text-danger">Non Returnable</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_warehouse">WAREHOUSE</label>
                                            <input type="text" class="form-control" id="product_warehouse"
                                                name="product_warehouse" placeholder="Enter warehouse name"
                                                >
                                        </div>
                                        <div class="form-group">
                                            <label for="product_entryperson">Entry Person</label>
                                            <input type="text" class="form-control" id="product_entryperson"
                                                name="product_entryperson" placeholder="Enter entry person name" required
                                                >
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 4: Finalize -->
                                    <div class="step-content" data-step="4">
                                        <div class="form-group">
                                            <label for="product_remarks">REMARKS</label>
                                            <textarea class="form-control" id="product_remarks" name="product_remarks" placeholder="Enter remarks"></textarea>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="submit" id="save_editproduct" class="btn btn-alt-primary">
                                            <i class="fa fa-plus mr-1"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Add New Product Modal -->
        </div>
        <!-- END Page Content -->
        @section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>


    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>

    <!-- Add JavaScript for Stepper -->

        <script>
            $(document).ready(function() {
            csrf_token = $('meta[name="csrf-token"]').attr('content');

            $('#add_product').click(function () {
                let formData = new FormData();

                formData.append('product_fullname', $('#product_fullname').val().trim());
                formData.append('product_shortname', $('#product_shortname').val().trim());
                formData.append('jda_systemname', $('#jda_systemname').val().trim());
                formData.append('product_sku', $('#product_sku').val().trim());
                formData.append('product_barcode', $('#product_barcode').val().trim());
                formData.append('product_type', $('#product_type').val());
                formData.append('product_warehouse', $('#product_warehouse').val().trim());
                formData.append('product_entryperson', $('#product_entryperson').val().trim());
                formData.append('product_remarks', $('#product_remarks').val().trim());

                // Fetch CSRF token dynamically from meta tag
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "/product_lists",
                    type: "POST",
                    data: formData,
                    processData: false, // Prevent jQuery from converting FormData into a query string
                    contentType: false, // Prevent jQuery from setting content type (needed for FormData)
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Send CSRF token in headers
                    },
                    success: function (response) {
                        alert('Successfully Added Product');
                        console.log(response);
                        $('#product_form')[0].reset();
                        getproductlists();
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('Failed to add product. Please check the console for details.');
                    }
                });
        });


            $(document).on('click', '.edit_productlist', function(){
                let product_id = $(this).data('product_id');
            $.ajax({
                url: "/pages/product_lists/editform",
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: {id: product_id},
                success: function(data) {
                    $('#edit_product_form #product_id').val(product_id);
                    $("#edit_product_form #product_fullname").val(data.product_fullname);
                    $("#edit_product_form #product_shortname").val(data.product_shortname);
                    $("#edit_product_form #jda_systemname").val(data.jda_systemname);
                    $("#edit_product_form #product_sku").val(data.product_sku);
                    $("#edit_product_form #product_barcode").val(data.product_barcode);
                    $("#edit_product_form #product_type").val(data.product_type);
                    $("#edit_product_form #product_warehouse").val(data.product_warehouse);
                    $("#edit_product_form #product_entryperson").val(data.product_entryperson);
                    $("#edit_product_form #product_remarks").val(data.product_remarks);
                    $("#edit_product_form #updated_at").val(data.updated_at);
                }
            });
            });

            $(document).on('click', '#save_editproduct', function(){
                $.ajax({
                    url: "/pages/product_lists/edit",
                    type: "post",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    data: $('#edit_product_form').serialize(),
                    success: function(data) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Product Updated Successfully'
                        });
                        $('#edit_product_form')[0].reset();
                        $('#edit_product').modal('hide');
                        getproductlists();
                    }
                })
            });

            $(document).on('click', '.delete_productlist', function () {
                let product_id = $(this).data('product_id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/pages/product_lists/delete/" + product_id,
                            type: "DELETE",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': csrf_token
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: response.status,
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 5000
                                });

                                getproductlists(); // Refresh product list
                            },
                            error: function (xhr) {
                                let response = xhr.responseJSON;

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response ? response.message : 'Something went wrong.',
                                });
                            }
                        });
                    }
                });
            });


            getproductlists();
            function getproductlists(){
            $.ajax({
                url: "/product_display",
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(productlists) {
                    console.log(productlists);
                    display_productlist(productlists);
                }
            });
            }
                function display_productlist(productlists){
                $('#product_table').DataTable({
                    destroy: true,
                    data: productlists,
                    columns: [
                        { data: null },
                        { data: 'product_sku' },
                        { data: 'product_barcode' },
                        { data: null },
                        { data: 'product_type' },
                        { data: 'product_warehouse' },
                        { data: 'product_entryperson' },
                        { data: 'created_at' },
                        { data: 'updated_at' },
                        { data: 'product_remarks' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`${productlists.indexOf(rowData) + 1}`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`${rowData.product_fullname} ${rowData.jda_systemname} <small class="text-muted mb-0 d-block">${rowData.product_shortname}</small>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if(rowData.product_type == 1){
                                    $(td).html(`<span class="badge badge-success">Returnable</span>`)
                                }else if(rowData.product_type == 2){
                                    $(td).html(`<span class="badge badge-danger">Non Returnable</span>`)
                                }
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 10,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html('<div><button type="button" class="btn btn-sm btn-danger delete_productlist" data-product_id="' + rowData.id + '"><i class="fa fa-trash mr-1"></i></button>' +
                                    '<button type="button" class="btn btn-sm btn-primary ml-2 edit_productlist"  data-toggle="modal" data-target="#edit_product" data-product_id="' + rowData.id + '" id="product_form"><i class="fas fa-pen mr-1"></i></button></div>')
                                $(td).addClass('align-middle')
                            }
                        },

                    ]

                })
            }

            });
        </script>
    @endsection
    @endsection
