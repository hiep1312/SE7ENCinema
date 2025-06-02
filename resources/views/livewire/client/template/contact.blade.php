@extends('clienttest')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>contact us</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; contact</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs contact form wrapper Start -->
	<div class="prs_contact_form_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="prs_contact_left_wrapper">
						<h2>Contact us</h2>
					</div>
					<div class="row">
						<form>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="prs_contact_input_wrapper">
									<input name="full_name" type="text" class="require" placeholder="Name">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="prs_contact_input_wrapper">
									<input type="email" class="require" data-valid="email"
										data-error="Email should be valid." placeholder="Email">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_contact_input_wrapper">
									<textarea name="message" class="require" rows="7" placeholder="Comment"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="response"></div>
								<div class="prs_contact_input_wrapper prs_contact_input_wrapper2">
									<ul>
										<li>
											<input type="hidden" name="form_type" value="contact" />
											<button type="button" class="submitForm">Submit</button>
										</li>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="prs_contact_right_section_wrapper">
						<ul>
							<li><a href="#"><i class="fa fa-facebook"></i> &nbsp;&nbsp;&nbsp;facebook.com/presenter</a>
							</li>
							<li>
								<a href="#">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path
											d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
									</svg>
									&nbsp;&nbsp;&nbsp;twitter.com/presenter
								</a>
							</li>
							<li><a href="#"><i class="fa fa-vimeo"></i> &nbsp;&nbsp;&nbsp;vimeo.com/presenter</a>
							</li>
							<li><a href="#"><i class="fa fa-instagram"></i>
									&nbsp;&nbsp;&nbsp;instagram.com/presenter</a>
							</li>
							<li><a href="#"><i class="fa fa-youtube-play"></i>
									&nbsp;&nbsp;&nbsp;youtube.com/presenter</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs contact form wrapper End -->
	<!-- prs contact map Start -->
	<div class="hs_contact_map_main_wrapper">
		<div id="map"></div>
	</div>
	<!-- prs contact map End -->
	<!-- prs contact info Start -->
	<div class="prs_contact_info_main_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="prs_contact_info_box_wrapper">
						<div class="prs_contact_info_smallbox"> <i class="flaticon-call-answer"></i>
						</div>
						<h3>contact</h3>
						<p>+91-123456789
							<br>+91-4444-5555
						</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="prs_contact_info_box_wrapper prs_contact_info_box_wrapper2">
						<div class="prs_contact_info_smallbox"> <i class="flaticon-call-answer"></i>
						</div>
						<h3>Location</h3>
						<p>601 - Ram Nagar , India
							<br>Omex City 245 , America
						</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="prs_contact_info_box_wrapper prs_contact_info_box_wrapper2">
						<div class="prs_contact_info_smallbox"> <i class="flaticon-call-answer"></i>
						</div>
						<h3>Email</h3>
						<p><a href="#">presenter@example.com</a>
							<br> <a href="#">movie@example.com</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs contact info End -->
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
	<script>
		function initMap() {
			var uluru = { lat: -36.742775, lng: 174.731559 };
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 15,
				scrollwheel: false,
				center: uluru
			});
			var marker = new google.maps.Marker({
				position: uluru,
				map: map
			});
		}
	</script>
	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi2zbxXa0ObGqaSBo5NJMdwLs_xtQ03nI&callback=initMap">
		</script>
@endsection
