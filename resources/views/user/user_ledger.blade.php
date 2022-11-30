@extends("layouts.app")
@section("style")
	<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                @if (Session::has('status'))
                <div class="alert alert-{{ Session::get('status') }} border-0 bg-{{ Session::get('status') }} alert-dismissible fade show" id="dismiss">
                    <div class="text-white">{{ Session::get('message')}}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                        {{ Session::forget('status') }}
                        {{ Session::forget('message') }}
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="getUserLedger" method="get">
                    <div class="card">
                        <div class="card-body">
                            <div class="row p-2">
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">Select User</label>
                                    <select name="user_id" id="" class="single-select">
                                        @if ($oldInputs)
                                            <option value="{{ $oldInputs['user_id'] }}">{{ $oldInputs['user_name'] }}</option>
                                        @endif
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">Select Week</label>
                                    <select name="week_id" id="" class="single-select">
                                        @if ($oldInputs2)
                                            <option value="{{ $oldInputs2['week_id'] }}">{{ $oldInputs2['week_no'] }}</option>
                                        @endif
                                        <option value="">Select Week</option>
                                        @foreach ($weeks as $week)
                                            <option value="{{ $week->id }}">{{ $week->week_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">From Date</label>
                                    <input type="date" name="from" id="" class="form-control">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">To Date</label>
                                    <input type="date" name="to" id="" class="form-control">
                                </div>
                            </div>
                            <div class="row px-2">
                                <div class="col-md-2 form-group">
                                    <label for="" class="form-label">&nbsp;</label>
                                    <input type="submit" value="Search" class="btn  btn-primary form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" >
                                    <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Week No</th>
                                                <th>Date</th>
                                                <th>BV</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @php($total_bv = 0)
                                            @php($balance = 0)
                                            @foreach ($userLedger as $userLedger)
                                            @php($balance+=$userLedger->balance)
                                            @php($total_bv+=$userLedger->bv)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $userLedger->week->week_no }}</td>
                                                <td>{{ $userLedger->created_at }}</td>
                                                <td>{{ $userLedger->bv }}</td>
                                                <td>{{ $userLedger->debit }}</td>
                                                <td>{{ $userLedger->credit }}</td>
                                                <td>{{ $userLedger->balance }}</td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ $total_bv }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
    <script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function () {
			$('#example').DataTable();
    });

    </script>
@endsection
