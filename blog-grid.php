<?php
require_once __DIR__ . '/api/db.php';
$pageTitle = "The Chef's Journal | Njogu Nderitu";
$pageDescription = 'Stories from the kitchen — recipes, culinary inspiration, food stories and behind-the-scenes moments from Njogu Nderitu.';
$activeNav = 'journal';
require __DIR__ . '/includes/header.php';

$posts = db()->query('SELECT * FROM blog_posts ORDER BY published_at DESC, id DESC')->fetchAll();
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Journal</span>
                		<h1>The Chef's Journal</h1>
                		<p>Stories from the kitchen — recipes, culinary inspiration, food stories and behind-the-scenes moments.</p>
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
				            <li class="active">Journal</li>
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
						<?php if (empty($posts)): ?>
						<p class="muted">No journal entries yet — check back soon.</p>
						<?php else: ?>
						<div class="hotmeal-blog hotmeal-blog-grid">
							<ul class="row">
								<?php foreach ($posts as $post): $postUrl = 'blog/' . rawurlencode($post['slug']); ?>
								<li class="col-md-6">
									<div class="hotmeal-blog-grid-wrap">
										<figure><a href="<?= htmlspecialchars($postUrl) ?>"><img src="<?= htmlspecialchars($post['cover_image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>"><span class="hover-btn"><i class="icon hotmeal-link"></i><small></small></span></a></figure>
										<div class="hotmeal-blog-grid-text">
											<span><?= htmlspecialchars($post['category']) ?></span>
											<time datetime="<?= htmlspecialchars($post['published_at']) ?>">The Chef's Journal by <a href="about-us">Njogu Nderitu</a></time>
											<h2><a href="<?= htmlspecialchars($postUrl) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
											<p><?= htmlspecialchars($post['excerpt']) ?></p>
											<a href="<?= htmlspecialchars($postUrl) ?>" class="hotmeal-simple-btn">Read More <i class="fa fa-angle-double-right"></i></a>
										</div>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
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
