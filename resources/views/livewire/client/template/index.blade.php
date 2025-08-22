@extends('clienttest')
@section("content")
	<!-- prs navigation End -->
	<!-- prs Slider Start -->
    @livewire('client.banners.client-banner-index')
	<!-- prs Slider End -->
	<!-- prs upcomung Slider Start -->
	<div class="prs_patner_main_section_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_heading_section_wrapper">
						<h2>hợp tác với </h2>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="prs_pn_slider_wraper">
						<div class="owl-carousel owl-theme">
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/b/b6/Universal_Pictures_logo.svg')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://ibrand.vn/wp-content/uploads/2024/07/Pepsi-logo-1.png')}}" alt="patner_img" >
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://lh5.googleusercontent.com/proxy/o-nurkmXUh09DNt3x9w7Qo6VQ_Avk6K6rTOYMR3SzTytePMcC5Gk_HIHVlgrvaXXfU2gjrtd23hGwv2vrVciq1bGCfYm-TIhqywqzqiiiwUkLg4bosI')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://is1-ssl.mzstatic.com/image/thumb/Purple221/v4/a7/a9/a9/a7a9a9df-2525-8305-6dea-c04f30fc7fc7/AppIcon-0-0-1x_U007emarketing-0-6-0-sRGB-85-220.png/1200x600wa.png')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Warner_Bros_logo.svg/355px-Warner_Bros_logo.svg.png')}}" alt="patner_img">
								</div>
							</div>
							<div class="item">
								<div class="prs_pn_img_wrapper">
									<img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Disney%2B_logo.svg/2560px-Disney%2B_logo.svg.png')}}" alt="patner_img">
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
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="prs_newsletter_text">
						<h3>xem phim giá hời ? đăng kí ngay!</h3>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_field">
                     <form action="/register" method="GET">
                         <input type="email" name="email" placeholder="Enter Your Email" required>
                         <button type="submit">Submit</button>
                     </form>
                 </div>
             </div>
			</div>
		</div>
	</div>
	<!-- prs Newsletter Wrapper End -->

@endsection
