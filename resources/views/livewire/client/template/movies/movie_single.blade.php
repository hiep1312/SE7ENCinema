@extends('components.layouts.client')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>The Race Return</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Movie Single</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs ms trailer wrapper Start -->
	<div class="prs_ms_trailer_vid_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Movie Trailer</h2>
					</div>
				</div>
				<div class="col-lg-12 col-lg-12 col-lg-12 col-lg-12">
					<div class="prs_ms_trailer_vid_wrapper">
						<div class="prs_ms_trailer_vid_img_overlay"></div>
						<div class="prs_ms_trailer_vid_icon_wrapper">
							<ul>
								<li><a class="test-popup-link button" rel='external'
										href='https://www.youtube.com/embed/ryzOXAO0Ss0' title='title'><i
											class="flaticon-play-button"></i></a>
								</li>
							</ul>
							<h2>View Trailer</h2>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-lg-12 col-lg-12 col-lg-12">
					<div class="prs_ms_trailer_slider_main_wrapper">
						<div class="prs_ms_trailer_slider_left_wrapper">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="prs_ms_trailer_slider_left_img_wrapper">
										<img src="{{ asset('client/assets/images/content/movie_single/vid_slider_img.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_ms_trailer_slider_left_img_wrapper">
										<img src="{{ asset('client/assets/images/content/movie_single/vid_slider_img.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_ms_trailer_slider_left_img_wrapper">
										<img src="{{ asset('client/assets/images/content/movie_single/vid_slider_img.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_ms_trailer_slider_left_img_wrapper">
										<img src="{{ asset('client/assets/images/content/movie_single/vid_slider_img.jpg')}}" alt="vp_img">
									</div>
								</div>
								<div class="item">
									<div class="prs_ms_trailer_slider_left_img_wrapper">
										<img src="{{ asset('client/assets/images/content/movie_single/vid_slider_img.jpg')}}" alt="vp_img">
									</div>
								</div>
							</div>
						</div>
						<div class="prs_ms_trailer_slider_right_wrapper">
							<h2>29 <span>July</span> 2025</h2>
							<p>Jhon Doe Presnts</p>
							<img src="{{ asset('client/assets/images/content/movie_single/movie_title.png')}}" alt="title_img">
							<h5><span>Starring -</span> Katirna Saif , Ajay Mehra , Nitin Kumar Senani, Parbesh Verma
								,Sandy</h5>
							<ul>
								<li>Directed by - <span>Akshay H.</span>
								</li>
								<li>Produced by - <span>Ajay S.</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs ms trailer wrapper End -->
	<!-- prs theater Slider Start -->
	<div class="prs_movie_cast_main_slider_wrapper">
		<div class="prs_movie_cast_heading_wrapper">
			<h2>Movie Cast</h2>
		</div>
		<div class="wrap-album-slider">
			<ul class="movie-cast-slider">
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c1.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Mariya</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c2.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Kaslera</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c3.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Mariya</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c4.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Vjay</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c5.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Mariya</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c6.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Mariya</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
				<li class="album-slider__item">
					<figure class="album">
						<div class="prs_ms_cast_slider_cont_wrapper">
							<div class="prs_ms_cast_img_wrapper">
								<img src="{{ asset('client/assets/images/content/movie_single/c3.jpg')}}" alt="cast_img">
							</div>
							<div class="prs_ms_cast_img_cont_wrapper">
								<h4><a href="#">Mariya</a></h4>
								<p>(Miano)</p>
							</div>
						</div>
					</figure>
					<!-- End album -->
				</li>
				<!-- End album slider item -->
			</ul>
			<!-- End slider -->
		</div>
	</div>
	<!-- prs theater Slider End -->
	<!-- prs syn Slider Start -->
	<div class="prs_syn_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="prs_syn_cont_wrapper">
						<h3>Synopsis</h3>
						<h4><span>Genre -</span> Action , Reality , Drama, Raching</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
							labore et dolore a aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
							laboris nisi ut aliquip ex ea commodo const. Duis aute irure dolor in reprehenderit in
							voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur at cupidatat non
							proident, sunt in culpa qui officia deserunt
							<br>
							<br>error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
							quae ab illo inveiere veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo
							enim ipsam voluptatem quia voluptas sit ur aut odit aut fugit, sed quia consequuntur magni
							dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem
							ipsum quia dolor sit amet,
							<br>
							<br>consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore
							et dolore magnam sam quaerat voluptatem.Lorem ipsum dolor sit amet, consectetur adipisicing
							elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
							veniam, quis nostrud exercitation ullamco laboris nisi ut ipii eex ea commodo consequat.
							Duis aute irure dolor in reprehenderit in voluptate velit esse.
						</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="prs_syn_img_wrapper">
						<img src="{{ asset('client/assets/images/content/movie_single/v1.jpg')}}" alt="syn_img">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs syn Slider End -->
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
	<!-- prs ms scene slider Start -->
	<div class="prs_ms_scene_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>behind the scenes</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_ms_scene_slider_wrapper">
						<div class="owl-carousel owl-theme">
							<div class="item">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/movie_single/sc1.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc1.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img prs_ms_scene_slider_img2">
											<img src="{{ asset('client/assets/images/content/movie_single/sc4.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc4.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/movie_single/sc2.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc2.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img prs_ms_scene_slider_img2">
											<img src="{{ asset('client/assets/images/content/movie_single/sc5.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc5.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img">
											<img src="{{ asset('client/assets/images/content/movie_single/sc3.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc3.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="prs_ms_scene_slider_img prs_ms_scene_slider_img2">
											<img src="{{ asset('client/assets/images/content/movie_single/sc6.jpg')}}" alt="scene_img">
											<div class="prs_ms_scene_img_overlay"> <a
													href="{{ asset('client/assets/images/content/movie_single/sc6.jpg')}}" class="venobox info"
													data-title="PORTFOLIO TITTLE" data-gall="gall12"><i
														class="flaticon-tool"></i></a>
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
	<!-- prs ms scene slider End -->
	<!-- prs ms crew Start -->
	<div class="prs_ms_crew_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Our Crew</h2>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_ms_crew_left_cont_wrapper">
						<ul>
							<li>Director & Screenplay: <span>Johan Doe</span>
							</li>
							<li>Presented by: <span>Sandeep S</span>
							</li>
							<li>Producers: <span>Farhan S</span>
							</li>
							<li>Story: <span>Ajay S</span>
							</li>
							<li>Director of Photography: <span>Akshay H</span>
							</li>
							<li>Production Designer: <span>Johan Doe</span>
							</li>
							<li>Music Composer: <span>Farhan S</span>
							</li>
							<li>VFX Supervisor: <span>Shruti J</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_ms_crew_left_cont_wrapper prs_ms_crew_left_cont_wrapper2">
						<ul>
							<li>Sound Design: <span>Johan Doe</span>
							</li>
							<li>Stunt Choreography: <span>Sandeep S</span>
							</li>
							<li>Additional Stunts: <span>Farhan S</span>
							</li>
							<li>Dance Choreographers: <span>Ajay S</span>
							</li>
							<li>Editor: <span>Akshay H</span>
							</li>
							<li>Costume Designers: <span>Johan Doe</span>
							</li>
							<li>Dialogues: <span>Farhan S</span>
							</li>
							<li>Music Composer: <span>Shruti J</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs ms crew End -->
	<!-- prs related movie slider Start -->
	<div class="prs_ms_rm_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Director Related Movies</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_ms_rm_slider_wrapper">
						<div class="owl-carousel owl-theme">
							<div class="item">
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
							<div class="item">
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
							<div class="item">
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
							<div class="item">
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs related movie slider End -->
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
