<?php
namespace xral\Resource\YML\Filter {
    use xral;
    use qtil;
    use kfiltr;
    use Symfony\Component\Yaml;
    
    class Emit {
        use kfiltr\Filter;
        
        /**
         * @see Symfony\Component\Yaml\Yaml\Parser
         * @var boolean 
         */
        private $exceptionOnInvalidType;
        
        /**
         * @see Symfony\Component\Yaml\Yaml\Parser
         * @var boolean 
         */
        private $objectSupport;
        
        private $inline;
        private $indent;
        
        /**
         * Default yml filter constructor
         * @param boolean $exceptionOnInvalidType
         * @param boolean $objectSupport
         */
        function __construct($inline = 2, $indent = 4, $exceptionOnInvalidType = false, $objectSupport = false) {
            $this->inline = $inline;
            $this->indent = $indent;
            $this->exceptionOnInvalidType = $exceptionOnInvalidType;
            $this->objectSupport = $objectSupport;
        }
        
        /**
         * Parses yml input
         * @param mixed $data
         * @return array
         */
        function execute($data) {
            if(!is_array($data)) {
                $data = (array)$data;
            }
            
            $yaml = new Yaml\Dumper();
            
            return  $yaml->dump(
                        $data,
                        $this->inline,
                        $this->indent,
                        $this->exceptionOnInvalidType, 
                        $this->objectSupport
                    );
        }
    }
}