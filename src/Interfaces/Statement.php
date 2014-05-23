<?php
namespace xral\Interfaces {
    use xral;
    
    interface Statement {
        function getQuery();
        function setQuery(xral\Query $query);
    }
}