<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        parent::setUp();
    }

    /**
     * @dataProvider QuestionUrlsProvider
     */
    public function testQuestionUrls(string $url): void
    {
        $this->client->request('GET', $url);
        $this->assertLessThan(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator<array>
     */
    public function QuestionUrlsProvider(): \Generator
    {
        yield ['/'];
        yield ['/question/create'];
        yield ['/login'];
        yield ['/logout'];
        yield ['/register'];
    }
}
