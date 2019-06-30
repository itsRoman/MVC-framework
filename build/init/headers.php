<?php

interface IAcceptance
{
    public function accept(IAcceptee $acceptee);
}

interface IAcceptee
{
    public function push($acceptable);
}

interface IRenderer
{
    public function render(IView $view);
}

interface IDataspace
{
    public function set($key, $value);
    public function get($key);
    public function has($key);
    public function import(IDataspace $dataspace);
    public function export();
}

interface IIdentifiable
{
    public function getID();
}

interface IComposite extends IIdentifiable
{
    public function attach(IIdentifiable $identifiable);
    public function getChildren();
    public function hasChildren();
}

interface IView extends IIdentifiable
{
    public function execute(Context $context);
    public function build();
}

interface IRequest
{
    public function getUri();
    public function getPort();
    public function getHost();
    public function getRequestMethod();
    public function isXmlHttpRequest();
}

interface IResponse
{
    public function setContent($content);
    public function addHeader($key, $value);
    public function setRedirect($url);
    public function send();
}

interface ISession
{
    public function start();
}

interface IController
{
    public function execute(Context $context);
}

interface IFilter
{
    public function process(IDataspace $dataspace);
}

interface IFilterable extends IDataspace
{
    public function addFilter(IFilter $filter);
    public function process();
}

interface IStatement
{
    
}

interface IRecord
{
    public function retrieve();
    public function where($clause);
    public function orWhere($clause);
    public function orderBy($orderby);
    public function limitBy($limitby);
    public function groupBy($groupby);
    public function insert();
    public function update();
    public function delete();
}

interface IRouter
{
    public function parse(IRequest $request);
}

interface IRule
{
    public function isSatisfiedBy($value);
    public function getErrorMessage();
}

interface IMapper
{
    
}

?>