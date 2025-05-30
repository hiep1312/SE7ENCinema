
@extends('components.layouts.client')
@section("content")
	<!-- prs title wrapper Start -->
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>Movie Booking</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Movie Bookin</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- prs video top Start -->
	<div class="prs_booking_main_div_section_wrapper">
		<div class="prs_top_video_section_wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="st_video_slider_inner_wrapper float_left">
							<div class="st_video_slider_overlay"></div>
							<div class="st_video_slide_sec float_left">
								<a rel='external' href='https://www.youtube.com/embed/ryzOXAO0Ss0' title='title'
									class="test-popup-link">
									<img src="{{ asset('client/assets/images/index_III/icon.png')}}" alt="img">
								</a>
								<h3>Aquaman</h3>
								<p>ENGLISH, HINDI, TAMIL</p>
								<h4>ACTION | Adventure | Fantasy</h4>
								<h5><span>2d</span> <span>3d</span> <span>D 4DX</span> <span>Imax 3D</span></h5>
							</div>
							<div class="st_video_slide_social float_left">
								<div class="st_slider_rating_btn_heart st_slider_rating_btn_heart_5th">
									<h5><i class="fa fa-heart"></i> 85%</h5>
									<h4>52,291 votes</h4>
								</div>
								<div class="st_video_slide_social_left float_left">
									<ul>
										<li><a href="#"><i class="fa fa-facebook-f"></i></a>
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
										<li><a href="#"><i class="fa fa-youtube"></i></a>
										</li>
									</ul>
								</div>
								<div class="st_video_slide_social_right float_left">
									<ul>
										<li data-animation="animated fadeInUp" class=""><i
												class="far fa-calendar-alt"></i> 14 Dec, 2025</li>
										<li data-animation="animated fadeInUp" class=""><i class="far fa-clock"></i> 2
											hrs 23 mins</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- prs video top End -->
		<!-- st slider rating wrapper Start -->
		<div class="st_slider_rating_main_wrapper float_left">
			<div class="container">
				<div class="st_calender_tabs">
					<ul class="nav nav-tabs">
						<li class="active"> <a data-toggle="tab" href="#home"><span>WED</span> <br> 19</a>
						</li>
						<li class="nav-item">
							<a data-toggle="tab" href="#menu1"> <span>THU</span>
								<br>20</a>
						</li>
						<li>
							<a data-toggle="tab" href="#menu2"> <span>FRI</span>
								<br>21</a>
						</li>
						<li>
							<a data-toggle="tab" href="#menu3"> <span>SAT</span>
								<br>22</a>
						</li>
						<li>
							<a data-toggle="tab" href="#menu4"> <span>SUN</span>
								<br>23</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- st slider rating wrapper End -->
		<!-- st slider sidebar wrapper Start -->
		<div class="st_slider_index_sidebar_main_wrapper st_slider_index_sidebar_main_wrapper_booking float_left">
			<div class="container">
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<div class="st_indx_slider_main_container float_left">
							<div class="row">
								<div class="col-md-12">
									<div class="tab-content">
										<div id="home" class="tab-pane active">
											<div class="st_calender_contant_main_wrapper float_left">
												<div class="st_calender_row_cont float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ariesplex SL Cinemas</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">10:00 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Artech Mall,<br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Greenfield, <br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Mall Of Travancore
																(Red Carpet)</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Dhanya Remya: Trivandrum</h3>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>JV Cinemas: Kattakkada</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>MT Cineplex 4K Dolby
																ATMOS: Pothencode</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>SPI: Kripa Cinemas -
																Mahathma Gandhi Road</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div
													class="st_calender_row_cont st_calender_row_cont2 st_calender_row_cont_last float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ganga Cine house
																4K Dolby Atmos: Attingal</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
											</div>
										</div>
										<div id="menu1" class="tab-pane fade">
											<div class="st_calender_contant_main_wrapper float_left">
												<div class="st_calender_row_cont float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ariesplex SL Cinemas</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">10:00 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Artech Mall,<br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Greenfield, <br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Mall Of Travancore
																(Red Carpet)</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Dhanya Remya: Trivandrum</h3>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="#">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div
													class="st_calender_row_cont st_calender_row_cont2 st_calender_row_cont_last float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ganga Cine house
																4K Dolby Atmos: Attingal</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
											</div>
										</div>
										<div id="menu2" class="tab-pane fade">
											<div class="st_calender_contant_main_wrapper float_left">
												<div class="st_calender_row_cont float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ariesplex SL Cinemas</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">10:00 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Artech Mall,<br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Greenfield, <br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div
													class="st_calender_row_cont st_calender_row_cont2 st_calender_row_cont_last float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ganga Cine house
																4K Dolby Atmos: Attingal</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
											</div>
										</div>
										<div id="menu3" class="tab-pane fade">
											<div class="st_calender_contant_main_wrapper float_left">
												<div class="st_calender_row_cont float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ariesplex SL Cinemas</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">10:00 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Artech Mall,<br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Greenfield, <br>
																Trivandrum</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Carnival: Mall Of Travancore
																(Red Carpet)</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Dhanya Remya: Trivandrum</h3>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>JV Cinemas: Kattakkada</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>MT Cineplex 4K Dolby
																ATMOS: Pothencode</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
												<div class="st_calender_row_cont st_calender_row_cont2 float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>SPI: Kripa Cinemas -
																Mahathma Gandhi Road</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/ticket.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div
													class="st_calender_row_cont st_calender_row_cont2 st_calender_row_cont_last float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ganga Cine house
																4K Dolby Atmos: Attingal</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="#">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
											</div>
										</div>
										<div id="menu4" class="tab-pane fade">
											<div class="st_calender_contant_main_wrapper float_left">
												<div class="st_calender_row_cont float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ariesplex SL Cinemas</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
																<li>
																	<img src="{{ asset('client/assets/images/content/bill.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">10:00 PM</a>
															</li>
														</ul>
													</div>
												</div>
												<div
													class="st_calender_row_cont st_calender_row_cont2 st_calender_row_cont_last float_left">
													<div class="st_calender_asc">
														<div class="st_calen_asc_heart"><a href="#"> <i
																	class="fa fa-heart"></i></a>
														</div>
														<div class="st_calen_asc_heart_cont">
															<h3>Ganga Cine house
																4K Dolby Atmos: Attingal</h3>
															<ul>
																<li>
																	<img src="{{ asset('client/assets/images/content/fast-food.png')}}">
																</li>
															</ul>
														</div>
													</div>
													<div class="st_calen_asc_tecket_time_select">
														<ul>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">11:30 AM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">02:45 PM</a>
															</li>
															<li> <span>
																	<h4>Rs.160.00</h4>
																	<p class="asc_pera1">Executive</p>
																	<p class="asc_pera2">Filling Fast</p>
																</span>
																<a href="seat_booking.html">06:30 PM</a>
															</li>
														</ul>
														<p class="asc_bottom_pera">Cancellation Available</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="prs_mcc_left_side_wrapper">
							<div class="prs_mcc_left_searchbar_wrapper">
								<input type="text" placeholder="Search Movie">
								<button><i class="flaticon-tool"></i>
								</button>
							</div>
							<div class="prs_mcc_bro_title_wrapper">
								<h2>browse title</h2>
								<ul>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">All
											<span>23,124</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Action
											<span>512</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Romantic
											<span>548</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Love
											<span>557</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Musical
											<span>554</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Drama
											<span>567</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Thriller
											<span>689</span></a>
									</li>
									<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">Horror
											<span>478</span></a>
									</li>
								</ul>
							</div>
							<div class="prs_mcc_event_title_wrapper">
								<h2>Top Events</h2>
								<img src="{{ asset('client/assets/images/content/movie_category/event_img.jpg')}}" alt="event_img">
								<h3><a href="#">Music Event in india</a></h3>
								<p>Pune <span><i class="fa fa-star"></i><i class="fa fa-star"></i><i
											class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i
											class="fa fa-star-o"></i></span>
								</p>
								<h4>June 07 <span>08:00-12:00 pm</span></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- st slider sidebar wrapper End -->
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
