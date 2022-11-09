
@if ($level == 1)
    <div class="chip chip-lg bg-info text-white px-4">{{ $levelsHeadingArray['name1'] }}</div>
    @elseif ($level == 2)
    <div class="chip px-4">{{ $levelsHeadingArray['name1'] }}</div> <i class="lni lni-angle-double-right"></i>
    <div class="chip chip-lg bg-info text-white px-4">{{ $levelsHeadingArray['name2'] }}</div>
    @elseif ($level == 3)
    <div class="chip px-4">{{ $levelsHeadingArray['name1'] }}</div> <i class="lni lni-angle-double-right"></i>
    <div class="chip px-4">{{ $levelsHeadingArray['name2'] }}</div> <i class="lni lni-angle-double-right"></i>
    <div class="chip chip-lg bg-info text-white px-4">{{ $levelsHeadingArray['name3'] }}</div>
@endif
<div class="table-responsive">
    <table id="example{{ $level }}"  class="table table-striped table-bordered" style="width:99%">
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
            @if (count($users) == 0)
                <tr>
                    <td colspan="10" style="text-align: center"> No Record Found</td>
                </tr>
            @endif
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
                                @if ($level < 3)
                                    <li><a onclick="viewUsers({{ $level+1 }},{{ $user->id }})" class="dropdown-item" href="javascript:;">View Users</a>
                                @endif
                                </li>
                                <li><a onclick="viewFullNetwork({{ $user->id }})" class=" dropdown-item" href="javascript:;" >View Full Network</a>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
