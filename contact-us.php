<?php
$pageTitle = 'Contact | Njogu Nderitu';
$pageDescription = "Let's talk food. Get in touch with Njogu Nderitu for bookings, collaborations, events and general enquiries.";
$activeNav = 'contact';
require __DIR__ . '/includes/header.php';
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Contact</span>
                		<h1>Let's Talk Food</h1>
                		<p>For bookings, collaborations, events and general enquiries.</p>
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
				            <li class="active">Contact</li>
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
		<div class="hotmeal-main-section hotmeal-contact-full">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="hotmeal-contact-us">
							<div class="hotmeal-contact-info">
								<ul>
									<li>
										<div class="hotmeal-contact-info-wrap">
											<i class="icon hotmeal-telephone"></i>
											<small></small>
											<h5>Call / WhatsApp</h5>
											<p><?= htmlspecialchars($phone) ?></p>
											<p><?= htmlspecialchars($whatsapp) ?></p>
										</div>
									</li>
									<li>
										<div class="hotmeal-contact-info-wrap">
											<i class="icon hotmeal-close-envelope"></i>
											<small></small>
											<h5>Mail Us At</h5>
											<a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
										</div>
									</li>
									<li>
										<div class="hotmeal-contact-info-wrap">
											<i class="icon hotmeal-location-pin"></i>
											<small></small>
											<h5>Based In</h5>
											<p>Kenya</p>
										</div>
									</li>
								</ul>
							</div>
							<div class="hotmeal-contact-form">
								<form method="post" action="api/contact.php" class="myform" novalidate>
									<ul>
										<li>
											<input name="name" placeholder="Name*" tabindex="0" type="text" required>
										</li>
										<li>
											<input name="phone" placeholder="Phone / WhatsApp" tabindex="0" type="text">
										</li>
										<li>
											<input name="email" placeholder="Email*" tabindex="0" type="email" required>
										</li>
										<li class="full">
											<textarea placeholder="Tell us about your enquiry*" name="message" required></textarea>
										</li>
										<li class="hotmeal-hp-field" aria-hidden="true">
											<input type="text" name="website" tabindex="-1" autocomplete="off">
										</li>
										<li class="full">
											<label>
												<input type="submit" name="submit" value="Send Now">
												<small></small>
											</label>
											<span class="output_message"></span>
										</li>
									</ul>
								</form>
							</div>
						</div>
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
							<p>It deserves an experience.</p>
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
