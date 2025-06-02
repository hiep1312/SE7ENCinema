@extends('clienttest')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>about us</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; About us</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs about presenter wrapper Start -->
	<div class="prs_about_presenter_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>About Presenter</h2>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_about_pre_left_sec_wrapper">
						<div class="prs_ap_left_img_wrapper">
							<img src="{{ asset('client/assets/images/content/about/ap1.jpg')}}" alt="about_img">
						</div>
						<div class="prs_ap_left_img_cont_wrapper">
							<h2>about our journey</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor nt ut
								labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exerciton ullamco
								laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in it in
								voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaeat
								cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est labonm.
								Sed ut perspiciatis unde omnis
								<br>
								<br>iste natus error sit voluptatem accusantium doloremque laudantium, totam reme ipsa
								quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt emo enim
								ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia tur magni
								dolores eos qui ratione voluptatem.
							</p>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_about_pre_right_sec_wrapper">
						<div class="prs_about_pre_right_slider_sec_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<img src="{{ asset('client/assets/images/content/about/ap2.jpg')}}" alt="about_img">
								</div>
								<div class="item">
									<img src="{{ asset('client/assets/images/content/about/ap2.jpg')}}" alt="about_img">
								</div>
								<div class="item">
									<img src="{{ asset('client/assets/images/content/about/ap2.jpg')}}" alt="about_img">
								</div>
							</div>
						</div>
						<div class="prs_about_pre_right_slider_sec_cont_wrapper">
							<h2>Who we are</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor nt ut
								labore et dolore magna aliqua. Ut enim ad minim veniams nostrud exerciton ullamco
								laboris nisi ut aliquip ex ea commodo consat.
								<br>
								<br>Duis aute irure dolor in it in voluptate it esscillumiore eu fugiat nulla pariatur.
								Excepteur sint occaeat idatat non proident, sunt in culpa qui officia deserunt mollit
								anim id estnm. ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
								dolor- emque laudantium totam remquae.
							</p>
							<ul>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp; Movie Trailer Making</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp; Vfs Work shop</li>
							</ul>
							<ul class="prs_ap_ul_last">
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp; Movie Trailer Making</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp; Vfs Work shop</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs about presenter wrapper End -->
	<!-- prs videos&photos slider Start -->
	<div class="prs_vp_main_section_wrapper prs_about_vp_main_section_wrapper">
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
				</div>
			</div>
		</div>
	</div>
	<!-- prs videos&photos slider End -->
	<!-- prs about team section Start -->
	<div class="prs_about_team_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Director And Team</h2>
					</div>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
					<div class="prs_about_team_first_mem_wrapper">
						<div class="prs_about_first_mem_img_wrapper">
							<img src="{{ asset('client/assets/images/content/about/tm1.jpg')}}" alt="team_img">
							<div class="prs_at_social_main_wrapper">
								<ul>
									<li><a href="#"><i class="fa fa-facebook"></i></a>
									</li>
									<li>
										<a href="#">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
												<path
													d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
											</svg>
										</a>
									</li>
									<li><a href="#"><i class="fa fa-linkedin"></i></a>
									</li>
									<li><a href="#"><i class="fa fa-youtube-play"></i></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="prs_about_first_mem_img_cont_wrapper">
							<h2><a href="#">Johan Doe</a></h2>
							<p>Director</p>
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2_inner">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm2.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">akshay h.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2_inner">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm3.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">ajay S.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2_inner">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm4.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">Sandy S.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm5.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">Farhan S.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm6.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">Vijay p.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_ap_colum_full">
							<div class="prs_about_team_first_mem_wrapper prs_about_team_first_mem_wrapper2">
								<div class="prs_about_first_mem_img_wrapper">
									<img src="{{ asset('client/assets/images/content/about/tm7.jpg')}}" alt="team_img">
									<div class="prs_at_social_main_wrapper prs_at_social_inner_main_wrapper">
										<ul>
											<li><a href="#"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="#">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
														<path
															d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
													</svg>
												</a>
											</li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a>
											</li>
											<li><a href="#"><i class="fa fa-youtube-play"></i></a>
											</li>
										</ul>
									</div>
								</div>
								<div
									class="prs_about_first_mem_img_cont_wrapper prs_about_first_mem_img_cont_inner_wrapper">
									<h2><a href="#">ajay S.</a></h2>
									<p>Director</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_animate_btn1 prs_upcom_main_wrapper prs_ap_btn_wrapper">
						<ul>
							<li><a href="#" class="button button--tamaya prs_upcom_main_btn"
									data-text="view more"><span>View More</span></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs about team section End -->
	<!-- counter section start-->
	<div class="counter_section">
		<section class="counter-section section-padding">
			<div class="container">
				<div class="row">
					<div class="trucking_counter">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 prs_count_colum1">
							<div class="con"> <i class="fa fa-user-o"></i>
							</div>
							<div class="count-description"> <span class="timer">2540</span>
								<h5 class="con1">Sucess Movie</h5>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 prs_count_colum2">
							<div class="con"> <i class="fa fa-users"></i>
							</div>
							<div class="count-description"> <span class="timer">7325</span>
								<h5 class="con2">Happy Clents</h5>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 prs_count_colum3">
							<div class="con"> <i class="fa fa-snapchat"></i>
							</div>
							<div class="count-description"> <span class="timer">1924</span>
								<h5 class="con3">Awards</h5>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 prs_count_colum4">
							<div class="con"> <i class="fa  fa-thumbs-o-up"></i>
							</div>
							<div class="count-description"> <span class="timer">4275</span>
								<h5 class="con4">Sucess Movie</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<!-- counter section end-->
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
