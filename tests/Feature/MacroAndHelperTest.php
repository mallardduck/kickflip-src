<?php

declare(strict_types=1);

namespace KickflipMonoTests\Feature;

use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use Kickflip\Enums\CliStateDirPaths;
use Kickflip\KickflipHelper;
use KickflipMonoTests\DataProviderHelpers;
use KickflipMonoTests\ReflectionHelpers;
use KickflipMonoTests\TestCase;

class MacroAndHelperTest extends TestCase {
    use DataProviderHelpers, ReflectionHelpers;

    /**
     * @dataProvider replaceEnvProvider
     */
    public function testStringMacroReplaceEnv(string $input, string $expected)
    {
        $stringFormat = Str::of('Hello, {env}');
        $results = (string) $stringFormat->replaceEnv($input);
        self::assertIsString($results);
        self::assertEquals($expected, $results);
    }

    public function replaceEnvProvider()
    {
        return $this->autoAddDataProviderKeys([
            ['world', 'Hello, world'],
            ['space', 'Hello, space'],
            ['Laravel', 'Hello, Laravel'],
            ['PHP', 'Hello, PHP'],
        ]);
    }

    /**
     * @dataProvider kickflipHelperConfigProvider
     * @dataProvider kickflipHelperNamedConfigProvider
     */
    public function testKickflipHelperConfigPaths(string $input, string $expected)
    {
        /**
         * @var string|Repository|null
         */
        $path = KickflipHelper::config('paths.' . $input);
        self::assertIsString($path);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $path);
    }

    public function kickflipHelperConfigProvider()
    {
        return $this->autoAddDataProviderKeys([
            ['baseDir', '/packages/kickflip'],
            ['cache', '/packages/kickflip/cache'],
            ['env_config', '/packages/kickflip/config/config.{env}.php'],
        ]);
    }

    public function kickflipHelperNamedConfigProvider()
    {
        return $this->autoAddDataProviderKeys([
            [CliStateDirPaths::Base, '/packages/kickflip'],
            [CliStateDirPaths::Cache, '/packages/kickflip/cache'],
            [CliStateDirPaths::EnvConfig, '/packages/kickflip/config/config.{env}.php'],
        ]);
    }

    /**
     * @dataProvider kickflipHelperNamedConfigProvider
     * @dataProvider kickflipHelperConfigProvider
     */
    public function testKickflipHelperNamedPath(string $input, string $expected)
    {
        $path = KickflipHelper::namedPath($input);
        self::assertIsString($path);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $path);
    }

    /**
     * @dataProvider assetUrlProviders
     */
    public function testKickflipHelperAssetUrl(string $input, string $expected)
    {
        $assetUrl = KickflipHelper::assetUrl($input);
        self::isHtmlStringOf($expected, $assetUrl);
    }

    public function assetUrlProviders()
    {
        return $this->autoAddDataProviderKeys([
            ['', 'http://kickflip.test/assets/'],
            ['blue', 'http://kickflip.test/assets/blue'],
            ['hello/world', 'http://kickflip.test/assets/hello/world'],
        ]);
    }

    /**
     * @dataProvider resourcePathProviders
     */
    public function testKickflipHelperResourcePath(string $input, string $expected)
    {
        $resourcePath = KickflipHelper::resourcePath($input);
        self::assertIsString($resourcePath);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $resourcePath);
    }

    public function resourcePathProviders()
    {
        return $this->autoAddDataProviderKeys([
            ['', '/packages/kickflip/resources'],
            ['blue', '/packages/kickflip/resources/blue'],
            ['hello/world', '/packages/kickflip/resources/hello/world'],
        ]);
    }

    /**
     * @dataProvider sourcePathProviders
     */
    public function testKickflipHelperSourcePath(string $input, string $expected)
    {
        $sourcePath = KickflipHelper::sourcePath($input);
        self::assertIsString($sourcePath);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $sourcePath);
    }

    public function sourcePathProviders()
    {
        return $this->autoAddDataProviderKeys([
            ['', '/packages/kickflip/source'],
            ['blue', '/packages/kickflip/source/blue'],
            ['hello/world', '/packages/kickflip/source/hello/world'],
        ]);
    }

    /**
     * @dataProvider buildPathProviders
     */
    public function testKickflipHelperBuildPath(string $input, string $expected)
    {
        $sourcePath = KickflipHelper::buildPath($input);
        self::assertIsString($sourcePath);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $sourcePath);
    }

    public function buildPathProviders()
    {
        return $this->autoAddDataProviderKeys([
            ['', '/packages/kickflip/build_{env}'],
            ['blue', '/packages/kickflip/build_{env}/blue'],
            ['hello/world', '/packages/kickflip/build_{env}/hello/world'],
        ]);
    }

    /**
     * @dataProvider buildPathReplacedProviders
     */
    public function testKickflipHelperBuildReplacedPath(string $buildPathInput, string $envInput, string $expected)
    {
        $buildPath = (string) Str::of(KickflipHelper::buildPath($buildPathInput))->replaceEnv($envInput);
        self::assertIsString($buildPath);
        self::assertEquals(dirname(__DIR__, 2) . $expected, $buildPath);
    }

    public function buildPathReplacedProviders()
    {
        return $this->autoAddDataProviderKeys([
            ['', 'prod', '/packages/kickflip/build_prod'],
            ['blue', 'site', '/packages/kickflip/build_site/blue'],
            ['hello/world', 'dev', '/packages/kickflip/build_dev/hello/world'],
        ]);
    }
}
