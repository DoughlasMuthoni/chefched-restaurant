<?php
/**
 * Shared <footer> for every public page. Relies on $phone/$whatsapp/$email/
 * $instagram/$tiktok/$facebook already being set by includes/header.php,
 * which every page includes first.
 */
?>
	<!--// Footer \\-->
	<footer id="hotmeal-footer" class="hotmeal-footer-one">
		<span class="hotmeal-footer-transparent"></span>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="hotmeal-footer-heading">
						<a href="/" class="logo"><img src="images/logo.png" alt="Njogu Nderitu"></a>
						<p>Njogu Nderitu is a Kenyan chef creating memorable culinary experiences across Kenya — private dining, events, corporate catering and culinary collaborations.</p>
					</div>
				</div>
			</div>
		</div>

		<!--// Footer Widget \\-->
		<div class="hotmeal-footer-widget">
			<div class="container">
				<div class="row">

					<!--// Widget About Us \\-->
					<aside class="col-md-4 widget widget_latest_post">
						<h2 class="footer-widget-title">The Chef's Journal</h2>
						<ul>
							<li><h6><a href="blog-grid">The Art Behind A Great Plate</a></h6></li>
							<li><h6><a href="blog-grid">Celebrating Kenyan Ingredients</a></h6></li>
							<li><h6><a href="blog-grid">Life Behind The Kitchen Door</a></h6></li>
						</ul>
					</aside>
					<!--// Widget About Us \\-->

					<!--// Widget Links \\-->
					<aside class="col-md-4 widget widget_links">
						<h2 class="footer-widget-title">Quick Links</h2>
						<ul>
							<li><a href="about-us">About Njogu Nderitu</a></li>
							<li><a href="services">Services</a></li>
							<li><a href="gallery">Gallery</a></li>
							<li><a href="blog-grid">The Chef's Journal</a></li>
							<li><a href="reservations">Book Njogu Nderitu</a></li>
							<li><a href="contact-us">Contact</a></li>
						</ul>
					</aside>
					<!--// Widget Links \\-->

					<!--// Widget Our Newsletter \\-->
					<aside class="col-md-4 widget widget_newsletter">
						<h2 class="footer-widget-title">Let's Talk Food</h2>
						<p>Njogu Nderitu, Kenya</p>
						<p><i class="fa fa-phone"></i> <?= htmlspecialchars($phone) ?><br>
						<i class="fa fa-whatsapp"></i> <?= htmlspecialchars($whatsapp) ?><br>
						<i class="fa fa-envelope"></i> <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></p>
						<div class="hotmeal-shop-social">
							<ul>
								<li><a href="<?= htmlspecialchars($instagram) ?>" target="_blank"><i class="fa fa-instagram"></i> <span class="btn-shape"></span></a></li>
								<li><a href="<?= htmlspecialchars($tiktok) ?>" target="_blank"><i class="fa fa-music"></i> <span class="btn-shape"></span></a></li>
								<li><a href="<?= htmlspecialchars($facebook) ?>" target="_blank"><i class="fa fa-facebook"></i> <span class="btn-shape"></span></a></li>
							</ul>
						</div>
					</aside>
					<!--// Widget Our Newsletter \\-->

				</div>
			</div>
		</div>
		<!--// Footer Widget \\-->

		<!--// CopyRight \\-->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="hotmeal-copyright">
						<p>&copy; Njogu Nderitu</p>
					</div>
				</div>
			</div>
		</div>
		<!--// CopyRight \\-->

	</footer>
	<!--// Footer \\-->

	<div class="clearfix"></div>

	</div>
	<!--// Main Wrapper \\-->

	<!-- Modal -->
	<div class="modal fade searchmodal" id="searchmodal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-body">
				<a href="#" class="hotmeal-close-btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
				<form>
					<input type="text" value="Type Your Keyword" onblur="if(this.value == '') { this.value ='Type Your Keyword'; }" onfocus="if(this.value =='Type Your Keyword') { this.value = ''; }">
					<input type="submit" value="">
					<i class="fa fa-search"></i>
				</form>
			</div>
		</div>
	</div>

	 <!-- jQuery (necessary for JavaScript plugins) -->
	 <script type="text/javascript" src="script/jquery.js"></script>
	 <script type="text/javascript" src="script/bootstrap.min.js"></script>
	 <script type="text/javascript" src="script/slick.slider.min.js"></script>
	 <script type="text/javascript" src="script/fancybox.pack.js"></script>
	 <script type="text/javascript" src="script/isotope.min.js"></script>
	 <script type="text/javascript" src="script/progressbar.js"></script>
	 <script type="text/javascript" src="script/functions.js"></script>

	</body>

</html>
