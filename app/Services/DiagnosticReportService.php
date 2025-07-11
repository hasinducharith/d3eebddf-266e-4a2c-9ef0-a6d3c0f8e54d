<?php 

namespace App\Services;

use App\Models\StudentResponse;
use App\Models\Question;

class DiagnosticReportService
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

        $latestResponse = $this->dataService->getLatestCompletedResponse($studentId);
        if (!$latestResponse) {
            return "No completed assessments found for this student.";
        }

        $assessment = $this->dataService->getAssessment($latestResponse->assessmentId);
        $questions = $this->dataService->getAllQuestionsForAssessment($latestResponse->assessmentId);

        $completedDate = $latestResponse->getCompletedDate();
        $formattedDate = $completedDate->format('jS F Y g:i A');

        $totalQuestions = count($questions);
        $correctAnswers = $this->calculateCorrectAnswers($latestResponse, $questions);
        
        $strandResults = $this->calculateStrandResults($latestResponse, $questions);

        $report = "{$student->getFullName()} recently completed {$assessment->name} assessment on {$formattedDate} \n";
        $report .= "He got {$correctAnswers} questions right out of {$totalQuestions}. Details by strand given below:\n\n";

        foreach ($strandResults as $strand => $result) {
            $report .= "{$strand}: {$result['correct']} out of {$result['total']} correct\n";
        }

        return $report;
    }

    private function calculateCorrectAnswers(StudentResponse $response, array $questions): int
    {
        $correct = 0;
        
        foreach ($questions as $question) {
            $studentAnswer = $response->getResponseForQuestion($question->id);
            if ($studentAnswer === $question->getCorrectAnswer()) {
                $correct++;
            }
        }

        return $correct;
    }

    private function calculateStrandResults(StudentResponse $response, array $questions): array
    {
        $strands = [];

        foreach ($questions as $question) {
            $strand = $question->strand;
            if (!isset($strands[$strand])) {
                $strands[$strand] = ['correct' => 0, 'total' => 0];
            }

            $strands[$strand]['total']++;
            
            $studentAnswer = $response->getResponseForQuestion($question->id);
            if ($studentAnswer === $question->getCorrectAnswer()) {
                $strands[$strand]['correct']++;
            }
        }

        return $strands;
    }
}