<?php

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\Test\EventListener;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\EventListener\LocaleListener;
use Contao\CoreBundle\Test\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Tests the LocaleListener class.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class LocaleListenerTest extends TestCase
{
    /**
     * Tests the object instantiation.
     */
    public function testInstantiation()
    {
        $listener = new LocaleListener(['en']);

        $this->assertInstanceOf('Contao\CoreBundle\EventListener\LocaleListener', $listener);
    }

    /**
     * Tests the onKernelRequest() method with a request attribute.
     *
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider localeTestData
     */
    public function testWithRequestAttribute($locale, $expected)
    {
        $session = $this->mockSession();

        $request = Request::create('/');
        $request->setSession($session);
        $request->attributes->set('_locale', $locale);

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener(['en']);
        $listener->setContainer($this->mockContainerWithContaoScopes(ContaoCoreBundle::SCOPE_FRONTEND));
        $listener->onKernelRequest($event);

        $this->assertEquals($expected, $request->attributes->get('_locale'));
        $this->assertEquals($expected, $session->get('_locale'));
    }

    /**
     * Tests the onKernelRequest() method with the session locale.
     *
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider localeTestData
     */
    public function testWithSessionValue($locale, $expected)
    {
        // The session values are already formatted, so we're passing in $expected here
        $session = $this->mockSession();
        $session->set('_locale', $expected);

        $request = Request::create('/');
        $request->setSession($session);

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener(['en']);
        $listener->setContainer($this->mockContainerWithContaoScopes(ContaoCoreBundle::SCOPE_FRONTEND));
        $listener->onKernelRequest($event);

        $this->assertEquals($expected, $request->attributes->get('_locale'));
        $this->assertEquals($expected, $session->get('_locale'));
    }

    /**
     * Tests the onKernelRequest() method with an accept language header.
     *
     * @param string $locale
     * @param string $expected
     * @param array  $available
     *
     * @dataProvider acceptLanguageTestData
     */
    public function testWithLanguageHeader($locale, $expected, array $available)
    {
        $session = $this->mockSession();

        $request = Request::create('/');
        $request->setSession($session);
        $request->headers->set('Accept-Language', $locale);

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener($available);
        $listener->setContainer($this->mockContainerWithContaoScopes(ContaoCoreBundle::SCOPE_FRONTEND));
        $listener->onKernelRequest($event);

        $this->assertEquals($expected, $request->attributes->get('_locale'));
        $this->assertEquals($expected, $session->get('_locale'));
    }

    /**
     * Tests the onKernelRequest() method without container.
     */
    public function testWithoutContainer()
    {
        $session = $this->mockSession();

        $request = Request::create('/');
        $request->setSession($session);
        $request->attributes->set('_locale', 'zh-TW');

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener(['en']);
        $listener->onKernelRequest($event);

        $this->assertEquals('zh-TW', $request->attributes->get('_locale'));
        $this->assertFalse($session->has('_locale'));
    }

    /**
     * Tests the onKernelRequest() method without session.
     *
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider localeTestData
     */
    public function testWithoutSession($locale, $expected)
    {
        $request = Request::create('/');
        $request->attributes->set('_locale', $locale);

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener(['en']);
        $listener->setContainer($this->mockContainerWithContaoScopes(ContaoCoreBundle::SCOPE_FRONTEND));
        $listener->onKernelRequest($event);

        $this->assertNull($request->getSession());
        $this->assertEquals($expected, $request->attributes->get('_locale'));
    }

    /**
     * Tests the onKernelRequest() method with an invalid locale.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLocale()
    {
        $request = Request::create('/');
        $request->attributes->set('_locale', 'invalid');

        $event = new GetResponseEvent($this->mockKernel(), $request, HttpKernelInterface::MASTER_REQUEST);

        $listener = new LocaleListener(['en']);
        $listener->setContainer($this->mockContainerWithContaoScopes(ContaoCoreBundle::SCOPE_FRONTEND));
        $listener->onKernelRequest($event);
    }

    /**
     * Tests the createWithLocales() method.
     */
    public function testCreateWithLocales()
    {
        $listener = LocaleListener::createWithLocales('de', $this->getRootDir().'/app');

        $this->assertInstanceOf('Contao\CoreBundle\EventListener\LocaleListener', $listener);

        $reflection = new \ReflectionClass($listener);
        $property = $reflection->getProperty('availableLocales');
        $property->setAccessible(true);
        $locales = $property->getValue($listener);

        $this->assertContains('de', $locales);
        $this->assertContains('en', $locales);
        $this->assertContains('it', $locales);
    }

    /**
     * Provides the test data for the locale tests.
     *
     * @return array
     */
    public function localeTestData()
    {
        return [
            [null, 'en'], // see #264
            ['en', 'en'],
            ['de', 'de'],
            ['de-CH', 'de_CH'],
            ['de_CH', 'de_CH'],
            ['zh-tw', 'zh_TW'],
        ];
    }

    /**
     * Provides the test data for the accept language header tests.
     *
     * @return array
     */
    public function acceptLanguageTestData()
    {
        return [
            [null, 'de', ['de', 'en']], // see #264
            ['de', 'de', ['de', 'en']],
            ['de, en', 'en', ['en']],
            ['de', 'en', ['en']],
            ['de-de, en', 'de', ['de', 'en']],
            ['de, fr, en', 'fr', ['en', 'fr']],
            ['fr, de-ch, en', 'de_CH', ['en', 'de_CH']],
        ];
    }
}
