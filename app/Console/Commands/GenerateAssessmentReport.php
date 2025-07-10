<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataService;
use App\Services\DiagnosticReportService;
use App\Services\ProgressReportService;
use App\Services\FeedbackReportService;

class GenerateAssessmentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessment:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate assessment reports for students';

    private DataService $dataService;
    private DiagnosticReportService $diagnosticService;
    private ProgressReportService $progressService;
    private FeedbackReportService $feedbackService;

    public function __construct()
    {
        parent::__construct();
        $this->dataService = new DataService();
        $this->diagnosticService = new DiagnosticReportService($this->dataService);
        $this->progressService = new ProgressReportService($this->dataService);
        $this->feedbackService = new FeedbackReportService($this->dataService);
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Please enter the following');

        $studentId = $this->ask('Student ID');
        $reportType = $this->ask('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback)');

        try {
            $report = match ($reportType) {
                '1' => $this->diagnosticService->generateReport($studentId),
                '2' => $this->progressService->generateReport($studentId),
                '3' => $this->feedbackService->generateReport($studentId),
                default => 'Invalid report type. Please choose 1, 2, or 3.'
            };

            $this->line('');
            foreach (explode("\n", $report) as $line) {
                $trimmed = trim($line);
                if ($trimmed !== '') {
                    $this->line($trimmed);
                }
            }
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error generating report: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
