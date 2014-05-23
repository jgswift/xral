<?php
namespace xral\Resource\XML\DOM {
    use xral;
    use xral\Resource\XML;
    
    class Adapter extends xral\Data\Adapter {
        
        /**
         * Factory for DOMDocument Query
         * @return \xral\Resource\XML\DOM
         */
        public function createQuery() {
            return new XML\DOM();
        }
    }
}