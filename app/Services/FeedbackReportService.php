<?php

namespace App\Services;

use App\Models\StudentResponse;

class FeedbackReportService
{
    private DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function generateReport(string $studentId): string
    {
        $wrongAnswerCount=0;
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

        $report = "{$student->getFullName()} recently completed {$assessment->name} assessment on {$formattedDate}";
        $report .= "He got {$correctAnswers} questions right out of {$totalQuestions}.";

        foreach ($questions as $question) {
            $studentAnswer = $latestResponse->getResponseForQuestion($question->id);
            $correctAnswer = $question->getCorrectAnswer();
            
            if ($studentAnswer !== $correctAnswer) {
                if($wrongAnswerCount == 0){
                    $report .= " Feedback for wrong answers given below";
                    $wrongAnswerCount++;
                }
                $studentOption = $question->getOptionByKey($studentAnswer);
                $correctOption = $question->getOptionByKey($correctAnswer);
                
                $report .= "Question: {$question->stem}\n";
                $report .= "Your answer: {$studentOption['label']} with value {$studentOption['value']}";
                $report .= "Right answer: {$correctOption['label']} with value {$correctOption['value']}";
                $report .= "Hint: {$question->getHint()}\n\n";
            }
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
}