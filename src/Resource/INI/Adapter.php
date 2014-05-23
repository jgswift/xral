<?php
namespace xral\Resource\INI {
    use xral;
    
    class Adapter extends xral\Data\Adapter {
        
        /**
         * Factory method
         * Instantiates INI query
         * @return \xral\Resource\INI\Query
         */
        public function createQuery() {
            return new Query();
        }
    }
}