@extends('layouts.master')

@section('title', 'All Administrators')

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
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-body">
				<h5 style="text-align: center;">Administrators List</h5>
				<div class="table-responsive">
				  <table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Name</th>
					      <th scope="col">Mobile</th>
					      <th scope="col">Email</th>
					      <th scope="col">Role</th>
					      <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					  	@foreach($administrators as $index => $administrator)
					    <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td><a href="{{ route('administrators.show', $administrator->id) }}">{{ $administrator->name }}</a></td>
                            <td>{{ mobileNumber($administrator->mobile) }}</td>
                            <td>{{ $administrator->email }}</td>
                            <td>{{ $administrator->role->role_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('administrators.edit', ['id' => $administrator->id]) }}" class="btn btn-primary" title="Edit Administrator">
                                    <i class="feather icon-edit"></i>
                                </a>
                            </td>
					    </tr>
						@endforeach
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
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
