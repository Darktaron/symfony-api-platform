<?php
    
    namespace App\Tests\User;
    
    use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
    use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
    use Symfony\Component\HttpFoundation\JsonResponse;
    
    class LoginActionTest extends UserTestBase{
        public function testLogin(): void{
            $payload = [
                'username' => 'demo@demo.com',
                'password' => 'password',
            ];
            
            self::$demo->request('POST',
                \sprintf("%s/login_check", $this->endpoint),
                [],
                [],
                [],
                \json_encode($payload)
            );
            
            $response = self::$demo->getResponse();
            
            $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
            $this->assertInstanceOf(JWTAuthenticationSuccessResponse::class, $response);
        }
    
        public function testLoginWithInvalidCredentials(): void{
            $payload = [
                'username' => 'demo@demo.com',
                'password' => 'invalid-password',
            ];
    
            self::$demo->request('POST',
                \sprintf("%s/login_check", $this->endpoint),
                [],
                [],
                [],
                \json_encode($payload)
            );
    
            $response = self::$demo->getResponse();
    
            $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
            $this->assertInstanceOf(JWTAuthenticationFailureResponse::class, $response);
        }
    }