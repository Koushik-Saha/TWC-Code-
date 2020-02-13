@extends('layouts.master')

@section('title', 'Bank Accounts')
{{-- testing line.. deleteabla --}}
@section('content')
    <!-- Button trigger modal -->
    <style>
        .required{
            color:red;
        }
        label {
            font-weight: 600;
        }
    </style>

    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addBankAccountModal">
        Add New Account
    </button>
    <button type="button" class="btn btn-danger mb-3" data-toggle="modal" data-target="#transferToEmployeeModal">
        Transfer To Employee
    </button>
    <button type="button" class="btn btn-warning mb-3" data-toggle="modal" data-target="#refundFromEmployeeModal">
        Refund From Employee
    </button>
    <button type="button" class="btn btn-danger mb-3" data-toggle="modal" data-target="#withdrawFromBankModal">
        Withdraw From Bank
    </button>
    <button type="button" class="btn btn-warning mb-3" data-toggle="modal" data-target="#depositToBankModal">
        Deposit To Bank
    </button>
    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#fromClientModal">
        From Client
    </button>
    <br><br>

    <!-- Add Bank Account Modal -->
    <div class="modal fade" id="addBankAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('bank.add')}}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Bank Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="accountFor" class="col-form-label">Account For: <span class="required">*</span></label>
                            <select name="accountFor" id="accountFor" class="custom-select" required>
                                <option selected disabled>--- Select Type ---</option>
                                <option value="office">Office</option>
                                <option value="manager">Manager</option>
                                <option value="accountant">Accountant</option>
                            </select>
                        </div>
                        <div id="usersForAccount">

                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Account Name: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Account Number: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="number" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Bank Name: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="bank" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Bank Branch: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="branch" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Initial Balance: <span class="required">*</span></label>
                            <input type="number" class="form-control" id="recipient-name" name="balance" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Transfer To Employee Modal -->
    <div class="modal fade" id="transferToEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bank.transfer.to_employee') }}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Transfer Money To Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="managerForProjects" class="col-form-label">For Project <span class="required">*</span></label>
                            <select name="project_id" id="managerForProjects" class="form-control" required="">
                                <option selected disabled>--- Select Project ---</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="managerProjectsResult"></div>
                        <div class="form-group">
                            <label for="paymentType" class="col-form-label">Payment Type <span class="required">*</span></label>
                            <select name="type" id="paymentType" class="form-control" required>
                                <option selected disabled>--- Select Payment Type</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                            <small class="form-text text-muted">If payment type is CASH or CHECK, no Bank Account balance will be updated!</small>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="bank_id" class="col-form-label">Office Account:<span class="required">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-control">
                                @foreach($adminBanks as $bank)
                                    <option value="{{$bank->bank_id}}">{{$bank->bank_name}} --- {{ $bank->bank_account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Date: <span class="required">*</span></label>
                            <input type="date" class="form-control" id="recipient-name" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Note: </label>
                            <textarea type="text" class="form-control" id="recipient-name" name="note" rows="5" style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Transfer</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Refund From Modal -->
    <div class="modal fade" id="refundFromEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bank.transfer.to_office') }}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Refund Money From Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @csrf
                        <div class="form-group">
                            <label for="managerForProjects1" class="col-form-label">For Project <span class="required">*</span></label>
                            <select name="project_id" id="managerForProjects1" class="form-control" required="">
                                <option selected disabled>--- Select Project ---</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="managerProjectsResult1"></div>
                        <div class="form-group">
                            <label for="paymentType" class="col-form-label">Payment Type <span class="required">*</span></label>
                            <select name="type" id="paymentType" class="form-control" required>
                                <option selected disabled>--- Select Payment Type</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                            <small class="form-text text-muted">If payment type is CASH or CHECK, no Bank Account balance will be updated!</small>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="bank_id" class="col-form-label">Office Account:<span class="required">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-control">
                                @foreach($adminBanks as $bank)
                                    <option value="{{$bank->bank_id}}">{{$bank->bank_name}} --- {{ $bank->bank_account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Date: <span class="required">*</span></label>
                            <input type="date" class="form-control" id="recipient-name" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Note: </label>
                            <textarea type="text" class="form-control" id="recipient-name" name="note" rows="5" style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Refund</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Withdraw From Bank Modal -->
    <div class="modal fade" id="withdrawFromBankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bank.withdraw') }}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Withdraw From Bank</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="bank_id" class="col-form-label">From Account:<span class="required">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-control">
                                @foreach($adminBanks as $bank)
                                    <option value="{{$bank->bank_id}}">{{$bank->bank_name}} --- {{ $bank->bank_account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Date: <span class="required">*</span></label>
                            <input type="date" class="form-control" id="recipient-name" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Note: </label>
                            <textarea type="text" class="form-control" id="recipient-name" name="note" rows="5" style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Withdraw</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Deposit To Bank Modal -->
    <div class="modal fade" id="depositToBankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bank.deposit') }}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Deposit To Bank</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="bank_id" class="col-form-label">To Account:<span class="required">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-control">
                                @foreach($adminBanks as $bank)
                                    <option value="{{$bank->bank_id}}">{{$bank->bank_name}} --- {{ $bank->bank_account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Date: <span class="required">*</span></label>
                            <input type="date" class="form-control" id="recipient-name" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Note: </label>
                            <textarea type="text" class="form-control" id="recipient-name" name="note" rows="5" style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Deposit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--From Client Modal -->
    <div class="modal fade" id="fromClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bank.recharge') }}" method="post" onsubmit="this.submit.disabled = true;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Receive From Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Select Customer <span class="required">*</span></label>
                            <select name="user_id" id="clientForProjects" class="form-control" required="">
                                <option selected disabled>--- Select Client ---</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="clientProjectsResult"></div>

                        <div class="form-group">
                            <label for="paymentType1" class="col-form-label">Payment Type <span class="required">*</span></label>
                            <select name="type" id="paymentType1" class="form-control" required>
                                <option selected disabled>--- Select Payment Type</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                            <small class="form-text text-muted">If payment type is CASH or CHECK, no Bank Account balance will be updated!</small>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="bank_id1" class="col-form-label">To Account <span class="required">*</span></label>
                            <select name="bank_id" id="bank_id1" class="form-control">
                                <option selected disabled>--- Select Bank ---</option>
                                @foreach($adminBanks as $bank)
                                    <option value="{{$bank->bank_id}}">
                                        {{$bank->bank_name}} --- {{ $bank->bank_account_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: <span class="required">*</span></label>
                            <input type="number" class="form-control" id="recipient-name" name="amount" required="">
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Date: <span class="required">*</span></label>
                            <input type="date" class="form-control" id="recipient-name" name="date" required="">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">note:</label>
                            <textarea class="form-control" id="message-text" name="note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Recharge</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card comp-card card-green prod-p-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-b-25">Cash Balance in Office</h6>
                            <h3 class="f-w-700 text-c-green text-white">৳ {{ number_format($cash, 2) }}</h3>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-alt text-c-red text-danger"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('bank.show', ['id' => 'cash']) }}" class="btn btn-link text-white">Cash Transaction History</a>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card comp-card">
                <div class="card-body">
                    <h4 class="text-center">All Bank Account List</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col" style="vertical-align: middle; text-align: center;">#</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Bank Details</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Account User</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banks as $index => $bank)
                                <tr>
                                    <td style="vertical-align: middle; text-align: center;">{{ $index+1 }}</td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a href="{{ route('bank.show', ['id' => $bank->bank_id]) }}" class="font-weight-bold" style="font-size: 14px;">
                                            {{$bank->bank_account_name}}
                                        </a>
                                        <br>
                                        {{ $bank->bank_account_no }}
                                        <br>
                                        {{ $bank->bank_name }}
                                        <br>
                                        {{ $bank->bank_branch }}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{ ($bank->user) ? $bank->user->name : 'Office' }}
                                        <br>
                                        <small>{{ ($bank->user) ? $bank->user->role->role_name : '' }}</small>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{ number_format($bank->bank_balance, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
    <script>
        $(document).ready(function() {
            $('#accountFor').change(function() {
                let type = this.value;
                if(type !== 'office') {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url  : "{{route('bank.users.search')}}",
                        type : "POST",
                        data : {type: type},
                        success : function(response){
                            $('#usersForAccount').html(response);
                        },
                        error : function(xhr, status){

                        }
                    });
                }
                else {
                    $('#usersForAccount').html(null);
                }
            });

            $('#clientForProjects').change(function() {
                let client_id = this.value;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{route('bank.client.project.search')}}",
                    type : "POST",
                    data : {client_id: client_id},
                    success : function(response){
                        $('#clientProjectsResult').html(response);
                    },
                    error : function(xhr, status){

                    }
                });
            });

            $('#managerForProjects').change(function() {
                let project_id = this.value;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{route('bank.project_manager.search')}}",
                    type : "POST",
                    data : { project_id: project_id },
                    success : function(response){
                        $('#managerProjectsResult').html(response);
                    },
                    error : function(xhr, status){
                        console.log(xhr);
                    }
                });
            });

            $('#managerForProjects1').change(function() {
                let project_id = this.value;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{route('bank.project_manager.search')}}",
                    type : "POST",
                    data : { project_id: project_id },
                    success : function(response){
                        $('#managerProjectsResult1').html(response);
                    },
                    error : function(xhr, status){
                        console.log(xhr);
                    }
                });
            });

            $('select#paymentType').change(function () {
                let type = this.value;
                if(type.toLowerCase() !== 'cash') {
                    $('select#bank_id').parent().css('display', 'block');
                }
                else {
                    $('select#bank_id').parent().css('display', 'none');
                }
            });

            $('select#paymentType1').change(function () {
                let type = this.value;
                if(type.toLowerCase() === 'bank' || type.toLowerCase() === 'check') {
                    $('select#bank_id1').parent().css('display', 'block');
                }
                else {
                    $('select#bank_id1').parent().css('display', 'none');
                }
            });
        });
    </script>
@endsection
