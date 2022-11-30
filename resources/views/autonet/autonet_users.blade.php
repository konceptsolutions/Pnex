@extends("layouts.app")
@section("style")
	<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Autonets</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Autonet Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">
                <form action="getAutonetUsers" method="get">
                    <div class="card">
                        <div class="card-body">
                            <div class="row p-2">
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">Select Autonet</label>
                                    <select name="autonet_id" id="" class="single-select">
                                        @if ($oldInputs)
                                            <option value="{{ $oldInputs['autonet_id'] }}">{{ $oldInputs['autonet_name'] }}</option>
                                        @endif
                                        @foreach ($autonets as $autonet)
                                            <option value="{{ $autonet->id }}">{{ $autonet->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="form-label">Total Bv</label>
                                    <input type="text" name="" id="" class="form-control" value="{{ $autonetBv }}" disabled>
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
                        <div class="row p-2">
                            <div class="col-12">
                                <div class="table-responsive" >
                                    <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>User Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @foreach ($autonetUsers as $autonetUser)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $autonetUser->user->name }}</td>
                                            </tr>
                                            @php($i++)
                                            @endforeach
                                        </tbody>

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
