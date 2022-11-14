
@if ($level == 2)
    <div class="chip chip-lg bg-info text-white px-4">{{ $levelsHeadingArray['name1'] }}</div>
    @elseif ($level == 3)
    <div class="chip px-4">{{ $levelsHeadingArray['name1'] }}</div> <i class="lni lni-angle-double-right"></i>
    <div class="chip chip-lg bg-info text-white px-4">{{ $levelsHeadingArray['name2'] }}</div>
@endif
<div class="table-responsive" id="responsive{{ $level }}">
    <input type="hidden" id="referedBy{{ $level }}" value="{{ $referedBy }}">
    <table id="table{{ $level }}"  class="table table-striped table-bordered" style="width:100%">
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
                 <th>View</th>
             </tr>
        </thead>
        <tbody>
            @if (count($users) == 0)
                <tr>
                    <td colspan="10" style="text-align: center"> No Record Found</td>
                </tr>
            @endif
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

                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded font-22 text-option"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a onclick="viewUsers({{ $level+1 }},{{ $user->id }})" class="dropdown-item" href="javascript:;">View Team</a>
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
        <span class="pagination-span" id="span{{ $level }}">
            @php( $users->onEachSide(1)->links())
            {!! $users->appends(\Request::except('page'))->render() !!}
        </span>
    </div>
</div>

</div>
