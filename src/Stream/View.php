<?php
namespace xral\Stream {
    use xral;
    use observr;
    
    class View extends xral\Data\View {
        
        /**
         * Performs base query view preparation
         * @return boolean
         */
        function prepare() {
            $query = $this->getQuery();
            
            if(func_num_args() > 1) {
                $args = func_get_args();
            } else {
                $args = [];
            }

            $this->setState(self::PREPARE, $e = new observr\Event($this, $args));
            if(!$e->canceled) {
                if(is_array($this->data)) {
                    $params = [];
                    if((isset($query->data) && is_array($params = $query->data)) ||
                       (is_array($params = (array)$query))) {
                        $query->data = array_merge($this->data, $params);
                    }
                }

                $this['args'] = $args;
                
                return parent::prepare();
            }

            return false;
        }
    }
}