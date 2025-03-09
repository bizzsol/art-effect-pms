ALTER TABLE requisitions
ADD COLUMN assigned_finance_id BIGINT UNSIGNED NULL AFTER is_po_generate;

ALTER TABLE requisitions ADD CONSTRAINT fk_assigned_finance FOREIGN KEY (assigned_finance_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE;