<?php
    
    namespace App\Tests\User;
    
    use Symfony\Component\HttpFoundation\JsonResponse;

    class RegisterActionTest extends UserTestBase{
        public function testRegister(): void{
            $payload = [
                'name'     => 'demo3',
                'email'    => 'demo3@demo.com',
                'password' => 'password',
            ];
            
            self::$client->request('POST',
                \sprintf('%s/register', $this->endpoint),
                [],
                [],
                [],
                json_encode($payload)
            );
            
            $response = self::$client->getResponse();
            $responseData = $this->getResponseData($response);
            
            $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
            $this->assertEquals($payload['email'], $responseData['email']);
        }
        
        public function testRegisterMissingParameters(): void{
            $payload = [
                'name'     => 'demo3',
                'password' => 'password',
            ];
            
            self::$client->request('POST',
                \sprintf('%s/register', $this->endpoint),
                [],
                [],
                [],
                json_encode($payload)
            );
            
            $response = self::$client->getResponse();
            $responseData = $this->getResponseData($response);
            
            $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        }
    }