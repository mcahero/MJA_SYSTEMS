@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

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
        document.addEventListener('DOMContentLoaded', function () {
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
            document.addEventListener('keydown', function (e) {
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
                    const name = selectedRow.querySelector('td:nth-child(3)').textContent.trim();

                    $('#addPcsModal').modal('show');
                    $('#skuLabel').text(sku);
                    $('#nameLabel').text(name);
                    $('#modalSku').val(sku);
                }
            });

            // Initialize the first row as highlighted
            highlightRow(currentRowIndex);
        });
        $('#addPcsModal').on('shown.bs.modal', function () {
                                $('#pcs').focus();
                            });

        document.addEventListener("keydown", (event) => {
            if (event.key === "F2") {
            event.preventDefault();
            window.location.href = "Warehouselogs";
            }
        });
        document.addEventListener("keydown", (event) => {
            if (event.key === "F1") {
            event.preventDefault();
            window.location.href = "Warehouse";
            }
        });
    </script>
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

        .content{
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
                    Warehouse
                </h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
     <div class="content">
        <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" href="Warehouse"> [ F1 ] Warehouse</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Warehouselogs"> [ F2 ] Logs</a>
          </ul>
        <div class="block block-rounded">
            <div class="block-content">
                <table style="font-size: 12px;" class="table table-bordered table-striped table-vcenter">
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
                            <td class="font-w600">SKU-001</td>
                            <td class="font-w600">Product 1</td>
                            <td class="font-w600">0</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="font-w600">SKU-002</td>
                            <td class="font-w600">Product 2</td>
                            <td class="font-w600">5</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td class="font-w600">SKU-003</td>
                            <td class="font-w600">Product 3</td>
                            <td class="font-w600">10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="addPcsModal" tabindex="-1" role="dialog" aria-labelledby="addPcsModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0 bg-primary">
                <h5 class="modal-title h6 text-white" id="addPcsModalLabel">Add Pieces to Warehouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="/warehouse/add-pcs" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">SKU: <span id="skuLabel">1001111011</span></p>
                    <p class="mb-0" style="font-size: 16px; font-weight: bold;">NAME: <span id="nameLabel">Quickchow</span></p>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mb-4">
                    <div class="text-center" style="width: 100px; height: 80px; background-color: #E0E0E0; border-radius: 8px;">
                        <p class="mb-0" style="font-size: 24px; font-weight: bold; line-height: 80px;">10</p>
                        <p class="mb-0" style="font-size: 14px; color: #6c757d;">Receiving</p>
                    </div>
                    <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                    <div class="text-center" style="width: 100px; height: 80px; border: 2px solid #000; border-radius: 8px;">
                        <input type="number" class="form-control text-center" id="pcs" name="pcs" style="height: 100%; border: none; font-size: 24px; font-weight: bold;" placeholder="0" required>
                        <p class="mb-0" style="font-size: 14px; color: #6c757d;">Warehouse</p>
                    </div>
                    </div>
                    <input type="hidden" name="sku" id="modalSku">
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary px-4" style="background-color: #00AEEF; border-color: #00AEEF;">Add PCS</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection