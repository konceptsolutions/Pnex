	@extends("layouts.app")

	@section("style")
	<link href="assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

	@endsection

		@section("wrapper")
            <div class="page-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">Manage Users</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Users List</li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                    <!--end breadcrumb-->
                    <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                                      <div class="ms-auto">
                                        <button type="button" id="reset" class="btn btn-outline-danger px-3 radius-30"><i class="fadeIn animated bx bx-eraser"></i> Reset</button>
                                        <button type="button" class="btn btn-outline-info px-3 radius-30"><i class="bx bxs-plus-square"></i> Add New</button>
                                    </div>
                                    </div>

                                    <!-- SmartWizard html -->
                                    <div id="smartwizard">
                                        <ul class="nav">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#all-Users">	<strong>All Users</strong>
                                                    <br></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#level-1">	<strong>Level 1</strong>
                                                    <br></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#level-2">	<strong>Level 2</strong>
                                                    <br></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#level-3">	<strong>Level 3</strong>
                                                    <br></a>
                                            </li>

                                        </ul>
                                        <div class="tab-content">
                                            <div id="all-Users" class="tab-pane" role="tabpanel" aria-labelledby="level-1">
                                                <h5>Users List</h5>
                                                <div class="table-responsive" >
                                                    <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr #</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Phone No</th>
                                                                <th>Reference No</th>
                                                                <th>Refered By</th>
                                                                <th>User Type</th>
                                                                <th>Status</th>
                                                                <th colspan="2" class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php($i = 1)
                                                            @foreach ($users as $user)
                                                                <tr>
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ $user->name }}</td>
                                                                    <td>{{ $user->email }}</td>
                                                                    <td>{{ $user->phone_no }}</td>
                                                                    <td>{{ $user->reference_no }}</td>
                                                                    <td>{{ $user->referedBy->name }}</td>
                                                                    <td>@if ($user->is_free_user == 0)
                                                                        <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>Paid</div>
                                                                        @else
                                                                        <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>Free</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>@if ($user->is_banned == 0)
                                                                        <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span></td>
                                                                        @else
                                                                        <span class="badge bg-gradient-bloody text-white shadow-sm w-100">Banned</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex order-actions">
                                                                            <a href="javascript:;" class=""><i class='bx bxs-edit'></i></a>
                                                                            <a href="javascript:;" class="ms-3"><i class='bx bxs-trash text-danger'></i></a>

                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropdown ms-auto">
                                                                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded font-22 text-option"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a onclick="viewUsers(1,{{ $user->id }})" class=" next-btn dropdown-item" href="javascript:;">View Users</a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                            <div id="level-1" class="tab-pane" role="tabpanel" aria-labelledby="level-1">
                                                <h5>Level 1 Content</h5>
                                                <div class="table-responsive">
                                                    <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr #</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Phone No</th>
                                                                <th>Reference No</th>
                                                                <th>Refered By</th>
                                                                <th>User Type</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                                <th>View</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="10" style="text-align: center"> No Record Found</td>
                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                            <div id="level-2" class="tab-pane" role="tabpanel" aria-labelledby="level-2">
                                                <h5>Level 2 Content</h5>
                                                <div>
                                                    <div class="table-responsive" >
                                                        <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr #</th>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Phone No</th>
                                                                    <th>Reference No</th>
                                                                    <th>Refered By</th>
                                                                    <th>User Type</th>
                                                                    <th>Status</th>
                                                                    <th>Actions</th>
                                                                    <th>View</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="10" style="text-align: center"> No Record Found</td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="level-3" class="tab-pane" role="tabpanel" aria-labelledby="level-3">
                                                <h5>Level 3 Content</h5>
                                                <div class="table-responsive" >
                                                    <table id="example"  class="table table-striped table-bordered" style="width:99%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr #</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Phone No</th>
                                                                <th>Reference No</th>
                                                                <th>Refered By</th>
                                                                <th>User Type</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                                <th>View</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="10" style="text-align: center"> No Record Found</td>
                                                            </tr>
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
                    <!--end row-->
                </div>
            </div>
		@endsection



	@section("script")
	<script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script>
		$(document).ready(function () {
			// Smart Wizard
			$('#smartwizard').smartWizard({
				selected: 0,
				theme: 'dots',
				transition: {
					animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
				},
				toolbarSettings: {
					toolbarPosition: 'none',
				}
			});
			// External Button Events
			$("#reset").on("click", function () {
				// Reset wizard
				$('#smartwizard').smartWizard("reset");
				return true;
			});
			$("#next").on("click", function () {
				// Navigate next
				$('#smartwizard').smartWizard("next");
				return true;
			});
		});
	</script>
    <script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	</script>

    <script>

        function viewUsers(level, user_id){
            $.ajax({
            type:'GET',
            url:"{{ url('getUsersAjax') }}",
            data:{
                level:level,
                user_id:user_id
            },
            success:function(data){
                $('#level-'+level).html(data);
                if (level < 4) {
                    $('#smartwizard').smartWizard("next");
                    return true;
                }
            }
            });
        }
    </script>

	@endsection
