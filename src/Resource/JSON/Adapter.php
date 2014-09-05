<?php
namespace xral\Resource\JSON {
    use xral;
    
    class Adapter extends xral\Data\Adapter {
        
        /**
         * Factory method to create YML query
         * @return \xral\Resource\YML\Query
         */
        public function createQuery() {
            return new Query();
        }
    }
}