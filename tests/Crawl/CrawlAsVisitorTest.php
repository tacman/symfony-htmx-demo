<?php

namespace App\Tests\Crawl;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use Survos\CrawlerBundle\Tests\BaseVisitLinksTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrawlAsVisitorTest extends BaseVisitLinksTest
{
	#[TestDox('/$method $url ($route)')]
	#[TestWith(['', '/', 200])]
	#[TestWith(['', '/like', 200])]
	#[TestWith(['', '/emoji', 200])]
	#[TestWith(['', '/guestbook', 200])]
	#[TestWith(['', '/dynamic-form', 200])]
	#[TestWith(['', '/register', 200])]
	#[TestWith(['', '/login', 200])]
	public function testRoute(string $username, string $url, string|int|null $expected): void
	{
		parent::testWithLogin($username, $url, (int)$expected);
	}
}
