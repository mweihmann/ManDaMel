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
        active
    )
VALUES
    (
        'Learn PHP eBook',
        'Complete PHP learning guide.',
        4,
        9.99,
        1,
        'files/php_ebook.pdf',
        1
    ),
    (
        'Task Manager App',
        'Boost productivity.',
        5,
        19.99,
        2,
        'files/task_app.zip',
        1
    ),
    (
        'JS Mastery Course',
        'Advanced JavaScript',
        5,
        49.99,
        3,
        'files/js_course.zip',
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