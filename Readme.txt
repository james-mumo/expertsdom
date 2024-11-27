do as below



ALTER TABLE assignments
ADD COLUMN order_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
ADD COLUMN amount_paid DECIMAL(10, 2) NOT NULL DEFAULT 0;
