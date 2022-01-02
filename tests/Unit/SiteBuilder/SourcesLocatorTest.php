<?php

declare(strict_types=1);

namespace KickflipMonoTests\Unit\SiteBuilder;

use Kickflip\Models\PageData;
use Kickflip\SiteBuilder\SourcesLocator;
use KickflipMonoTests\DataProviderHelpers;
use KickflipMonoTests\ReflectionHelpers;
use KickflipMonoTests\TestCase;

class SourcesLocatorTest extends TestCase {
    use DataProviderHelpers, ReflectionHelpers;
    public function testCanVerifyClassExists()
    {
        self::assertClassExists(SourcesLocator::class);
    }

    public function testItCanCreateSourcesLocator()
    {
        self::assertInstanceOf(SourcesLocator::class, new SourcesLocator(dirname(__DIR__, 2) . '/sources'));
    }

    public function testCanVerifySourcesLocatorProperties()
    {
        self::assertHasProperties(
            new SourcesLocator(dirname(__DIR__, 2) . '/sources'),
            [
                'sourcesBasePath',
                'renderPageList',
                'bladeSources',
                'markdownSources',
                'markdownBladeSources',
            ]
        );
    }

    public function testCanVerifySourcesLocatorMethods()
    {
        $sourceLocator = new SourcesLocator(dirname(__DIR__, 2) . '/sources');
        self::assertIsArray($sourceLocator->getRenderPageList());
        self::assertCount(7, $sourceLocator->getRenderPageList());
        foreach ($sourceLocator->getRenderPageList() as $pageData) {
            self::assertInstanceOf(PageData::class, $pageData);
        }
        self::assertIsArray($sourceLocator->getCopyFileList());
    }
}
