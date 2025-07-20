@extends('clienttest')
@section("content")
	<!-- prs navigation End -->
	<!-- prs Slider Start -->
    @livewire('client.banners.client-banner-index')
	<!-- prs Slider End -->
	<!-- prs upcomung Slider Start -->
	<div class="prs_upcom_slider_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Upcoming Movies</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_upcome_tabs_wrapper">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#best" aria-controls="best" role="tab"
									data-toggle="tab">Upcoming Movies</a>
							</li>
							<li role="presentation"><a href="#hot" aria-controls="hot" role="tab"
									data-toggle="tab">Relesed Movies</a>
							</li>
							<li role="presentation"><a href="#trand" aria-controls="trand" role="tab"
									data-toggle="tab">Best of library</a>
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
										</li>
									</ul>
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="trand">
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
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
	<!-- prs upcomung Slider End -->
	<!-- prs video section Start -->
	<div class="prs_video_section_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_video_sec_icon_wrapper">
						<ul>
							<li><a class="test-popup-link button" rel='external'
									href='https://www.youtube.com/embed/ryzOXAO0Ss0' title='title'><i
										class="flaticon-play-button"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs video section End -->
	<!-- prs web series Slider Start -->
	<div class="prs_upcom_slider_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>web series</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_upcome_tabs_wrapper">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#best1" aria-controls="best" role="tab"
									data-toggle="tab">Upcoming Movies</a>
							</li>
							<li role="presentation"><a href="#hot1" aria-controls="hot" role="tab"
									data-toggle="tab">Relesed Movies</a>
							</li>
							<li role="presentation"><a href="#trand1" aria-controls="trand" role="tab"
									data-toggle="tab">Best of library</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="best1">
						<div class="prs_upcom_slider_slides_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="hot1">
						<div class="prs_upcom_slider_slides_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="trand1">
						<div class="prs_upcom_slider_slides_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
								<div class="item">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
											<div class="prs_upcom_movie_box_wrapper prs_upcom_movie_video_box_wrapper">
												<div class="prs_upcom_movie_video_overlay"></div>
												<div class="prs_upcom_movie_img_box prs_webseri_movie_img_box">
													<div class="prs_webseri_video_img_sec_icon_wrapper">
														<ul>
															<li><a class="test-popup-link button" rel='external'
																	href='https://www.youtube.com/embed/ryzOXAO0Ss0'
																	title='title'><i
																		class="flaticon-play-button"></i></a>
															</li>
														</ul>
													</div>
													<div class="prs_upcom_video_img_cont_over">
														<ul>
															<li>Drama,Action</li>
															<li>Rating :&nbsp;&nbsp;<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o"></i>
																<i class="fa fa-star-o"></i>
															</li>
														</ul>
														<h2>terrorise of the year</h2>
														<p>Release on cinema : 27 june 2025</p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws2.jpg')}}" alt="movie_img" />
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
										<div
											class="col-lg-3 col-md-3 col-sm-6 col-xs-6 hidden-sm prs_upcom_slide_second">
											<div class="prs_upcom_movie_box_wrapper">
												<div class="prs_upcom_movie_img_box">
													<img src="{{ asset('client/assets/images/content/ws3.jpg')}}" alt="movie_img" />
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
										<div class="cc_featured_second_section">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws4.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws5.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws6.jpg')}}" alt="movie_img" />
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
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
												<div class="prs_upcom_movie_box_wrapper">
													<div class="prs_upcom_movie_img_box">
														<img src="{{ asset('client/assets/images/content/ws7.jpg')}}" alt="movie_img" />
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
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
	<!-- prs web series Slider End -->
	<!-- prs theater Slider Start -->
	<div class="prs_theater_main_slider_wrapper">
		<div class="prs_theater_img_overlay"></div>
		<div class="prs_theater_sec_heading_wrapper">
			<h2>TOP MOVIES IN THEATRES</h2>
		</div>
		<div class="wrap-album-slider">
			<ul class="album-slider">
				<li class="album-slider__item">
					<figure class="album">
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up2.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up3.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up4.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up5.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up6.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_upcom_movie_box_wrapper">
							<div class="prs_upcom_movie_img_box">
								<img src="{{ asset('client/assets/images/content/up7.jpg')}}" alt="movie_img" />
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
										<li><a href="movie_booking.html"><i class="flaticon-cart-of-ecommerce"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End album body -->
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
			</ul>
			<!-- End slider -->
		</div>
	</div>
	<!-- prs theater Slider End -->
	<!-- prs letest news Start	-->
	<div class="prs_ln_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>LAtest News</h2>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_ln_left_img_wrapper">
						<div class="prs_in_left_img_overlay"></div>
						<div class="prs_webseri_video_sec_icon_wrapper ">
							<ul>
								<li><a class="test-popup-link button" rel='external'
										href='https://www.youtube.com/embed/ryzOXAO0Ss0' title='title'><i
											class="flaticon-play-button"></i></a>
								</li>
							</ul>
						</div>
						<div class="prs_prs_webseri_video_sec_icon_cont_wrapper">
							<p>28 Feb 2025</p>
							<h2>The News of theater</h2>
							<ul>
								<li><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<a href="#">120 Like</a>
								</li>
								<li><i class="fa fa-comments-o"></i>&nbsp;&nbsp;<a href="#">52 Comments</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_ln_right_main_wrapper">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_ln_right_first_box_wrapper">
									<div class="prs_in_right_box_img_wrapper">
										<img src="{{ asset('client/assets/images/content/nl2.jpg')}}" alt="news_img" />
									</div>
									<div class="prs_in_right_box_img_cont_wrapper">
										<h2><a href="#">The News of theater</a></h2>
										<h3>28 feb 2025</h3>
										<p>Lorem ipsum dolor sit amet, consectuir adipisicing elit, sed do eiusmod
											tempor incididunt ut labore.</p>
										<ul>
											<li><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<a href="#">120 Like</a>
											</li>
											<li><i class="fa fa-comments-o"></i>&nbsp;&nbsp;<a href="#">52 comments</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_ln_right_first_box_wrapper2">
									<div class="prs_in_right_box_img_wrapper">
										<img src="{{ asset('client/assets/images/content/nl3.jpg')}}" alt="news_img" />
									</div>
									<div class="prs_in_right_box_img_cont_wrapper">
										<h2><a href="#">The News of theater</a></h2>
										<h3>28 feb 2025</h3>
										<p>Lorem ipsum dolor sit amet, consectuir adipisicing elit, sed do eiusmod
											tempor incididunt ut labore.</p>
										<ul>
											<li><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<a href="#">120 Like</a>
											</li>
											<li><i class="fa fa-comments-o"></i>&nbsp;&nbsp;<a href="#">52 comments</a>
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
	<!-- prs letest news End -->
	<!-- prs feature slider Start -->
	<div class="prs_feature_slider_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>FEATURED EVENTS</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_feature_slider_wrapper">
						<div class="owl-carousel owl-theme">
							<div class="item prs_feature_slider_item_wrapper">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/ft1.jpg')}}" alt="feature_img">
										<div class="prs_ft_btn_wrapper">
											<ul>
												<li><a href="#">Book Now</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="prs_feature_img_cont">
										<h2><a href="#">Music Event in india</a></h2>
										<div class="prs_ft_small_cont_left">
											<p>Mumbai & Pune</p>
										</div>
										<div class="prs_ft_small_cont_right"> <i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-o"></i>
											<i class="fa fa-star-o"></i>
										</div>
										<ul>
											<li>June 07 - july 08</li>
											<li>08:00-12:00 pm</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="item prs_feature_slider_item_wrapper">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/ft2.jpg')}}" alt="feature_img">
										<div class="prs_ft_btn_wrapper">
											<ul>
												<li><a href="#">Book Now</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="prs_feature_img_cont">
										<h2><a href="#">Music Event in india</a></h2>
										<div class="prs_ft_small_cont_left">
											<p>Mumbai & Pune</p>
										</div>
										<div class="prs_ft_small_cont_right"> <i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-o"></i>
											<i class="fa fa-star-o"></i>
										</div>
										<ul>
											<li>June 07 - july 08</li>
											<li>08:00-12:00 pm</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="item prs_feature_slider_item_wrapper">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/ft3.jpg')}}" alt="feature_img">
										<div class="prs_ft_btn_wrapper">
											<ul>
												<li><a href="#">Book Now</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="prs_feature_img_cont">
										<h2><a href="#">Music Event in india</a></h2>
										<div class="prs_ft_small_cont_left">
											<p>Mumbai & Pune</p>
										</div>
										<div class="prs_ft_small_cont_right"> <i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-o"></i>
											<i class="fa fa-star-o"></i>
										</div>
										<ul>
											<li>June 07 - july 08</li>
											<li>08:00-12:00 pm</li>
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
	<!-- prs feature slider End -->
	<!-- prs videos&photos slider Start -->
	<div class="prs_vp_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Video & photos</h2>
					</div>
				</div>
				<div class="prs_vp_main_slider_wrapper">
					<div class="prs_vp_left_slidebar_wrapper">
						<div class="wrap-album-slider">
							<ul class="prs_vp_left_slider">
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp1.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp1.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp2.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp2.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp3.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp3.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp4.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp4.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp5.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp5.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp6.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp6.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
							</ul>
						</div>
					</div>
					<div class="prs_vp_center_slidebar_wrapper">
						<div class="prs_vp_center_slider">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="prs_vp_center_slider_img_wrapper">
										<img src="{{ asset('client/assets/images/content/vp7.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_vp_center_slider_img_wrapper">
										<img src="{{ asset('client/assets/images/content/vp7.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_vp_center_slider_img_wrapper">
										<img src="{{ asset('client/assets/images/content/vp7.jpg')}}" alt="vp_img">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="prs_vp_right_slidebar_wrapper">
						<div class="wrap-album-slider">
							<ul class="prs_vp_right_slider">
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp6.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp6.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp5.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp5.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp4.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp4.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp3.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp3.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp2.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp2.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
								<li class="album-slider__item prs_vp_hover_overlay">
									<figure class="album">
										<div class="prs_vp_img_overlay">
											<img src="{{ asset('client/assets/images/content/vp1.jpg')}}" alt="vp_img"> <a
												href="{{ asset('client/assets/images/content/vp1.jpg')}}" class="venobox info"
												data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
													class="fa fa-search"></i></a>
										</div>
									</figure>
									<!-- End album -->
								</li>
							</ul>
						</div>
					</div>
					<div class="prs_vp_bottom_banner_wrapper">
						<img src="{{ asset('client/assets/images/content/vp8.jpg')}}" alt="banner">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs videos&photos slider End -->
	<!-- prs patner slider Start -->
	<div class="prs_patner_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Our Patner’s</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_pn_slider_wraper">
						<div class="owl-carousel owl-theme">
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p1.jpg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p2.jpg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p3.jpg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p4.jpg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p5.jpg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('client/assets/images/content/p6.jpg')}}" alt="patner_img">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs patner slider End -->
	<!-- prs Newsletter Wrapper Start -->
	<div class="prs_newsletter_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
					<div class="prs_newsletter_text">
						<h3>Get update sign up now !</h3>
					</div>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<div class="prs_newsletter_field">
						<input type="text" placeholder="Enter Your Email">
						<button type="submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs Newsletter Wrapper End -->

@endsection
