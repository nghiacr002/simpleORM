<?php

namespace SimpleORM\Adapter;

interface IAdapter
{

    public function connect($configs = array());

    public function disconnect();

    public function execute($query, $bind_params = array());

    public function hasError();

    public function getDriverInfo();

    public function getErrors();

    public function escape($query);

    public function getLastQuery();

    public function startTransaction();

    public function commitTransaction();

    public function rollback();
}
