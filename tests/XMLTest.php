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
            $this->assertEquals(775,strlen($result));
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
                  ->from($file)
                  ->where('authors/author','Not anyone important');
            
            $result = $query();
            
            $this->assertInstanceOf('SimpleXMLIterator', $result[0]);
        }
        
        function testSimpleInsertClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->select('/library/books')
                  ->update($file)
                  ->insert(['book' => [
                      'name' => 'The Catcher In The Rye',
                      'authors' => [
                          'author' => 'J. D. Salinger'
                      ],
                      'ISBN' => '0316769533 9780316769532',
                      'publisher' => 'Amazon',
                      'pages' => 277
                  ]]);
            
            $query();
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('name','The Catcher In The Rye');
            
            $result = $query();
                        
            $this->assertEquals(1,count($result));
        }
        
        function testSimpleDeleteClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\Simple();
            
            $query->delete('//book')
                  ->update($file)
                  ->where('name','The Catcher In The Rye');
            
            $query();
            
            $query = new XML\Simple();
            
            $query->select('//book')
                  ->from($file)
                  ->where('name','The Catcher In The Rye');
            
            $result = $query();
            
            $this->assertEquals(0,count($result));
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
            
            $this->assertEquals(424, strlen($result->textContent));
        }
        
        function testDOMInsertClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->select('/library/books')
                  ->update($file)
                  ->insert(['book' => [
                      'name' => 'The Catcher In The Rye',
                      'authors' => [
                          'author' => 'J. D. Salinger'
                      ],
                      'ISBN' => '0316769533 9780316769532',
                      'publisher' => 'Amazon',
                      'pages' => 277
                  ]]);
            
            $query();
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->from($file)
                  ->where('name','The Catcher In The Rye');
            
            $result = $query();
            
            $this->assertEquals(1,count($result));
        }
        
        function testDOMDeleteClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.xml');
            
            $query = new XML\DOM();
            
            $query->delete('//book')
                  ->update($file)
                  ->where('name','The Catcher In The Rye');
            
            $query();
            
            $query = new XML\DOM();
            
            $query->select('//book')
                  ->from($file)
                  ->where('name','The Catcher In The Rye');
            
            $result = $query();
            
            $this->assertEquals(0,count($result));
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