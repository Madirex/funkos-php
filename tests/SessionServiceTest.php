<?php

use PHPUnit\Framework\TestCase;
use services\SessionService;

require_once __DIR__ . '/../src/services/SessionService.php';

/**
 * Tests para SessionService
 */
class SessionServiceTest extends TestCase
{

    /**
     * Test getInstance
     */
    public function testGetInstance()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertInstanceOf(SessionService::class, $sessionServiceMock);
    }

    /**
     * Test isAdmin
     */
    public function testIsAdmin()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isAdmin'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('isAdmin')
            ->willReturn(true);
        $this->assertTrue($sessionServiceMock->isAdmin());
    }

    /**
     * Test isLoggedIn
     */
    function testIsLoggedIn()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isLoggedIn'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);
        $this->assertTrue($sessionServiceMock->isLoggedIn());
    }

    /**
     * Test getVisitCount
     */
    function testGetVisitCount()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVisitCount'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('getVisitCount')
            ->willReturn(1);
        $this->assertEquals(1, $sessionServiceMock->getVisitCount());
    }

    /**
     * Test refreshLastActivity
     */
    function testRefreshLastActivity()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['refreshLastActivity'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('refreshLastActivity')
            ->willReturn(null);
        $this->assertNull($sessionServiceMock->refreshLastActivity());
    }

    /**
     * Test getUsername
     */
    function testGetUsername()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUsername'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('getUsername')
            ->willReturn('Test');
        $this->assertEquals('Test', $sessionServiceMock->getUsername());
    }

    /**
     * Test login
     */
    function testLogin()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['login'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('login')
            ->with('Test', true)
            ->willReturn(null);
        $this->assertNull($sessionServiceMock->login('Test', true));
    }

    /**
     * Test logout
     */
    function testLogout()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['logout'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('logout')
            ->willReturn(null);
        $this->assertNull($sessionServiceMock->logout());
    }

    /**
     * Test clear
     */
    function testClear()
    {
        $sessionServiceMock = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['clear'])
            ->getMock();
        $sessionServiceMock->expects($this->once())
            ->method('clear')
            ->willReturn(null);
        $this->assertNull($sessionServiceMock->clear());
    }
}
