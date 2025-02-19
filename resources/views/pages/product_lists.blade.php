@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
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
    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <!-- Add JavaScript for Stepper -->
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
                    data-target="#product-modal">
                    <i class="fa fa-plus mr-1"></i> Add Product (F1)
                </button>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table style="font-size: 12px;"
                        class="table table-bordered table-striped table-vcenter js-dataTable-full">
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
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $product->product_sku }}</td>
                                    <td>{{ $product->product_barcode }}</td>
                                    <td style="width: 15%;">
                                        <span>{{ $product->product_fullname }}</span>
                                        <span>{{ $product->jda_systemname }}</span>
                                        <small class="text-muted mb-0 d-block">{{ $product->product_shortname }}</small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-pill {{ $product->product_type == 'Returnable' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $product->product_type }}
                                        </span>
                                    </td>
                                    <td>{{ $product->product_warehouse }}</td>
                                    <td>{{ $product->product_entryperson }}</td>
                                    <td>{{ \Carbon\Carbon::parse($product->created_at)->format('m/d/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($product->updated_at)->format('m/d/Y') }}</td>
                                    <td>{{ $product->product_remarks }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('products.delete', $product->id) }}"
                                            class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#edit-product-modal" data-id="{{ $product->id }}"
                                            data-toggle="tooltip" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!-- END Your Block -->
        <!-- Add New Product Modal -->
        <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
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
                            <form id="product-form" action="{{ route('products.store') }}" method="POST">
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
                                        <button type="submit" class="btn btn-alt-primary">
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
        {{-- <div class="modal fade" id="edit-product-modal" tabindex="-1" role="dialog"
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
                            <form id="product-form" action="{{ route('products.edit') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" id="product_id" value="{{ $product->id }}">
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
                                                name="product_fullname" placeholder="Enter the full product name" required
                                                value="{{ $product->product_fullname }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_shortname">Shortcut Name (optional)</label>
                                            <input type="text" class="form-control" id="product_shortname"
                                                name="product_shortname" placeholder="Enter shortcut name (if any)"
                                                value="{{ $product->product_shortname }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="jda_systemname">JDA System Name (optional)</label>
                                            <input type="text" class="form-control" id="jda_systemname"
                                                name="jda_systemname" placeholder="Enter JDA system name (if any)"
                                                value="{{ $product->jda_systemname }}">
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
                                                value="{{ $product->product_sku }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_barcode">BARCODE</label>
                                            <input type="text" class="form-control" id="product_barcode"
                                                name="product_barcode" placeholder="000000001" required
                                                value="{{ $product->product_barcode }}">
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
                                                <option value="Returnable" class="text-success"
                                                    {{ $product->product_type == 'Returnable' ? 'selected' : '' }}>
                                                    Returnable</option>
                                                <option value="Non Returnable" class="text-danger"
                                                    {{ $product->product_type == 'Non Returnable' ? 'selected' : '' }}>Non
                                                    Returnable</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_warehouse">WAREHOUSE</label>
                                            <input type="text" class="form-control" id="product_warehouse"
                                                name="product_warehouse" placeholder="Enter warehouse name"
                                                value="{{ $product->product_warehouse }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_entryperson">Entry Person</label>
                                            <input type="text" class="form-control" id="product_entryperson"
                                                name="product_entryperson" placeholder="Enter entry person name" required
                                                value="{{ $product->product_entryperson }}">
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
                                            <textarea class="form-control" id="product_remarks" name="product_remarks" placeholder="Enter remarks"> {{ $product->product_remarks }}</textarea>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="submit" value="Update" class="btn btn-alt-primary">
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
        </div> --}}
        <!-- END Page Content -->
    @endsection
