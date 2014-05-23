<?php
namespace xral\Data {
    use xral;
    use qtil;
    use observr;
    use kfiltr;
    
    abstract class Adapter {
        use observr\Subject, kfiltr\Filter;
        
        const READY = 0;
        
        /**
         * List of views
         * @var array
         */
        public $views;
        
        /**
         * Default constructor for adapter
         * @param qtil\Collection|xral\Data\View|array $views
         */
        public function __construct($views = null) {
            if($views instanceof View) {
                $views = new qtil\Collection([$views]);
            } elseif(qtil\ArrayUtil::isIterable($views)) {
                $views = new qtil\Collection($views);
            } else {
                $views = new qtil\Collection();
            }
            
            $this->views = $views;
        }
        
        /**
         * Meant to be overridden
         * Factory method to provide query instance for adapter
         */
        abstract function createQuery();
        
        /**
         * Executes adapter
         * @return \observr\Event
         * @throws xral\Exception
         */
        public function execute() {
            if($this->views->count() === 0) {
                throw new xral\Exception('Unable to process null views in '. get_class( $this ));
            }

            if(func_num_args() > 0) {
                $arguments = func_get_args();
            } else {
                $arguments = [];
            }

            $results = [];
            foreach($this->views as $view) {
                if($view->hasQuery()){
                    $query = $view->getQuery();
                } else {
                    $query = $view->setQuery($this->createQuery());
                }

                $view->setAdapter($this);
                if(!$view->isPrepared()) {
                    call_user_func_array([$view,'prepare'], $arguments);
                }

                $view->unprepare();
                
                $r = $query->execute();
                
                $this->setState(self::READY, $e = new observr\Event($this,[
                    'query'     => $query,
                    'view'      => $view,
                    'result'    => $r
                ]));
                
                if($e->canceled) {
                    break;
                } else {
                    $results[] = $e['result'];
                }
            }
            
            return $results;
        }
    }
}