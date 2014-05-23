<?php
namespace xral\Resource\XML\Filter {
    use xral;
    
    class Recursive extends xral\Data\Filter\Recursive {

        /**
         * More specific recursion method for simplexml and domdocument filters
         * @param string $input
         * @param mixed $parent
         * @return mixed
         */
        function execute($input, $parent = null) {
            if(method_exists($input, 'children')) {
                $children = $input->children();

                if(method_exists($children, 'size')) {
                    if($children->size() === 0) {
                        unset($children);
                    }
                }
            } elseif(method_exists($input, 'getChildren')) {
                $children = $input->getChildren();
            }

            if(is_array($input)) {
                foreach($input as $key => $value) {
                    $v = $this->execute($value, $parent);
                    $input[$key] = $v;
                }
            } else {
                $input = $this->filter($input, $parent);
            }

            if(isset($children) && 
               (is_array($children) || $children instanceof \Traversable)) {
                foreach($children as $child) {
                    $this->execute($child, $input);
                }
            }

            if(is_null($parent)) {
                $this->setState(self::COMPLETED);
            }

            return $input;
        }
    }
}