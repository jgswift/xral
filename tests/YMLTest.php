<?php
namespace xral\Tests {
    use qio;
    use xral\Resource\YML;
    
    class YMLTest extends xralTestCase {
        function testQuery() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file);
            
            $result = $query();
            
            $resource = $query->getResource();
            $this->assertEquals(8,count($result));
            $this->assertInstanceOf('qio\Resource',$resource);
        }
        
        function testYPathClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file)
                  ->ypath('product');
            
            $result = $query();
            
            $this->assertEquals(3,count($result['product']));
        }
        
        function testYPathValueClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file)
                  ->ypath('product/quantity');
            
            $result = $query();
            $this->assertEquals([4,1,1],$result['product']['quantity']);
        }
        
        function testYPathAndClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file)
                  ->ypath('invoice|product');
            
            $result = $query();
            
            $this->assertEquals(2,count($result));
        }
        
        function testYPath2AttrClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file)
                  ->ypath('product[price="450" and quantity="4"]');
            
            $result = $query();
            $this->assertEquals(1,count($result));
        }
        
        function testYPathMulti1AttrClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->from($file)
                  ->ypath('product[quantity="1"]');
            
            $result = $query();
            
            $this->assertEquals(2,count($result['product']));
        }
        
        function testWhereClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->from($file)
                  ->where('quantity',1);
            
            $result = $query();
            
            $this->assertEquals(2,count($result['product']));
        }
        
        function testYAMLUpdateClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->update($file)
                  ->where('quantity',4)
                  ->set('quantity',5);
            
            $result = $query();
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->update($file)
                  ->where('quantity',5)
                  ->set('quantity',4);
            
            $result = $query();
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->from($file)
                  ->where('quantity',4);
            
            $result = $query();
            
            $this->assertEquals(1,count($result['product']));
        }
        
        function testYAMLInsertClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->update($file)
                  ->insert([
                      'sku' => 'BB5280R',
                      'quantity' => 6,
                      'description' => 'Baseball Glove',
                      'price' => 50
                  ]);
            
            $result = $query();
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->from($file)
                  ->where('quantity',6);
            
            $result = $query();
            
            $this->assertEquals(1,count($result['product']));
        }
        
        function testYAMLDeleteClause() {
            $file = new qio\File(__DIR__.'/Mock/mock.yml');
            
            $query = new YML\Query();
            
            $query->delete('product')
                  ->update($file)
                  ->where('quantity',6);
            
            $result = $query();
            
            $query = new YML\Query();
            
            $query->select('product')
                  ->from($file)
                  ->where('quantity',6);
            
            $result = $query();
            
            $this->assertEquals(0,count($result['product']));
        }
    }
}