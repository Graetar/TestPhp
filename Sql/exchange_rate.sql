CREATE TABLE IF NOT EXISTS exchange_rate (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code_from VARCHAR(3) NOT NULL,
    code_to VARCHAR(3) NOT NULL,
    rate FLOAT,
    rate_update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_code_from FOREIGN KEY (code) REFERENCES currency(code),
    CONSTRAINT FK_code_to FOREIGN KEY (code) REFERENCES currency(code)
);