<?php

declare(strict_types=1);

namespace KickflipMonoTests\Feature\View;

use Illuminate\View\View;
use Kickflip\Models\SiteData;
use KickflipMonoTests\DataProviderHelpers;
use KickflipMonoTests\Mocks\MarkdownHelpersMock;
use KickflipMonoTests\TestCase;
use Spatie\LaravelMarkdown\MarkdownRenderer as BaseMarkdownRenderer;

class MarkdownHelpersTest extends TestCase {
    use DataProviderHelpers;
    public function testDetermineAutoExtendEnabled()
    {
        $mockSiteData = SiteData::fromConfig([
            'baseUrl' => 'http://example.com',
            'production' => true,
            'siteName' => 'Example Site',
            'siteDescription' => 'This is an example site.',
        ]);
        $mockPageData = $this->getTestPageData();

        $markdownHelpers = new MarkdownHelpersMock();
        self::assertTrue($markdownHelpers->isAutoExtendEnabled($mockSiteData, $mockPageData));
    }

    public function testCanDetermineIfPageExtendIsEnabled()
    {
        $mockSiteData = SiteData::fromConfig([
            'baseUrl' => 'http://example.com',
            'production' => true,
            'siteName' => 'Example Site',
            'siteDescription' => 'This is an example site.',
        ]);
        $mockPageData = $this->getTestPageData();
        $renderedPageMarkdown = app(BaseMarkdownRenderer::class)
            ->convertToHtml(
                file_get_contents($mockPageData->source->getFullPath()),
            );

        $markdownHelpers = new MarkdownHelpersMock();
        self::assertTrue($markdownHelpers->isPageExtendEnabled($mockPageData, $renderedPageMarkdown));
    }

    /**
     * @dataProvider siteDataProvider
     */
    public function testCanPrepareExtendedRenderedFromMarkdown(int $pageId, string $expectedSection, string $expectedExtends)
    {
        $mockSiteData = SiteData::fromConfig([
            'baseUrl' => 'http://example.com',
            'production' => true,
            'siteName' => 'Example Site',
            'siteDescription' => 'This is an example site.',
        ]);
        $mockPageData = $this->getTestPageData($pageId);
        $renderedPageMarkdown = app(BaseMarkdownRenderer::class)
            ->convertToHtml(
                file_get_contents($mockPageData->source->getFullPath()),
            );
        $markdownHelpers = new MarkdownHelpersMock();
        $preparedExtendedRender = $markdownHelpers->prepareExtendedRender($mockPageData, $renderedPageMarkdown);
        self::assertIsArray($preparedExtendedRender);
        self::assertCount(3, $preparedExtendedRender);
        // Verify shape
        self::assertIsString($preparedExtendedRender[0]);
        self::assertEquals($expectedSection, $preparedExtendedRender[0]);
        self::assertIsString($preparedExtendedRender[1]);
        self::assertIsString($preparedExtendedRender[2]);
        self::assertEquals($expectedExtends, $preparedExtendedRender[2]);
    }

    public function siteDataProvider()
    {
        return $this->autoAddDataProviderKeys([
            [0, 'content', 'layouts.master'],
            [1, 'postContent', 'layouts.post'],
            [4, 'postContent', 'layouts.post'],
        ]);
    }

    public function testThrowsExceptionWithNonExtendedPageData()
    {
        $mockSiteData = SiteData::fromConfig([
            'baseUrl' => 'http://example.com',
            'production' => true,
            'siteName' => 'Example Site',
            'siteDescription' => 'This is an example site.',
        ]);
        $mockPageData = $this->getTestPageData(6);
        $renderedPageMarkdown = app(BaseMarkdownRenderer::class)
            ->convertToHtml(
                file_get_contents($mockPageData->source->getFullPath()),
            );
        $markdownHelpers = new MarkdownHelpersMock();
        $this->expectError();
        $markdownHelpers->prepareExtendedRender($mockPageData, $renderedPageMarkdown);
    }

    public function testCanMakeAView()
    {
        $mockSiteData = SiteData::fromConfig([
            'baseUrl' => 'http://example.com',
            'production' => true,
            'siteName' => 'Example Site',
            'siteDescription' => 'This is an example site.',
        ]);
        $mockPageData = $this->getTestPageData();
        $renderedPageMarkdown = app(BaseMarkdownRenderer::class)
            ->convertToHtml(
                file_get_contents($mockPageData->source->getFullPath()),
            );

        $markdownHelpers = new MarkdownHelpersMock();
        $preparedExtendedRender = $markdownHelpers->makeView([
            '__env' => app('view'),
            'app' => app(),
            'site' => $mockSiteData,
            'page' => $mockPageData,
        ], $renderedPageMarkdown);
        self::assertInstanceOf(View::class, $preparedExtendedRender);
        self::assertIsString($preparedExtendedRender->render());
    }
}
