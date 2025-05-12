USE mandamel;

INSERT INTO
    users (
        username,
        pronouns,
        given_name,
        surname,
        email,
        telephone,
        country,
        city,
        postal_code,
        street,
        house_number,
        role,
        user_state,
        password_hash
    )
VALUES
    (
        'admin',
        'he/him',
        'Max',
        'Admin',
        'admin@example.com',
        '1234567890',
        'Germany',
        'Berlin',
        '10115',
        'Hauptstraße',
        '1A',
        'admin',
        'active',
        '$2y$10$TthgLD7O2tRf3mIvRV7NIuXnPNp4eubWTFY4g4rFZfEJ302c7jvvG'
    ),
    (
        'lisa',
        'she/her',
        'Lisa',
        'User',
        'lisa@example.com',
        '0987654321',
        'Germany',
        'Munich',
        '80331',
        'Nebenstraße',
        '99',
        'user',
        'active',
        '$2y$10$ygTi/5dPftyz.e4D5zsRMul3vsr3mNhT/9TDnLIRuj/vDKlZjfoqa'
    );

INSERT INTO
    payment_info (
        user_id,
        method,
        creditcard_number,
        creditcard_expiry,
        creditcard_cvv,
        holder_name
    )
VALUES
    (
        2,
        'creditcard',
        '4111111111111111',
        '12/26',
        '123',
        'Lisa User'
    );

INSERT INTO
    categories (name)
VALUES
    ('eBooks'),
    ('Software'),
    ('Courses');

INSERT INTO
    products (
        name,
        description,
        rating,
        price,
        category_id,
        file_path,
        image,
        active
    )
VALUES
    (
        'Learn PHP eBook',
        'Complete PHP learning guide',
        4,
        9.99,
        1,
        'php_ebook.pdf',
        'php_image.png',
        1
    ),
    (
        'Task Manager App',
        'Boost productivity',
        5,
        19.99,
        2,
        'task_app.zip',
        'task_app.png',
        1
    ),
    (
        'JS Mastery Course',
        'Advanced JavaScript',
        3,
        49.99,
        3,
        'js_course.zip',
        'js_kurs.png',
        1
    ),
    (
        'HTML Quickstart Guide',
        'Basic HTML with examples and cheatsheet',
        2,
        6.99,
        1,
        'html_guide.pdf',
        'html.jpg',
        1
    ),
    (
        'Bug Tracker Pro',
        'Tool to track software bugs and tickets',
        1,
        14.50,
        2,
        'bugtracker.zip',
        'bug.jpg',
        1
    ),
    (
        'CSS Secrets Course',
        'Learn layout techniques and animations',
        4,
        29.00,
        3,
        'css_course.zip',
        'css.jpg',
        1
    ),
    (
        'MySQL Crash Course',
        'Fundamentals of databases explained quickly',
        5,
        12.90,
        1,
        'mysql_crashkurs.pdf',
        'mysql.jpg',
        1
    ),
    (
        'Python for Beginners',
        'Beginner-friendly Python course',
        5,
        39.99,
        3,
        'python_course.zip',
        'python.jpg',
        1
    );

INSERT INTO
    vouchers (code, value, expires_at)
VALUES
    ('A1B2C', 25.00, '2026-04-15 17:34:33'),
    ('D3E4F', 10.00, '2026-04-15 17:34:33');

INSERT INTO
    promo_codes (code, discount, type, expires_at, usage_limit)
VALUES
    (
        'WELCOME10',
        10.00,
        'percentage',
        '2026-04-15 17:34:33',
        100
    ),
    ('SAVE5', 5.00, 'fixed', '2026-04-15 17:34:33', 50);

INSERT INTO
    orders (
        user_id,
        payment_method,
        total,
        promo_code_id,
        voucher_id
    )
VALUES
    (2, 'creditcard', 49.99, 1, NULL);

INSERT INTO
    order_items (order_id, product_id, price)
VALUES
    (1, 3, 49.99);

INSERT INTO
    refresh_tokens (user_id, token, expires_at)
VALUES
    (2, 'sample_refresh_token', '2026-04-15 17:34:33');