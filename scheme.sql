USE moneyhub;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS periods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    period_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type TINYINT(1) NOT NULL COMMENT '0 = expense, 1 = income',
    category_id INT,
    period_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE SET NULL
);

-- samples
INSERT INTO categories (cat_name) VALUES 
('Groceries'),
('Transportation'),
('Utilities'),
('Entertainment'),
('Healthcare'),
('Salary'),
('Freelance'),
('Investment'),
('Other');

INSERT INTO periods (period_name) VALUES 
('January 2025'),
('February 2025'),
('March 2025'),
('April 2025'),
('May 2025'),
('June 2025'),
('July 2025'),
('August 2025'),
('September 2025'),
('October 2025'),
('November 2025'),
('December 2025');

INSERT INTO items (item_name, amount, type, category_id, period_id) VALUES 
('Monthly Salary', 3500.00, 1, 6, 7),
('Grocery Shopping', 125.50, 0, 1, 7),
('Gas Bill', 89.25, 0, 3, 7),
('Movie Night', 45.00, 0, 4, 7),
('Freelance Project', 500.00, 1, 7, 7);