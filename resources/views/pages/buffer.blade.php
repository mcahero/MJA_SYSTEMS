@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
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
                            <th>PCS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="font-w600">SKU-000000001</td>
                            <td class="font-w600">Quickchow4</td>
                            <td class="font-w600">50</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="font-w600">SKU-000000002</td>
                            <td class="font-w600">Quickchow5</td>
                            <td class="font-w600">100</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td class="font-w600">SKU-000000003</td>
                            <td class="font-w600">Quickchow6</td>
                            <td class="font-w600">150</td>
                        </tr>
                    </tbody>
                </table>
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
                    <form action="/warehouse/add-pcs" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="sku_id">SKU #</label>
                                <select class="form-control select2" style="width: 100%;" id="sku_id" name="sku_id"
                                    required>
                                    <option value="">Search SKU #</option>
                                </select>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">Selected SKU: <input
                                            type="text" class="form-control" id="selected_sku" name="product_name"
                                            readonly></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">Name:<input type="text"
                                            class="form-control" id="product_name" name="product_name" readonly></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <div class="text-center"
                                    style="width: 100px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                                    <p id="warehouse_pcs_display" class="mb-0"
                                        style="font-size: 24px; font-weight: bold; line-height: 80px;">0</p>
                                    <b class="mb-0" style="font-size: 12px; color: #6c757d;">Warehouse</b>
                                </div>

                                <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                                <div class="text-center"
                                    style="width: 100px; height: 80px; border: 2px solid #000; border-radius: 8px;">
                                    <input type="number" class="form-control text-center" id="pcs" name="pcs"
                                        style="height: 100%; border: none; font-size: 24px; font-weight: bold;"
                                        placeholder="0" required>
                                    <b class="mb-0" style="font-size: 12px; color: #6c757d;">Add to Buffer</b>
                                </div>
                                <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                                <div class="text-center"
                                    style="width: 100px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                                    <p class="mb-0" style="font-size: 24px; font-weight: bold; line-height: 80px;">10
                                    </p>
                                    <b class="mb-0 text-nowrap" style="font-size: 12px; color: #6c757d;">Current Buffer
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
                            <button type="submit" class="btn btn-primary btn-sm btn-block"
                                style="background-color: #00AEEF; border-color: #00AEEF;">Add PCS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="addPcsModal" tabindex="-1" role="dialog" aria-labelledby="addPcsModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0 bg-primary">
                    <h5 class="modal-title h6 text-white" id="addPcsModalLabel">Add Pieces to Buffer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/warehouse/add-pcs" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <p class="mb-0" style="font-size: 16px; font-weight: bold;">SKU: <span
                                    id="skuLabel">1001111011</span></p>
                            <p class="mb-0" style="font-size: 16px; font-weight: bold;">NAME: <span
                                    id="nameLabel">Quickchow</span></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-4">
                            <div class="text-center"
                                style="width: 100px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                                <p class="mb-0" style="font-size: 24px; font-weight: bold; line-height: 80px;">10</p>
                                <p class="mb-0" style="font-size: 12px; color: #6c757d;">Warehouse</p>
                            </div>
                            <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                            <div class="text-center"
                                style="width: 100px; height: 80px; border: 2px solid #000; border-radius: 8px;">
                                <input type="number" class="form-control text-center" id="pcs" name="pcs"
                                    style="height: 100%; border: none; font-size: 24px; font-weight: bold;"
                                    placeholder="0" required>
                                <p class="mb-0" style="font-size: 12px; color: #6c757d;">Buffer</p>
                            </div>
                        </div>
                        <input type="hidden" name="sku" id="modalSku">
                        <div class="form-group text-left mb-0 mt-2">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control"style="font-size: 12px;" id="remarks" name="remarks" rows="3"
                                placeholder="Enter remarks here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-sm btn-block add-to-buffer "
                            style="background-color: #00AEEF; border-color: #00AEEF;">Add PCS</button>
                    </div>
                </form>
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

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            csrf_token = $('meta[name="csrf-token"]').attr('content');

            // // Fetch warehouse products when modal opens
            // $("#Addbuffermodal").on("show.bs.modal", function () {
            //     console.log("Fetching warehouse products...");
            //     $.ajax({
            //         url: "/warehouse/products",
            //         type: "GET",
            //         success: function (data) {
            //             console.log("Products loaded:", data);
            //             let rows = "";
            //             data.forEach(product => {
            //                 rows += `
        //                     <tr data-sku="${product.product_sku}">
        //                         <td>${product.sku}</td>
        //                         <td>${product.product_name}</td>
        //                         <td class="warehouse-pcs">${product.pcs}</td>
        //                         <td>
        //                             <input type="number" class="buffer-input form-control" data-id="${product.id}" min="1" max="${product.pcs}">
        //                         </td>
        //                         <td>
        //                             <button class="add-to-buffer btn btn-success">Add</button>
        //                         </td>
        //                     </tr>
        //                 `;
            //             });
            //             $("#warehouse-table").html(rows);
            //         }
            //     });
            // });


            $.ajax({
                url: '/pages/buffer/getproducts',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(response) {

                    console.log('Received product lists:', response);

                    $('#sku_id').empty();
                    $('#sku_id').append(
                        '<option value="" disabled selected>Select SKU</option>');
                    $.each(response, function(index, sku) {
                        $('#sku_id').append('<option value="' + sku.id + '">' + sku
                            .product_sku + '</option>');
                    });

                    $('#sku_id').on('change', function() {
                        let selectedSku = $(this).val();
                        let selectedProduct = response.find(sku => sku.id ==
                            selectedSku);

                        if (selectedProduct) {
                            let productName = selectedProduct.product_shortname ?
                                `${selectedProduct.product_fullname} (${selectedProduct.product_shortname})` :
                                selectedProduct.product_fullname;


                            $('#product_name').val(
                                productName); // Set value in the input field
                            $('#selected_sku').val(selectedProduct
                                .product_sku);

                        }

                        console.log('Selected SKU:', selectedSku);
                        console.log('Selected Product:', selectedProduct);
                        console.log('Selected Product Name:', $('#product_name')
                            .val());
                    });
                },
            });

            $.ajax({
                url: '/pages/buffer/getwarehouseproducts',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(response) {
                    console.log('Received product lists:', response);

                    $('#sku_id').on('change', function() {
                        let selectedSku = $(this).val();
                        let selectedProduct = response.find(sku => sku.id == selectedSku);

                        if (selectedProduct) {
                            let pcs = selectedProduct
                            .pcs; // Assuming `pcs` is the field for quantity

                            // Update the warehouse quantity in the div
                            $('#warehouse_pcs_display').text(pcs);
                        }

                        console.log('Selected SKU:', selectedSku);
                        console.log('Selected Product:', selectedProduct);
                    });
                },
            });


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


            // Add product to buffer and update warehouse
            $(document).on("click", ".add-to-buffer", function() {
                let row = $(this).closest("tr");
                let sku = row.data("sku");
                let pcs = row.find(".buffer-input").val();
                let productId = row.find(".buffer-input").data("id");

                if (!pcs || pcs <= 0) {
                    alert("Enter a valid number of pieces.");
                    return;
                }

                $.ajax({
                    url: "/warehouse/add-to-buffer",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        sku: sku,
                        pcs: pcs,
                        product_id: productId
                    },
                    success: function(response) {
                        alert("Stock added to buffer!");
                        row.find(".warehouse-pcs").text(response.new_warehouse_pcs);
                        row.find(".buffer-input").val(""); // Reset input
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.responseJSON.message);
                    }
                });
            });


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

                // Navigate rows using arrow keys
                document.addEventListener('keydown', function(e) {
                    const rows = table.querySelectorAll('tbody tr');

                    if (e.key === 'ArrowDown') {
                        if (currentRowIndex < rows.length - 1) {
                            currentRowIndex++;
                            highlightRow(currentRowIndex);
                        }
                    } else if (e.key === 'ArrowUp') {
                        if (currentRowIndex > 0) {
                            currentRowIndex--;
                            highlightRow(currentRowIndex);
                        }
                    } else if (e.key === 'Enter') {
                        // Open modal with data from the current row
                        const selectedRow = rows[currentRowIndex];
                        const sku = selectedRow.querySelector('td:nth-child(2)').textContent.trim();
                        const name = selectedRow.querySelector('td:nth-child(3)').textContent
                    .trim();

                        $('#addPcsModal').modal('show');
                        $('#skuLabel').text(sku);
                        $('#nameLabel').text(name);
                        $('#modalSku').val(sku);
                    }
                });

                // Initialize the first row as highlighted
                highlightRow(currentRowIndex);
            });
            $('#addPcsModal').on('shown.bs.modal', function() {
                $('#pcs').focus();
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
