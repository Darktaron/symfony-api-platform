<?php
    
    namespace App\Tests\User;
    
    use App\Tests\TestBase;

    class UserTestBase extends TestBase{
        protected string $endpoint;
    
        protected function setUp(): void{
            parent::setUp();
            $this->endpoint = '/api/v1/users';
        }
    }