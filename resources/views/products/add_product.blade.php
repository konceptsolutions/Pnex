
	@extends("layouts.app")

	@section("style")
	<link href="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />
    <link href="assets/plugins/fancy-file-uploader/fancy_fileupload.css" rel="stylesheet" />

	@endsection

		@section("wrapper")
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Products</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Add Product</li>
							</ol>
						</nav>
					</div>

				</div>
				<!--end breadcrumb-->
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
				<div class="card">
				  <div class="card-body p-4">
					  <h5 class="card-title">Add New Product</h5>
					  <hr/>
                       <div class="form-body mt-4">
                        <form action="{{ url('addProduct') }}" method="post" >
                            @csrf
                            <div class="row">
                               <div class="col-lg-8 ">
                               <div class="border border-3 p-4 rounded borderRmv">
                                <div class="mb-3">
                                    <label for="inputProductTitle" class="form-label">Product Title</label>
                                    <input type="text" name="title" required class="form-control" id="title" placeholder="Enter product title">
                                  </div>
                                <div class="mb-3">
                                    <label for="inputProductDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                  </div>


                                  <div class="mb-3">
                                    <label for="inputProductDescription" class="form-label">Product Images</label>
                                    <input id="image-uploadify" type="file" accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf" multiple>
                                  </div>
                                </div>
                               </div>
                               <div class="col-lg-4">
                                <div class="border border-3 p-4 rounded borderRmv">
                                  <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="inputPrice" class="form-label">Price</label>
                                        <input type="number" required min="1" class="form-control" id="price" name="price" placeholder="00.00">
                                      </div>
                                    <div class="col-md-12">
                                        <label for="inputPrice" class="form-label">BV</label>
                                        <input type="number" required min="1" class="form-control" id="bv" name="bv" placeholder="00.00">
                                      </div>
                                      <div class="col-12">
                                        <label for="inputProductType"  class="form-label">Product Type</label>
                                        <select class="form-select" required id="product_type_id" name="product_type_id">
                                            <option value="1">Plot</option>
                                          </select>
                                      </div>
                                      <div class="col-12">
                                        <label for="inputVendor" class="form-label">Sub Type</label>
                                        <select class="form-select" required id="product_sub_type_id" name="product_sub_type_id">
                                            <option value="1">Commercial</option>
                                            <option value="2">Residential</option>
                                          </select>
                                      </div>

                                      <div class="col-12">
                                          <div class="d-grid">
                                             <button type="submit" class="btn btn-primary">Save Product</button>
                                          </div>
                                      </div>
                                  </div>
                               </div>
                              </div>
                           </div><!--end row-->
                        </form>
					</div>
				  </div>
			  </div>


			</div>
		</div>
		<!--end page wrapper -->
		@endsection

	@section("script")
	<script src="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
    <script src="assets/plugins/fancy-file-uploader/jquery.ui.widget.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.fileupload.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.iframe-transport.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js"></script>
	<script>
		$(document).ready(function () {
			// $('#image-uploadify').imageuploadify();

            $('#image-uploadify').FancyFileUpload({
			params: {
				action: 'fileuploader'
			},
			maxfilesize: 1000000
		});
            checkWidth();
		})

        function checkWidth()
        {
            /*If browser resized, check width again */
            if ($(window).width() < 576) {
                $('html').addClass('mobile');
            $('.borderRmv').removeClass('border border-3 rounded p-4');
            }
        }

        $(window).resize(function() {
            /*If browser resized, check width again */
            if ($(window).width() < 576) {
            $('.borderRmv').removeClass('border border-3 rounded p-4');
            }else {
                $('.borderRmv').addClass('border border-3 rounded p-4');
            }
        });
	</script>
	@endsection
