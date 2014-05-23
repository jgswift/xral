<?php
namespace xral\Resource\XML {
    class Iterator extends \SimpleXMLIterator {
        
        /**
         * Helper method to retrieve simplexml attributes
         * @param string $ns
         * @param boolean $is_prefix
         * @return array
         */
        function attributes($ns = null, $is_prefix = null)
        {
            $attr = (array)parent::attributes($ns, $is_prefix);

            if(isset($attr['@attributes'])) {
               return $attr['@attributes'];
            }

            return [];
        }
        
        /**
         * Check if iterator is xml element
         * @return boolean
         */
        function isElement() {
            return $this->xpath('.') == [$this];
        }
        
        /**
         * Check if iterator is xml attribute
         * @return boolean
         */
        function isAttribute() {
            return $this[0] == $this && $this->xpath('.') != [$this];
        }
        
        /**
         * Check if iterator is xml attribute collection
         * @return boolean
         */
        function isAttributes() {
            return $this->attributes() === null;
        }
        
        /**
         * Check if iterator is xml element collection
         * @return boolean
         */
        function isElements() {
            return $this[0] != $this && $this->attributes() !== null;
        }
        
        /**
         * Check if iterator is single xml result
         * @return boolean
         */
        function isSingle() {
            return $this[0] == $this;
        }
        
        /**
         * Check if iterator is empty xml list
         * @return boolean
         */
        function isEmptyList() {
            return $this[0] == null;
        }
        
        /**
         * Check if xml element is root element
         * @return boolean
         */
        function isRoot() {
            return $this->xpath('/*') == [$this];
        }
    }
}