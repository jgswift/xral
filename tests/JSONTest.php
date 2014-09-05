<?php
namespace xral\Tests {
    use qio;
    use xral\Resource\JSON;
    
    class JSONTest extends xralTestCase {
        function testQuery() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->from($file);
            
            $result = $query();
            
            $this->assertEquals(2,count($result));
        }
        
        function testQINQIntegration() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->from($file)->where(function($person) {
                return ($person['money'] > 5000) ? true : false;
            });
            
            $result = $query->execute();
            
            $this->assertEquals(1,count($result));
        }
        
        function testSelect() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->select(function($person) {
                return [
                    'name' => $person['firstName'].' '.$person['lastName']
                ];
            })->from($file);
            
            $result = $query->execute();
            $this->assertEquals(2,count($result));
            $this->assertEquals('john doe',$result[1]['name']);
        }
    }
}