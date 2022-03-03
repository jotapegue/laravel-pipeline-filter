<?php

namespace Jotapegue\FilterPipeline;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Model;

class ModelFilter
{
    protected $model = Model::class;
    protected $filters = [];

    // TODO - Automate adding filters
    public function filters()
    {
        return app(Pipeline::class)
            ->send($this->model::query())
            ->through($this->filters)
            ->thenReturn();
    }
}
