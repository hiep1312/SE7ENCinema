    {{-- @extends('components.layouts.client') --}}
	<div id="preloader">
		<div id="status">
			<img src="{{ asset('client/assets/images/header/horoscope.gif')}}" id="preloader_image" alt="loader">
		</div>
	</div>
	<!-- color picker start -->
	<div id="style-switcher">
		<div>
			<h3>Choose Color</h3>
			<ul class="colors">
				<li>
					<p class='colorchange' id='color'></p>
				</li>
				<li>
					<p class='colorchange' id='color2'></p>
				</li>
				<li>
					<p class='colorchange' id='color3'></p>
				</li>
				<li>
					<p class='colorchange' id='color4'></p>
				</li>
				<li>
					<p class='colorchange' id='color5'></p>
				</li>
				<li>
					<p class='colorchange' id='style'></p>
				</li>
			</ul>
		</div>
		<div class="bottom"> <a href="" class="settings"><i class="fa fa-gear"></i></a> </div>
	</div>
	<!-- color picker end -->
	<!-- prs navigation Start -->
	<div class="prs_navigation_main_wrapper">
		<div class="container-fluid">
			<div id="search_open" class="gc_search_box">
				<input type="text" placeholder="Search here">
				<button><i class="fa fa-search" aria-hidden="true"></i>
				</button>
			</div>
			<div class="prs_navi_left_main_wrapper">
				<div class="prs_logo_main_wrapper">
					<a href="{{ route('client.index')}}">
						<img src="{{ asset('client/assets/images/header/logo.png')}}" alt="logo" />
					</a>
				</div>
				<div class="prs_menu_main_wrapper">
					<nav class="navbar navbar-default">
						<div id="dl-menu" class="xv-menuwrapper responsive-menu">
							<button class="dl-trigger">
								<img src="{{ asset('client/assets/images/header/bars.png')}}" alt="bar_png">
							</button>
							<div class="prs_mobail_searchbar_wrapper" id="search_button"> <i class="fa fa-search"></i>
							</div>
							<div class="clearfix"></div>
							<ul class="dl-menu">
								<li class="parent"><a href="{{ route('client.index')}}">Home</a>
									<ul class="lg-submenu">
										<li><a href="index.html">Index-I</a></li>
										<li><a href="index2.html">Index-II</a></li>
										<li><a href="index3.html">Index-III</a></li>
										<li><a href="index4.html">Index-IV</a></li>
										<li><a href="index5.html">Index-V</a></li>
									</ul>
								</li>
								<li class="parent megamenu"><a href="#">movie</a>
									<ul class="lg-submenu">
										<li><a>Popular Hindi Movies</a>
											<ul class="lg-submenu">
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Bajiro
														Mastani</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Drishyam</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Queen</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Wanted</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Veer</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Jannat</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Baaghi</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Baaghi-2</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Ki & Ka</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Kahaani</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Jab We Met</a>
												</li>
											</ul>
										</li>
										<li><a>Popular Kannada Movies</a>
											<ul class="lg-submenu">
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Zoom</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Kirik
														Party</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Mahakali</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">karvva</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Ishtakamya</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a
														href="#">Jigarthanda</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Abhimani</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Priyanka</a>
												</li>
											</ul>
										</li>
										<li><a>Popular Bengali Movies</a>
											<ul class="lg-submenu">
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Baro Bou</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Tomake</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Jeevan</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Haraner </a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Bidhilipi</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Bhalobasa </a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Prateek</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Aparanher</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a
														href="#">Mukhyamantri</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Charmurti</a>
												</li>
											</ul>
										</li>
										<li><a>Popular Hollywood Movies</a>
											<ul class="lg-submenu">
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Wind River</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Logan</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Coco</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Meyerowitz
													</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Ragnarok</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Driver</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Dunkirk</a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Big Sick </a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">American </a>
												</li>
												<li class="ar_left"><i class="fa fa-film"></i><a href="#">Logan </a>
												</li>
											</ul>
										</li>
										<li>
											<div class="prs_navi_slider_wraper">
												<div class="owl-carousel owl-theme">
													<div class="item">
														<img src="{{ asset('client/assets/images/content/up1.jpg') }}" alt="navi_img">
													</div>
													<div class="item">
														<img src="{{ asset('client/assets/images/content/up2.jpg') }}" alt="navi_img">
													</div>
													<div class="item">
														<img src="{{ asset('client/assets/images/content/up3.jpg') }}" alt="navi_img">
													</div>
												</div>
											</div>
										</li>
									</ul>
								</li>
								<li class="parent"><a href="#">tv show</a>
									<ul class="lg-submenu">
										<li><a href="#">Star Plus</a></li>
										<li><a href="#">Star Jalsha</a></li>
										<li><a href="#">Star Gold</a></li>
										<li><a href="#">Sony TV</a></li>
										<li><a href="#">Sab TV</a></li>
										<li><a href="#">Sony Pal</a></li>
										<li><a href="#">Set Max</a></li>
									</ul>
								</li>
								<li class="parent megamenu"><a href="#">video</a>
									<ul class="lg-submenu prs_navi_video_wrapper">
										<li>
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp1.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
										<li>
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp2.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
										<li>
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp3.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
										<li>
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp4.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
										<li>
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp5.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
										<li class="hidden-sm">
											<div class="prs_video_navi_img_main_wrapper">
												<img src="{{ asset('client/assets/images/content/vp6.jpg')}}" alt="video_img">
												<div class="prs_video_navi_overlay_wrapper"> <a
														class="test-popup-link button" rel='external'
														href='https://www.youtube.com/embed/ryzOXAO0Ss0'
														title='title'><i class="flaticon-play-button"></i></a>
												</div>
											</div>
										</li>
									</ul>
								</li>
								<li class="parent"><a href="#">pages</a>
									<ul class="lg-submenu">
										<li class="parent"><a href="#">Blog</a>
											<ul class="lg-submenu">
												<li><a href="{{ route('client.blog_category')}}">Blog-Category</a>
												</li>
												<li><a href="{{ route('client.blog_single')}}">Blog-Single</a>
												</li>
											</ul>
										</li>
										<li class="parent"><a href="#">Event</a>
											<ul class="lg-submenu">
												<li><a href="event_category.html">Event-Category</a>
												</li>
												<li><a href="event_single.html">Event-Single</a>
												</li>
											</ul>
										</li>
										<li class="parent"><a href="#">Movie</a>
											<ul class="lg-submenu">
												<li><a href="movie_category.html">Movie-Category</a>
												</li>
												<li><a href="movie_single.html">Movie-Single</a>
												</li>
												<li><a href="movie_single_second.html">Movie-Single-II</a>
												</li>
											</ul>
										</li>
										<li><a href="gallery.html">gallery</a>
										</li>
										<li><a href="booking_type.html">Booking-Type</a>
										</li>
										<li><a href="confirmation_screen.html">Confirmation-Screen</a>
										</li>
										<li><a href="movie_booking.html">Movie-Booking</a>
										</li>
										<li><a href="seat_booking.html">Seat-Booking</a>
										</li>
									</ul>
								</li>
								<li class="parent"><a href="contact.html">contact</a>
								</li>
							</ul>
						</div>
						<!-- /dl-menuwrapper -->
					</nav>
				</div>
			</div>
			<div class="prs_navi_right_main_wrapper">
				<div class="prs_slidebar_wrapper">
					<button class="second-nav-toggler" type="button">
						<img src="{{ asset('client/assets/images/header/bars.png')}}" alt="bar_png">
					</button>
				</div>
				<div class="prs_top_login_btn_wrapper">
					<div class="prs_animate_btn1">
						<ul>
							<li><a href="#" class="button button--tamaya" data-text="sign up" data-toggle="modal"
									data-target="#myModal"><span>sign up</span></a>
							</li>
						</ul>
					</div>
				</div>
				<div class="product-heading">
					<div class="con">
						<select>
							<option>All Categories</option>
							<option>Movie</option>
							<option>Video</option>
							<option>Music</option>
							<option>TV-Show</option>
						</select>
						<input type="text" placeholder="Search Movie , Video , Music">
						<button type="submit"><i class="flaticon-tool"></i>
						</button>
					</div>
				</div>
			</div>
			<div id="mobile-nav" data-prevent-default="true" data-mouse-events="true">
				<div class="mobail_nav_overlay"></div>
				<div class="mobile-nav-box">
					<div class="mobile-logo">
						<a href="index.html" class="mobile-main-logo">
							<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
								xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="46.996px"
								height="40px" viewBox="0 0 46.996 40" enable-background="new 0 0 46.996 40"
								xml:space="preserve">
								<g>
									<g>
										<path d="M39.919,19.833C39.919,8.88,30.984,0,19.959,0C8.937,0,0,8.88,0,19.833
			c0,10.954,8.937,19.834,19.959,19.834C30.984,39.666,39.919,30.787,39.919,19.833z M35.675,14.425
			c0.379,0,0.686,0.307,0.686,0.683s-0.305,0.683-0.686,0.683c-0.38,0-0.688-0.307-0.688-0.683S35.295,14.425,35.675,14.425z
			 M34.482,20.461c0,0.491-0.025,0.976-0.071,1.452l-11.214-4.512l6.396-7.733C32.592,12.311,34.482,16.167,34.482,20.461z
			 M19.083,2.277c0.379,0,0.687,0.305,0.687,0.682c0,0.378-0.306,0.684-0.687,0.684c-0.378,0-0.686-0.306-0.686-0.684
			C18.396,2.584,18.704,2.277,19.083,2.277z M19.959,6.031c1.916,0,3.743,0.372,5.416,1.042l-6.335,7.662l-6.252-6.82
			C14.906,6.718,17.351,6.031,19.959,6.031z M3.128,16.473c-0.378,0-0.687-0.306-0.687-0.684c0-0.377,0.307-0.682,0.687-0.682
			c0.38,0,0.686,0.305,0.686,0.682C3.814,16.167,3.508,16.473,3.128,16.473z M5.535,22.119c-0.063-0.545-0.098-1.098-0.098-1.658
			c0-3.612,1.339-6.911,3.547-9.444l6.502,7.095L5.535,22.119z M10.462,35.354c-0.379,0-0.687-0.306-0.687-0.683
			s0.307-0.682,0.687-0.682c0.379,0,0.687,0.305,0.687,0.682S10.842,35.354,10.462,35.354z M6.925,26.828l10.4-4.186l0.239,12.052
			C12.88,33.921,8.956,30.922,6.925,26.828z M19.513,22.326c-1.529,0-2.771-1.232-2.771-2.752c0-1.521,1.241-2.753,2.771-2.753
			c1.53,0,2.771,1.232,2.771,2.753C22.284,21.096,21.043,22.326,19.513,22.326z M29.939,33.99c-0.378,0-0.686-0.308-0.686-0.683
			c0-0.377,0.307-0.683,0.686-0.683s0.688,0.306,0.688,0.683C30.626,33.683,30.319,33.99,29.939,33.99z M22.482,34.672
			l-0.246-12.388l10.846,4.365C31.098,30.799,27.177,33.854,22.482,34.672z M35.314,34.585c-1.837,1.531-6.061,4.306-6.061,4.306
			C37.652,36.448,45.953,40,45.953,40l1.043-8.658C41.41,30.454,38.125,32.244,35.314,34.585z" />
									</g>
								</g>
							</svg><span>Movie Pro</span>
						</a>
						<a href="#" class="manu-close"><i class="fa fa-times"></i></a>
					</div>
					<ul class="mobile-list-nav">
						<li><a href="about.html">OVERVIEW</a>
						</li>
						<li><a href="movie_single.html">MOVIE</a>
						</li>
						<li><a href="event_single.html">EVENT</a>
						</li>
						<li><a href="gallery.html">GALLERY</a>
						</li>
						<li><a href="blog_single.html">BLOG</a>
						</li>
						<li><a href="contact.html">CONTACT</a>
						</li>
					</ul>
					<div class="product-heading prs_slidebar_searchbar_wrapper">
						<div class="con">
							<select>
								<option>All Categories</option>
								<option>Movie</option>
								<option>Video</option>
								<option>Music</option>
								<option>TV-Show</option>
							</select>
							<input type="text" placeholder="Search Movie , Video , Music">
							<button type="submit"><i class="flaticon-tool"></i>
							</button>
						</div>
					</div>
					<div class="achivement-blog">
						<ul class="flat-list">
							<li>
								<a href="#"> <i class="fa fa-facebook"></i>
									<h6>Facebook</h6>
									<span class="counter">12546</span>
								</a>
							</li>
							<li>
								<a href="#">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path
											d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
									</svg>
									<h6>Twitter</h6>
									<span class="counter">12546</span>
								</a>
							</li>
							<li>
								<a href="#"> <i class="fa fa-pinterest"></i>
									<h6>Pinterest</h6>
									<span class="counter">12546</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="prs_top_login_btn_wrapper prs_slidebar_searchbar_btn_wrapper">
						<div class="prs_animate_btn1">
							<ul>
								<li><a href="#" class="button button--tamaya" data-text="sign up" data-toggle="modal"
										data-target="#myModal"><span>sign up</span></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
