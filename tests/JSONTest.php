<?php
namespace xral\Tests {
    use qio;
    use xral\Resource\JSON;
    
    class JSONTest extends xralTestCase {
        function testJSONQuery() {
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
        
        function testJSONSelect() {
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
        
        function testJSONUpdate() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->update($file)
                  ->set('firstName','bob')
                  ->where(function($person) {
                      return ($person['firstName'] == 'billy') ? true : false;
                  });
                  
            $result = $query();
            
            $query = new JSON\Query();
            
            $query->from($file)->where(function($person) {
                return ($person['firstName'] == 'bob') ? true : false;
            });
            
            $result = $query();
            $this->assertEquals(1,count($result));
            
            $query = new JSON\Query();
            
            $query->update($file)
                  ->set('firstName','billy')
                  ->where(function($person) {
                      return ($person['firstName'] == 'bob') ? true : false;
                  });
                  
            $query();
        }
        
        function testJSONInsert() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->update($file)
                  ->insert([
                      'firstName' => 'jane',
                      'lastName' => 'doe',
                      'gender' => 'female',
                      'money' => 50000
                  ]);
                  
            $query();
            
            $query = new JSON\Query();
            
            $query->from($file)
                  ->where('gender','female');
                  
            $result = $query();
            $this->assertEquals(1,count($result));
        }
        
        function testJSONDelete() {
            $file = new qio\File(__DIR__.'/Mock/mock.json');
            
            $query = new JSON\Query();
            
            $query->update($file)
                  ->delete()
                  ->where('gender','female');
                  
            $result = $query();
            
            $query = new JSON\Query();
            
            $query->from($file)
                  ->where('gender','female');
                  
            $result = $query();
            $this->assertEquals(0,count($result));
        }
        
        function testJSONMultiSelect() {
            $file = new qio\File(__DIR__.'/Mock/mock2.json');
            
            $query = new JSON\Query();
            
            $i = 0;
            $query->from($file)->select("people");
            
            $this->assertEquals(2,count($query()));
        }
    }
}