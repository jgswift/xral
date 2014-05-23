<?php
namespace xral\Interfaces {
    interface Statement {
        function getQuery();
        function setQuery(Query $query);
    }
}