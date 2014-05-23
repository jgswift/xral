<?php
namespace xral\Resource\XML\Filter {
    use kfiltr;
    class DOMDocument {
        use kfiltr\Filter;
        
        /**
         * Creates DOMDocument
         * and loads XML with given data
         * @param string $data
         * @return \DOMDocument
         */
        function execute($data) {
            $doc = new \DOMDocument();
            $doc->loadXML($data);
            
            return $doc;
        }
    }
}