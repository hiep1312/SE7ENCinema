<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie Pro Responsive HTML Template</title>
        <meta name="description" content="Movie Pro" />
        <meta name="keywords" content="Movie Pro" />
        <meta name="author" content="" />
        <meta name="MobileOptimized" content="320" />

        <title>{{ $title ?? 'SE7ENCinema' }}</title>
        @vite('resources/css/app.css', 'resources/js/app.js')
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
<head>

	<!--Template style -->
	<link rel="stylesheet" type="text/css" href="css/animate.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="css/flaticon.css" />
	<link rel="stylesheet" type="text/css" href="css/owl.carousel.css" />
	<link rel="stylesheet" type="text/css" href="css/owl.theme.default.css" />
	<link rel="stylesheet" type="text/css" href="css/dl-menu.css" />
	<link rel="stylesheet" type="text/css" href="css/nice-select.css" />
	<link rel="stylesheet" type="text/css" href="css/magnific-popup.css" />
	<link rel="stylesheet" type="text/css" href="css/venobox.css" />
	<link rel="stylesheet" type="text/css" href="js/plugin/rs_slider/layers.css" />
	<link rel="stylesheet" type="text/css" href="js/plugin/rs_slider/navigation.css" />
	<link rel="stylesheet" type="text/css" href="js/plugin/rs_slider/settings.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/responsive.css" />
	<link rel="stylesheet" id="theme-color" type="text/css" href="#" />
	<!-- favicon links -->
	<link rel="shortcut icon" type="image/png" href="images/header/favicon.ico" />
</head>

	<script src="js/jquery_min.js"></script>
	<script src="js/modernizr.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/owl.carousel.js"></script>
	<script src="js/jquery.dlmenu.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.magnific-popup.js"></script>
	<script src="js/jquery.bxslider.min.js"></script>
	<script src="js/venobox.min.js"></script>
	<script src="js/smothscroll_part1.js"></script>
	<script src="js/smothscroll_part2.js"></script>
	<script src="js/plugin/rs_slider/jquery.themepunch.revolution.min.js"></script>
	<script src="js/plugin/rs_slider/jquery.themepunch.tools.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.addon.snow.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.actions.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.carousel.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.kenburn.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.layeranimation.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.migration.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.navigation.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.parallax.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.slideanims.min.js"></script>
	<script src="js/plugin/rs_slider/revolution.extension.video.min.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>
