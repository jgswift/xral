<?php
namespace xral\Resource\JSON\Filter {
    use kfiltr;
    
    class Decoder {
        use kfiltr\Filter;
        
        /**
         * Parses json input
         * @param string $data
         * @return array
         */
        function execute($data) {
            if(!is_string($data)) {
                $data = (string)$data;
            }
            
            return \json_decode($data,true);
        }
    }
}