<?php

namespace Jotapegue\FilterPipeline;

use Closure;
use ReflectionClass;
use Illuminate\Support\Str;

abstract class Filter
{
    protected $param = null;
    protected $scope = null;

    public function __construct()
    {
        $className = $this->getNameClass();

        $this->setScope($className);
        $this->setParam($className);
    }

    public function handle($request, Closure $next)
    {
        return (!$this->hasRequestParam() || $this->requestParamIsNull())
            ? $this->builder($request, $next)
            : $this->builFilter($request, $next);
    }

    protected function getRequestParam()
    {
        return request($this->param);
    }

    protected function filter($builder)
    {
        $scope = $this->scope;
        return $builder->$scope($this->getRequestParam());
    }

    private function setScope(string $scope) : void
    {
        $this->scope = Str::snake($scope);
    }

    private function setParam(string $param) : void
    {
        $this->param = Str::snake($param);
    }

    private function hasRequestParam() : bool
    {
        return request()->has($this->param);
    }

    private function requestParamIsNull() : bool
    {
        return is_null($this->getRequestParam());
    }

    private function builder($request, Closure $next)
    {
        return $next($request);
    }

    private function getNameClass() : string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    private function builFilter($request, Closure $next)
    {
        return $this->filter($this->builder($request, $next));
    }
}
