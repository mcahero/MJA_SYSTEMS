@extends('layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#sku_id').select2({
                placeholder: 'Search SKU #',
                allowClear: true,
                ajax: {
                    url: '{{ route('sku.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.product_sku + ' - ' + item.product_fullname
                                }
                            }),
                            pagination: {
                                more: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
            });

            // When SKU is selected
            $('#sku_id').on('change', function() {
                const skuId = $(this).val();
                if (skuId) {
                    getSystemCounts(skuId);
                }
            });

            // Calculate missing counts when physical counts change
            $('.physical-count').on('input', function() {
                calculateMissingCounts();
                calculateSystemTotal();
                calculateMissingTotal();
                calculateTotalItems(); //add this one again.
            });
            calculateSystemTotal();
            calculateTotalItems(); // add this one to initialize the total.
        });

        function getSystemCounts(skuId) {
            $.ajax({
                url: '{{ route('sku.system-counts') }}',
                type: 'GET',
                data: {
                    sku_id: skuId
                },
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                        alert(response.error);
                        return;
                    }

                    // Update system counts
                    $('#system-warehouse').text(response.warehouse);
                    $('#system-buffer').text(response.buffer);
                    $('#system-display').text(response.display);
                    $('#system-sold').text(response.sold);

                    // Store system counts in data attributes
                    $('[id^="system-"]').each(function() {
                        $(this).data('value', parseFloat($(this).text().replace(/,/g, '')) || 0);
                    });

                    calculateMissingCounts();
                    calculateSystemTotal();
                    calculateMissingTotal();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching system counts:", errorThrown);
                    alert("Error fetching system counts. Please check the console for details.");
                }
            });
        }

        function calculateMissingCounts() {
            $('.physical-count').each(function() {
                const location = $(this).data('location');
                const physicalCount = parseFloat($(this).val()) || 0;
                const systemCount = parseFloat($(`#system-${location}`).data('value')) || 0;
                const missingCount = systemCount - physicalCount;

                $(`#missing-${location}`).text(numberFormat(missingCount));
            });
        }

        function calculateTotalItems() {
            let total = 0;
            $('.physical-count').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#physical-total').val(numberFormat(total));
        }
        // Function to calculate the system total
        function calculateSystemTotal() {
            let systemTotal = 0;
            $('[id^="system-"]').each(function() {
                if (this.id !== "system-total") {
                    systemTotal += parseFloat($(this).text().replace(/,/g, '')) || 0;
                }
            });
            $('#system-total').text(numberFormat(systemTotal));
        }

        // Function to calculate the missing total
        function calculateMissingTotal() {
            let missingTotal = 0;
            $('[id^="missing-"]').each(function() {
                if (this.id !== "missing-total") {
                    missingTotal += parseFloat($(this).text().replace(/,/g, '')) || 0;
                }
            });
            $('#missing-total').text(numberFormat(missingTotal));
        }

        function numberFormat(number) {
            return Number(number).toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
    </script>
@endsection

@section('content')
    <!-- Hero and other content... -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Missing Count
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Missing Sku</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Missing Items</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <div class="form-group col-12 p-3">
                    <label for="sku_id">SKU #</label>
                    <select class="form-control select2" style="width: 100%;" id="sku_id" name="sku_id">
                        <option value="">Search SKU #</option>
                    </select>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-vcenter ">
                    <thead class="thead-light">
                        <th></th>
                        <th class="text-right">Warehouse</th>
                        <th class="text-right">Buffer</th>
                        <th class="text-right">Display</th>
                        <th class="text-right">Sold</th>
                        <th class="text-right">Total Items</th>
                        <thead>
                            <tr>
                                <td>System Count</td>
                                <td class="text-right" id="system-warehouse">0</td>
                                <td class="text-right" id="system-buffer">0</td>
                                <td class="text-right" id="system-display">0</td>
                                <td class="text-right" id="system-sold">0</td>
                                <td class="text-right" id="system-total">0</td>
                            </tr>
                            </tbody>
                        <tbody>
                            <tr>
                                <td>Physical Count</td>
                                <td><input type="number" class="form-control text-right physical-count"
                                        data-location="warehouse"></td>
                                <td><input type="number" class="form-control text-right physical-count"
                                        data-location="buffer">
                                </td>
                                <td><input type="number" class="form-control text-right physical-count"
                                        data-location="display"></td>
                                <td><input type="number" class="form-control text-right physical-count"
                                        data-location="sold">
                                </td>
                                <td><input type="text" class="form-control text-right" id="physical-total" readonly></td>
                            </tr>
                        </tbody>
                    <tbody>
                        <tr>
                            <td>Missing Count</td>
                            <td class="text-right" id="missing-warehouse">0</td>
                            <td class="text-right" id="missing-buffer">0</td>
                            <td class="text-right" id="missing-display">0</td>
                            <td class="text-right" id="missing-sold">0</td>
                            <td class="text-right" id="missing-total">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
