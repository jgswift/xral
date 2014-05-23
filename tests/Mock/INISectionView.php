<?php
namespace xral\Tests\Mock {
    use xral\Data\Filter;
    use xral\Resource\INI;
    
    class INISectionView extends INI\View {
        
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
            
            $query->from($this->file);
            
            $query->assemble();
            
            $query->addFilter(new Filter\Recursive(new INISectionFilter()));
            
            return parent::prepare();
        }
    }
}