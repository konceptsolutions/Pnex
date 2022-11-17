@extends("layouts.app")

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
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-xl-2">
                                @if (Session::get('role_id') == 1)
                                <a href="addProduct" class="btn btn-outline-info mb-3 mb-lg-0"><i class='bx bxs-plus-square'></i>New Product</a>
                                @endif
                            </div>
                            <div class="col-lg-9 col-xl-10">
                                <form class="float-lg-end">
                                    <div class="row row-cols-lg-auto g-2">
                                        <div class="col-12">
                                            <div class="position-relative">
                                                <input type="text" class="form-control ps-5" placeholder="Search Product..."> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" class="btn btn-white">Sort By</button>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-white dropdown-toggle dropdown-toggle-nocaret px-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-chevron-down'></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    </ul>
                                                </div>
                                                </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" class="btn btn-white">Collection Type</button>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-white dropdown-toggle dropdown-toggle-nocaret px-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bxs-category'></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    </ul>
                                                </div>
                                                </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-white">Price Range</button>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-white dropdown-toggle dropdown-toggle-nocaret px-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-slider'></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="btnGroupDrop1">
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                                    </ul>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 product-grid">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card">
                        <img src="assets/images/products/11.png" class="card-img-top" alt="...">

                        <div class="card-body">
                            <h6 class="card-title cursor-pointer">{{ $product->name }}</h6>
                            <div class="clearfix mb-2">
                                <p class="mb-0 float-start"> <strong>Price</strong></p>
                                <p class="mb-0 float-end fw-bold"><span>{{ number_format($product->price) }}</span></p>
                            </div>
                            <div class="clearfix mb-2">
                                <p class="mb-0 float-start"> <strong>BV</strong></p>
                                <p class="mb-0 float-end fw-bold"><span>{{ number_format($product->bv) }}</span></p>
                            </div>
                            @if (Session::get('user_id') != 1)
                                <div class="clearfix mb-2 text-center">
                                    <a href="buyProduct?product_id={{ $product->id }}" class="buyProduct">
                                        <button type="button" class="btn btn-sm btn-outline-info px-4 ">Buy Now</button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!--end row-->


    </div>
</div>
<!--end page wrapper -->
@endsection

@section("script")

    <script>
        $('.buyProduct').on('click',function(){
            if (!confirm('Are you sure you want to buy this product')) {
                event.preventDefault();
            }
        });
    </script>
@endsection
