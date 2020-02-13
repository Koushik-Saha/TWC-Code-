@extends('layouts.master')

@section('title', 'Create New Item')

@section('style')
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
				<h3 style="text-align: center;">Edit Item</h3>
				<form action="{{route('items.edit', ['id' => $item->item_id])}}" method="post" enctype="multipart/form-data">
					@csrf
					@method('PATCH')
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="name" value="{{ old('name', $item->item_name) }}" required="">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Item Unit: <span class="red">*</span></label>
                        <input type="text" class="form-control" id="recipient-name" name="unit" value="{{ old('unit', $item->item_unit) }}" required="">
                    </div>
                    <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Per Unit Price <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="unit_price" value="{{ old('unit_price', $item->item_price) }}" >
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Final Price <span class="red">*</span></label>
                        <input type="text" class="form-control" id="finalPrice" onblur="finalCalc()" name="item_price_final"
                               value="{{  $item->item_price_final }}" >
                        <small class="red">Please add final price after updating manager final price</small>
                    </div>
                    @foreach($items as $item)
                        <input type="hidden" class="form-control" id="ilQuantity" name="names" value="{{ $item->il_quantity}}" required="">
                    @endforeach
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Approve Total Cost <span class="red">*</span></label>
                        <input type="text" class="form-control" id="finalPriceUpdated" name="final_price_up"
                               value="" >
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Reusable: <span class="red">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline1" name="reusable" value="1" class="custom-control-input" {{ ($item->item_reusable) ? 'checked' : '' }} required>
                            <label class="custom-control-label" for="customRadioInline1">Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline2" name="reusable" value="0" class="custom-control-input" {{ (!$item->item_reusable) ? 'checked' : '' }} required>
                            <label class="custom-control-label" for="customRadioInline2">NO</label>
                        </div>
                    </div>
					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary">Update</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
    <script>
        function finalCalc() {
            let quantity = document.getElementById('ilQuantity').value;
            let finalPrice = document.getElementById('finalPrice').value;
            let finalResult = quantity * finalPrice;
            document.getElementById('finalPriceUpdated').value = finalResult;

        }
    </script>
</div>

@endsection


@section('script')
<script>
    $(document).on('click', '#deleteBtn', function(el) {
        el.preventDefault();
        var postId = $(this).data("id");

        swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this imaginary file!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Deleting...", {
              icon: "success",
            });
            window.location.href = window.location.href = "delete/" + postId;
          }
        });

    });
</script>
@endsection
