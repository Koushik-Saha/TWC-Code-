@extends('layouts.master')

@section('title', $page_title)

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


@if(Auth::user()->isAdmin())
    <div class="row">
        <div class="col-md-4">

            <a class="btn waves-effect waves-light btn-warning" href="{{ route('project.add') }}">
                <i class="fas fa-plus"></i>Create Project
            </a>

        </div>


    </div>
@endif

<br>
<div class="row">
  <div class="col-md-12">
    <div class="card comp-card">
          <div class="card-body">
            <h3 style="text-align: center;">{{ $page_title }}</h3>
              <div class="table-responsive">
                <table class="table" id="allProjects">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Project Name</th>
                      <th scope="col">Location</th>
                      <th scope="col">Client Name</th>
                      <th scope="col">Est. Total</th>
                      <th scope="col">Rcv. Total</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $index => $project)
                        <tr>
                          <th scope="row">{{ $index+1 }}</th>
                          <td>
                              <a href="{{ route('project.show', ['id' => $project->project_id]) }}" title="See Project Details">
                                  {{ $project->project_name }}
                              </a>
                          </td>
                          <td>{{ $project->project_location }}</td>
                          <td>
                              <a href="{{ route('client.show', ['id' => $project->client->id]) }}" title="See Client Details">
                                  {{ $project->client->name }}
                              </a>
                          </td>
                          <td>{{ number_format($project->project_price, 2) }}</td>
                          <td>{{ number_format($project->payments()->where('payment_type', '=', 'credit')->where('payment_purpose', '=', 'project_money')->sum('payment_amount'), 2) }}</td>

                          <td>
                              @if(strtolower($project->project_status) === 'active')
                                  <span class="label label-primary">{{ $project->project_status }}</span>
                              @elseif(strtolower($project->project_status) === 'hold')
                                  <span class="label label-warning">{{ $project->project_status }}</span>
                              @elseif(strtolower($project->project_status) === 'canceled')
                                  <span class="label label-danger">{{ $project->project_status }}</span>
                              @else
                                  <span class="label label-success">{{ $project->project_status }}</span>
                              @endif
                          </td>
                          <td class="text-center">
                              <a class="btn btn-warning" href="{{ route('project.edit', ['id' => $project->project_id]) }}" title="Edit Project">
                                  <i class="feather icon-edit"></i>
                              </a>
                          </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <th>Total</th>
                            <th>{{ number_format($projects->sum('project_price'),2) }}</th>
                        @endif
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
      </div>
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
            $('#allProjects').DataTable( {
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
            $('#allProjects').DataTable( {
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
