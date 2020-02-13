<br>
<div class="row">
  <div class="col-md-12">
    <div class="card comp-card">
      <div class="card-body">
        <h3 style="text-align: center;">
          Salary Report of
          <span class="text-success">{{ $month }}</span>
          for
          <span style="color: #f91484;">{{ $project->project_name }}</span>
        </h3>
        <div class="table-responsive">
          <table class="table" id="allProjects">
            <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Designation</th>
              <th scope="col">Salary</th>
              <th scope="col">Total Payable</th>
              <th scope="col">Paid</th>
              <th scope="col">Due</th>
              <th scope="col" style="max-width: 150px;">Note</th>

            </tr>
            </thead>
            <tbody>
            @php
              $totalPayable = 0;
              $totalPaid = 0;
              $totalDue = 0;
            @endphp

            @foreach($staffs as $index => $labour)
              <tr>
                <th scope="row">{{ $index + 1 }}</th>
                <td><a href="{{route('man_power.show', ['project' => $project->project_id, 'id' => $labour->id])}}">{{$labour->name}}</a></td>

                <td>
                  @if($labour->role->role_slug == 'machine')
                    <span class="label label-success">{{$labour->role->role_name}}</span>
                  @elseif($labour->role_slug == 'labour')
                    <span class="label label-warning">{{$labour->role->role_name}}</span>
                  @else
                    <span class="label label-primary">{{$labour->role->role_name}}</span>
                  @endif
                </td>
                <td>{{ number_format($labour->salary, 2) }}/-</td>

                @php
                  $payable = getMonthlyAttendancesOfUser($reqMonth, $labour)->sum('attendance_payable_amount');
                  $totalPayable += $payable;
                  $paid = getMonthlyPaymentsToStaff($reqMonth, $labour)->sum('payment_amount');
                  $totalPaid += $paid;
                  $totalDue += ($payable - $paid);
                @endphp

                <td>{{ number_format($payable, 2) }}/-</td>
                <td>{{ number_format($paid, 2) }}/-</td>
                <td>{{ number_format(($payable - $paid), 2) }}/-</td>
                <td style="max-width: 150px; white-space: normal; text-align: justify;">{!! $labour->note !!}</td>
              </tr>
            @endforeach

            <tr>
              <td class="sr-only d-print-none">{{ $staffs->count() + 1 }}</td>
              <td></td>
              <td></td>
              <td class="font-weight-bold">Total</td>
              <td class="font-weight-bold">{{ number_format($totalPayable, 2) }}/-</td>
              <td class="font-weight-bold">{{ number_format($totalPaid, 2) }}/-</td>
              <td class="font-weight-bold">{{ number_format($totalDue, 2) }}/-</td>
              <td></td>
            </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.title = '{{ $title }}';

    $('#allProjects').DataTable( {
      responsive: true,
      dom: 'Bfrtip',
      buttons: [
        'csv', 'pdf',
        {
          extend: 'print',
          //messageTop: 'This print was produced using the Print button for DataTables'
        }
      ]
    });
    $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
  </script>
</div>
