<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Rocker</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ url('dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        {{-- <li class="menu-label"></li> --}}

        {{-- -------------------------------------------Admin Routes----------------------------------------------------- --}}
        @if (Session::get('role_id') == 1)
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-group"></i>
                    </div>
                    <div class="menu-title">Manage Users</div>
                </a>
                <ul>
                    <li> <a href="{{ url('getUsers') }}" ><i
                                class="bx bx-right-arrow-alt"></i>Users List</a>
                    </li>
                </ul>
            </li>
        @else

            {{-- ---------------------------------------------------------Admin Routes End----------------------------------------- --}}

            {{-- ---------------------------------------------------------User Routes---------------------------------------------- --}}
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="lni lni-network"></i>
                    </div>
                    <div class="menu-title">Team</div>
                </a>
                <ul>
                    <li> <a href="{{ url('viewTeam') }}" ><i class="bx bx-right-arrow-alt"></i>View Team</a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
