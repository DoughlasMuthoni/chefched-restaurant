<?php
/**
 * Shared <head>/<header> for every public page, replacing what used to be
 * 8 byte-identical copies. Each page sets $pageTitle, $pageDescription and
 * $activeNav (one of: home/about/services/gallery/journal/reservations/contact)
 * before including this file. Also loads the live contact/social settings
 * once into $contactInfo (plus convenience variables $phone/$whatsapp/$email/
 * $instagram/$tiktok/$facebook) — set here, not in footer.php, because
 * several pages (contact-us, reservations, blog-detail) echo these in their
 * own main content, which renders before footer.php is included.
 */

// These pages render fresh from the database on every request (services,
// gallery, journal, contact info, homepage content all now dashboard-edited)
// but nothing told browsers that, so a visitor's browser can serve a stale
// cached copy indefinitely after a dashboard edit. Force revalidation.
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../api/db.php';

$contactInfo = [];
$row = db()->query("SELECT value FROM site_content WHERE content_key = 'contact_info'")->fetch();
if ($row) {
    $contactInfo = json_decode($row['value'], true) ?: [];
}
$phone = $contactInfo['phone'] ?? '+254 XXX XXX XXX';
$whatsapp = $contactInfo['whatsapp'] ?? '+254 XXX XXX XXX';
$email = $contactInfo['email'] ?? 'chefched@example.com';
$instagram = $contactInfo['instagram_url'] ?? 'https://www.instagram.com/chefchednjogu/';
$tiktok = $contactInfo['tiktok_url'] ?? 'https://www.tiktok.com/@chefchednjogu';
$facebook = $contactInfo['facebook_url'] ?? 'https://www.facebook.com/chefchednjogu';

$pageTitle = $pageTitle ?? 'Njogu Nderitu';
$pageDescription = $pageDescription ?? '';
$activeNav = $activeNav ?? '';

function nav_active(string $key, string $current): string {
    return $key === $current ? 'active' : '';
}
?><!DOCTYPE html>
<html lang="en">

<head>
	 <meta charset="utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	 <!-- Forces every relative href/src/action (css, js, images, nav links, form
	      actions) to resolve against the site root no matter how deep the
	      current URL is — needed now that /blog/<slug> is two path segments
	      deep, unlike every other page which lives flat at the root. -->
	 <base href="/">
	 <title><?= htmlspecialchars($pageTitle) ?></title>
	 <?php if ($pageDescription !== ''): ?><meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
	 <?php endif; ?>
	 <link rel="icon" href="images/logo.png" type="image/png">
	 <link rel="shortcut icon" href="images/logo.png" type="image/png">

	 <!-- Css Files -->
	 <link href="css/bootstrap.css" rel="stylesheet">
	 <link href="css/font-awesome.css" rel="stylesheet">
	 <link href="css/flaticon.css" rel="stylesheet">
	 <link href="css/slick-slider.css" rel="stylesheet">
	 <link href="css/fancybox.css" rel="stylesheet">
	 <link href="style.css" rel="stylesheet">
	 <link href="css/color.css" rel="stylesheet">
	 <link href="css/responsive.css" rel="stylesheet">


	 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	 <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	 <!--[if lt IE 9]>
		 <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	 <![endif]-->
	</head>
	<body>

	 <!--// Main Wrapper \\-->
	 <div class="hotmeal-main-wrapper">

	<!--// Header \\-->
	<header id="hotmeal-header" class="hotmeal-header-one">
		<div class="hotmeal-main-header">
			<div class="container">
				<div class="row">
					<aside class="col-md-2"> <a href="/" class="logo"><img src="images/logo.png" alt="Njogu Nderitu"></a> </aside>
					<aside class="col-md-10">
						<!--// Navigation \\-->
						<a href="#menu" class="menu-link active"><span></span></a>
						<nav id="menu" class="menu navbar navbar-default">
							<ul class="level-1 navbar-nav">
								<li class="<?= nav_active('home', $activeNav) ?>"><a href="/">Home</a></li>
								<li class="<?= nav_active('about', $activeNav) ?>"><a href="about-us">About</a></li>
								<li class="<?= nav_active('services', $activeNav) ?>"><a href="services">Services</a></li>
								<li class="<?= nav_active('gallery', $activeNav) ?>"><a href="gallery">Gallery</a></li>
								<li class="<?= nav_active('journal', $activeNav) ?>"><a href="blog-grid">Journal</a></li>
								<li class="<?= nav_active('reservations', $activeNav) ?>"><a href="reservations">Book Now</a></li>
								<li class="<?= nav_active('contact', $activeNav) ?>"><a href="contact-us">Contact</a></li>
							</ul>
						</nav>
						<!--// Navigation \\-->
						<ul class="hotmeal-user-list">
							<li><a href="#" class="fa fa-search" data-toggle="modal" data-target="#searchmodal"></a></li>
						</ul>
					</aside>
			 	</div>
			</div>
		</div>
	</header>
	<!--// Header \\-->
