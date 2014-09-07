<?php
namespace xral\Resource\JSON\Filter {
    use kfiltr;
    
    class Encoder {
        use kfiltr\Filter;
        
        /**
         * Parses json input
         * @param array $data
         * @return array
         */
        function execute($data) {
            if(!is_array($data)) {
                $data = (array)$data;
            }
            
            return \json_encode($data);
        }
    }
}