<?php
namespace xral\Resource\YML\Filter {
    use xral;
    use qtil;
    use kfiltr;
    use Symfony\Component\Yaml;
    
    class Parse {
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
        
        /**
         * Default yml filter constructor
         * @param boolean $exceptionOnInvalidType
         * @param boolean $objectSupport
         */
        function __construct($exceptionOnInvalidType = false, $objectSupport = false) {
            $this->exceptionOnInvalidType = $exceptionOnInvalidType;
            $this->objectSupport = $objectSupport;
        }
        
        /**
         * Parses yml input
         * @param mixed $data
         * @return array
         */
        function execute($data) {
            if(!is_string($data)) {
                $data = (string)$data;
            }
            
            $parser = new Yaml\Parser();
            
            return  $parser->parse(
                        $data, 
                        $this->exceptionOnInvalidType, 
                        $this->objectSupport
                    );
        }
    }
}