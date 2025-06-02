@extends('clienttest')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>Event-Categories</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Event</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs ec ue wrapper Start -->
	<div class="prs_ec_ue_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>Upcoming Events</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_ec_ue_left_img_wrapper">
						<img src="{{ asset('client/assets/images/content/event/e1.jpg')}}" alt="event_img">
					</div>
					<div class="prs_ec_ue_right_img_wrapper">
						<div class="prs_feature_img_cont prs_ec_ue_right_img_cont">
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
							<p class="prs_up_pera_sec">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
								eiud tempor incididunt ut labore et dolore magna aliqua. Ut enim ad quis nostrud
								exercitation ullamco laboris nisi ut aliquip ex ea commodo is aute irure dolor in
								reprehenderit in voluptate velit esse cillum fugiat nulla pariatur Excepteur sint. <a
									href="#">Read More</a>
							</p>
						</div>
						<div class="prs_ec_ue_timer_wrapper">
							<div id="clockdiv">
								<div><span class="days"></span>
									<div class="smalltext">Days</div>
								</div>
								<div><span class="hours"></span>
									<div class="smalltext">Hours</div>
								</div>
								<div><span class="minutes"></span>
									<div class="smalltext">Mins</div>
								</div>
								<div><span class="seconds"></span>
									<div class="smalltext">Secs</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="prs_feature_img_box_wrapper prs_ec_ue_inner_feat_wrapper">
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
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="prs_feature_img_box_wrapper prs_ec_ue_inner_feat_wrapper">
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
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="prs_feature_img_box_wrapper prs_ec_ue_inner_feat_wrapper">
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
	<!-- prs ec ue wrapper End -->
	<!-- prs feature slider Start -->
	<div class="prs_ec_pe_slider_main_wrapper">
		<div class="prs_ec_pe_slider_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_ec_pe_heading_section_wrapper">
						<h2>FEATURED EVENTS</h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_feature_slider_wrapper">
						<div class="owl-carousel owl-theme">
							<div class="item">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/event/e2.jpg')}}" alt="feature_img">
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
							<div class="item">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/event/e3.jpg')}}" alt="feature_img">
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
							<div class="item">
								<div class="prs_feature_img_box_wrapper">
									<div class="prs_feature_img">
										<img src="{{ asset('client/assets/images/content/event/e4.jpg')}}" alt="feature_img">
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

	<!--main js file end-->
	<script>
		// CountDown Js
		var deadline = 'september 1 2025 11:59:00 GMT-0400';
		function time_remaining(endtime) {
			var t = Date.parse(endtime) - Date.parse(new Date());
			var seconds = Math.floor((t / 1000) % 60);
			var minutes = Math.floor((t / 1000 / 60) % 60);
			var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
			var days = Math.floor(t / (1000 * 60 * 60 * 24));
			return { 'total': t, 'days': days, 'hours': hours, 'minutes': minutes, 'seconds': seconds };
		}
		function run_clock(id, endtime) {
			var clock = document.getElementById(id);

			// get spans where our clock numbers are held
			var days_span = clock.querySelector('.days');
			var hours_span = clock.querySelector('.hours');
			var minutes_span = clock.querySelector('.minutes');
			var seconds_span = clock.querySelector('.seconds');

			function update_clock() {
				var t = time_remaining(endtime);

				// update the numbers in each part of the clock
				days_span.innerHTML = t.days;
				hours_span.innerHTML = ('0' + t.hours).slice(-2);
				minutes_span.innerHTML = ('0' + t.minutes).slice(-2);
				seconds_span.innerHTML = ('0' + t.seconds).slice(-2);

				if (t.total <= 0) { clearInterval(timeinterval); }
			}
			update_clock();
			var timeinterval = setInterval(update_clock, 1000);
		}
		run_clock('clockdiv', deadline);
	</script>
@endsection
