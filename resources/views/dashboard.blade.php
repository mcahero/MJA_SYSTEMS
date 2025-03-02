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
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">Dashboard</h1>
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
            <div class="row align-items-center">
                <div class="col-6 p-3">
                    <h4 class="fw-semibold mb-0">
                        <span class="fs-2 fw-semibold text-primary">Today's Transactions</span>
                    </h4>
                </div>
                <div class="col-6 p-3 text-right">
                    <h6 class="fw-semibold mb-0">
                        {{ \Carbon\Carbon::now('Asia/Manila')->isoFormat('dddd, MMMM D, YYYY h:mm') }}
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-lg-3">
                    <a class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-primary">35</div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Warehouse Total In
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-4">
                    <a class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-dark">120</div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Warehouse Total Out
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-4">
                    <a class="block block-rounded block-link-shadow text-center">
                        <div class="block-content block-content-full">
                            <div class="fs-2 fw-semibold text-dark">69,841</div>
                        </div>
                        <div class="block-content py-2 bg-body-light">
                            <p class="fw-medium fs-sm text-muted mb-0">
                                Total B.O
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">SKU Count</h3>
                    <div class="block-options">
                        <div class="dropdown">
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ecom-filters">
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    Pending..
                                    <span class="badge bg-black-50 rounded-pill">35</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    Processing
                                    <span class="badge bg-warning rounded-pill">15</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    For Delivery
                                    <span class="badge bg-info rounded-pill">20</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    Canceled
                                    <span class="badge bg-danger rounded-pill">72</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    Delivered
                                    <span class="badge bg-success rounded-pill">890</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="javascript:void(0)">
                                    All
                                    <span class="badge bg-primary rounded-pill">997</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive">
                        <table class="table table-borderless table-vcenter js-dataTable-full" id="one-ecom-orders">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Warehouse</th>
                                    <th>Buffer</th>
                                    <th>Selling</th>
                                    <th>Sold</th>
                                    <th>B.O</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 10; $i++)
                                    <tr>
                                        <td><a href="javascript:void(0)">SKU-{{ sprintf('%08d', rand(1, 99999999)) }}</a>
                                        </td>
                                        <td>{{ rand(100, 999) }}</td>
                                        <td>{{ rand(100, 999) }}</td>
                                        <td>
                                            <span>{{ rand(100, 999) }}</span>
                                        </td>
                                        <td>{{ rand(100, 999) }}</td>
                                        <td>
                                            <span>{{ rand(100, 999) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ rand(100, 999) }}</span>
                                        </td>
                                    </tr>
                                @endfor
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- END Page Content -->
@endsection
