-- =============================================================
-- poss_php — Full Database Schema
-- =============================================================

-- USE poss_php; (Commented out for Shared Hosting Compatibility)

-- =============================================================
-- 1. USERS
-- =============================================================

CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('super_admin','admin','manager','stock_keeper','cashier') NOT NULL DEFAULT 'cashier',
    active     TINYINT(1)    NOT NULL DEFAULT 1,
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- =============================================================
-- 2. MONEY ACCOUNTS
-- =============================================================

CREATE TABLE IF NOT EXISTS accounts (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type       ENUM('cash','bank') NOT NULL,
    name       VARCHAR(100)        NOT NULL,
    balance    DECIMAL(15,2)       NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS account_transactions (
    id             INT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
    account_id     INT UNSIGNED     NOT NULL,
    direction      ENUM('in','out') NOT NULL,
    amount         DECIMAL(15,2)    NOT NULL,
    reference_type ENUM('purchase','sale','credit_settlement','adjustment') NOT NULL,
    reference_id   INT UNSIGNED     DEFAULT NULL,
    note           TEXT             DEFAULT NULL,
    created_by     INT UNSIGNED     DEFAULT NULL,
    created_at     TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================
-- 3. SUPPLIERS
-- =============================================================

CREATE TABLE IF NOT EXISTS suppliers (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    phone      VARCHAR(30)  DEFAULT NULL,
    email      VARCHAR(150) DEFAULT NULL,
    address    TEXT         DEFAULT NULL,
    active     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- =============================================================
-- 4. CATEGORIES & PRODUCTS
-- =============================================================

CREATE TABLE IF NOT EXISTS categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    description TEXT         DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS products (
    id              INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED  DEFAULT NULL,
    supplier_id     INT UNSIGNED  DEFAULT NULL,
    name            VARCHAR(150)  NOT NULL,
    barcode         VARCHAR(100)  DEFAULT NULL UNIQUE,
    buying_price    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    price_retail    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    price_wholesale DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    image           VARCHAR(255)  DEFAULT NULL,
    active          TINYINT(1)    NOT NULL DEFAULT 1,
    created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

-- =============================================================
-- 5. PURCHASES (buying from suppliers)
-- =============================================================

CREATE TABLE IF NOT EXISTS purchases (
    id             INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    supplier_id    INT UNSIGNED  NOT NULL,
    account_id     INT UNSIGNED  DEFAULT NULL,
    payment_method ENUM('cash','bank','credit') NOT NULL,
    total          DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status         ENUM('pending', 'received', 'cancelled') NOT NULL DEFAULT 'pending',
    note           TEXT          DEFAULT NULL,
    created_by     INT UNSIGNED  DEFAULT NULL,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (account_id)  REFERENCES accounts(id)  ON DELETE SET NULL,
    FOREIGN KEY (created_by)  REFERENCES users(id)     ON DELETE SET NULL
);

-- =============================================================
-- 6. BATCHES
-- =============================================================

CREATE TABLE IF NOT EXISTS batches (
    id            INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    product_id    INT UNSIGNED  NOT NULL,
    purchase_id   INT UNSIGNED  DEFAULT NULL,
    batch_number  VARCHAR(100)  NOT NULL,
    qty_received  INT UNSIGNED  NOT NULL DEFAULT 0,
    qty_remaining INT UNSIGNED  NOT NULL DEFAULT 0,
    cost_price    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    purchase_date DATE          NOT NULL,
    note          TEXT          DEFAULT NULL,
    created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id)  REFERENCES products(id)  ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE SET NULL
);

-- =============================================================
-- 7. PURCHASE ITEMS
-- =============================================================

CREATE TABLE IF NOT EXISTS purchase_items (
    id          INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT UNSIGNED  NOT NULL,
    product_id  INT UNSIGNED  DEFAULT NULL,
    batch_id    INT UNSIGNED  DEFAULT NULL,
    qty         INT UNSIGNED  NOT NULL DEFAULT 1,
    unit_cost   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    subtotal    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id)  ON DELETE CASCADE,
    FOREIGN KEY (product_id)  REFERENCES products(id)   ON DELETE SET NULL,
    FOREIGN KEY (batch_id)    REFERENCES batches(id)    ON DELETE SET NULL
);

-- =============================================================
-- 8. STOCK SUMMARY
-- =============================================================

CREATE TABLE IF NOT EXISTS stock (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id    INT UNSIGNED NOT NULL UNIQUE,
    qty_total     INT UNSIGNED NOT NULL DEFAULT 0,
    low_threshold INT UNSIGNED NOT NULL DEFAULT 5,
    updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =============================================================
-- 9. CUSTOMERS
-- =============================================================

CREATE TABLE IF NOT EXISTS customers (
    id           INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150)  NOT NULL,
    phone        VARCHAR(30)   DEFAULT NULL,
    email        VARCHAR(150)  DEFAULT NULL,
    address      TEXT          DEFAULT NULL,
    type         ENUM('retail','wholesale') NOT NULL DEFAULT 'retail',
    credit_limit DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    credit_used  DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    active       TINYINT(1)    NOT NULL DEFAULT 1,
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- =============================================================
-- 10. SALES
-- =============================================================

CREATE TABLE IF NOT EXISTS sales (
    id             INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    customer_id    INT UNSIGNED  DEFAULT NULL,
    created_by     INT UNSIGNED  DEFAULT NULL,
    price_type     ENUM('retail','wholesale') NOT NULL DEFAULT 'retail',
    subtotal       DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    discount       DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    tax            DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total          DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    payment_method ENUM('cash','bank','credit') NOT NULL DEFAULT 'cash',
    account_id     INT UNSIGNED  DEFAULT NULL,
    status         ENUM('completed','voided') NOT NULL DEFAULT 'completed',
    note           TEXT          DEFAULT NULL,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by)  REFERENCES users(id)     ON DELETE SET NULL,
    FOREIGN KEY (account_id)  REFERENCES accounts(id)  ON DELETE SET NULL
);

-- =============================================================
-- 11. SALE ITEMS
-- =============================================================

CREATE TABLE IF NOT EXISTS sale_items (
    id         INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    sale_id    INT UNSIGNED  NOT NULL,
    product_id INT UNSIGNED  DEFAULT NULL,
    batch_id   INT UNSIGNED  DEFAULT NULL,
    qty        INT UNSIGNED  NOT NULL DEFAULT 1,
    unit_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    subtotal   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (sale_id)    REFERENCES sales(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (batch_id)   REFERENCES batches(id)  ON DELETE SET NULL
);

-- =============================================================
-- 12. SUPPLIER CREDITS
-- =============================================================

CREATE TABLE IF NOT EXISTS supplier_credits (
    id               INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    supplier_id      INT UNSIGNED  NOT NULL,
    purchase_id      INT UNSIGNED  NOT NULL UNIQUE,
    original_amount  DECIMAL(15,2) NOT NULL,
    amount_remaining DECIMAL(15,2) NOT NULL,
    status           ENUM('unpaid','partial','settled') NOT NULL DEFAULT 'unpaid',
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS supplier_credit_payments (
    id                 INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    supplier_credit_id INT UNSIGNED  NOT NULL,
    account_id         INT UNSIGNED  NOT NULL,
    amount             DECIMAL(15,2) NOT NULL,
    note               TEXT          DEFAULT NULL,
    created_by         INT UNSIGNED  DEFAULT NULL,
    created_at         TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_credit_id) REFERENCES supplier_credits(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id)         REFERENCES accounts(id),
    FOREIGN KEY (created_by)         REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================
-- 13. CUSTOMER CREDITS
-- =============================================================

CREATE TABLE IF NOT EXISTS customer_credits (
    id               INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    customer_id      INT UNSIGNED  NOT NULL,
    sale_id          INT UNSIGNED  NOT NULL UNIQUE,
    original_amount  DECIMAL(15,2) NOT NULL,
    amount_remaining DECIMAL(15,2) NOT NULL,
    status           ENUM('unpaid','partial','settled') NOT NULL DEFAULT 'unpaid',
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (sale_id)     REFERENCES sales(id)     ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS customer_credit_payments (
    id                 INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    customer_credit_id INT UNSIGNED  NOT NULL,
    account_id         INT UNSIGNED  NOT NULL,
    amount             DECIMAL(15,2) NOT NULL,
    note               TEXT          DEFAULT NULL,
    created_by         INT UNSIGNED  DEFAULT NULL,
    created_at         TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_credit_id) REFERENCES customer_credits(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id)         REFERENCES accounts(id),
    FOREIGN KEY (created_by)         REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================
-- 14. NOTIFICATIONS
-- =============================================================

CREATE TABLE IF NOT EXISTS notifications (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    to_user_id     INT UNSIGNED NOT NULL,
    from_user_id   INT UNSIGNED DEFAULT NULL,
    type           VARCHAR(50)  NOT NULL,           -- e.g. 'low_stock', 'system'
    message        TEXT         NOT NULL,
    reference_type VARCHAR(50)  DEFAULT NULL,       -- e.g. 'product', 'stock'
    reference_id   INT UNSIGNED DEFAULT NULL,
    is_read        TINYINT(1)   NOT NULL DEFAULT 0,
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (to_user_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================
-- 15. ACTIVITY LOG
-- =============================================================

CREATE TABLE IF NOT EXISTS activity_log (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED NOT NULL,
    action         VARCHAR(100) NOT NULL,           -- e.g. 'sale_created', 'login', 'logout'
    reference_type VARCHAR(50)  DEFAULT NULL,       -- e.g. 'sale', 'product'
    reference_id   INT UNSIGNED DEFAULT NULL,
    ip_address     VARCHAR(45)  DEFAULT NULL,
    note           TEXT         DEFAULT NULL,
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);