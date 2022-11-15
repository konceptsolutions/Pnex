@extends("layouts.app")
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="container">
                <div class="main-body">
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
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="p-4 border radius-15">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="assets/images/avatars/avatar-101.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                            <div class="mt-3">
                                                <h4>{{ ucFirst($user->name) }}</h4>
                                                <b><p class="text-secondary mb-1">Reference ID</p></b>
                                                <p class="text-muted font-size-sm">{{ $user->reference_no }}</p>
                                            </div>
                                        </div>
                                        <hr class="my-4" />
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h4 class="mb-0"><i class="fadeIn animated bx bx-envelope-open"></i></h4>
                                                <span class="text-secondary">{{ $user->email }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h4 class="mb-0"><i class="fadeIn animated bx bx-mobile"></i></h4>
                                                <span class="text-secondary">{{ $user->phone_no }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="border p-4 radius-15">

                                        <div class="form-body">
                                            <form class="row g-3 needs-validation" novalidate method="POST" action="{{ url('updateUser') }}">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <div class="col-sm-12">
                                                    <label for="name" class="form-label">Full Name *</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}"
                                                        placeholder="Bilal Khan" required>
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Please Enter Your Name</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="email" class="form-label">Email Address
                                                        </label>
                                                    <input type="email" readonly class="form-control" id="email" name="email" value="{{$user->email}}"
                                                        placeholder="example@user.com" required>
                                                </div>
                                                <div class="col-12">
                                                    <label for="password" class="form-label">Reference ID</label>
                                                    <div class="input-group" id="show_hide_password">
                                                        <input type="text" readonly class="form-control border-end-0"
                                                            id="password" name="password" placeholder="Enter Password"
                                                            required minlength="5" value="{{ $user->reference_no }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="phone_no" class="form-label">Phone No
                                                        *</label>
                                                    <input type="number" class="form-control" id="phone_no"
                                                        name="phone_no" placeholder="Phone No" required minlength="11" value="{{ $user->phone_no }}">
                                                    <div class="valid-feedback">Looks good!</div>
                                                    <div class="invalid-feedback">Phone No must be 11 characters long</div>
                                                </div>


                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary"><i
                                                                class='bx bx-user'></i>Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endsection



