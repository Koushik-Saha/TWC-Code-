@extends('layouts.master')

@section('title', 'Incomes')

@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

<style>
    div.dt-buttons {
        clear: both;
        margin-right:auto;
    }
</style>
@endsection
{{-- testing line.. deleteabla --}}
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="rbt-data-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center w-100">All Payment Information By Date to Date </h4>
                    </div>
                    <div class="row input-daterange col-12 ml-5" >
                        <div class="col-md-4">
                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                        </div>
                        <div class="col-md-4">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Search</button>
                            <button type="button" name="refresh" id="refresh" class="btn btn-default">ALL</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="order_table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Type</th>
                                        <th>Amounts</th>
                                        <th>Purpose</th>
                                        <th>Project</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Received By</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.input-daterange').datepicker({
                todayBtn:'linked',
                format:'yyyy-mm-dd',
                autoclose:true
            });

            load_data();

            function load_data(from_date = '', to_date = '')
            {
                $('#order_table').DataTable({
                    processing: true,
                    serverSide: true,
                    "lengthMenu": [ [10, 50, 100, 200, -1], [10, 50, 100, 200, "All"] ],
                    language : {
                        sLengthMenu: "Show _MENU_"
                    },
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'pdf', 'print'
                    ],
                    ajax: {
                        url:'{{ route("bank.income-report") }}',
                        data:{from_date:from_date, to_date:to_date}
                    },
                    columns: [
                        {
                            data: 'payment_date',
                            name:'payment_date'
                        },
                        {
                            data:'payment_by',
                            name:'payment_by'
                        },
                        {
                            data:'payment_type',
                            name:'payment_type'
                        },
                        {
                            data:'payment_amount',
                            name:'payment_amount'
                        },
                        {
                            data:'payment_purpose',
                            name:'payment_purpose'
                        },
                        {
                            data:'project_name',
                            name:'project_name'
                        },
                        {
                            data:'fromUser_name',
                            name:'fromUser_name'
                        },
                        {
                            data:'toUser_name',
                            name:'toUser_name'
                        },
                        {
                            data:'activityUser_name',
                            name:'activityUser_name'
                        }
                    ]
                });
                $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
            }

            $('#filter').click(function(){
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if(from_date != '' &&  to_date != '')
                {
                    $('#order_table').DataTable().destroy();
                    load_data(from_date, to_date);
                }
                else
                {
                    alert('Both Date is required');
                }
            });

            $('#refresh').click(function(){
                $('#from_date').val('');
                $('#to_date').val('');
                $('#order_table').DataTable().destroy();
                load_data();
            });

        });
    </script>

@endsection


