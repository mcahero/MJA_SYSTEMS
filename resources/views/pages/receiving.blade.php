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

    <style>
        .stepper-progress {
            margin-bottom: 20px;
        }

        .progress {
            height: 5px;
            margin-bottom: 15px;
        }

        .stepper-steps {
            display: flex;
            justify-content: space-between;
            list-style: none;
            padding: 0;
        }

        .stepper-steps .step {
            text-align: center;
            cursor: pointer;
            font-size: 14px;
        }

        .stepper-steps .step.active {
            font-weight: bold;
            color: #007bff;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

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
    </style>

    <!-- Add JavaScript for Stepper and Keyboard Shortcuts -->
    <script>
        $(document).ready(function() {
            const steps = $(".step");
            const stepContents = $(".step-content");
            const progressBar = $("#step-progress");
            const nextButtons = $(".step-next");
            const prevButtons = $(".step-prev");
            const dateInput = $('#date_input');
            const colorCodeDiv = $('#color-code-div');
            const colorCodeH1 = $('#color_code_h1');

            let currentStep = 1;
            let activeSegment = null;
            let enteredMonthLetter = '';
            let enteredYearLetter = '';

            const monthMap = {
                A: '01',
                B: '02',
                C: '03',
                D: '04',
                E: '05',
                F: '06',
                G: '07',
                H: '08',
                I: '09',
                J: '10',
                K: '11',
                L: '12'
            };

            const yearMap = {
                A: '2021',
                B: '2022',
                C: '2023',
                D: '2024',
                E: '2025',
                F: '2026',
                G: '2027',
                H: '2028',
                I: '2029',
                J: '2030',
                K: '2031',
                L: '2032'
            };

            const colorMap = {
                A: '#007f00',
                B: '#002f99',
                C: '#00bfff',
                D: '#8b4513',
                E: '#555555',
                F: '#cccccc',
                G: '#ff4500',
                H: '#ffa500',
                I: '#d87093',
                J: '#ff6347',
                K: '#4b0082',
                L: '#228b22'
            };

            // Utility Functions
            function showStep(step) {
                steps.removeClass("active").filter(`[data-step="${step}"]`).addClass("active");
                stepContents.removeClass("active").filter(`[data-step="${step}"]`).addClass("active");
                progressBar.css("width", `${(step / 3) * 100}%`);
            }

            function validateStep(step) {
                const activeStepContent = $(`.step-content[data-step="${step}"]`);
                const inputs = activeStepContent.find("[required]");
                let isValid = true;

                inputs.each(function() {
                    const input = $(this);
                    if (!input.val().trim()) {
                        input.addClass("is-invalid");
                        isValid = false;
                    } else {
                        input.removeClass("is-invalid");
                    }
                });

                return isValid;
            }

            function updateProgress(step) {
                if (validateStep(currentStep)) {
                    currentStep = step;
                    showStep(currentStep);
                }
            }

            function activateSegment(segment) {
                activeSegment = segment;
                dateInput.css("caretColor", segment === 'month' ? 'blue' : 'green');
                setCaretPosition(dateInput[0], segment === 'month' ? 0 : 3, segment === 'month' ? 2 : 7);
            }

            function deactivateSegment() {
                activeSegment = null;
                dateInput.css("caretColor", 'black');
            }

            function handleKeydown(e) {
                let value = dateInput.val();

                if (activeSegment === 'month' && e.key.toUpperCase() in monthMap) {
                    const month = monthMap[e.key.toUpperCase()];
                    value = month + value.slice(2);
                    dateInput.val(value);
                    setCaretPosition(dateInput[0], 3);
                    activateSegment('year');
                    enteredMonthLetter = e.key.toUpperCase();
                    updateColorCode(enteredMonthLetter, enteredYearLetter);
                    e.preventDefault();
                } else if (activeSegment === 'year' && e.key.toUpperCase() in yearMap) {
                    const year = yearMap[e.key.toUpperCase()];
                    value = value.slice(0, 3) + year;
                    dateInput.val(value);
                    setCaretPosition(dateInput[0], 7);
                    deactivateSegment();
                    enteredYearLetter = e.key.toUpperCase();
                    updateColorCode(enteredMonthLetter, enteredYearLetter);
                    e.preventDefault();
                }

                if (!/[A-Za-z0-9]/.test(e.key)) e.preventDefault();

                if (e.key === "Enter") {
                    deactivateSegment();
                    $(document.activeElement).blur();
                    e.preventDefault();
                }
            }

            function updateColorCode(monthKey, yearKey) {
                if (monthKey in colorMap) {
                    colorCodeH1.css("backgroundColor", colorMap[monthKey]);
                    colorCodeH1.text(`${monthKey}${yearKey}`);
                }
            }

            function getCaretPosition(input) {
                return input.selectionStart;
            }

            function setCaretPosition(input, start, end = start) {
                input.setSelectionRange(start, end);
            }

            // Event Handlers
            nextButtons.on("click", function() {
                updateProgress(currentStep + 1);
            });

            prevButtons.on("click", function() {
                if (currentStep > 1) {
                    updateProgress(currentStep - 1);
                }
            });

            $(document).on("keydown", function(event) {
                if (event.key === "F1") {
                    event.preventDefault();
                    $('#product-modal').modal('show');
                } else if (event.key === "ArrowRight") {
                    updateProgress(currentStep + 1);
                } else if (event.key === "ArrowLeft") {
                    if (currentStep > 1) updateProgress(currentStep - 1);
                } else if (event.key === "Enter" && currentStep === 3) {
                    event.preventDefault();
                    $("#product-form").submit();
                } else if (event.key === "ArrowUp" || event.key === "ArrowDown") {
                    const activeStepContent = $(".step-content.active");
                    const inputs = activeStepContent.find("input, select, textarea").toArray();
                    const currentIndex = inputs.indexOf(document.activeElement);

                    if (event.key === "ArrowUp" && currentIndex > 0) {
                        inputs[currentIndex - 1].focus();
                    } else if (event.key === "ArrowDown" && currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    }
                } else if (event.key === "t") {
                    const productTypeSelect = $("#product-type");
                    if (productTypeSelect.length) {
                        const currentValue = productTypeSelect.val();
                        productTypeSelect.val(currentValue === "Returnable" ? "Non Returnable" :
                            "Returnable");
                    }
                }
            });

            dateInput.on('focus', function() {
                activateSegment('month');
            });

            dateInput.on('click', function() {
                const caretPos = getCaretPosition(dateInput[0]);
                if (caretPos <= 2) activateSegment('month');
                else activateSegment('year');
            });

            dateInput.on('keydown', handleKeydown);

            $('.select2').select2();

            $('#product_sku_step1').on('change', function() {
                const products = @json($products);
                const productId = $(this).val();
                const product = products.find(p => p.id == productId);

                $('#product_name').val(product?.product_fullname || '');
                $('#product_barcode').val(product?.product_barcode || '');
                $('#product_type').val(product?.product_type || '');
            });

            // Initial State
            showStep(currentStep);
            activateSegment('month');
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Receiving </h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="block block-rounded">
            <div class="block-header">
                <button type="button" class="btn btn-primary" id="add-receiving-btn" data-toggle="modal"
                    data-target="#product-modal">
                    <i class="fa fa-plus mr-1"></i> Add Receiving (F1)
                </button>
            </div>
            <div class="block-content">
            </div>
            <div class="block-content block-content-full">
                <table style="font-size: 12px;" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="font-size: 12px;"> </th>
                            <th style="font-size: 12px;">Transaction #</th>
                            <th style="font-size: 12px;">SKU #</th>
                            <th style="font-size: 12px;">ARRIVAL DATE</th>
                            <th style="font-size: 12px;">NAME</th>
                            <th style="font-size: 12px;">PCS</th>
                            <th style="font-size: 12px;">COLOR CODE</th>
                            <th style="font-size: 12px;">CHECKER</th>
                            <th style="font-size: 12px;">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($receivings->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center">No receivings found.</td>
                            </tr>
                        @else
                            @foreach ($receivings as $receiving)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $receiving->transaction_number }}</td>
                                    <td>{{ $receiving->product->product_sku }}</td>
                                    <td>{{ \Carbon\Carbon::parse($receiving->created_at)->format('m/d/Y') }}</td>
                                    <td style="width: 15%;">
                                        <span>{{ $receiving->product->product_fullname }}</span>
                                        <small
                                            class="text-muted mb-0 d-block">{{ $receiving->product->product_shortname }}</small>
                                    </td>
                                    <td>{{ $receiving->pcs }}</td>
                                    <td><span class="badge badge-pill badge-success">{{ $receiving->expiry_date }}</span>
                                    </td>
                                    <td>{{ $receiving->checker }}</td>
                                    <td>{{ $receiving->remarks }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Your Block -->

        <!-- Add New Receiving Modal -->
        <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary text-white">
                            <h3 class="block-title">Add New Receiving</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form id="product-form" action="{{ route('receivings.store') }}" method="POST">
                                @csrf
                                <!-- Stepper Progress Bar -->
                                <div class="stepper-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" id="step-progress" style="width: 25%;"></div>
                                    </div>
                                    <ul class="stepper-steps">
                                        <li class="step active" data-step="1">Transaction Details</li>
                                        <li class="step" data-step="2">Product Details</li>
                                        <li class="step" data-step="4">Finalize</li>
                                    </ul>
                                </div>

                                <!-- Stepper Body -->
                                <div class="stepper-body">
                                    <!-- Step 1: Transaction Details -->
                                    <div class="step-content active" data-step="1">
                                        <div class="form-group">
                                            <label for="product_sku_step1">SKU #</label>
                                            <select class="form-control select2" style="width: 100%;" id="product_sku_step1"
                                                name="product_sku_step1" required>
                                                <option value="">Select SKU</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_sku }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row" id="product-details">
                                            <div class="form-group col-12">
                                                <label for="product_name">Product Name</label>
                                                <input type="text" class="form-control" id="product_name"
                                                    name="product_name" readonly>
                                            </div>
                                            <div class="form-group col-6">
                                                <label for="product_barcode">Barcode</label>
                                                <input type="text" class="form-control" id="product_barcode"
                                                    name="product_barcode" readonly>
                                            </div>
                                            <div class="form-group col-6 ">
                                                <label for="product_type">Type</label>
                                                <input type="text" class="form-control" id="product_type"
                                                    name="product_type" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-alt-primary step-next">
                                                    Next <i class="fa fa-arrow-right ml-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2: Product Details -->
                                    <div class="step-content" data-step="2">
                                        <div class="form-group">
                                            <label for="transaction_number">TRANSACTION #</label>
                                            <input type="text" class="form-control" id="transaction_number"
                                                name="transaction_number" placeholder="Enter transaction number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_pcs">PCS</label>
                                            <input type="number" class="form-control" id="product_pcs"
                                                name="product_pcs" placeholder="Enter number of pieces" required
                                                min="0" step="1"
                                                onkeydown="return event.key !== 'ArrowUp' && event.key !== 'ArrowDown'">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    style="margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center;">
                                                    <h3>EXPIRY DATE</h3>
                                                    <p>Click a segment to edit. Use shortcuts for Month (A-L) and Year
                                                        (A-J).</p>

                                                    <!-- Date Input Field -->
                                                    <input type="text" id="date_input"
                                                        style="width: 90%; padding: 10px; font-size: 18px; text-align: center; border: 1px solid #ccc; border-radius: 5px;"
                                                        value="MM/YYYY" maxlength="7" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="color-code-div"
                                                    style=" margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center;">
                                                    <h3>COLOR CODE</h3>
                                                    <p>EXIPRY COLOR CODE</p>

                                                    <!-- Checker Input Field -->
                                                    <h1 id="color_code_h1"
                                                        style="font-size: 30px; display: inline-block; color: #fff; background-color: #ccc; padding: 10px; border-radius: 10px;">
                                                        AE
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 3: Finalize -->
                                    <div class="step-content" data-step="3">
                                        <div class="form-group">
                                            <label for="checker">Checker</label>
                                            <input type="text" class="form-control" id="checker" name="checker"
                                                placeholder="Enter checker name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter remarks"></textarea>
                                        </div>
                                        <button type="button" class="btn btn-alt-secondary step-prev">
                                            <i class="fa fa-arrow-left mr-1"></i> Back
                                        </button>
                                        <button type="submit" class="btn btn-alt-primary">
                                            <i class="fa fa-plus mr-1"></i> Add Receiving
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
            <!-- END Add New Receiving Modal -->
        </div>
        <!-- END Page Content -->
    @endsection
