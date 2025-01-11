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
        document.addEventListener("DOMContentLoaded", () => {
            const steps = document.querySelectorAll(".step");
            const stepContents = document.querySelectorAll(".step-content");
            const progressBar = document.getElementById("step-progress");
            const nextButtons = document.querySelectorAll(".step-next");
            const prevButtons = document.querySelectorAll(".step-prev");
            const dateInput = document.getElementById('date-input');

            let currentStep = 1;

            function showStep(step) {
                steps.forEach(s => s.classList.toggle("active", s.dataset.step == step));
                stepContents.forEach(content => content.classList.toggle("active", content.dataset.step == step));

                // Update progress bar
                progressBar.style.width = `${(step / 3) * 100}%`;
            }

            function validateStep(step) {
                const activeStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
                const inputs = activeStepContent.querySelectorAll("[required]");
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });

                return isValid;
            }

            nextButtons.forEach(button => {
                button.addEventListener("click", () => {
                    if (validateStep(currentStep) && currentStep < 3) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener("click", () => {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            showStep(currentStep);

            // Add event listener for keyboard shortcuts
            document.addEventListener("keydown", (event) => {
                if (event.key === "F1") {
                    event.preventDefault();
                    $('#product-modal').modal('show');
                } else if (event.key === "ArrowRight") {
                    // Right Arrow to go to next step
                    if (validateStep(currentStep) && currentStep < 3) {
                        currentStep++;
                        showStep(currentStep);
                    }
                } else if (event.key === "ArrowLeft") {
                    // Left Arrow to go to previous step
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                } else if (event.key === "Enter") {
                    // Enter to submit the form only if on the final step
                    if (currentStep === 3) {
                        event.preventDefault();
                        document.getElementById("product-form").submit();
                    } else {
                        event.preventDefault();
                    }
                } else if (event.key === "ArrowUp" || event.key === "ArrowDown") {
                    // Up and Down Arrow to navigate between input fields
                    const activeStepContent = document.querySelector(`.step-content.active`);
                    const inputs = Array.from(activeStepContent.querySelectorAll(
                        "input, select, textarea"));
                    const currentIndex = inputs.indexOf(document.activeElement);

                    if (event.key === "ArrowUp" && currentIndex > 0) {
                        inputs[currentIndex - 1].focus();
                    } else if (event.key === "ArrowDown" && currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    }
                } else if (event.key === "t") {
                    // T to toggle product type
                    const productTypeSelect = document.getElementById("product-type");
                    if (productTypeSelect) {
                        const currentValue = productTypeSelect.value;
                        productTypeSelect.value = currentValue === "Returnable" ? "Non Returnable" :
                            "Returnable";
                    }
                }
            });

            // Initialize Select2
            $('.select2').select2();

            // Focus on date input when reaching the step
            dateInput.addEventListener('focus', () => {
                activateSegment('month');
            });

            // Handle keydown events for input
            dateInput.addEventListener('keydown', (e) => {
                let value = dateInput.value;

                if (activeSegment === 'month') {
                    if (e.key.toUpperCase() in monthMap) {
                        const month = monthMap[e.key.toUpperCase()];
                        value = month + value.slice(2);
                        dateInput.value = value;
                        setCaretPosition(dateInput, 3); // Move to year segment
                        activateSegment('year');
                        e.preventDefault();
                    }
                } else if (activeSegment === 'year') {
                    if (!isNaN(e.key) && e.key >= '0' && e.key <= '9') {
                        const year = yearBase + parseInt(e.key);
                        value = value.slice(0, 3) + year;
                        dateInput.value = value;
                        setCaretPosition(dateInput, 7); // End of input
                        deactivateSegment();
                        e.preventDefault();
                    }
                }

                // Prevent invalid input
                if (!/[A-Za-z0-9]/.test(e.key)) {
                    e.preventDefault();
                }

                // Handle Enter key to save and go back to main navigation
                if (e.key === "Enter") {
                    deactivateSegment();
                    document.activeElement.blur(); // Remove focus from date input
                    e.preventDefault();
                }
            });

            // Function to activate a specific segment
            function activateSegment(segment) {
                activeSegment = segment;

                if (segment === 'month') {
                    setCaretPosition(dateInput, 0, 2);
                } else if (segment === 'year') {
                    setCaretPosition(dateInput, 3, 7);
                }
            }

            // Function to deactivate the active segment
            function deactivateSegment() {
                activeSegment = null;
            }

            // Move caret to the appropriate segment
            function moveCaretToSegment(caretPos) {
                if (caretPos >= 0 && caretPos <= 2) {
                    activateSegment('month');
                } else if (caretPos >= 3 && caretPos <= 7) {
                    activateSegment('year');
                }
            }

            // Helper function to get the caret position
            function getCaretPosition(input) {
                return input.selectionStart;
            }

            // Helper function to set the caret position
            function setCaretPosition(input, start, end = start) {
                input.setSelectionRange(start, end);
            }

            // Initialize by activating the first segment (month)
            activateSegment('month');
        });
    </script>
    <script>
        const dateInput = document.getElementById('date-input');

        // Mapping for month and year shortcuts
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
        const yearBase = 2020;

        let activeSegment = null; // Keeps track of the selected segment

        // Add click event to isolate segments
        dateInput.addEventListener('click', () => {
            const caretPos = getCaretPosition(dateInput);
            moveCaretToSegment(caretPos);
        });

        // Handle keydown events for input
        dateInput.addEventListener('keydown', (e) => {
            let value = dateInput.value;

            if (activeSegment === 'month') {
                if (e.key.toUpperCase() in monthMap) {
                    const month = monthMap[e.key.toUpperCase()];
                    value = month + value.slice(2);
                    dateInput.value = value;
                    setCaretPosition(dateInput, 3); // Move to year segment
                    activateSegment('year');
                    e.preventDefault();
                }
            } else if (activeSegment === 'year') {
                if (!isNaN(e.key) && e.key >= '0' && e.key <= '9') {
                    const year = yearBase + parseInt(e.key);
                    value = value.slice(0, 3) + year;
                    dateInput.value = value;
                    setCaretPosition(dateInput, 7); // End of input
                    deactivateSegment();
                    e.preventDefault();
                }
            }

            // Prevent invalid input
            if (!/[A-Za-z0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Function to activate a specific segment
        function activateSegment(segment) {
            activeSegment = segment;

            if (segment === 'month') {
                setCaretPosition(dateInput, 0, 2);
            } else if (segment === 'year') {
                setCaretPosition(dateInput, 3, 7);
            }
        }

        // Function to deactivate the active segment
        function deactivateSegment() {
            activeSegment = null;
        }

        // Move caret to the appropriate segment
        function moveCaretToSegment(caretPos) {
            if (caretPos >= 0 && caretPos <= 2) {
                activateSegment('month');
            } else if (caretPos >= 3 && caretPos <= 7) {
                activateSegment('year');
            }
        }

        // Helper function to get the caret position
        function getCaretPosition(input) {
            return input.selectionStart;
        }

        // Helper function to set the caret position
        function setCaretPosition(input, start, end = start) {
            input.setSelectionRange(start, end);
        }

        // Initialize by activating the first segment (month)
        activateSegment('month');
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
                        @foreach ($receivings as $receiving)
                    <tr>
                        <td>{{ $receiving->product->product_sku }}</td>
                        <td>{{ $receiving->transaction_number }}</td>
                        <td>{{ $receiving->pcs }}</td>
                        <td>{{ $receiving->checker }}</td>
                        <td>{{ $receiving->expiry_date }}</td>
                        <td>{{ $receiving->remarks }}</td>
                    </tr>
                @endforeach
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
                            <form id="product-form" action="be_pages_dashboard.php" method="POST">
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
                                            <label for="product-sku-step1">SKU #</label>
                                            <select class="form-control select2" style="width: 100%;" id="product-sku-step1"
                                                name="product-sku-step1" required>
                                                <option value="">Select SKU</option>
                                                <option value="000000001">000000001</option>
                                                <option value="000000002">000000002</option>
                                                <option value="000000003">000000003</option>
                                            </select>
                                        </div>
                                        <div class="row" id="product-details">
                                            <div class="form-group col-12">
                                                <label for="product-name">Product Name</label>
                                                <input type="text" class="form-control" id="product-name"
                                                    name="product-name" readonly>
                                            </div>
                                            <div class="form-group col-6">
                                                <label for="product-barcode">Barcode</label>
                                                <input type="text" class="form-control" id="product-barcode"
                                                    name="product-barcode" readonly>
                                            </div>
                                            <div class="form-group col-6 ">
                                                <label for="product-type">Type</label>
                                                <input type="text" class="form-control" id="product-type"
                                                    name="product-type" readonly>
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
                                            <label for="transaction-number">TRANSACTION #</label>
                                            <input type="text" class="form-control" id="transaction-number"
                                                name="transaction-number" placeholder="Enter transaction number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product-pcs">PCS</label>
                                            <input type="number" class="form-control" id="product-pcs"
                                                name="product-pcs" placeholder="Enter number of pieces" required
                                                min="0" step="1"
                                                onkeydown="return event.key !== 'ArrowUp' && event.key !== 'ArrowDown'">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    style="margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center;">
                                                    <h3>EXPIRY DATE</h3>
                                                    <p>Click a segment to edit. Use shortcuts for Month (A-L) and Year
                                                        (0-9).</p>

                                                    <!-- Date Input Field -->
                                                    <input type="text" id="expiry-date" name="expiry-date"
                                                        style="width: 90%; padding: 10px; font-size: 18px; text-align: center; border: 1px solid #ccc; border-radius: 5px;"
                                                        value="MM/YYYY" maxlength="7" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    style=" margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center;">
                                                    <h3>Checker</h3>
                                                    <p>EXIPRY COLOR CODE</p>

                                                    <!-- Checker Input Field -->
                                                    <h1
                                                        style="font-size: 30px; display: inline-block; background-color: #ccc; padding: 10px; border-radius: 10px;">
                                                        CHECKER</h1>
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
