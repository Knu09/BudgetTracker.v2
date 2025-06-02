-- +goose Up
-- +goose StatementBegin
CREATE TABLE budget (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(30) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- +goose StatementEnd

-- +goose Down
-- +goose StatementBegin
DROP TABLE budget;
-- +goose StatementEnd
