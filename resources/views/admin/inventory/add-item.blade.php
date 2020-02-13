@extends('layouts.master')


@section('title', 'Add Item')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<style>
	.form-group {
margin-bottom: unset;
	}
	.form-group {
	margin-bottom: unset;
	}
</style>
@endsection
@section('content')
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Add a new item in Inventory</h3>
				<form action="{{ route('items.add') }}" method="post" enctype="multipart/form-data" onsubmit="this.submit.disabled = true;">
					@csrf
					<div class="form-group">
		            	<label for="item-name" class="col-form-label">Select Item: <span class="red">*</span></label>
{{--						<v-select placeholder="Select Item to Purchase" label="item_name" id="item-name"--}}
{{--                                  :options="{{ json_encode($items) }}"--}}
{{--                                  :reduce="item => item.item_id"--}}
{{--                                  @if(old('item_id')) :value="{{ old('item_id') }}" @endif--}}
{{--                                  @input="setVueSelected($event, 'purchaseItemId')" :filter-by="selectVendorFilterBy">--}}
{{--							<template slot="option" slot-scope="option">--}}
{{--								<span v-text="option.item_id + ' - ' + option.item_name + ' - ( ' + option.item_unit + ' )'"></span>--}}
{{--							</template>--}}
{{--						</v-select>--}}
{{--						<input type="hidden" id="purchaseItemId" name="item_id" value="{{ old('item_id') }}">--}}

		            	<select  name="item_id" class="form-control" required="" id="select-state" placeholder="Select Item...">
                            <option value="">--- Select Item ---</option>
							@foreach($items as $item)
                                    <option {{ (old("item_id") == $item->item_id ? "selected":"") }} value="{{$item->item_id}}">
                                        {{ '#' . $item->item_id . ' --- ' . $item->item_name }} &nbsp;&nbsp; {{ $item->item_unit }}
                                    </option>
							@endforeach
						</select >
		            </div>

		            <div class="form-group">
		            	<label for="quantity" class="col-form-label">Quantity : <span class="red">*</span></label>
		            	<input placeholder="Please enter quantity" type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required="">
		            </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Per Unit Price : <span class="red">*</span></label>
                        <input placeholder="Please per unit price for your item" type="text" onblur="cal()" class="form-control" id="unit_price" name="unit_payable" value="{{ old('unit_payable') }}" required="">
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Total Price</label>
                        <input type="text" readonly id="total_price" name="il_cost" class="form-control">
                    </div>
					<br>
					<div class="form-group">
						<label for="vendorsForProject" class="col-form-label">Which Project : <span class="red">*</span><span class="red">*</span> </label>
						<select name="project_id" id="vendorsForProject" class="form-control" required="">
							<option selected disabled>--- Select Project ---</option>
							@foreach($projects as $project)
								<option {{ (old("project_id") == $project->project_id ? "selected":"") }} value="{{$project->project_id}}">{{$project->project_name}}</option>
							@endforeach

						</select>
					</div>
					<div id="vendorSelection"></div>

					<div class="form-group">
		            	<label for="note" class="col-form-label">Note :</label>
		            	<textarea placeholder="Please enter some note" class="form-control" name="note" id="" cols="30" rows="5">{!!old('note')!!}</textarea>
		            </div>

					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary">Add</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>
<br>

@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            $('select').selectize({
                sortField: 'text'
            });
        });
    </script>
	<script>
		$(document).ready(function () {
			$('#vendorsForProject').change(function() {
				let project_id = this.value;

				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					url  : "{{route('items.project_vendors')}}",
					type : "POST",
					data : { project_id: project_id },
					success : function(response){
						$('#vendorSelection').html(response);
					},
					error : function(xhr, status){
						console.log(xhr);
					}
				});
			});
		});
	</script>

    <script>
        function cal() {
            const itemQuantity = document.getElementById('quantity').value;
            const unitPrice = document.getElementById('unit_price').value;
            const finalValue = itemQuantity * unitPrice;

            document.getElementById('total_price').value = finalValue;
            //console.log(finalValue);
        }
    </script>
@endsection
