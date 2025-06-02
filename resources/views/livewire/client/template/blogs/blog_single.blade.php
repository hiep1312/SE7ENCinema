@extends('clienttest')
@section("content")
	<div class="prs_title_main_sec_wrapper">
		<div class="prs_title_img_overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_title_heading_wrapper">
						<h2>Blog Singe Page</h2>
						<ul>
							<li><a href="{{ route('client.index')}}">Home</a>
							</li>
							<li>&nbsp;&nbsp; >&nbsp;&nbsp; Blog</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prs title wrapper End -->
	<!-- hs sidebar Start -->
	<div class="hs_blog_categories_main_wrapper hs_blog_categories_main_wrapper2">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<div class="hs_blog_left_sidebar_main_wrapper">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_blog_box1_main_wrapper">
									<div class="hs_blog_box1_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/b1.jpg')}}" alt="blog_img">
									</div>
									<div class="hs_blog_box1_cont_main_wrapper">
										<div class="hs_blog_cont_heading_wrapper">
											<ul>
												<li>March 07, 2025</li>
												<li>by Admin</li>
											</ul>
											<h2>Simplicity is about subtracting the obvious and adding part area of
												the meaningful</h2>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
												tempor incididunt ut labore et ore magna aliqua. Ut enim ad minim
												veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
												comi consequat. Duis aute irure dolor in reprehenderit in voluptate
												velit esse cillum dolore eu fugiat nulla parire Excepteur sint occaecat
												cupidatat non proident,
												<br>
												<br>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
												eiusmod tempor incididunt ut labore et lore magna aliqua. Ut enim ad
												minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
												ex ea codo consequat. Duis aute irure dolor in reprehenderit in
												voluptate velit esse cillum dolore eu fugiat nulla paturi Excepteur sint
												occaecat cupidatat non proident, sunt in culpa qui officia deserunt
												mollit anim id est labume Sed ut perspiciatis unde omnis iste natus
												erroet dolore magnam aliquam quaerat voluptatem.button bgin culpa qui
												officia deserunt mollit animin culpa qui officia deserunt mollit anim.
											</p>
										</div>
										<div class="prs_bs_three_img_sec_wrapper">
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="prs_bs_three_img">
														<img src="{{ asset('client/assets/images/content/blog_category/bs1.jpg')}}" alt="blog_img">
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="prs_bs_three_img">
														<img src="{{ asset('client/assets/images/content/blog_category/bs2.jpg')}}" alt="blog_img">
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="prs_bs_three_img">
														<img src="{{ asset('client/assets/images/content/blog_category/bs3.jpg')}}" alt="blog_img">
													</div>
												</div>
											</div>
										</div>
										<p class="prs_blog_pera">Lorem ipsum dolor sit amet, consectetur adipisicing
											elit, sed do eiusmod tempor incididunt ut labore et ore magna aliqua. Ut
											enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
											aliquip ex ea comi consequat. Duis aute irure dolor in reprehenderit in
											voluptate velit esse cillum dolore eu fugiat nulla parire Excepteur sint
											occaecat cupidatat non proident,</p>
										<div class="prs_bs_admin_section_wrapper">
											<div class="prs_bs_admin_cont_wrapper">
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
													eiusmod temport ut labore et dolore magna aliqua. Ut enim ad minim
													veniam,</p>
												<h5>by - Akshay H.</h5>
											</div>
											<div class="prs_bs_admin_img_cont_wrapper">
												<img src="{{ asset('client/assets/images/content/blog_category/bs4.jpg')}}" alt="blog_img">
											</div>
										</div>
										<p class="prs_bs_admin_pera_last_wrapper">Lorem ipsum dolor sit amet,
											consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
											ore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
											laboris nisi ut aliquip ex ea comi consequat. Duis aute irure dolor in
											reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla parire
											Excepteur sint occaecat cupidatat non proident,</p>
									</div>
									<div class="hs_blog_box1_bottom_cont_main_wrapper">
										<div class="hs_blog_box1_bottom_cont_left">
											<ul>
												<li><i class="fa fa-thumbs-up"></i> &nbsp;&nbsp;<a href="#">1244
														Likes</a>
												</li>
												<li><i class="fa fa-comments"></i> &nbsp;&nbsp;<a href="#">256
														Comments</a>
												</li>
												<li><i class="fa fa-tags"></i> &nbsp;&nbsp;<a href="#">Presenter
														Movie</a>
												</li>
											</ul>
										</div>
										<div class="hs_blog_box1_bottom_cont_right">
											<ul>
												<li><a href="#"><i class="fa fa-facebook"></i></a>
												</li>
												<li><a href="#"><i class="fa fa-youtube-play"></i></a>
												</li>
												<li><a href="#"><i class="fa fa-linkedin"></i></a>
												</li>
												<li>
													<a href="#">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
															<path
																d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
														</svg>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_bs_about_admin_main_wrapper">
									<div class="prs_bs_about_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/bs5.jpg')}}" alt="blog_img">
									</div>
									<div class="prs_bs_about_img_cont_wrapper">
										<h4>About Admin : Sandy</h4>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
											tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam,
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_bs_comment_heading_wrapper">
									<h2>Comments</h2>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_rs_comment_main_wrapper hs_rs_comment_main_wrapper2">
									<div class="hs_rs_comment_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/comm_img1.jpg')}}" alt="comment_img">
									</div>
									<div
										class="hs_rs_comment_img_cont_wrapper hs_rs_blog_single_comment_img_cont_wrapper">
										<h2>Joahn Doe<br> <span>Jan 2 , 2025 - Friday</span> <a href="#"> - Reply</a>
										</h2>
										<p>The actor, director and producer, son to well-known stunt choreographer of
											Bollywood, rried to one of the most vivacious, bubbly, live-wire actress, is
											none other than our dashing Ajay Devgan, originally named Vishal Devgan !
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_rs_comment_main_wrapper">
									<div class="hs_rs_comment_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/comm_img2.jpg')}}" alt="comment_img">
									</div>
									<div
										class="hs_rs_comment_img_cont_wrapper hs_rs_blog_single_comment_img_cont_wrapper">
										<h2>Joahn Doe<br> <span>Jan 2 , 2025 - Friday</span> <a href="#"> - Reply</a>
										</h2>
										<p>The actor, director and producer, son to well-known stunt choreographer of
											Bollywood, rried to one of the most vivacious, bubbly, live-wire actress, is
											none other than our dashing Ajay Devgan, originally named Vishal Devgan !
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_rs_comment_main_wrapper">
									<div class="hs_rs_comment_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/comm_img3.jpg')}}" alt="comment_img">
									</div>
									<div
										class="hs_rs_comment_img_cont_wrapper hs_rs_blog_single_comment_img_cont_wrapper">
										<h2>Joahn Doe<br> <span>Jan 2 , 2025 - Friday</span> <a href="#"> - Reply</a>
										</h2>
										<p>The actor, director and producer, son to well-known stunt choreographer of
											Bollywood, rried to one of the most vivacious, bubbly, live-wire actress, is
											none other than our dashing Ajay Devgan, originally named Vishal Devgan !
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_rs_comment_main_wrapper">
									<div class="hs_rs_comment_img_wrapper">
										<img src="{{ asset('client/assets/images/content/blog_category/comm_img1.jpg')}}" alt="comment_img">
									</div>
									<div
										class="hs_rs_comment_img_cont_wrapper hs_rs_blog_single_comment_img_cont_wrapper">
										<h2>Joahn Doe<br> <span>Jan 2 , 2025 - Friday</span> <a href="#"> - Reply</a>
										</h2>
										<p>The actor, director and producer, son to well-known stunt choreographer of
											Bollywood, rried to one of the most vivacious, bubbly, live-wire actress, is
											none other than our dashing Ajay Devgan, originally named Vishal Devgan !
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="prs_bs_comment_heading_wrapper prs_bs_leave_comment_heading_wrapper">
									<h2>LEave a comment</h2>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="hs_kd_six_sec_input_wrapper">
									<input type="text" placeholder="Name">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="hs_kd_six_sec_input_wrapper">
									<input type="email" placeholder="Email">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_kd_six_sec_input_wrapper">
									<textarea rows="6" placeholder="Comments"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="hs_kd_six_sec_btn">
									<ul>
										<li><a href="#">Send a Comment</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="hs_blog_right_sidebar_main_wrapper">
						<div class="prs_mcc_left_searchbar_wrapper">
							<input type="text" placeholder="Search Movie">
							<button><i class="flaticon-tool"></i>
							</button>
						</div>
						<div class="prs_bc_right_about_wrapper">
							<img src="{{ asset('client/assets/images/content/blog_category/side_img1.jpg')}}" alt="side_img">
							<h2>About Presenter</h2>
							<p>Lorem ipsum dolor sit amet ue adipisicing elit, sed do eiuodor incididunt ut part.</p>
							<h5><a href="#">Read More <i class="fa fa-long-arrow-right"></i></a></h5>
						</div>
						<div class="prs_mcc_bro_title_wrapper">
							<h2>Category</h2>
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
						<div class="prs_mcc_bro_title_wrapper">
							<h2>Recent News</h2>
							<div class="hs_blog_right_recnt_cont_wrapper">
								<div class="hs_footer_ln_img_wrapper">
									<img src="{{ asset('client/assets/images/content/blog_category/n1.jpg')}}" alt="ln_img" />
								</div>
								<div class="hs_footer_ln_cont_wrapper">
									<h4>Lorem spum
										menus.</h4>
									<p>12 May 2025</p>
								</div>
							</div>
							<div class="hs_blog_right_recnt_cont_wrapper hs_blog_right_recnt_cont_wrapper2">
								<div class="hs_footer_ln_img_wrapper">
									<img src="{{ asset('client/assets/images/content/blog_category/n2.jpg')}}" alt="ln_img" />
								</div>
								<div class="hs_footer_ln_cont_wrapper">
									<h4>Lorem spum
										menus.</h4>
									<p>12 May 2025</p>
								</div>
							</div>
							<div class="hs_blog_right_recnt_cont_wrapper hs_blog_right_recnt_cont_wrapper2">
								<div class="hs_footer_ln_img_wrapper">
									<img src="{{ asset('client/assets/images/content/blog_category/n3.jpg')}}" alt="ln_img" />
								</div>
								<div class="hs_footer_ln_cont_wrapper">
									<h4>Lorem spum
										menus.</h4>
									<p>12 May 2025</p>
								</div>
							</div>
						</div>
						<div class="prs_mcc_bro_title_wrapper">
							<h2>Archives</h2>
							<ul>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2012
										<span>23,124</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2013
										<span>512</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2014
										<span>548</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2015
										<span>557</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2016
										<span>554</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2017
										<span>567</span></a>
								</li>
								<li><i class="fa fa-caret-right"></i> &nbsp;&nbsp;&nbsp;<a href="#">2025
										<span>689</span></a>
								</li>
							</ul>
						</div>
						<div class="prs_blog_right_sub_btn_wrapper">
							<h2>Subscribe</h2>
							<input type="text" placeholder="Your email id">
							<ul>
								<li><a href="#">Subscribe</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- hs sidebar End -->
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
@endsection
