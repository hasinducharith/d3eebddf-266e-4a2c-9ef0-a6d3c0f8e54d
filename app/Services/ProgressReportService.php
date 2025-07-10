<?php

namespace App\Services;

use App\Models\StudentResponse;

class ProgressReportService
{
    private DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function generateReport(string $studentId): string
    {
        $student = $this->dataService->getStudent($studentId);
        if (!$student) {
            return "Student not found.";
        }

        $responses = $this->dataService->getStudentResponses($studentId);
        if (empty($responses)) {
            return "No completed assessments found for this student.";
        }

        // Sort responses by completion date
        usort($responses, function ($a, $b) {
            return $a->getCompletedDate() <=> $b->getCompletedDate();
        });

        $assessment = $this->dataService->getAssessment($responses[0]->assessmentId);
        $questions = $this->dataService->getAllQuestionsForAssessment($responses[0]->assessmentId);
        $totalQuestions = count($questions);

        $report = "{$student->getFullName()} has completed {$assessment->name} assessment " . count($responses) . " times in total. Date and raw score given below";

        foreach ($responses as $response) {
            $date = $response->getCompletedDate()->format('jS F Y');
            $score = $response->results['rawScore'];
            $report .= "Date: {$date}, Raw Score: {$score} out of {$totalQuestions}";
        }

        if (count($responses) > 1) {
            $oldestScore = $responses[0]->results['rawScore'];
            $newestScore = $responses[count($responses) - 1]->results['rawScore'];
            $improvement = $newestScore - $oldestScore;
            
            $report .= "\n{$student->getFullName()} got {$improvement} more correct in the recent completed assessment than the oldest";
        }

        return $report;
    }
}