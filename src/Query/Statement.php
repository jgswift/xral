<?php
namespace xral\Query {
    use xral;
    use kfiltr;
    
    class Statement implements xral\Interfaces\Statement {
        use kfiltr\Filter;
        
        /**
         * Stores statement query
         * @var xral\Interfaces\Query 
         */
        private $query;
        
        /**
         * Stores statement constructor arguments
         * @var array 
         */
        private $arguments = [];
        
        /**
         * Default constructor for query statements
         * TODO: Update to variadic 5.6
         * @param array $args
         */
        function __construct() {
            $this->arguments = func_get_args();
        }
        
        /**
         * Retrieve statement arguments
         * @return array
         */
        function getArguments() {
            return $this->arguments;
        }
        
        /**
         * Retrieve statement query
         * @return xral\Interfaces\Query
         */
        public function getQuery() {
            return $this->query;
        }

        /**
         * Update statement query
         * @param xral\Interfaces\Query $query
         * @return xral\Interfaces\Query
         */
        public function setQuery(xral\Interfaces\Query $query) {
            return $this->query = $query;
        }
    }
}