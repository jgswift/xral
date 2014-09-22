<?php
namespace xral\Tests {
    use qio;
    use xral\Resource\INI;
    
    class INITest extends xralTestCase {
        function testQuery() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $query->from($file);
            
            $result = $query();
            
            $this->assertEquals(3,count($result));
        }
        
        function testWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $query->from($file)
                  ->where('type','planet');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testInsertClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $n = 10;
            
            $query->update($file)
                  ->section('foosec')
                  ->insert('z',$n);
            
            $query();
            
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            $query = new INI\Query();
            $query->from($file)
                  ->section('foosec');
            
            $result = $query();
            
            $this->assertEquals($n,$result['foosec']['z']);
        }
        
        function testDeleteClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $n = 10;
            
            $query->update($file)
                  ->section('foosec')
                  ->delete('z');
            
            $query();
            
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            $query = new INI\Query();
            $query->from($file)
                  ->section('foosec');
            
            $result = $query();
            
            $this->assertFalse(array_key_exists('z',$result['foosec']));
        }
        
        function testWhereSelectClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $query->from($file)
                  ->where('othersec.x','5');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testWhereSectionClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $query->from($file)
                  ->where('othersec.x',5);
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }

        function testSectionClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $query->section('othersec')
                  ->from($file);
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testUpdateClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $n = (string)rand(1,15);
            $query->update($file)
                  ->section('foosec')
                  ->set('y',$n);
            
            $query();
            
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            $query = new INI\Query();
            $query->from($file)
                  ->section('foosec');
            
            $result = $query();
            
            $this->assertEquals($n,$result['foosec']['y']);
        }
        
        function testUpdateAliasClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.ini');
            
            $query = new INI\Query();
            
            $n = (string)rand(1,15);
            $query->update($file)
                  ->set('foosec.y',$n);
            
            $query();
            
            $query = new INI\Query();
            
            $query->from($file)
                  ->section('foosec');
            
            $result = $query();
            
            $this->assertEquals($n,$result['foosec']['y']);
        }
        
        function testAdapter() {
            $adapter = new INI\Adapter();
            
            $view = new Mock\INISectionView($adapter,__DIR__.'/Mock/mock.ini');
            
            $adapter->views->add($view);
            
            $result = $adapter->execute();
            
            $this->assertEquals(3,count($result[0]));
            
            foreach($result[0] as $section) {
                $this->assertInstanceOf('xral\Tests\Mock\Section', $section);
            }
        }
    }
}