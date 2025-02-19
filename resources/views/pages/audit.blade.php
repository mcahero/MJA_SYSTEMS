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
                <h3 class="block-title">Missing Count</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-vcenter">
                    <tbody>
                        <tr>
                            <td>Warehouse</td>
                            <td>Buffer</td>
                            <td>Selling</td>
                            <td>Unresolved</td>
                            <td>Total Items</td>
                        </tr>
                        <tr>
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
