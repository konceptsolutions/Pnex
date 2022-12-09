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
                        <li class="breadcrumb-item active" aria-current="page">Autonets</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
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
                                                <th>Name</th>
                                                <th>Percentage</th>
                                                <th>BV</th>
                                                <th>Network Bv</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @foreach ($autonets as $autonet)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $autonet->name }}</td>
                                                <td>{{ $autonet->percentage }}</td>
                                                <td>{{ $autonet->bv }}</td>
                                                <td>{{ $autonet->network_bv }}</td>
                                                <td >
                                                    <div class="order-actions">
                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModal" href="" class=""><i class='bx bxs-edit'></i></a>
                                                    </div>
                                                </td>
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

        {{-- --------------------Modal for editing autonet----------------- --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Autonet Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 form-group">
                            <label for="" class="form-label">Percentage</label>
                            <input type="text" name="percentage" id="" placeholder="Enter Percentage" class="form-control mb-3">
                        </div>
                        <div class="col-12 form-group">
                            <label for="" class="form-label">BV</label>
                            <input type="text" name="percentage" id="" placeholder="Enter BV" class="form-control mb-3">
                        </div>
                        <div class="col-12 form-group">
                            <label for="" class="form-label">Network Bv</label>
                            <input type="text" name="percentage" id="" placeholder="Enter Network BV" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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
