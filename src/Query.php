<?php
namespace xral {
    use qio;
    use qtil;
    use kfiltr;
    use observr;
        
    abstract class Query implements Interfaces\Query {
        use qtil\ArrayAccess, qtil\Chain, kfiltr\Hook, kfiltr\Filter, observr\Subject {
            qtil\Chain::link as _link;
        }
        
        /**
         * Stores resource to access and mutate
         * @var qio\Resource
         */
        private $resource;
        
        /**
         * Stores query options
         * @var array
         */
        public $data = [];
        
        /**
         * Default query constructor
         * @param qio\Resource $resource
         */
        public function __construct(qio\Resource $resource = null) {
            $this->resource = $resource;
        }
        
        /**
         * Retrieve query resource
         * @return qio\Resource
         */
        public function getResource() {
            return $this->resource;
        }
        
        /**
         * Updates query resource
         * @param qio\Resource $resource
         * @return qio\Resource
         */
        public function setResource(qio\Resource $resource) {
            return $this->resource = $resource;
        }
        
        /**
         * Ensures query statement references query
         * @param string $name
         * @param array $arguments
         * @return \qinq\Interfaces\Statement
         */
        public function link($name, array $arguments) {
            $link = $this->_link($name,$arguments);
            
            if($link instanceof Interfaces\Statement) {
                $link->setQuery($this);
            }
            
            return $link;
        }
        
        /**
         * Applies query filters to input
         * @param mixed $input
         * @return mixed
         */
        protected function translate($input) {
            if($this->hasFilters()) {
                $filters = $this->getFilters();
                
                foreach($filters as $filter) {
                    $input = $filter($input);
                }
            }
            
            return $input;
        }
        
        /**
         * overridden by individual queries
         * individual queries add specific filters necessary for those queries
         */
        abstract protected function assemble();
    }
}