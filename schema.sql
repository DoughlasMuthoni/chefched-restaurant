-- Njogu Nderitu — production schema (MySQL / phpMyAdmin)
-- Run this once against the database created via cPanel's MySQL Databases tool.

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS enquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  event_type VARCHAR(60),
  event_date DATE NULL,
  location VARCHAR(190),
  guests VARCHAR(20),
  service_required VARCHAR(60),
  message TEXT,
  status ENUM('new','contacted','booked','closed') NOT NULL DEFAULT 'new',
  whatsapp_sent TINYINT(1) NOT NULL DEFAULT 0,
  email_sent TINYINT(1) NOT NULL DEFAULT 0,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40),
  message TEXT NOT NULL,
  status ENUM('new','read','responded') NOT NULL DEFAULT 'new',
  whatsapp_sent TINYINT(1) NOT NULL DEFAULT 0,
  email_sent TINYINT(1) NOT NULL DEFAULT 0,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS submission_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ip_address VARCHAR(45) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_enquiries_status ON enquiries(status);
CREATE INDEX idx_enquiries_created ON enquiries(created_at);
CREATE INDEX idx_messages_status ON contact_messages(status);
CREATE INDEX idx_messages_created ON contact_messages(created_at);
CREATE INDEX idx_submission_log_ip_time ON submission_log(ip_address, created_at);
