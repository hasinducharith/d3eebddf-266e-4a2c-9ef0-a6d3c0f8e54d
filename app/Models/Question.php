<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public string $id;
    public string $stem;
    public string $type;
    public string $strand;
    public array $config;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->stem = $data['stem'];
        $this->type = $data['type'];
        $this->strand = $data['strand'];
        $this->config = $data['config'];
    }

    public function getCorrectAnswer(): string
    {
        return $this->config['key'];
    }

    public function getHint(): string
    {
        return $this->config['hint'];
    }

    public function getOptionByKey(string $key): ?array
    {
        foreach ($this->config['options'] as $option) {
            if ($option['id'] === $key) {
                return $option;
            }
        }
        return null;
    }
}
