<?php
    
    namespace App\Tests;
    
    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Exception;
    use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
    use Liip\TestFixturesBundle\Test\FixturesTrait;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;
    
    class TestBase extends WebTestCase{
        use FixturesTrait;
        use RecreateDatabaseTrait;
        
        protected static ?KernelBrowser $client = null;
        protected static ?KernelBrowser $demo   = null;
        protected static ?KernelBrowser $demo1  = null;
        protected static ?KernelBrowser $demo2  = null;
        
        protected function setUp(): void{
            if(null === self::$client){
                self::$client = static::createClient();
                self::$client->setServerParameters(
                    [
                        'CONTENT_TYPE' => 'application/json',
                        'HTTP_ACCEPT'  => 'application/ld+json',
                    ]);
            }
            
            if(null === self::$demo){
                self::$demo = clone self::$client;
                $this->createAuthenticatedUser(self::$demo, 'demo@demo.com');
            }
            
            if(null === self::$demo1){
                self::$demo1 = clone self::$client;
                $this->createAuthenticatedUser(self::$demo1, 'demo1@demo.com');
            }
            
            if(null === self::$demo2){
                self::$demo2 = clone self::$client;
                $this->createAuthenticatedUser(self::$demo2, 'demo2@demo.com');
            }
        }
        
        private function createAuthenticatedUser(KernelBrowser &$client, string $email): void{
            $user = $this->getContainer()->get('App\Repository\UserRepository')->findOneByEmailOrFail($email);
            
            $token = $this
                ->getContainer()
                ->get('Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface')
                ->create($user);
            
            $client->setServerParameters(
                [
                    'CONTENT_TYPE'       => 'application/json',
                    'HTTP_ACCEPT'        => 'application/ld+json',
                    'HTTP_Authorization' => \sprintf('Bearer %s', $token),
                ]
            );
        }
        
        protected function getResponseData(Response $response): array{
            return \json_decode($response->getContent(), true);
        }
        
        protected function initDbConnection(): Connection{
            return $this->getContainer()->get('doctrine')->getConnection();
        }
        
        /**
         * @return false|mixed
         *
         * @throws Exception
         */
        protected function getDemoId(){
            return $this->initDbConnection()->query("select id from user where email = 'demo@demo.com'")->fetchColumn(0);
        }
        
        /**
         * @return false|mixed
         *
         * @throws Exception
         */
        protected function getDemo1Id(){
            return $this->initDbConnection()->query("select id from user where email = 'demo1@demo.com'")->fetchColumn(0);
        }
        
        /**
         * @return false|mixed
         *
         * @throws Exception
         */
        protected function getDemo2Id(){
            return $this->initDbConnection()->query("select id from user where email = 'demo2@demo.com'")->fetchColumn(0);
        }
    }
