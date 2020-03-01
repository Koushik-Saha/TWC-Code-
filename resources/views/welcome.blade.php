@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card comp-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-b-25">Total Projects</h6>
                            <h3 class="f-w-700 text-c-blue">
                                {{ countProjects() }}
                            </h3>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building bg-c-blue"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card comp-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-b-25">Active Projects</h6>
                            <h3 class="f-w-700 text-c-green">
                                {{ countProjects('active') }}
                            </h3>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullseye bg-c-green"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card comp-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-b-25">Completed Projects</h6>
                            <h3 class="f-w-700 text-c-green">
                                {{ countProjects('completed') }}
                            </h3>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check bg-c-yellow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        @php

            use App\Models\Attendance;use App\Models\User;use Carbon\Carbon;use Illuminate\Support\Facades\DB;

            $currentUser = \Illuminate\Support\Facades\Auth::user()->id;

            if(Auth::user()->isManager()) {
                $income = Auth::user()->managerPayments()->whereIn('payment_purpose', ['employee_transfer', 'employee_refund'])->sum('payment_amount');
                $expense = Auth::user()->expenses()->where('payment_type', '=', 'debit')->sum('payment_amount');
                $itemExpense = DB::table('purchase_items')->where('user_id','=',$currentUser)->sum('amount');
            }
            else if(Auth::user()->isAccountant()) {
                $income = Auth::user()->payments()->whereIn('payment_purpose', ['vendor_refund', 'loan_received', 'project_money', 'office_withdraw', 'employee_refund'])
                    ->whereDate('created_at', '=', Carbon::now())
                    ->sum('payment_amount');
                $expense = Auth::user()->expenses()->where('payment_type', '=', 'debit')
                    ->whereDate('created_at', '=', Carbon::now())
                    ->sum('payment_amount');
            }
            else {
                $income = \App\Models\Payment::wherePaymentType('credit')
                    ->whereIn('payment_purpose', ['project_money', 'loan_received'])->sum('payment_amount');
                $expense = \App\Models\Payment::wherePaymentType('debit')->sum('payment_amount');
            }

            $roles = \App\Models\Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
                ->pluck('role_id')
                ->toArray();

            $mans = \App\Models\User::whereIn('role_id', $roles)
                ->get()->count();

            $activeTodayIds = Attendance::whereDate('attendance_date', '=', Carbon::now()->toDateString())
                ->pluck('attendance_user_id')
                ->toArray();

            $activeToday = User::whereIn('id', $activeTodayIds)->count();

        @endphp

        @if(Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isAccountant())
            <div class="col-md-4">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-25">Total Received {{ Auth::user()->isAccountant() ? 'Today' : '' }}</h6>
                                <h3 class="f-w-700 text-c-green">{{ number_format($income,2) }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-c-green"></i>
                                {{--                                <i class="fas fa-hand-paper bg-c-green"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-25">Total Expenses {{ Auth::user()->isAccountant() ? 'Today' : '' }}</h6>
                                <h3 class="f-w-700 text-c-red">{{ number_format($expense,2) }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle bg-c-red"></i>
                                {{--                                <i class="fab fa-creative-commons-nc bg-c-red"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::user()->isManager())
                <div class="col-md-4">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-25">Remaining
                                        Balance {{ Auth::user()->isAccountant() ? 'Today' : '' }}</h6>
                                    <h3 class="f-w-700 text-c-purple">{{ number_format($income - $expense,2) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-minus-circle bg-c-purple"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-25">Total Expenses In
                                        Item {{ Auth::user()->isAccountant() ? 'Today' : '' }}</h6>
                                    <h3 class="f-w-700 text-c-red">{{ number_format($itemExpense,2) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle bg-c-red"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

    </div>

    <div class="row justify-content-center">
        @if(Auth::user()->isAdmin())
            <div class="col-md-4">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-25">Active Project's Staffs</h6>
                                <h3 class="f-w-700 text-c-lite-green">{{ $mans }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user bg-c-lite-green"></i>
                                {{--                                <i class="fas fa-hand-paper bg-c-lite-green"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-25">Today's Present Staffs</h6>
                                <h3 class="f-w-700 text-c-lite-green">{{ $activeToday }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-circle bg-c-lite-green"></i>
                                {{--                                <i class="fas fa-hand-paper bg-c-lite-green"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection


@section('script')
    <script language="JavaScript"
            src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
@endsection
