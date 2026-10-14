<?php
require_once __DIR__ . '/api/db.php';
$pdo = db();

$slug = trim((string) ($_GET['slug'] ?? ''));
$post = null;
if ($slug !== '') {
    $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE slug = ?');
    $stmt->execute([$slug]);
    $post = $stmt->fetch() ?: null;
}

$pageTitle = $post ? $post['title'] . ' | Njogu Nderitu' : "Journal Entry Not Found | Njogu Nderitu";
$pageDescription = $post ? $post['excerpt'] : '';
$activeNav = 'journal';
require __DIR__ . '/includes/header.php';

$recentStmt = $pdo->prepare('SELECT title, slug FROM blog_posts WHERE id != ? ORDER BY published_at DESC LIMIT 3');
$recentStmt->execute([$post['id'] ?? 0]);
$recentPosts = $recentStmt->fetchAll();

$categories = $pdo->query(
    "SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != '' ORDER BY category"
)->fetchAll(PDO::FETCH_COLUMN);
?>

	<!--// SubHeader \\-->
	<div class="hotmeal-subheader">
		<span class="hotmeal-subheader-transparent"></span>
		<div class="hotmeal-subheader-text">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                	<span>Journal</span>
	                	<?php if ($post): ?>
                		<h1><?= htmlspecialchars($post['title']) ?></h1>
                		<p><?= htmlspecialchars($post['excerpt']) ?></p>
                		<?php else: ?>
                		<h1>Journal Entry Not Found</h1>
                		<p>That story may have moved — take a look at the journal instead.</p>
                		<?php endif; ?>
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
				            <li><a href="blog-grid">Journal</a></li>
				            <?php if ($post): ?><li class="active"><?= htmlspecialchars($post['title']) ?></li><?php endif; ?>
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

					<?php if (!$post): ?>
					<div class="col-md-12" style="text-align:center;padding:40px 0;">
						<p><a href="blog-grid" class="hotmeal-learn-btn">Back To The Journal <span class="btn-shape"></span></a></p>
					</div>
					<?php else: ?>
					<div class="col-md-9">
						<figure class="hotmeal-blog-thumb"><img src="<?= htmlspecialchars($post['cover_image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>"></figure>
						<div class="hotmeal-blog-heading">
							<h2><?= htmlspecialchars($post['title']) ?></h2>
							<ul class="hotmeal-blog-option">
								<li>Posted By: <a href="about-us">Njogu Nderitu</a></li>
							</ul>
						</div>
						<div class="hotmeal-rich-editor">
							<?= $post['body'] /* deliberately raw — the only admin user (Njogu Nderitu, authenticated) authors this HTML; no WYSIWYG/escaping by design */ ?>
						</div>
						<?php if (!empty($post['category'])): ?>
						<div class="hotmeal-post-tags">
	                            <div class="hotmeal-social-tag">
	                                <a href="blog-grid"><?= htmlspecialchars($post['category']) ?> <span class="btn-shape"></span></a>
	                            </div>
	                            <div class="hotmeal-blog-social">
	                                <ul>
	                                    <li><a href="<?= htmlspecialchars($facebook) ?>" class="fa fa-facebook"></a></li>
										<li><a href="<?= htmlspecialchars($instagram) ?>" class="fa fa-instagram"></a></li>
										<li><a href="<?= htmlspecialchars($tiktok) ?>" class="fa fa-music"></a></li>
	                                </ul>
	                            </div>
	                        </div>
	                        <?php endif; ?>
	                        <?php if (!empty($recentPosts)): ?>
	                        <h2 class="hotmeal-section-heading">More From The Journal</h2>
	                        <div class="hotmeal-blog hotmeal-related-blog">
	                        	<ul class="row">
	                        		<?php foreach ($recentPosts as $related): ?>
	                        		<li class="col-md-4">
	                        			<div class="hotmeal-related-blog-text">
	                        				<h5><a href="blog/<?= rawurlencode($related['slug']) ?>"><?= htmlspecialchars($related['title']) ?></a></h5>
	                        			</div>
	                        		</li>
	                        		<?php endforeach; ?>
	                        	</ul>
	                        </div>
	                        <?php endif; ?>
					</div>

					<!--// Sidebar \\-->
                    <aside class="col-md-3">

                        <!--// Widget Author \\-->
                        <div class="widget widget_author">
                        	<figure><a href="about-us"><img src="extra-images/chef-ched-avatar.jpg" alt="Njogu Nderitu"></a></figure>
                        	<div class="widget-author-text">
                        		<h5><a href="about-us">Njogu Nderitu</a></h5>
                        		<p>A Kenyan chef with seven years of professional experience, creating memorable private dining, event, corporate and collaboration experiences.</p>
                        	</div>
                        </div>
                        <!--// Widget Author \\-->

                        <?php if (!empty($recentPosts)): ?>
                        <!--// Widget Popular Post \\-->
                        <div class="widget widget_popular_post">
                        	<h2 class="widget-heading">From The Journal</h2>
                        	<ul>
                        		<?php foreach ($recentPosts as $related): ?>
                        		<li>
                        			<h6><a href="blog/<?= rawurlencode($related['slug']) ?>"><?= htmlspecialchars($related['title']) ?></a></h6>
                        		</li>
                        		<?php endforeach; ?>
                        	</ul>
                        </div>
                        <!--// Widget Popular Post \\-->
                        <?php endif; ?>

                        <?php if (!empty($categories)): ?>
                        <!--// Widget Cetagories \\-->
                        <div class="widget widget_cetagories">
                        	<h2 class="widget-heading">Categories</h2>
                        	<ul>
                        		<?php foreach ($categories as $cat): ?>
                        		<li><a href="blog-grid"><?= htmlspecialchars($cat) ?></a></li>
                        		<?php endforeach; ?>
                        	</ul>
                        </div>
                        <!--// Widget Cetagories \\-->
                        <?php endif; ?>

                    </aside>
                    <!--// Sidebar \\-->
                    <?php endif; ?>

				</div>
			</div>
		</div>
		<!--// Main Section \\-->

	</div>
	<!--// Main Content \\-->

<?php require __DIR__ . '/includes/footer.php'; ?>
