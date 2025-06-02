@extends('clienttest')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>our gallery</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Gallery</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs gallery wrapper Start -->
	<div class="prs_gallery_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Our Photo & Videos</h2>
					</div>
				</div>
				<div class="portfolio-area ptb-100">
					<div class="container">
						<div class="portfolio-filter clearfix text-center">
							<ul class="list-inline" id="filter">
								<li><a class="active" data-group="all">All</a>
								</li>
								<li><a data-group="business">Photos</a>
								</li>
								<li><a data-group="website"> Videos</a>
								</li>
							</ul>
						</div>
						<div class="row three-column">
							<div id="gridWrapper" class="clearfix">
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "website", "logo"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g1.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g1.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "photoshop"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g2.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g2.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "logo", "photoshop"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g3.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g3.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "logo", "website", "business"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g4.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g4.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "photoshop"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g5.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g5.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "logo"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g6.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g6.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "logo"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g7.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g7.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "logo"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g8.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g8.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 portfolio-wrapper III_column"
									data-groups='["all", "business", "website", "logo"]'>
									<div class="portfolio-thumb">
										<div class="gc_filter_cont_overlay_wrapper prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/gallery/g9.jpg')}}" class="zoom image img-responsive"
												alt="service_img" />
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/gallery/g9.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<!-- /.portfolio-thumb -->
								</div>
							</div>
							<!--/#gridWrapper-->
						</div>
						<!-- /.row -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_animate_btn1 prs_upcom_main_wrapper prs_gallery_btn_wrapper">
									<ul>
										<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
												data-text="view all"><span>View All</span></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /.container -->
				</div>
				<!--/.portfolio-area-->
			</div>
		</div>
	</div>
	<!-- prs gallery wrapper End -->
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
