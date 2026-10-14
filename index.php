<?php
require_once __DIR__ . '/api/db.php';
$pageTitle = 'Njogu Nderitu | Personal Chef & Culinary Experiences, Kenya';
$pageDescription = 'Njogu Nderitu is a Kenyan personal chef creating memorable private dining, event, corporate and culinary collaboration experiences.';
$activeNav = 'home';
require __DIR__ . '/includes/header.php';

$homeRow = db()->query("SELECT value FROM site_content WHERE content_key = 'homepage'")->fetch();
$home = $homeRow ? json_decode($homeRow['value'], true) : [];
$hero = $home['hero'] ?? [];
$chefIntro = $home['chef_intro'] ?? [];
$philosophy = $home['philosophy'] ?? [];
$pillars = $philosophy['pillars'] ?? [];
?>

	<!--// Main Banner \\-->
	<div class="hotmeal-banner">

		<!--// Slider \\-->
		<?php foreach ($hero as $slide): ?>
		<div class="hotmeal-banner-layer">
			<img src="<?= htmlspecialchars($slide['image'] ?? '') ?>" alt="<?= htmlspecialchars($slide['alt'] ?? '') ?>">
			<span class="hotmeal-banner-transparent"></span>
			<div class="hotmeal-banner-caption">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="hotmeal-banner-text">
								<strong><?= htmlspecialchars($slide['years_badge'] ?? '') ?><small><?= htmlspecialchars($slide['years_label'] ?? '') ?></small><span>Culinary Experience</span></strong>
								<h1><?= htmlspecialchars($slide['h1'] ?? '') ?></h1>
								<h2><?= htmlspecialchars($slide['h2'] ?? '') ?></h2>
								<h3><?= htmlspecialchars($slide['h3'] ?? '') ?></h3>
								<p><?= htmlspecialchars($slide['paragraph'] ?? '') ?></p>
								<a href="<?= htmlspecialchars($slide['btn1_href'] ?? '#') ?>" class="hotmeal-banner-btn"><?= htmlspecialchars($slide['btn1_label'] ?? '') ?> <span></span></a>
								<a href="<?= htmlspecialchars($slide['btn2_href'] ?? '#') ?>" class="hotmeal-banner-btn"><?= htmlspecialchars($slide['btn2_label'] ?? '') ?> <span></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<!--// Slider \\-->

	</div>
	<!--// Main Banner \\-->

	<!--// Main Content \\-->
	<div class="hotmeal-main-content">

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-special-flavoursfull">
			<div class="container">
				<div class="row">

					<aside class="col-md-4">
						<div class="hotmeal-special-flavours-text">
							<div class="hotmeal-fancy-title">
								<span><?= htmlspecialchars($chefIntro['eyebrow'] ?? '') ?></span>
								<h2><?= htmlspecialchars($chefIntro['heading'] ?? '') ?></h2>
							</div>
							<p><?= htmlspecialchars($chefIntro['paragraph1'] ?? '') ?></p>
							<p><?= htmlspecialchars($chefIntro['paragraph2'] ?? '') ?></p>
							<a href="about-us" class="hotmeal-learn-btn">Learn More <span class="btn-shape"></span></a>
						</div>
					</aside>
					<aside class="col-md-4">
						<figure class="hotmeal-flavours-thumb"><a href="about-us"><img src="<?= htmlspecialchars($chefIntro['image'] ?? '') ?>" alt="Njogu Nderitu"></a>
							<figcaption>
								<span><?= htmlspecialchars($chefIntro['stat_badge'] ?? '') ?></span>
								<h2><a href="about-us"><?= htmlspecialchars($chefIntro['chef_name'] ?? '') ?></a></h2>
								<p><?= htmlspecialchars($chefIntro['chef_tagline'] ?? '') ?></p>
							</figcaption>
						</figure>
					</aside>
					<aside class="col-md-4">
						<div class="hotmeal-flavours-list">
							<ul>
								<li>
									<figure><a href="services"><img src="extra-images/flavours-list-img1.jpg" alt="Kenyan and African cuisine"></a></figure>
									<div class="hotmeal-flavours-list-text">
										<h6><a href="services">Kenyan &amp; African Cuisine</a></h6>
										<p>Celebrating familiar flavours and the richness of African culinary heritage.</p>
									</div>
								</li>
								<li>
									<figure><a href="services"><img src="extra-images/flavours-list-img2.jpg" alt="Contemporary cuisine"></a></figure>
									<div class="hotmeal-flavours-list-text">
										<h6><a href="services">Contemporary Cuisine</a></h6>
										<p>Reimagining classic ingredients through modern technique and presentation.</p>
									</div>
								</li>
								<li>
									<figure><a href="services"><img src="extra-images/flavours-list-img3.jpg" alt="Private dining experience"></a></figure>
									<div class="hotmeal-flavours-list-text">
										<h6><a href="services">Private Dining</a></h6>
										<p>Personalised dining experiences designed around you and your guests.</p>
									</div>
								</li>
							</ul>
						</div>
					</aside>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="hotmeal-fancy-title">
							<span><?= htmlspecialchars($philosophy['eyebrow'] ?? '') ?></span>
							<h2><?= htmlspecialchars($philosophy['heading'] ?? '') ?></h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
					</div>
					<div class="col-md-4">
						<?php if (!empty($philosophy['image'])): ?>
						<style>.hotmeal-service-grid-thumb:before{background-image:url('<?= htmlspecialchars($philosophy['image'], ENT_QUOTES) ?>');}</style>
						<?php endif; ?>
						<div class="hotmeal-service-grid-thumb"></div>
					</div>
					<div class="col-md-8">
						<div class="hotmeal-service hotmeal-service-grid">
							<ul class="row">
								<li class="col-md-12">
									<div class="hotmeal-service-grid-text">
										<p style="margin-bottom:20px;"><?= htmlspecialchars($philosophy['intro'] ?? '') ?></p>
									</div>
								</li>
								<?php foreach ($pillars as $pillar): ?>
								<li class="col-md-4">
									<div class="hotmeal-service-grid-text">
										<i class="icon <?= htmlspecialchars($pillar['icon'] ?? '') ?>"></i>
										<h5><a href="about-us"><?= htmlspecialchars($pillar['heading'] ?? '') ?></a></h5>
										<p><?= htmlspecialchars($pillar['text'] ?? '') ?></p>
										<span class="btn-shape"></span>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-menu-listfull">
			<span class="menu-list-transperant"></span>
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-fancy-title color">
							<span>Culinary Expertise</span>
							<h2>Crafted With Skill. Served With Passion.</h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
	                  	<div class="hotmeal-menu hotmeal-menu-list">
	   						<ul class="row">
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-dish"></i> Kenyan &amp; African Cuisine</a></h5>
	   									<p>Celebrating familiar flavours, traditional ingredients and the richness of African culinary heritage.</p>
	   								</div>
	   							</li>
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-cutlery-1"></i> Contemporary Cuisine</a></h5>
	   									<p>Reimagining classic ingredients and dishes through modern techniques, presentation and creativity.</p>
	   								</div>
	   							</li>
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-chef-hat"></i> Private Dining</a></h5>
	   									<p>Personalised dining experiences designed around the occasion, the guests and the desired culinary experience.</p>
	   								</div>
	   							</li>
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-cupcake"></i> Events &amp; Celebrations</a></h5>
	   									<p>Professional culinary services for celebrations, social gatherings and special occasions.</p>
	   								</div>
	   							</li>
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-office"></i> Corporate Experiences</a></h5>
	   									<p>Quality food and professional service for corporate functions, meetings and hospitality events.</p>
	   								</div>
	   							</li>
	   							<li class="col-md-6">
	   								<div class="hotmeal-menu-list-text">
	   									<h5><a href="services"><i class="icon hotmeal-chef"></i> Culinary Collaborations</a></h5>
	   									<p>Working with brands, hospitality businesses and fellow creatives to develop memorable food experiences.</p>
	   								</div>
	   							</li>
	   						</ul>
	   					</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="hotmeal-fancy-title">
							<span>A Taste Of Njogu Nderitu</span>
							<h2>Signature Dishes</h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
						<p style="text-align:center;max-width:700px;margin:0 auto 40px;">Explore a selection of dishes that represent Njogu's culinary style, creativity and passion for food.</p>
						<div class="hotmeal-gallery hotmeal-modern-gallery">
							<ul class="row">
								<li class="col-md-4">
									<div class="hotmeal-modern-gallery-wrap">
										<figure><a data-fancybox-group="signature" href="extra-images/recipie-img1.png" class="fancybox"><img src="extra-images/recipie-img1.png" alt="Signature Dish One"></a>
											<figcaption>
												<h5>Signature Dish One</h5>
												<span>A beautifully presented creation combining quality ingredients, balanced flavours and contemporary presentation.</span>
											</figcaption>
										</figure>
									</div>
								</li>
								<li class="col-md-4">
									<div class="hotmeal-modern-gallery-wrap">
										<figure><a data-fancybox-group="signature" href="extra-images/recipie-img2.png" class="fancybox"><img src="extra-images/recipie-img2.png" alt="Signature Dish Two"></a>
											<figcaption>
												<h5>Signature Dish Two</h5>
												<span>A modern interpretation inspired by familiar flavours, elevated through professional culinary technique.</span>
											</figcaption>
										</figure>
									</div>
								</li>
								<li class="col-md-4">
									<div class="hotmeal-modern-gallery-wrap">
										<figure><a data-fancybox-group="signature" href="extra-images/recipie-img3.png" class="fancybox"><img src="extra-images/recipie-img3.png" alt="Signature Dish Three"></a>
											<figcaption>
												<h5>Signature Dish Three</h5>
												<span>A memorable dish created to showcase the versatility and creativity behind Njogu Nderitu's kitchen.</span>
											</figcaption>
										</figure>
									</div>
								</li>
							</ul>
						</div>
						<p style="text-align:center;font-style:italic;margin-top:20px;">Dish names, descriptions and photographs can be updated as the chef's portfolio develops.</p>
						<div style="text-align:center;margin-top:10px;">
							<a href="gallery" class="hotmeal-learn-btn">View The Food Gallery <span class="btn-shape"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-service-providefull">
			<img src="extra-images/service-provide-img1.png" alt="" class="img-one">
			<img src="extra-images/service-provide-img2.png" alt="" class="img-two">
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-fancy-title">
							<span>The Experience</span>
							<h2>What You Can Expect</h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
						<div class="hotmeal-service hotmeal-service-grid">
							<ul class="row">
								<li class="col-md-3">
									<div class="hotmeal-service-grid-text">
										<i class="icon hotmeal-chef-hat"></i>
										<h5><a href="about-us">Professionalism</a></h5>
										<p>Every engagement is handled with preparation, communication and attention to detail.</p>
										<span class="btn-shape"></span>
									</div>
								</li>
								<li class="col-md-3">
									<div class="hotmeal-service-grid-text">
										<i class="icon hotmeal-cutlery-1"></i>
										<h5><a href="about-us">Creativity</a></h5>
										<p>Menus and experiences are designed to be distinctive, memorable and appropriate for the occasion.</p>
										<span class="btn-shape"></span>
									</div>
								</li>
								<li class="col-md-3">
									<div class="hotmeal-service-grid-text">
										<i class="icon hotmeal-serve"></i>
										<h5><a href="about-us">Quality</a></h5>
										<p>From ingredient selection to final presentation, quality remains at the centre of the process.</p>
										<span class="btn-shape"></span>
									</div>
								</li>
								<li class="col-md-3">
									<div class="hotmeal-service-grid-text">
										<i class="icon hotmeal-chef"></i>
										<h5><a href="about-us">Personal Touch</a></h5>
										<p>Every client and event is different. Njogu takes the time to create an experience around it.</p>
										<span class="btn-shape"></span>
									</div>
								</li>
							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-testimonialfull">
			<span class="testimonial-transparent"></span>
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-testimonial-wrap">
							<div class="hotmeal-testimonial">
								<div class="hotmeal-testimonial-layer">
									<figure><img src="extra-images/testimonial-img-1.jpg" alt=""></figure>
									<div class="hotmeal-testimonial-text">
										<i class="icon hotmeal-quote"></i>
										<h2>What People Are Saying</h2>
										<p>An incredible experience from start to finish. The food was exceptional and the attention to detail really stood out.</p>
										<div class="hotmeal-info">
											<h5>Client</h5>
											<span>Private Dining</span>
										</div>
									</div>
								</div>
								<div class="hotmeal-testimonial-layer">
									<figure><img src="extra-images/testimonial-img-2.jpg" alt=""></figure>
									<div class="hotmeal-testimonial-text">
										<i class="icon hotmeal-quote"></i>
										<h2>What People Are Saying</h2>
										<p>Professional, creative and passionate about his craft. Njogu Nderitu knows how to create an experience around food.</p>
										<div class="hotmeal-info">
											<h5>Client / Partner</h5>
											<span>Culinary Collaboration</span>
										</div>
									</div>
								</div>
								<div class="hotmeal-testimonial-layer">
									<figure><img src="extra-images/testimonial-img-2.jpg" alt=""></figure>
									<div class="hotmeal-testimonial-text">
										<i class="icon hotmeal-quote"></i>
										<h2>What People Are Saying</h2>
										<p>The presentation, flavour and professionalism were outstanding.</p>
										<div class="hotmeal-info">
											<h5>Client</h5>
											<span>Event Catering</span>
										</div>
									</div>
								</div>
							</div>
							<p style="text-align:center;font-style:italic;color:#fff;margin-top:20px;">Real client testimonials can be added as they become available.</p>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-blog-mediumfull">
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-fancy-title">
							<span>The Chef's Journal</span>
							<h2>Stories From The Kitchen</h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
						<div class="hotmeal-blog hotmeal-blog-medium">
							<div class="row hotmeal-blog-medium-slide">
								<div class="col-md-6 hotmeal-blog-medium-layer">
									<figure><a href="blog-grid"><img src="extra-images/blog-medium-img1.jpg" alt=""> <span class="hover-btn"><i class="icon hotmeal-link"></i><small></small></span></a></figure>
									<div class="hotmeal-blog-medium-text">
										<span>Cooking</span>
										<h5><a href="blog-grid">The Art Behind A Great Plate</a></h5>
										<p>What turns an ordinary meal into a memorable culinary experience?</p>
										<a href="blog-grid" class="hotmeal-simple-btn">Read More <i class="fa fa-angle-double-right"></i></a>
									</div>
								</div>
								<div class="col-md-6 hotmeal-blog-medium-layer">
									<figure><a href="blog-grid"><img src="extra-images/blog-medium-img2.jpg" alt=""> <span class="hover-btn"><i class="icon hotmeal-link"></i><small></small></span></a></figure>
									<div class="hotmeal-blog-medium-text">
										<span>Kenyan Ingredients</span>
										<h5><a href="blog-grid">Celebrating Kenyan Ingredients</a></h5>
										<p>Discovering the ingredients, flavours and food traditions that make Kenya unique.</p>
										<a href="blog-grid" class="hotmeal-simple-btn">Read More <i class="fa fa-angle-double-right"></i></a>
									</div>
								</div>
								<div class="col-md-6 hotmeal-blog-medium-layer">
									<figure><a href="blog-grid"><img src="extra-images/blog-medium-img3.jpg" alt=""> <span class="hover-btn"><i class="icon hotmeal-link"></i><small></small></span></a></figure>
									<div class="hotmeal-blog-medium-text">
										<span>Behind The Scenes</span>
										<h5><a href="blog-grid">Life Behind The Kitchen Door</a></h5>
										<p>A look at the discipline, creativity and passion behind professional cooking.</p>
										<a href="blog-grid" class="hotmeal-simple-btn">Read More <i class="fa fa-angle-double-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div style="text-align:center;margin-top:10px;">
							<a href="blog-grid" class="hotmeal-learn-btn">Explore The Journal <span class="btn-shape"></span></a>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-partnerfull">
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-fancy-title">
							<span>Good Food, Great Partnerships</span>
							<h2>Collaborations</h2>
							<img src="images/fancy-title-img.png" alt="">
						</div>
						<div class="hotmeal-partner">
							<ul>
								<li><a href="services"><img src="extra-images/partner-img1.jpg" alt=""></a></li>
								<li><a href="services"><img src="extra-images/partner-img2.jpg" alt=""></a></li>
								<li><a href="services"><img src="extra-images/partner-img3.jpg" alt=""></a></li>
								<li><a href="services"><img src="extra-images/partner-img4.jpg" alt=""></a></li>
								<li><a href="services"><img src="extra-images/partner-img3.jpg" alt=""></a></li>
								<li><a href="services"><img src="extra-images/partner-img4.jpg" alt=""></a></li>
							</ul>
						</div>
						<p style="text-align:center;font-style:italic;margin-top:10px;">Partner and brand logos can be added as collaborations are confirmed.</p>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-reserve-tablefull">
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-reserve-table" style="float:none;width:100%;text-align:center;">
							<div class="hotmeal-fancy-title">
								<span>Final Call</span>
								<h2>Your Occasion Deserves More Than Just Food</h2>
							</div>
							<p>It deserves an experience. Tell us about your event, your vision and what you would like your guests to experience.</p>
							<a href="reservations" class="hotmeal-learn-btn">Book Njogu Nderitu <span class="btn-shape"></span></a>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

	</div>
	<!--// Main Content \\-->

<?php require __DIR__ . '/includes/footer.php'; ?>
