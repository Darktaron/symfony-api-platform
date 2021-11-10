<?php

namespace App\Messenger\Message;

    class UserRegisteredMessage
    {
        private string $id;
        private string $name;
        private string $email;
        private string $token;

        public function __construct(string $id, string $name, string $email, string $token)
        {
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->token = $token;
        }

        public function setId(string $id): void
        {
            $this->id = $id;
        }

        public function getId(): string
        {
            return $this->id;
        }

        public function setName(string $name): void
        {
            $this->name = $name;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function setToken(string $token): void
        {
            $this->token = $token;
        }

        public function getToken(): string
        {
            return $this->token;
        }
    }
