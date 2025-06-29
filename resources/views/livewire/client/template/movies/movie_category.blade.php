@extends('clienttest')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>Danh Sách Phim</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Danh Sách Phim</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs mc slider wrapper Start -->
	<div class="prs_mc_slider_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Comming soon</h2>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_upcome_tabs_wrapper">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#best" aria-controls="best" role="tab"
									data-toggle="tab">Phim Sắp Chiếu</a>
							</li>
							<li role="presentation"><a href="#hot" aria-controls="hot" role="tab"
									data-toggle="tab">Phim Đang Chiếu</a>
							</li>	
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs mc slider wrapper End -->
	<!-- prs mc category slidebar Start -->
	<div class="prs_mc_category_sidebar_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 hidden-sm hidden-xs">
					<div class="prs_mcc_left_side_wrapper">
						<div class="prs_mcc_left_searchbar_wrapper">
							<input type="text" placeholder="Search Movie">
							<button><i class="flaticon-tool"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<div class="prs_mcc_right_side_wrapper">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_mcc_right_side_heading_wrapper">
									<h2>Danh Sách Phim</h2>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="tab-content">
									<div id="grid" class="tab-pane fade in active">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first">
												<div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/movie_category/up1.jpg')}}"
															alt="movie_img" />
														<div class="prs_upcom_movie_img_overlay"></div>
														<div class="prs_upcom_movie_img_btn_wrapper">
															<ul>
																<li><a href="#">View Trailer</a>
																</li>
																<li><a href="#">View Details</a>
																</li>
															</ul>
														</div>
													</div>
													<div class="prs_upcom_movie_content_box">
														<div class="prs_upcom_movie_content_box_inner">
															<h2><a href="#">Busting Car</a></h2>
															<p>Drama , Acation</p> <i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="prs_upcom_movie_content_box_inner_icon">
															<ul>
																<li><a href="movie_booking.html"><i
																			class="flaticon-cart-of-ecommerce"></i></a>
																</li>
															</ul>
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
				</div>
				
			</div>
		</div>
	</div>
	<!-- prs Newsletter Wrapper End -->
@endsection
