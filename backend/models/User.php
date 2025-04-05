<?php
class User {
    public function __construct(
        public int $user_id,
        public string $username,
        public string $given_name,
        public string $surname,
        public string $email,
        public string $role,
        public string $user_state,
        public string $created_at
    ) {}
}
