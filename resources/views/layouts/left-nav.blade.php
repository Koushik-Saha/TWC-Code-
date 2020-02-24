<nav class="pcoded-navbar">
    <div class="nav-list">
        <div class="pcoded-inner-navbar main-menu">
            <div class="pcoded-navigation-label">Navigation</div>

            {{--            Dashboard--}}
            <ul class="pcoded-item pcoded-left-item" id="bsoftSideNav">
                <li>
                    <a href="{{ route('index') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>

                {{--                Projects--}}
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-layers"></i>
                        </span>
                        <span class="pcoded-mtext">Projects</span>
                        <span class="pcoded-badge label label-danger">{{ countProjects('active') }}</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if(Auth::user()->isAdmin())
                            <li class="">
                                <a href="{{ route('project.add') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Add Project</span>
                                </a>
                            </li>
                        @endif
                        <li class="">
                            <a href="{{ route('project.all', ['status' => 'active']) }}"
                               class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Active Projects</span>
                                <span class="pcoded-badge label label-danger">{{ countProjects('active') }}</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('project.all', ['status' => 'hold']) }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Hold Projects</span>
                                <span class="pcoded-badge label label-danger">{{ countProjects('hold') }}</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('project.all', ['status' => 'completed']) }}"
                               class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Completed Projects</span>
                                <span class="pcoded-badge label label-danger">{{ countProjects('completed') }}</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('project.all', ['status' => 'canceled']) }}"
                               class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Canceled Projects</span>
                                <span class="pcoded-badge label label-danger">{{ countProjects('canceled') }}</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('project.all') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">All Projects</span>
                                <span class="pcoded-badge label label-danger">{{ countProjects() }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--                Clients--}}
                @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                    <li class="">
                        <a href="{{ route('client.all') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-user-plus"></i>
                        </span>
                            <span class="pcoded-mtext">Clients</span>
                        </a>
                    </li>
                @endif


                {{--                Vendor--}}
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-inbox"></i>
                        </span>
                        <span class="pcoded-mtext">Vendors</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('vendor.add') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Add Vendor</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('vendor.all') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">All Vendors</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                {{--                Administrators--}}
                @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                    <li class="pcoded-hasmenu">
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-user-check"></i>
                        </span>
                            <span class="pcoded-mtext">Administrators</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li class="">
                                <a href="{{ route('administrators.add') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Add New</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('administrators.all') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">All Admin / Manager List</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{--                Working Shifts--}}
                @can('manage-shifts')
                    <li>
                        <a href="{{ route('shift.all') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-clock"></i>
                        </span>
                            <span class="pcoded-mtext">Working Shifts</span>
                        </a>
                    </li>
                @endcan


                {{--                Man Power--}}
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Man Power</span>
                        {{--<span class="pcoded-badge label label-warning">NEW</span>--}}
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="">
                            <a href="{{ route('man_power.add') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Add New Staff</span>
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin())
                            <li class="">
                                <a href="{{ route('man_power.designation') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Add Designation</span>
                                </a>
                            </li>
                        @endif
                        <li class="">
                            <a href="{{ route('man_power.all') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Staff List</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('man_power.staff.attendance.all') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Staff Attendance</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('man_power.monthly.all') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Staff Monthly <br>Salary Report</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('man_power.attendance_report') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Staff Attendance Report</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--                Category--}}
                @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                    <li class="pcoded-hasmenu">
                        <a href="#" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-clipboard"></i></span>
                            <span class="pcoded-mtext">Category</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li class="">
                                <a href="{{ route('mother-category.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Mother Category</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('category.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Category</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('sub-category.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Sub Category</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('manufacturer.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Manufacturer</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{--                Inventory--}}
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-codepen"></i></span>
                        <span class="pcoded-mtext">Inventory</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('items.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Create New Unit</span>
                                </a>
                            </li>
                        @endif

                        <li class="">
                            <a href="{{ route('items.request-item') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Request New Item</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="{{ route('items.add') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Purchase Item</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('items.index') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Item List / Transferred Items</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('items.approved') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Approve Item List</span>
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('items.purchase') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Purchase Requisition <br> History</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('items.all-lists') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">All Project Item</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-codepen"></i></span>
                        <span class="pcoded-mtext">New Inventory</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('create-inventory') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Create New Item</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('inventory.all-lists') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">All Item Lists</span>
                                </a>
                            </li>
                        @endif
                        <li class="">
                            <a href="{{ route('request-inventory') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Request Item</span>
                            </a>
                        </li>
                        @php
                            use App\Models\RequestItem;
                            $itemList = RequestItem::select(
                            DB::raw('
                                     cartId,
                                    MAX(created_at) AS created_at
                                    '))->groupBy('cartId')->get();
                        @endphp
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('request-list') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Requested Item List</span>
                                    <span class="pcoded-badge label label-danger">{{ $itemList->count() }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="">
                            <a href="{{ route('purchase-package-list') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Purchase Item</span>
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <li class="">
                                <a href="{{ route('purchase-history') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Purchased History</span>
                                </a>
                            </li>
{{--                            <li class="">--}}
{{--                                <a href="{{ route('inventory.requestByProjects') }}" class="waves-effect waves-dark">--}}
{{--                                    <span class="pcoded-mtext">Requested Item List<br> By Projects</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        @endif
                    </ul>
                </li>

                {{--                Accounting--}}
                @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                    <li class="pcoded-hasmenu">
                        <a href="#" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-credit-card"></i></span>
                            <span class="pcoded-mtext">Accounting</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li class="">
                                <a href="{{ route('bank.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Cash / Banks</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('loan.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Loans</span>
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="">
                                    <a href="{{ route('bank.income') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Income Report</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('bank.expense') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Expense Report</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('bank.transfer_to_employee') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Transfer To Employee</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('bank.refund_to_employee') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Refund From Employee</span>
                                    </a>
                                </li>
                            @endif
                            <li class="">
                                <a href="{{ route('bank.income-report') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Date by Date <br> Cash Transaction<br> History</span>
                                </a>
                            </li>
                            {{-- <li class="">
                                <a href="{{route('bank.index')}}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">All Transection</span>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                @endif

                {{--                Profile--}}
                <li class="">
                    <a href="{{ route('profile') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-user"></i>
                        </span>
                        <span class="pcoded-mtext">Profile</span>
                    </a>
                </li>

                {{--                Settings--}}
                @if(Auth::user()->isAdmin())
                    <li class="pcoded-hasmenu">
                        <a href="#" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                            <span class="pcoded-mtext">Setting</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li class="">
                                <a href="{{ route('options') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Preferences</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('settings-layout') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Logo Settings</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
