	@extends("layouts.app")

	@section("style")
	<link href="assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

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
                            <div class="card border-top border-0 border-4 border-info">
                                <div class="card-body">
                                    <div class=" p-2 ">
                                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                                            <div class="ms-auto">
                                                <button type="button" onclick="reset()" class="btn btn-outline-danger px-3 "><i class="fadeIn animated bx bx-eraser"></i> Reset</button>
                                                <button type="button" class="btn btn-outline-info px-3 " data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bxs-plus-square"></i> Add New</button>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Create User Account</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="border p-4 rounded">
                                                            <div class="form-body">
                                                                <form class="row g-3 needs-validation" novalidate method="POST" >
                                                                    @csrf
                                                                    <div class="col-sm-12">
                                                                        <label for="name" class="form-label">Full Name *</label>
                                                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                                                            placeholder="Bilal Khan" required>
                                                                        <div class="valid-feedback">Looks good!</div>
                                                                        <div class="invalid-feedback">Please Enter Your Name</div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <label for="email" class="form-label">Email Address
                                                                            *</label>
                                                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                                                                            placeholder="example@user.com" required>
                                                                        <div class="valid-feedback">Looks good!</div>
                                                                        <div class="invalid-feedback">Please Enter a Valid Email Address</div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label for="password" class="form-label">Password *</label>
                                                                        <div class="input-group" id="show_hide_password">
                                                                            <input type="password" class="form-control border-end-0"
                                                                                id="password" name="password" placeholder="Enter Password"
                                                                                required minlength="5">
                                                                            <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                                                    class='bx bx-hide'></i></a>
                                                                            <div class="valid-feedback">Looks good!</div>
                                                                            <div class="invalid-feedback">Password must be 5 characters long
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label for="reference_no" class="form-label">Reference ID
                                                                            *</label>
                                                                        <input type="text" class="form-control" name="reference_no"
                                                                            id="reference_no" placeholder="Reference ID" required value="{{ old('reference_no') }}">
                                                                        <div class="valid-feedback">Looks good!</div>
                                                                        <div class="invalid-feedback">Please Enter Reference ID</div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <label for="phone_no" class="form-label">Phone No
                                                                            *</label>
                                                                        <input type="number" class="form-control" id="phone_no"
                                                                            name="phone_no" placeholder="Phone No" required minlength="11" value="{{ old('phone_no') }}">
                                                                        <div class="valid-feedback">Looks good!</div>
                                                                        <div class="invalid-feedback">Phone No must be 11 characters long</div>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
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
                                                <li class="nav-item" id="autoScroll">
                                                    <a class="nav-link" href="#level-3">	<strong>Level 3</strong>
                                                        <br></a>
                                                </li>

                                            </ul>
                                            <div class="tab-content" >
                                                <div id="all-Users" class="tab-pane" role="tabpanel" aria-labelledby="all-Users">
                                                    <h5>Users List</h5>
                                                    <div class="table-responsive" id="responsive0" >
                                                    <input type="hidden" id="referedBy0" value="">

                                                        <table id="table0"  class="table table-striped table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr class="sortLinks">
                                                                   <th> @sortablelink('id','Sr#')</th>
                                                                   <th>@sortablelink('name','Name')</th>
                                                                    <th>Email</th>
                                                                    <th>Phone No</th>
                                                                    <th>Reference No</th>
                                                                    <th>Refered By</th>
                                                                    <th>User Type</th>
                                                                    <th>@sortablelink('is_banned','Status')</th>
                                                                    <th  class="text-center">Actions</th>
                                                                    <th>View</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php($srno = ($users->perPage() * ($users->currentPage() - 1)) )
                                                                @foreach ($users as $user)
                                                                @php($srno++)
                                                                    <tr>
                                                                        <td>{{ $srno }}</td>
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
                                                                                    <li><a onclick="viewUsers(1,{{ $user->id }})" class=" next-btn dropdown-item" href="javascript:;">View Team</a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div>Showing {{($users->currentpage()-1)*$users->perpage()+1}} to {{$users->currentpage()*$users->perpage()}}
                                                                    of  {{$users->total()}} entries
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <span class="pagination-span" id="span0">
                                                                    @php( $users->onEachSide(1)->links())
                                                                    {!! $users->appends(\Request::except('page'))->render() !!}
                                                                </span>
                                                            </div>
                                                        </div>
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
                    </div>
                    <!--end row-->

                </div>
            </div>
		@endsection



	@section("script")
	<script src="assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
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
            reset();
		});
	</script>

        <script>

        $(document).ready(function() {
			var table = $('#usersTable').DataTable({
            lengthChange: false,
                paging: false,

                "info": false,
                sort: false,
		  });

          table.buttons().container()
                .appendTo('#usersTable_wrapper .col-md-6:eq(0)');
        });

	</script>
	<script>
        function reset(){
            search('page=1',0);
            $('#smartwizard').smartWizard("reset");
            return true;
        }

        function next(){
            $('#smartwizard').smartWizard("next");
            return true;
        }

        function viewFullNetwork(user_id){
            reset();
            viewUsers(1,user_id);
            // next();
        }

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
                    next();
                }
                var table = $('#table'+level).DataTable({
                    lengthChange: false,
                    paging: false,

                    sort: false,
                    "info": false,
                });
                table.buttons().container()
                    .appendTo('#table'+level+'_wrapper .col-md-6:eq(0)');
            }
            });
        }

        $(document).on('click', '.pagination-span a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('?')[1];
            let spanId = $(this).closest('span').attr('id');
            let level = spanId.charAt(spanId.length - 1);
            search(page,level);
            });
            $(document).on('click', '.sortLinks a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('?')[1];
            let tableId = $(this).parent().closest('table').attr('id');
            let level = tableId.charAt(tableId.length - 1);
            search(page,level);
            });
            function search(page,level){
                let referedBy = $("#referedBy"+level).val();
            $.ajax({
                url: "{{route('getPaginatedUsersAjax')}}?"+page,
                method: 'GET',
                data: {
                    level:level,
                    referedBy:referedBy
                },
                success: function(result) {
                    $("#responsive"+level).html(result);
                    var table = $('#table1'+level).DataTable({
                        lengthChange: false,
                        paging: false,

                        sort: false,
                        "info": false,
                    });
                    table.buttons().container()
                        .appendTo('#table1'+level+'_wrapper .col-md-6:eq(0)');

                        $("html, body").animate({
                            scrollTop: $("#autoScroll").offset().top
                        }, 0)
                }
            });
        }
    </script>

	@endsection
