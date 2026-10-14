<?php
require_once __DIR__ . '/api/db.php';
$pageTitle = 'Services | Njogu Nderitu';
$pageDescription = 'Private chef experiences, event catering, corporate catering and culinary collaborations with Njogu Nderitu.';
$activeNav = 'services';
require __DIR__ . '/includes/header.php';

$services = db()->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Services</span>
                		<h1>Let's Create Something Memorable</h1>
                		<p>Private dining, events, corporate catering and culinary collaborations.</p>
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
				            <li class="active">Services</li>
				        </ul>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
    <!--// SubHeader \\-->

	<!--// Main Content \\-->
	<div class="hotmeal-main-content">

		<?php foreach ($services as $i => $service): ?>
		<!--// Main Section \\-->
		<div class="hotmeal-main-section hotmeal-aboutus-full">
			<div class="container">
				<div class="row">

					<?php if ($i % 2 === 0): ?>
					<div class="col-md-6">
						<div class="hotmeal-about-text">
							<div class="hotmeal-fancy-title">
								<span><?= htmlspecialchars($service['eyebrow']) ?></span>
								<h2><?= htmlspecialchars($service['title']) ?></h2>
							</div>
							<p><?= htmlspecialchars($service['description']) ?></p>
							<?php if (!empty($service['sub_line'])): ?><p><em><?= htmlspecialchars($service['sub_line']) ?></em></p><?php endif; ?>
							<a href="<?= htmlspecialchars($service['cta_href']) ?>" class="hotmeal-learn-btn"><?= htmlspecialchars($service['cta_label']) ?> <span class="btn-shape"></span></a>
						</div>
					</div>
					<div class="col-md-6">
						<img class="about-thumb" src="<?= htmlspecialchars($service['image_path']) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
					</div>
					<?php else: ?>
					<div class="col-md-6">
						<img class="about-thumb" src="<?= htmlspecialchars($service['image_path']) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
					</div>
					<div class="col-md-6">
						<div class="hotmeal-about-text">
							<div class="hotmeal-fancy-title">
								<span><?= htmlspecialchars($service['eyebrow']) ?></span>
								<h2><?= htmlspecialchars($service['title']) ?></h2>
							</div>
							<p><?= htmlspecialchars($service['description']) ?></p>
							<?php if (!empty($service['sub_line'])): ?><p><em><?= htmlspecialchars($service['sub_line']) ?></em></p><?php endif; ?>
							<a href="<?= htmlspecialchars($service['cta_href']) ?>" class="hotmeal-learn-btn"><?= htmlspecialchars($service['cta_label']) ?> <span class="btn-shape"></span></a>
						</div>
					</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->
		<?php endforeach; ?>

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
