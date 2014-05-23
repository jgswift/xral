<?php
namespace xral\Resource\XML {
    class Simple extends Query {
        /**
         * Default SimpleXML query assembly
         */
        function assemble() {
            if(!parent::assemble()) {
                return;
            }
            
            $this->addFilter(new Filter\SimpleXML());
            
            $this->addFilter(function($document) {
                return $this->setDocument($document);
            });
            
            if(isset($this['xpath']) &&
               !empty($this['xpath'])) {
                $this->addFilter(new Filter\SimpleXML\XPath($this));
            }
            
            $this->attach(self::SAVE,function($s,$e) {
                $this->getDocument()->asXml($this->getResource()->getPath());
            });
        }
    }
}