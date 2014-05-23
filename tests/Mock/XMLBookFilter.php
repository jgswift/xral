<?php
namespace xral\Tests\Mock {
    use kfiltr;
    
    class XMLBookFilter {
        use kfiltr\Factory\Mapper;
        
        function __construct() {
            $this->setFactory(new GenericFactory());
            $this->setMapping([
                'book' => 'xral\Tests\Mock\Book'
            ],function($input) {
                if($input instanceof \SimpleXMLElement) {
                    return $input->getName();
                } else {
                    return (string)$input;
                }
            });
        }
    }
}