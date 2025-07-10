<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResponse extends Model
{
    public string $id;
    public string $assessmentId;
    public string $assigned;
    public string $started;
    public ?string $completed;
    public array $student;
    public array $responses;
    public array $results;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->assessmentId = $data['assessmentId'];
        $this->assigned = $data['assigned'];
        $this->started = $data['started'];
        $this->completed = $data['completed'] ?? null;
        $this->student = $data['student'];
        $this->responses = $data['responses'];
        $this->results = $data['results'];
    }

    public function isCompleted(): bool
    {
        return $this->completed !== null;
    }

    public function getCompletedDate(): ?\DateTime
    {
        if (!$this->completed) {
            return null;
        }
        return \DateTime::createFromFormat('d/m/Y H:i:s', $this->completed);
    }

    public function getResponseForQuestion(string $questionId): ?string
    {
        foreach ($this->responses as $response) {
            if ($response['questionId'] === $questionId) {
                return $response['response'];
            }
        }
        return null;
    }
}
