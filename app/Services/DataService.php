<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\StudentResponse;
use Illuminate\Support\Facades\Storage;

class DataService
{
    private array $students = [];
    private array $assessments = [];
    private array $questions = [];
    private array $studentResponses = [];

    public function __construct()
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        // Load students
        $studentsData = json_decode(Storage::get('data/students.json'), true);
        foreach ($studentsData as $studentData) {
            $this->students[$studentData['id']] = new Student($studentData);
        }

        // Load assessments
        $assessmentsData = json_decode(Storage::get('data/assessments.json'), true);
        foreach ($assessmentsData as $assessmentData) {
            $this->assessments[$assessmentData['id']] = new Assessment($assessmentData);
        }

        // Load questions
        $questionsData = json_decode(Storage::get('data/questions.json'), true);
        foreach ($questionsData as $questionData) {
            $this->questions[$questionData['id']] = new Question($questionData);
        }

        // Load student responses
        $responsesData = json_decode(Storage::get('data/student-responses.json'), true);
        foreach ($responsesData as $responseData) {
            $this->studentResponses[] = new StudentResponse($responseData);
        }
    }

    public function getStudent(string $id): ?Student
    {
        return $this->students[$id] ?? null;
    }

    public function getAssessment(string $id): ?Assessment
    {
        return $this->assessments[$id] ?? null;
    }

    public function getQuestion(string $id): ?Question
    {
        return $this->questions[$id] ?? null;
    }

    public function getStudentResponses(string $studentId): array
    {
        return array_filter($this->studentResponses, function ($response) use ($studentId) {
            return $response->student['id'] === $studentId && $response->isCompleted();
        });
    }

    public function getLatestCompletedResponse(string $studentId): ?StudentResponse
    {
        $responses = $this->getStudentResponses($studentId);
        
        if (empty($responses)) {
            return null;
        }

        // Sort by completion date, latest first
        usort($responses, function ($a, $b) {
            return $b->getCompletedDate() <=> $a->getCompletedDate();
        });

        return $responses[0];
    }

    public function getAllQuestionsForAssessment(string $assessmentId): array
    {
        $assessment = $this->getAssessment($assessmentId);
        if (!$assessment) {
            return [];
        }

        $questions = [];
        foreach ($assessment->questions as $questionRef) {
            $question = $this->getQuestion($questionRef['questionId']);
            if ($question) {
                $questions[] = $question;
            }
        }

        return $questions;
    }
}