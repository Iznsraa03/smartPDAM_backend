<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:     $data['name'],
            email:    $data['email'],
            phone:    $data['phone'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'password' => $this->password,
        ];
    }
}
