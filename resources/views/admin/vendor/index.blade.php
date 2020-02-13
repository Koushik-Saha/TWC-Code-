@extends('layouts.master')

@section('title', 'Vendors List')

@section('style')
    <style>
        .all {
            white-space: normal !important;
            word-break: break-all;
        }
        /*table {*/
        /*    !*table-layout: fixed;*!*/
        /*    over-flow: break-word;*/
        /*}*/
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <a class="btn waves-effect waves-light btn-warning" href="{{ route('vendor.add') }}">
                <i class="fas fa-plus"></i>Create Vendor
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <div class="rbt-data-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center w-100">Vendor's</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="FranchiseTable" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name/Title</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Projects</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vendors as $index => $vendor)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td >
                                            <a href="{{ route('vendor.show', $vendor->id) }}" title="See Vendor Details">
                                                {{ $vendor->name }}
                                            </a>
                                        </td>
                                        <td class="all">
                                            {{ $vendor->address }}
                                        </td>
                                        <td>
                                            {{ mobileNumber($vendor->mobile) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('project.show', $vendor->project_id) }}" title="See Project Details">
                                                {{ $vendor->project_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('vendor.edit', ['id' => $vendor->id]) }}"
                                               class="btn btn-sm btn-outline-success">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>

            {{--	  <vendors-data-table--}}
            {{--			  :projects="{{ json_encode($projects) }}">--}}
            {{--	  </vendors-data-table>--}}
        </div>
    </div>
@endsection


@section('script')
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    @if(Auth::user()->isAdmin())
        <script>
            $('#FranchiseTable').DataTable( {
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'pdf', 'print'
                ]
            } );
            $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
        </script>
    @else
        <script>
            $('#FranchiseTable').DataTable( {
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ]
            } );
            $('.buttons-print').addClass('btn btn-success mr-1');
        </script>
    @endif

@endsection

