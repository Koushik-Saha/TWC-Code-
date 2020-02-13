<div class="form-group">
    <label for="recipient-name" class="col-form-label">Select Vendor : <span class="red">*</span><span class="red">*</span></label>
    <select name="vendor_id" id="recipient-name" class="form-control" required="">
        <option selected disabled>--- Select Vendor ---</option>
        @foreach($vendors as $vendor)
            <option {{ (old("vendor_id") == $vendor->id ? "selected":"") }} value="{{$vendor->id}}" >{{$vendor->name}}</option>
        @endforeach
    </select>
</div>
<br>
