<?php
namespace xral\Resource\XML {
    class DOM extends Query {
        
        /**
         * Default assembly for DOMDocument queries
         */
        function assemble() {
            if(!parent::assemble()) {
                return;
            }
            
            $this->addFilter(new Filter\DOMDocument());
            
            $this->addFilter(function($document) {
                return $this->setDocument($document);
            });
            
            if(isset($this['xpath']) &&
               !empty($this['xpath'])) {
                $this->addFilter(new Filter\DOMDocument\XPath($this));
            }
        }
    }
}