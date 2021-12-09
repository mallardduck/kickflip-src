<?php

use Kickflip\Models\NavItem;
use Kickflip\Models\SiteData;

it('throws exception when creating empty SiteData', function () {
    SiteData::fromConfig([], []);
})->throws(\Exception::class, 'Cannot initialize SiteData with empty site config array.');

test('a basic NavItem can be created', function($title, $url) {
    $navItem = NavItem::make($title, $url);
    expect($navItem->getLabel())->toBeString()->toBe($title);
    expect($navItem->title)->toBeString()->toBe($title);
    expect($navItem->hasUrl())->toBeTrue();
    expect($navItem->getUrl())->toBeString()->toBe($url);
    expect($navItem->url)->toBeString()->toBe($url);
    expect($navItem->hasChildren())->toBeFalse();
})->with([
    ['Basic Page', '/basic'],
    ['Another Page', '/another-page'],
]);

test('an advanced NavItem can be created', function() {
    $basicPage = NavItem::make('Basic Page', '/basic');
    $basicPage->setChildren([
        NavItem::make('Another Page', '/another-page')
    ]);
    expect($basicPage->hasChildren())->toBeTrue();
});
