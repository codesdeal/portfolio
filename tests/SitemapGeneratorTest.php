<?php
use WP_Mock\Tools\TestCase;

class SitemapGeneratorTest extends TestCase {
    public function setUp(): void {
        parent::setUp();
        WP_Mock::setUp();
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
        parent::tearDown();
    }

    public function test_get_post_priority() {
        // Mock WordPress functions
        WP_Mock::userFunction('get_post', [
            'times' => 1,
            'args' => [123],
            'return' => (object) [
                'post_type' => 'post',
                'post_date' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ]
        ]);

        $generator = new _Themename_Sitemap_Generator();
        $priority = $this->invokeMethod($generator, 'get_post_priority', [(object)[
            'post_type' => 'post',
            'post_date' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]]);

        $this->assertEquals('0.7', $priority);
    }

    /**
     * Helper method to test private/protected methods
     */
    protected function invokeMethod($object, $methodName, array $parameters = []) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}