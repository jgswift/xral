<?php
namespace xral\Resource\INI\Filter {
    use kfiltr;
    class Parse {
        use kfiltr\Filter;
        
        /**
         * Whether or not to process sections
         * Default is false
         * @var boolean 
         */
        private $process_sections;
        
        /**
         * Scanner type
         * @see parse_ini_string
         * @var integer
         */
        private $scanner_mode;
        
        function __construct($process_sections=false,$scanner_mode=\INI_SCANNER_NORMAL) {
            $this->process_sections = $process_sections;
            $this->scanner_mode = $scanner_mode;
        }
        
        /**
         * Alias for parse_ini_string
         * @see parse_ini_string
         * @param mixed $data
         * @return array
         */
        function execute($data) {
            if(!is_string($data)) {
                $data = (string)$data;
            }
            
            return parse_ini_string($data, $this->process_sections, $this->scanner_mode);
        }
    }
}