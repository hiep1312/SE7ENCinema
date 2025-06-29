@extends('clienttest')
@section("content")
	<!-- prs upcomung Slider Start -->
	<div class="prs_upcom_slider_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Trang Danh Sách Phim</h2>
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
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="best">
						<div class="prs_upcom_slider_slides_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up1.jpg')}}" alt="movie_img" />
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
					<div role="tabpanel" class="tab-pane fade" id="hot">
						<div class="prs_upcom_slider_slides_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up8.jpg')}}" alt="movie_img" />
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
	<!-- prs upcomung Slider End -->
@endsection
