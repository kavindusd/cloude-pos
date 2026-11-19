-- =============================================================
-- poss_php — Seed Data
-- =============================================================
USE poss_php;

-- Super admin (password: superadmin123)
-- Generate hash: php -r "echo password_hash('superadmin123', PASSWORD_BCRYPT);"
-- Replace hash before running
REPLACE INTO users (name, email, password, role) VALUES
('Super Admin', 'superadmin@poss.local', '$2y$10$20szMnebNLVkoyRnEDk51O1Kne3iNGr7yIpqw7M28XPBPyDrgMbcm', 'super_admin');

-- Default accounts
REPLACE INTO accounts (id, type, name, balance) VALUES
(1, 'cash', 'Main Cash Drawer', 0.00),
(2, 'bank', 'Main Bank Account', 0.00);

-- Sample categories
REPLACE INTO categories (id, name) VALUES
(1, 'Beverages'),
(2, 'Snacks'),
(3, 'Electronics'),
(4, 'Groceries');

-- Sample supplier
REPLACE INTO suppliers (id, name, phone, email) VALUES
(1, 'Sample Supplier Co.', '+94 11 234 5678', 'supplier@example.com');