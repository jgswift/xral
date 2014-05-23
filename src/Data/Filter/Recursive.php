<?php
namespace xral\Data\Filter {
    use qtil;
    use kfiltr;
    use observr;
    
    class Recursive {
        use kfiltr\Filter, kfiltr\Hook, observr\Subject;
        
        const COMPLETED = 'completed';

        /**
         * Default generic recursive filter constructor
         * @param type $filters
         * @param type $parent
         */
        function __construct($filters = null, $parent = null) {
            if(is_array($filters)) {
                $this->setFilters($filters);
            } elseif(!is_null($filters)) {
                $this->addFilter($filters);
            }
        }

        /**
         * Performs filtering
         * @param mixed $input
         * @param mixed $parent
         * @return mixed
         */
        protected function filter($input, $parent = null) {
            $filters = $this->getFilters();
            foreach($filters as $filter) {
                $input = $filter($input, $parent);
            }

            return $input;
        }

        /**
         * Performs recursive filtering
         * @param mixed $input
         * @param mixed $parent
         * @return mixed
         */
        function execute($input, $parent = null) {
            if(qtil\ArrayUtil::isMulti($input)) {
                foreach($input as $key => $value) {
                    $v = $this->execute($value, $parent);
                    $input[$key] = $v;
                }
            } else {
                $input = $this->filter($input, $parent);
            }

            if(is_null($parent)) {
                $this->setState(self::COMPLETED);
            }

            return $input;
        }
    }
}