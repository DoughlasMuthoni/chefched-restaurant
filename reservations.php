<?php
$pageTitle = 'Reservations | Njogu Nderitu';
$pageDescription = 'Have an occasion worth remembering? Tell us about your event and book Njogu Nderitu.';
$activeNav = 'reservations';
require __DIR__ . '/includes/header.php';
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Book Njogu Nderitu</span>
                		<h1>Have An Occasion Worth Remembering?</h1>
                		<p>Tell us about your event, your vision and what you would like your guests to experience.</p>
                	</div>
                </div>
            </div>
        </div>
        <div class="hotmeal-breadcrumb">
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
        				<ul>
				            <li><a href="/">Home</a></li>
				            <li class="active">Book Njogu Nderitu</li>
				        </ul>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
    <!--// SubHeader \\-->

	<!--// Main Content \\-->
	<div class="hotmeal-main-content">

		<!--// Main Section \\-->
		<div class="hotmeal-main-section">
			<div class="container">
				<div class="row">

					<div class="col-md-12">
						<div class="hotmeal-reserve-table hotmeal-reservation-wrap">
							<div class="hotmeal-fancy-title">
								<span>Work With Njogu Nderitu</span>
								<h2>Book Njogu Nderitu</h2>
								<img src="images/fancy-title-img.png" alt="">
							</div>
							<form class="myform" action="api/enquiry.php" method="post" novalidate>
								<ul>
									<li>
										<input type="text" name="name" placeholder="Name*" required>
									</li>
									<li>
										<input type="email" name="email" placeholder="Email*" required>
									</li>
									<li>
										<input type="text" name="phone" placeholder="Phone / WhatsApp*" required>
									</li>
									<li>
										<select name="event_type" required>
											<option value="">Event Type*</option>
											<option>Private Dinner</option>
											<option>Birthday / Anniversary</option>
											<option>Family Celebration</option>
											<option>Wedding</option>
											<option>Corporate Event</option>
											<option>Culinary Collaboration</option>
											<option>Other</option>
										</select>
									</li>
									<li>
										<input type="date" name="event_date" placeholder="Event Date">
									</li>
									<li>
										<input type="text" name="location" placeholder="Location*" required>
									</li>
									<li>
										<input type="number" min="1" name="guests" placeholder="Number Of Guests*" required>
									</li>
									<li>
										<select name="service_required" required>
											<option value="">Service Required*</option>
											<option>Private Chef Experience</option>
											<option>Event Catering</option>
											<option>Corporate Catering</option>
											<option>Culinary Collaboration</option>
											<option>Not Sure Yet</option>
										</select>
									</li>
									<li class="full-form"><textarea name="message" placeholder="Tell Us About Your Event*"></textarea></li>
									<li class="hotmeal-hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></li>
									<li><label class="submit"><input type="submit" value="Send Enquiry"><span></span></label></li>
									<li class="full-form"><span class="output_message"></span></li>
								</ul>
							</form>
						</div>
					</div>
					<div class="col-md-6">
						<div class="hotmeal-reservation-info">
							<div class="hotmeal-fancy-title">
								<span>Let's Talk Food</span>
								<h2>Contact Njogu Nderitu</h2>
							</div>
							<p>For bookings, collaborations, events and general enquiries, reach out directly or send the form on this page.</p>
							<ul>
								<li>
									<h6>Call Us At</h6>
									<p><?= htmlspecialchars($phone) ?></p>
								</li>
								<li>
									<h6>WhatsApp</h6>
									<p><?= htmlspecialchars($whatsapp) ?></p>
								</li>
								<li>
									<h6>Mail Us At</h6>
									<a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
								</li>
								<li>
									<h6>Follow Us</h6>
									<ul class="hotmeal-reservation-social">
	                                    <li><a href="<?= htmlspecialchars($instagram) ?>" class="fa fa-instagram"></a></li>
										<li><a href="<?= htmlspecialchars($tiktok) ?>" class="fa fa-music"></a></li>
										<li><a href="<?= htmlspecialchars($facebook) ?>" class="fa fa-facebook-square"></a></li>
	                                </ul>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="hotmeal-reservation-twitter">
							<div class="hotmeal-fancy-title">
								<span>Perfect For</span>
								<h2>Occasions Njogu Nderitu Serves</h2>
							</div>
							<p>Whether it's an intimate private dining experience, a special celebration, a corporate event or a culinary collaboration, Njogu brings professionalism, passion and attention to detail to every table.</p>
							<ul>
								<li>
									<h6><i class="icon hotmeal-chef-hat"></i> Private Dining</h6>
									<p>Intimate dinners, birthdays, anniversaries, family celebrations and special occasions.</p>
								</li>
								<li>
									<h6><i class="icon hotmeal-cupcake"></i> Events &amp; Celebrations</h6>
									<p>Weddings, celebrations, parties, launches and special events.</p>
								</li>
								<li>
									<h6><i class="icon hotmeal-office"></i> Corporate</h6>
									<p>Corporate meetings, conferences, launches, team events and executive functions.</p>
								</li>
							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

	</div>
	<!--// Main Content \\-->

<?php require __DIR__ . '/includes/footer.php'; ?>
