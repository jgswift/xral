<?php
namespace xral\Resource\XML\Filter {
    use kfiltr;
    class SimpleXML {
        use kfiltr\Filter;
        
        /**
         * SimpleXML class to populate elements using
         * @var string
         */
        private $className;
        
        /**
         * @see simplexml_load_string
         * @var integer 
         */
        private $options;
        
        /**
         * @see simplexml_load_string
         * @var string
         */
        private $namespace;
        
        /**
         * @see simplexml_load_string
         * @var boolean
         */
        private $isPrefix;
        
        /**
         * Filter for simplexml_load_string
         * @see simplexml_load_string
         * @param string $className
         * @param integer $options
         * @param string $namespace
         * @param boolean $isPrefix
         */
        function __construct(
                $className = 'xral\Resource\XML\Iterator',
                $options = 0,
                $namespace = '',
                $isPrefix = false
        ) {
            $this->className = $className;
            $this->options = $options;
            $this->namespace = $namespace;
            $this->isPrefix = $isPrefix;
        }
        
        /**
         * @see simplexml_load_string
         * @param mixed $data
         * @return mixed
         */
        function execute($data) {
            if(!is_string($data)) {
                $data = (string)$data;
            }
            
            return simplexml_load_string($data, $this->className, $this->options, $this->namespace, $this->isPrefix);
        }
    }
}