<?php
namespace xral\Tests\Mock {
    use xral\Resource\XML;
    
    class XMLBookView extends XML\View {
        
        private $file;
        
        public function __construct(\xral\Data\Adapter $adapter = null, $file = null) {
            parent::__construct($adapter);
            $this->file = $file;
        }
        
        public function getFile() {
            return $this->file;
        }
        
        public function setFile($file) {
            return $this->file = $file;
        }
        
        public function prepare() {
            $query = $this->getQuery();
            
            $query->select('//book')
                  ->from($this->file);
            
            $query->assemble();
            
            $query->addFilter(new XML\Filter\Recursive([
                new XMLBookFilter()
            ]));
            
            return parent::prepare();
        }
    }
}