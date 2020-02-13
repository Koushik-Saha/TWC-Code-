@extends('layouts.master')

@section('title', 'Invoice')

@section('style')



@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <button class="btn btn-success" id="printBtn">
                <i class="feather icon-printer"></i>
            </button>
        </div>
        <br>
        <br>
        <br>
        <div class="col-12">
            <div class="card" id="invoice">
                <div class="card-header" style="padding: 15px 15px 0 15px; border: 2px solid #5d5d5d; border-radius: 5px 5px 0 0; font-weight: 600;">
                    <h1 class="w-100 text-center" style="">
                        {{ getOption('company_name') }}
                    </h1>
                    <div class="text-center">
                        @if(getOption('company_address'))
                            {{ getOption('company_address') }}
                        @endif

                        @if(getOption('company_phone'))
                            <br>
                            {{ getOption('company_phone') }}
                        @endif
                    </div>
                    <hr>
                    <div>
                        <div class="row" style="padding: 20px;">
                            <div class="col-4" style="border: 1px solid #b7b7b7; padding: 8px; margin: 3px 0;">
                                Invoice No:
                                @if($invoice->invoice_id < 10)
                                    #00{{ $invoice->invoice_id }}
                                @elseif($invoice->invoice_id < 100)
                                    #0{{ $invoice->invoice_id }}
                                @endif
                            </div>
                            <div class="col-2"></div>
                            <div class="col-6" style="border: 1px solid #b7b7b7; padding: 8px; margin: 3px 0;">
                                Date:
                            </div>
                            <div class="col-12" style="border: 1px solid #b7b7b7; padding: 8px; margin: 3px 0;">
                                Name:
                            </div>
                            <div class="col-12" style="border: 1px solid #b7b7b7; padding: 8px; margin: 3px 0;">
                                Address:
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="border: 2px solid #a9a9a9; border-top: 0; border-radius: 0 0 5px 5px;">
                    <div class="row" style="border: 1px solid #b7b7b7; margin: 0;">
                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Item:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ $invoice->itemLog->item->item_name }}
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Quantity:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ $invoice->itemLog->il_quantity }}
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Rate:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ number_format(($invoice->itemLog->il_cost / $invoice->itemLog->il_quantity), 2) }} TK
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Total:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ number_format($invoice->itemLog->il_cost, 2) }} TK
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            From:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ $invoice->invoice_address_from }}
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            To:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">
                            {{ $invoice->invoice_address_to }}
                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Truck No:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">

                        </div>

                        <div class="col-3" style="font-weight: 600; padding: 20px; border-right: 1px solid #b7b7b7;">
                            Truck Rent:
                        </div>
                        <div class="col-9" style="font-weight: 600; padding: 20px;">

                        </div>
                    </div>
                    <br><br><br><br>
                    <div class="row">
                        <div class="col-6" style="font-weight: 600; font-size: 15px; text-decoration: overline;">
                            Driver Signature
                        </div>
                        <div class="col-6" style="text-align: right; font-weight: 600; font-size: 15px; text-decoration: overline;">
                            Behalf of {{ getOption('company_name') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('js/printThis/printThis.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#printBtn').click(function (e) {
                $('#invoice').printThis({
                    importCSS: true,
                    //loadCSS: "path/to/new/CSS/file",
                    /*header: "{{ getOption('company_name') . ' - Invoice' }}",
                    footer: null*/
                });
            });
        });
    </script>

@endsection
