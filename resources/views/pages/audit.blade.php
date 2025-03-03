@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Missing Count
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Examples</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Blank</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
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
                <table class="table table-borderless table-vcenter table-striped">
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Warehouse</td>
                            <td>Buffer</td>
                            <td>Selling</td>
                            <td>Display</td>
                            <td>Sold</td>
                            <td>Total Items</td>
                        </tr>
                        <tr>
                            <td> System Count </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> Physical Count </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td> Missing Count </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                            <td> = </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Your Block -->
    </div>
    <!-- END Page Content -->
@endsection
