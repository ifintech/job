<?php

/**
 * Class DemoTest
 * @description 测试用例示范
 * @doc https://phpunit.de/manual/current/zh_cn/index.html
 */
class DemoTest extends \Base\Test\TestCase {
    public function setUp(){
        parent::setUp();
    }
    /**
     * @test
     * @dataProvider additionProvider
     */
    public function testAction($name){
        $params = array(
            'id' => 1,
            'name' => $name,
        );
        $response = $this->execRequest('GET', 'api_demo', $params);

        $expect = 'marc';

        $this->assertEquals($expect, $response['data']);
    }

    public function additionProvider(){
        return array(
            array('marc')
        );
    }
}