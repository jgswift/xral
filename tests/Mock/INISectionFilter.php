<?php
namespace xral\Tests\Mock {
    use kfiltr;
    use qtil;
    
    class INISectionFilter {
        use kfiltr\Factory\Mapper;
        
        function __construct() {
            $this->setFactory(new GenericFactory());
            $this->setMapping([
                'section' => 'xral\Tests\Mock\Section'
            ],function($input) {
                if(!qtil\ArrayUtil::isMulti($input) && qtil\ArrayUtil::isIterable($input)) {
                    return 'section';
                }
            });
        }
    }
}