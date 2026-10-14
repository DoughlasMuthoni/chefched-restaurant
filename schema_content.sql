-- CMS content tables for dashboard-managed site content.
-- Run this once via phpMyAdmin's SQL tab against the same database as schema.sql.
-- Seed data below mirrors the copy already live on the site today, so nothing
-- changes visually right after migration — the dashboard becomes the source
-- of truth going forward.

CREATE TABLE IF NOT EXISTS site_content (
  content_key VARCHAR(60) PRIMARY KEY,
  value JSON NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  eyebrow VARCHAR(100),
  title VARCHAR(160) NOT NULL,
  description TEXT,
  sub_line VARCHAR(255),
  cta_label VARCHAR(80),
  cta_href VARCHAR(160),
  image_path VARCHAR(255),
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS gallery_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caption VARCHAR(160),
  image_path VARCHAR(255) NOT NULL,
  categories VARCHAR(255),
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blog_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) UNIQUE NOT NULL,
  excerpt TEXT,
  body LONGTEXT,
  category VARCHAR(80),
  cover_image_path VARCHAR(255),
  published_at DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_blog_posts_published ON blog_posts(published_at);

-- ---------------------------------------------------------------------------
-- Seed: site_content
-- ---------------------------------------------------------------------------

INSERT INTO site_content (content_key, value) VALUES (
  'contact_info',
  '{
    "phone": "+254 XXX XXX XXX",
    "whatsapp": "+254 XXX XXX XXX",
    "email": "chefched@example.com",
    "instagram_url": "https://www.instagram.com/chefchednjogu/",
    "tiktok_url": "https://www.tiktok.com/@chefchednjogu",
    "facebook_url": "https://www.facebook.com/chefchednjogu"
  }'
);

INSERT INTO site_content (content_key, value) VALUES (
  'homepage',
  '{
    "hero": [
      {
        "image": "extra-images/banner-1.jpg",
        "alt": "Njogu Nderitu plating a dish",
        "years_badge": "7",
        "years_label": "Yrs",
        "h1": "Njogu Nderitu",
        "h2": "Creating Memorable Experiences",
        "h3": "One Plate At A Time",
        "paragraph": "A Kenyan chef with a passion for exceptional food, creativity and memorable culinary experiences.",
        "btn1_label": "Book Njogu Nderitu", "btn1_href": "reservations",
        "btn2_label": "Explore His Journey", "btn2_href": "about-us"
      },
      {
        "image": "extra-images/banner-2.jpg",
        "alt": "Private dining table set by Njogu Nderitu",
        "years_badge": "7",
        "years_label": "Yrs",
        "h1": "More Than A Chef",
        "h2": "A Storyteller Through Food",
        "h3": "Private Dining. Events. Corporate.",
        "paragraph": "Whether it''s an intimate dinner, a celebration, a corporate event or a culinary collaboration, Njogu brings professionalism, passion and attention to detail to every table.",
        "btn1_label": "Book Njogu Nderitu", "btn1_href": "reservations",
        "btn2_label": "View Services", "btn2_href": "services"
      }
    ],
    "chef_intro": {
      "eyebrow": "More Than A Chef",
      "heading": "A Storyteller Through Food",
      "paragraph1": "Food has the power to bring people together, create memories and tell stories. For Njogu Nderitu, cooking is more than preparing a meal — it is about transforming quality ingredients into experiences that people remember.",
      "paragraph2": "Over the past seven years, Njogu has developed his craft through hands-on culinary experience, continuous learning and a passion for creating food that combines flavour, presentation and personality.",
      "image": "extra-images/chef-ched-portrait.jpg",
      "stat_badge": "7 Years Experience",
      "chef_name": "Njogu Nderitu",
      "chef_tagline": "Kenyan Chef & Culinary Storyteller"
    },
    "philosophy": {
      "eyebrow": "The Culinary Philosophy",
      "heading": "Simple Ingredients. Bold Ideas. Exceptional Experiences.",
      "intro": "Njogu believes great food starts with quality ingredients and is elevated through technique, creativity and attention to detail. His cooking is inspired by the richness of Kenyan food culture while embracing contemporary culinary ideas and international influences.",
      "image": "extra-images/service-grid-thumb.jpg",
      "pillars": [
        {"icon": "hotmeal-dish", "heading": "Purpose", "text": "Every plate has a purpose."},
        {"icon": "hotmeal-quote", "heading": "Story", "text": "Every experience should tell a story."},
        {"icon": "hotmeal-cutlery", "heading": "Craving", "text": "Every guest should leave wanting another bite."}
      ]
    }
  }'
);

-- ---------------------------------------------------------------------------
-- Seed: services (from services.html)
-- ---------------------------------------------------------------------------

INSERT INTO services (eyebrow, title, description, sub_line, cta_label, cta_href, image_path, sort_order) VALUES
('Private Chef Experiences', 'A Professional Kitchen, Brought To You',
 'Bring a professional culinary experience directly to your home, villa or private venue. From menu planning and preparation to presentation and service, Njogu Nderitu creates personalised dining experiences designed around you and your guests.',
 'Perfect for: intimate dinners, birthdays, anniversaries, family celebrations and special occasions.',
 'Enquire About Private Dining', 'reservations', 'extra-images/chef-ched-portrait.jpg', 1),

