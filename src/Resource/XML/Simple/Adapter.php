<?php
namespace xral\Resource\XML\Simple {
    use xral;
    use xral\Resource\XML;
    
    class Adapter extends xral\Data\Adapter {
        
        /**
         * Factory for SimpleXML Query
         * @return \xral\Resource\XML\Simple
         */
        public function createQuery() {
            return new XML\Simple();
        }
    }
}