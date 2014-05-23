<?php
namespace xral\Tests {
    use qio;
    use xral\Resource\XML;
    
    class XMLTest extends xralTestCase {
        function testTextQuery() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Query();
            
            $query->from($file);
            
            $result = $query();
            
            $resource = $query->getResource();
            $this->assertEquals(775,strlen($result[0]));
            $this->assertInstanceOf('qio\Resource',$resource);
        }
        
        function testSimpleUpdateClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->update($file)
                  ->set('author','A guy')
                  ->where('authors/author','Not anyone important');
            
            $query();
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->update($file)
                  ->set('author','Not anyone important')
                  ->where('authors/author','A guy');
            
            $query();
            
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->update($file)
                  ->where('authors/author','Not anyone important');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testSimpleSelectClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file);
            
            $result = $query();
            
            $this->assertEquals(2,count($result));
        }
        
        function testSimpleWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('ISBN','999-9-99-999999-9');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testSimpleOrWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('authors/author',[
                      'Someone',
                      'Someone else'
                  ]);
            
            $result = $query();

            $this->assertEquals(4,count($result));
        }
        
        function testSimpleContainsClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->contains('ISBN','999-9-99-999999-9');
            
            $result = $query();
            $this->assertEquals(1,count($result));
        }
        
        function testSimpleMultiWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('authors/author','Someone')
                  ->where('publisher','A company');
            
            $result = $query();
            
            $this->assertEquals(2,count($result));
        }
        
        function testSimpleClone() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('authors/author','Someone')
                  ->where('publisher','A company');
            
            $query2 = clone($query);
            $result = $query();
            $result2 = $query2();
            
            $this->assertEquals(count($result),count($result2));
        }
        
        function testSimpleStorage() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('authors/author','Someone')
                  ->where('publisher','A company');
            
            $query_serial = serialize($query);
            $query2 = unserialize($query_serial);
            
            $result = $query();
            $result2 = $query2();
            
            $this->assertEquals(count($result),count($result2));
        }
        
        function testDOMQuery() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->from($file);
            
            $result = $query();
            
            $this->assertEquals(424, strlen($result[0]->textContent));
        }
        
        function testDOMSelectClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->from($file);
            
            $result = $query();
            
            $this->assertEquals(2,$result[0]->length);
        }
        
        function testDOMWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->from($file)
                  ->where('ISBN','999-9-99-999999-9');
            
            $result = $query();
            
            $this->assertEquals(1,$result[0]->length);
        }
        
        function testDOMUpdateClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->update($file)
                  ->set('author','A guy')
                  ->where('authors/author','Not anyone important');
            
            $query();
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->update($file)
                  ->set('author','Not anyone important')
                  ->where('authors/author','A guy');
            
            $query();
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->update($file)
                  ->where('authors/author','Not anyone important');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testAdapter() {
            $adapter = new XML\Simple\Adapter();
            
            $view = new Mock\XMLBookView($adapter,__DIR__.'/Mock/mock.xml');
            
            $adapter->views->add($view);
            
            $result = $adapter->execute();
            
            $this->assertEquals(1,count($result));
            
            foreach($result[0] as $book) {
                $this->assertInstanceOf('xral\Tests\Mock\Book', $book);
            }
        }
    }
}