('Event Catering', 'Exceptional Food For Occasions That Matter',
 'Njogu Nderitu provides professional culinary services designed to complement your event, from menu development to preparation and presentation.',
 'Ideal for: weddings, celebrations, parties, launches and special events.',
 'Plan Your Event', 'reservations', 'extra-images/shop-detail-img1.jpg', 2),

('Corporate Catering', 'Professional Food For Professional Occasions',
 'Create a memorable experience for your team, clients or guests with carefully prepared menus and dependable culinary service.',
 'Ideal for: corporate meetings, conferences, launches, team events and executive functions.',
 'Make An Enquiry', 'reservations', 'extra-images/shop-detail-img2.jpg', 3),

('Culinary Collaborations', 'Good Food Creates Great Partnerships',
 'Njogu Nderitu works with hospitality businesses, food brands, event organisers and creative teams to develop culinary experiences and content that connect with audiences.',
 NULL,
 'Partner With Njogu Nderitu', 'reservations', 'extra-images/shop-detail-img3.jpg', 4);

-- ---------------------------------------------------------------------------
-- Seed: gallery_items (from gallery.html)
-- ---------------------------------------------------------------------------

INSERT INTO gallery_items (caption, image_path, categories, sort_order) VALUES
('Plated Creation', 'extra-images/modern-gallery-img1.jpg', 'food', 1),
('Private Dining', 'extra-images/modern-gallery-img2.jpg', 'private-dining chef-portraits', 2),
('Signature Plating', 'extra-images/modern-gallery-img3.jpg', 'food', 3),
('Event Service', 'extra-images/modern-gallery-img4.jpg', 'events chef-portraits', 4),
('In The Kitchen', 'extra-images/modern-gallery-img5.jpg', 'behind-scenes', 5),
('Dish Detail', 'extra-images/modern-gallery-img6.jpg', 'food', 6),
('Preparation', 'extra-images/modern-gallery-img7.jpg', 'behind-scenes', 7),
('Celebration Setup', 'extra-images/modern-gallery-img8.jpg', 'private-dining events', 8),
('Njogu Nderitu At Work', 'extra-images/chef-ched-portrait.jpg', 'chef-portraits', 9);

-- ---------------------------------------------------------------------------
-- Seed: blog_posts (from blog-grid.html + blog-detail.html)
-- Only the first post has real long-form content today (blog-detail.html is
-- currently one generic static page); the other three get their existing
-- excerpt plus an honest "more coming soon" placeholder instead of fabricated
-- long-form copy.
-- ---------------------------------------------------------------------------

INSERT INTO blog_posts (title, slug, excerpt, body, category, cover_image_path, published_at) VALUES
('The Art Behind A Great Plate', 'the-art-behind-a-great-plate',
 'What turns an ordinary meal into a memorable culinary experience?',
 '<p>What turns an ordinary meal into a memorable culinary experience? It rarely comes down to a single ingredient or technique. It is the combination of quality produce, thoughtful preparation, confident presentation and the atmosphere around the table that transforms a dish into a story worth remembering.</p><p>For Njogu Nderitu, every plate begins with a question: what should the guest feel when this arrives in front of them? That question shapes everything that follows — the choice of ingredients, the balance of flavours, and the way the dish is finally presented.</p><blockquote>Every plate has a purpose. Every experience should tell a story.<h6>~ Njogu Nderitu</h6><span>Personal Chef</span></blockquote><p>Great plating is not decoration for its own sake — it is communication. It tells the guest what to expect before the first bite, and it is one more way a chef can make an ordinary moment feel intentional and special.</p>',
 'Cooking', 'extra-images/blog-thumb-img.jpg', '2026-01-01'),

('Celebrating Kenyan Ingredients', 'celebrating-kenyan-ingredients',
 'Discovering the ingredients, flavours and food traditions that make Kenya unique.',
 '<p>Discovering the ingredients, flavours and food traditions that make Kenya unique.</p><p><em>Njogu Nderitu will be expanding this story soon — check back for the full journal entry.</em></p>',
 'Kenyan Ingredients', 'extra-images/blog-grid-img2.jpg', '2026-01-01'),

('Life Behind The Kitchen Door', 'life-behind-the-kitchen-door',
 'A look at the discipline, creativity and passion behind professional cooking.',
 '<p>A look at the discipline, creativity and passion behind professional cooking.</p><p><em>Njogu Nderitu will be expanding this story soon — check back for the full journal entry.</em></p>',
 'Behind The Scenes', 'extra-images/blog-grid-img3.jpg', '2026-01-01'),

('From Ingredient To Experience', 'from-ingredient-to-experience',
 'How an idea becomes a finished dish.',
 '<p>How an idea becomes a finished dish.</p><p><em>Njogu Nderitu will be expanding this story soon — check back for the full journal entry.</em></p>',
 'Cooking', 'extra-images/blog-grid-img4.jpg', '2026-01-01');
