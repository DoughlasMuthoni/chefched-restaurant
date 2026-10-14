<?php
require_once __DIR__ . '/api/db.php';
$pageTitle = 'Gallery | Njogu Nderitu';
$pageDescription = 'Food. People. Experiences. A look behind the scenes into the world of Njogu Nderitu.';
$activeNav = 'gallery';
require __DIR__ . '/includes/header.php';

$items = db()->query('SELECT * FROM gallery_items ORDER BY sort_order ASC, id ASC')->fetchAll();

function category_label(string $slug): string {
    return ucwords(str_replace('-', ' ', $slug));
}

// Distinct categories across all items, in first-seen order, for the filter buttons.
$categories = [];
foreach ($items as $item) {
    foreach (array_filter(explode(' ', (string) $item['categories'])) as $slug) {
        $categories[$slug] = category_label($slug);
    }
}
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader hotmeal-gallery-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Gallery</span>
                		<h1>Food. People. Experiences.</h1>
                		<p>Take a look behind the scenes and discover the world of Njogu Nderitu.</p>
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
						<div class="hotmeal-gallery-filterable">
	                        <ul class="filters-button-group">
	                          	<li><a data-filter="*" class="is-checked" href="javascript:void(0)">All</a></li>
	                          	<?php foreach ($categories as $slug => $label): ?>
	                          	<li><a data-filter=".<?= htmlspecialchars($slug) ?>" href="javascript:void(0)"><?= htmlspecialchars($label) ?></a></li>
	                          	<?php endforeach; ?>
	                      	</ul>
	                  	</div>
						<div class="hotmeal-gallery hotmeal-modern-gallery hotmeal-gallery-filter">
							<ul class="row">
								<?php foreach ($items as $item):
									$firstCategory = explode(' ', (string) $item['categories'])[0] ?? '';
									$label = $firstCategory !== '' ? category_label($firstCategory) : '';
								?>
								<li class="col-md-4 element-item <?= htmlspecialchars($item['categories']) ?>">
									<div class="hotmeal-modern-gallery-wrap">
										<figure><a data-fancybox-group="group" href="<?= htmlspecialchars($item['image_path']) ?>" class="fancybox"><img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['caption']) ?>"></a>
											<figcaption>
												<h5><?= htmlspecialchars($item['caption']) ?></h5>
												<?php if ($label !== ''): ?><span><?= htmlspecialchars($label) ?></span><?php endif; ?>
											</figcaption>
										</figure>
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
