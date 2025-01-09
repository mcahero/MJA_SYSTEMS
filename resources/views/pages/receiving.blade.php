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
    </style>

    <!-- Add JavaScript for Stepper and Keyboard Shortcuts -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const steps = document.querySelectorAll(".step");
            const stepContents = document.querySelectorAll(".step-content");
            const progressBar = document.getElementById("step-progress");
            const nextButtons = document.querySelectorAll(".step-next");
            const prevButtons = document.querySelectorAll(".step-prev");

            let currentStep = 1;

            function showStep(step) {
                steps.forEach(s => s.classList.toggle("active", s.dataset.step == step));
                stepContents.forEach(content => content.classList.toggle("active", content.dataset.step == step));

                // Update progress bar
                progressBar.style.width = `${(step / steps.length) * 100}%`;
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
                    if (validateStep(currentStep) && currentStep < steps.length) {
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
                    if (validateStep(currentStep) && currentStep < steps.length) {
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
                    if (currentStep === steps.length) {
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
                            <th> </th>
                            <th>Transaction #</th>
                            <th>ARRIVAL DATE</th>
                            <th>SKU #</th>
                            <th>NAME</th>
                            <th>PCS</th>
                            <th>COLOR CODE</th>
                            <th>CHECKER</th>
                            <th>REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>000000001</td>
                            <td>12/1/2025</td>
                            <td>000000001</td>
                            <td style="width: 15%;">
                                <span>Shampoo</span>
                                <small class="text-muted mb-0 d-block">JDA Shampoo</small>
                            </td>
                            <td>1</td>
                            <td><span class="badge badge-pill badge-success">AE</span></td>
                            <td>Mark Smith</td>
                            <td>-</td>
                        </tr>
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
                                        <li class="step" data-step="3">Additional Info</li>
                                        <li class="step" data-step="4">Finalize</li>
                                    </ul>
                                </div>

                                <!-- Stepper Body -->
                                <div class="stepper-body">
                                    <!-- Step 1: Transaction Details -->
                                    <div class="step-content active" data-step="1">
                                        <div class="form-group">
                                            <label for="transaction-number">Transaction #</label>
                                            <input type="text" class="form-control" id="transaction-number"
                                                name="transaction-number" placeholder="Enter transaction number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="arrival-date">Arrival Date</label>
                                            <input type="date" class="form-control" id="arrival-date" name="arrival-date"
                                                required>
                                        </div>
                                        <button type="button" class="btn btn-alt-primary step-next">
                                            Next <i class="fa fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>

                                    <!-- Step 2: Product Details -->
                                    <div class="step-content" data-step="2">
                                        <div class="form-group">
                                            <label for="product-sku">SKU #</label>
                                            <input type="text" class="form-control" id="product-sku" name="product-sku"
                                                placeholder="000000001" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product-name">Name</label>
                                            <input type="text" class="form-control" id="product-name" name="product-name"
                                                placeholder="Enter product name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product-pcs">PCS</label>
                                            <input type="number" class="form-control" id="product-pcs" name="product-pcs"
                                                placeholder="Enter number of pieces" required>
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
                                            <label for="color-code">Color Code</label>
                                            <input type="text" class="form-control" id="color-code" name="color-code"
                                                placeholder="Enter color code" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="checker">Checker</label>
                                            <input type="text" class="form-control" id="checker" name="checker"
                                                placeholder="Enter checker name" required>
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
