USE mandamel;

-- Insert sample users
INSERT INTO users (username, pronouns, given_name, surname, email, telephone, country, city, postal_code, street, house_number, role, user_state, newsletter, password_hash)
VALUES 
('admin', 'he/him', 'Admin', 'User', 'admin@example.com', '+123456789', 'Germany', 'Berlin', '10115', 'Admin Street', '1', 'admin', 'active', 1, '$2y$10$ZRmEOcluqYBnP9/Mp0l9TuJP5JqOspQZn/XE5Fl/L/uv1C2tMgVQO'), 
('employee1', 'she/her', 'Emily', 'Worker', 'employee1@example.com', '+987654321', 'Germany', 'Munich', '80331', 'Employee Road', '2', 'employee', 'active', 0, '$2y$10$ZRmEOcluqYBnP9/Mp0l9TuJP5JqOspQZn/XE5Fl/L/uv1C2tMgVQO'),
('user1', 'they/them', 'Alex', 'Customer', 'user1@example.com', '+111222333', 'Germany', 'Hamburg', '20095', 'User Lane', '3', 'user', 'active', 1, '$2y$10$ZRmEOcluqYBnP9/Mp0l9TuJP5JqOspQZn/XE5Fl/L/uv1C2tMgVQO');

-- Insert sample products
INSERT INTO products (name, description, price)
VALUES 
('Online Course: Web Development', 'Learn full-stack web development with hands-on projects.', 49.99),
('Premium E-Book: Digital Marketing', 'A complete guide to digital marketing strategies.', 19.99),
('3D Model Pack', 'A set of high-quality 3D assets for game developers.', 29.99);
