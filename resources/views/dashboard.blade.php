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
    <script>
        jQuery(function() {
                    Dashmix.helpers(['dt-buttons']);
                    $('.js-dataTable-full').dataTable({
                            pageLength: 10,
                            lengthMenu: [
                                [5, 10, 20, 50],
                                [5, 10, 20, 50]
                            ],
                            autoWidth: false,
                            order: [
                                [0, 'asc'],
                            });
                    });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">Inventory Dashboard</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <main id="container">
        <div class="content">
            <!-- Today's Header -->
            <div class="row align-items-center">
                <div class="col-6 p-3">
                    <h4 class="fw-semibold mb-0">
                        <span class="fs-2 fw-semibold text-primary">Today's Summary</span>
                    </h4>
                </div>
                <div class="col-6 p-3 text-right">
                    <h6 class="fw-semibold mb-0">
                        {{ $today->isoFormat('dddd, MMMM D, YYYY h:mm A') }}
                    </h6>
                </div>
            </div>

            <!-- Metrics Blocks -->
            <div class="row">
                <!-- Warehouse Total In -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-primary">
                                {{ number_format($warehouseTotalIn) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Warehouse In
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Total Out -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-danger">
                                {{ number_format($warehouseTotalOut) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Warehouse Out
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buffer Total In -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-info">
                                {{ number_format($bufferTotalIn) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Buffer In
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buffer Total Out -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-warning">
                                {{ number_format($bufferTotalOut) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Buffer Out
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total B.O -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-dark">
                                {{ number_format($totalBO) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Total B.O
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="col-6 col-lg-2">
                    <div class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-success">
                                {{ number_format($totalTransactions) }}
                            </div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Total Stock
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKU Inventory Table -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">SKU Inventory Summary</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="fullscreen_toggle"></button>
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead class="thead-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>Warehouse</th>
                                    <th>Buffer</th>
                                    <th>Display</th>
                                    <th>Sold</th>
                                    <th>B.O</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventoryData as $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product->product_sku }}</td>
                                        <td>{{ number_format($product->warehouse_qty) }}</td>
                                        <td>{{ number_format($product->buffer_qty) }}</td>
                                        <td>{{ number_format($product->display_qty) }}</td>
                                        <td>{{ number_format($product->sold_qty) }}</td>
                                        <td>{{ number_format($product->bo_qty) }}</td>
                                        <td class="fw-bold text-primary">{{ number_format($product->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- END Page Content -->
@endsection
