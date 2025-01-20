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
        $('#addPcsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var sku = button.data('sku');
            var modal = $(this);
            modal.find('.modal-body #modalSku').val(sku);
        });
    </script>
    <style>

    </style>

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
        <!-- Your Block -->
        <div class="block block-rounded">
            <div class="block-content">
                <form action="/warehouse/search" method="GET">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search.." name="q"
                                value="{{ request('q') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
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
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addPcsModal">
                                    Add PCS
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal fade" id="addPcsModal" tabindex="-1" role="dialog" aria-labelledby="addPcsModalLabel"
                    aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content text-center">
                            <div class="modal-header border-0 bg-primary">
                                <h5 class="modal-title h6  text-white" id="addPcsModalLabel">Add Pieces to Warehouse</h5>
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
                                            <p class="mb-0"
                                                style="font-size: 24px; font-weight: bold; line-height: 80px;">10
                                            </p>
                                            <p class="mb-0" style="font-size: 14px; color: #6c757d;">Receiving</p>
                                        </div>
                                        <span class="mx-3" style="font-size: 40px; font-weight: bold;">&rarr;</span>
                                        <div class="text-center"
                                            style="width: 100px; height: 80px; border: 2px solid #000; border-radius: 8px;">
                                            <input type="number" class="form-control text-center" id="pcs"
                                                name="pcs"
                                                style="height: 100%; border: none; font-size: 24px; font-weight: bold;"
                                                placeholder="0" required>
                                            <p class="mb-0" style="font-size: 14px; color: #6c757d;">Warehouse</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="sku" id="modalSku">
                                </div>
                                <div class="modal-footer border-0 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary px-4"
                                        style="background-color: #00AEEF; border-color: #00AEEF;">Add PCS</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END Your Block -->
    </div>
    <!-- END Page Content -->
@endsection
