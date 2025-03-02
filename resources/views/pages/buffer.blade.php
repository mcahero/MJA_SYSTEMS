@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        /* For Chrome, Edge, Safari */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* For Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .content {
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Buffer
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="buffer"> [ F1 ] Buffer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="bufferlogs"> [ F2 ] Buffer Logs</a>
        </ul>
        <div class="block block-rounded">
            <div class="block-content ">
                <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal"
                    data-target="#Addbuffermodal">
                    <i class="fa fa-plus mr-2"></i>Add Pcs to Buffer
                </button>
                <table id="buffer_table" style="font-size: 12px;" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>SKU #</th>
                            <th>Name</th>
                            <th>Entry Date</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Balance</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                {{-- <table id="buffer_table" style="font-size: 12px;" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>SKU #</th>
                            <th>Name</th>
                            <th>Last Modified</th>
                            <th>PCS</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table> --}}
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="Addbuffermodal" tabindex="-1" aria-labelledby="AddbuffermodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Pcs to Buffer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Bar -->
                    <form id="buffer_form">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="sku_id">SKU #</label>
                                <select class="form-control select2" style="width: 100%;" id="sku_id" name="sku_id">
                                    <option value="">Search SKU #</option>
                                </select>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">Selected SKU: <input
                                            type="text" class="form-control" id="selected_sku" name="product_name"
                                            placeholder="No Selected SKU..." readonly></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">Product Name:<input
                                            type="text" placeholder="No Selected SKU..." class="form-control"
                                            id="product_name" name="product_name" readonly></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <div class="text-center"
                                    style="width: 130px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                                    <p id="balance_pcs" class="mb-0"
                                        style="font-size: 24px; font-weight: bold; line-height: 80px;">0</p>
                                    <b class="mb-0" style="font-size: 12px; color: #6c757d;">Warehouse</b>
                                </div>
                                <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                                <div class="text-center" id="dynamic_container"
                                    style="width: 130px; height: 80px; border: 2px solid #000; border-radius: 8px;">
                                    <input type="number" class="form-control text-center" id="buffer_pcs_in"
                                        name="buffer_pcs_in"
                                        style="height: 100%; border: none; font-size: 24px; font-weight: bold;"
                                        placeholder="0" required>
                                    <b class="mb-0" style="font-size: 12px; color: #6c757d;">Add to Buffer</b>
                                </div>


                                <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                                <div class="text-center"
                                    style="width: 130px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                                    <p class="mb-0" style="font-size: 24px; font-weight: bold; line-height: 80px;">0
                                    </p>
                                    <b class="mb-0 text-nowrap" style="font-size: 12px; color: #6c757d;">Current
                                        Buffer
                                        Pcs</b>
                                </div>
                            </div>
                            <div class="form-group text-left mb-0 mt-2">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control"style="font-size: 12px;" id="remarks" name="remarks" rows="3"
                                    placeholder="Enter remarks here..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="button" id="addToBuffer" class="btn btn-primary btn-sm btn-block"
                                style="background-color: #00AEEF; border-color: #00AEEF;">Add PCS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
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
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>


    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            csrf_token = $('meta[name="csrf-token"]').attr('content');

            $('#addToBuffer').click(function() {
                let formData = new FormData();
                let product_sku = $('#sku_id').val().trim();
                let buffer_pcs_in = $('#buffer_pcs_in').val().trim();
                let remarks = $('#remarks').val().trim();
                let balance_pcs = $('#balance_pcs').text().trim();

                console.log('Adding PCS to buffer:', {
                    product_sku,
                    buffer_pcs_in,
                    remarks,
                    balance_pcs
                });

                formData.append('sku_id', parseInt(product_sku, 10));
                formData.append('balance_pcs', balance_pcs);
                formData.append('buffer_pcs_in', buffer_pcs_in);
                formData.append('remarks', remarks);

                $.ajax({
                    url: '/warehouse/buffer/add_pcs',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    success: function(response) {
                        $('#Addbuffermodal').modal('hide');
                        console.log('Response:', response);
                        Swal.fire({
                            icon: 'success',
                            title: 'PCS added to buffer successfully.',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: false,
                        });
                        $('#buffer_form')[0].reset();
                        $('#balance_pcs').text('0');

                        getbuffer()
                        getpcs()

                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding PCS to buffer:', error);

                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Failed to add PCS to buffer. Please try again.',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                    }
                });
            });

            $.ajax({
                url: '/pages/buffer/getwarehouseproducts',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(response) {
                    console.log('Received product lists:', response);

                    $('#sku_id').empty().append(
                        '<option value="" disabled selected>Select SKU</option>');

                    $.each(response, function(index, sku) {
                        $('#sku_id').append('<option value="' + sku.sku_id +
                            '" data-balance="' + sku.balance_pcs + '">' + sku.product_sku +
                            '</option>');
                    });

                    $('#sku_id').on('change', function() {
                        let selectedSkuId = $(this).val();
                        let selectedProduct = response.find(sku => sku.sku_id == selectedSkuId);

                        if (selectedProduct) {
                            let productName = selectedProduct.product_shortname ?
                                `${selectedProduct.product_fullname} (${selectedProduct.product_shortname})` :
                                selectedProduct.product_fullname;

                            $('#product_name').val(productName);
                            $('#selected_sku').val(selectedProduct.product_sku);
                            $('#balance_pcs').text(selectedProduct.balance_pcs);
                        } else {
                            $('#product_name').val('');
                            $('#selected_sku').val('');
                            $('#balance_pcs').text('0');
                        }
                        console.log('Selected sku_id:', selectedSkuId);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching warehouse products:', xhr.responseText);
                }
            });

            getpcs()

            function getpcs() {
                $.ajax({
                    url: '/pages/buffer/getwareproducts',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    success: function(response) {
                        if (!Array.isArray(response)) {
                            console.error('Invalid response format:', response);
                            return;
                        }

                        $('#sku_id').on('change', function() {
                            let selectedSku = $(this).val();
                            let selectedProduct = response.find(sku => sku.id == selectedSku);

                            if (selectedProduct) {
                                let balance_pcs = selectedProduct.pcs;
                                $('#balance_pcs').text(balance_pcs);
                            } else {
                                $('#balance_pcs').text('N/A');
                            }

                            console.log('Selected SKU:', selectedSku);
                            console.log('Selected getwareproducts:', selectedProduct);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching warehouse products:', error);
                    }
                });

            }


            let productList = [];

            $(document).ready(function() {
                const csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/pages/buffer/getproducts',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    success: function(products) {
                        productList = products;
                        getbuffer();
                    }
                });
            });

            function getbuffer() {
                console.log('Fetching buffer list...');
                $.ajax({
                    url: '/pages/buffer/getbuffer',
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    success: function(data) {
                        console.log('Received buffer list:', data);
                        displaybufferlist(data);
                    }
                });
            }

            function displaybufferlist(data) {
                $('#buffer_table').DataTable({
                    destroy: true,
                    data: data,
                    columns: [{
                            data: 'id',
                            render: function(data, type, row) {
                                const paddedId = String(data).padStart(3, '0');
                                return `${paddedId}-${data}`;
                            }
                        },
                        {
                            data: null
                        },
                        {
                            data: null
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'buffer_pcs_in'
                        },
                        {
                            data: 'buffer_pcs_out'
                        },
                        {
                            data: 'buffer_balance_pcs'
                        },
                        {
                            data: 'remarks'
                        }
                    ],
                    columnDefs: [{
                            targets: 1,
                            render: (data, type, row) => {
                                const product = productList.find(p => p.id == row.product_sku);
                                return product ?
                                    `${product.product_sku}` :
                                    'Loading...';
                            }
                        },
                        {
                            targets: 2,
                            width: '20%',
                            render: (data, type, row) => {
                                const product = productList.find(p => p.id == row.product_sku);
                                return product ?
                                    `${product.product_fullname} <small class="text-muted">(${product.product_shortname})</small>  <small class="text-muted">${product.jda_systemname}</small>` :
                                    'Loading...';
                            }
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ] // Add this line to set initial sorting
                });
            }

            $(document).ready(function() {
                $('#Addbuffermodal').on('shown.bs.modal', function() {
                    // Destroy existing instance (if any) to prevent duplicates
                    if ($.fn.select2 && $('#sku_id').data('select2')) {
                        $('#sku_id').select2('destroy');
                    }

                    // Re-initialize Select2
                    $('#sku_id').select2({
                        dropdownParent: $('#Addbuffermodal'),
                        selectOnClose: true,
                        width: '100%',
                        placeholder: "Search or select an option",
                    });
                });
            });

            function adjustWidthBasedOnInput(inputId, containerId) {
                document.getElementById(inputId).addEventListener('input', function() {
                    let inputLength = this.value.length;
                    let parentDiv = document.getElementById(containerId);

                    // Set dynamic width based on input length, but ensure a minimum of 100px
                    let newWidth = 100 + (inputLength * 10);
                    parentDiv.style.width = newWidth + 'px';
                });
            }
            adjustWidthBasedOnInput('buffer_pcs_in', 'dynamic_container');

            document.addEventListener('DOMContentLoaded', function() {
                const table = document.querySelector('table');
                let currentRowIndex = 0;

                // Add a class to highlight the selected row
                function highlightRow(index) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach((row, i) => {
                        if (i === index) {
                            row.classList.add('table-primary');
                        } else {
                            row.classList.remove('table-primary');
                        }
                    });
                }

            });
            document.addEventListener("keydown", (event) => {
                if (event.ctrlKey && event.key === "F") {
                    event.preventDefault();
                    $('#Addbuffermodal').modal('show');
                }
            });

            document.addEventListener("keydown", (event) => {
                if (event.key === "F2") {
                    event.preventDefault();
                    window.location.href = "bufferlogs";
                }
            });
            document.addEventListener("keydown", (event) => {
                if (event.key === "F1") {
                    event.preventDefault();
                    window.location.href = "buffer";
                }
            });
        });
    </script>
@endsection
@endsection
