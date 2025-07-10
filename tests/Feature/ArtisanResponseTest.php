<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtisanResponseTest extends TestCase
{
    public function testDiagnosticReportFlow()
    {
        $this->artisan('assessment:report')
            ->expectsOutput('Please enter the following')
            ->expectsQuestion('Student ID', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback)', 1)
            ->expectsOutput('Tony Stark recently completed Numeracy assessment on 16th December 2021 10:46 AM')
            ->expectsOutput('He got 15 questions right out of 16. Details by strand given below:')
            ->expectsOutput('Number and Algebra: 5 out of 5 correct')
            ->expectsOutput('Measurement and Geometry: 7 out of 7 correct')
            ->expectsOutput('Statistics and Probability: 3 out of 4 correct')
            ->assertExitCode(0);
    }

    public function testProgressReportFlow()
    {
        $this->artisan('assessment:report')
            ->expectsOutput('Please enter the following')
            ->expectsQuestion('Student ID', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback)', 2)
            ->expectsOutput('Tony Stark has completed Numeracy assessment 3 times in total. Date and raw score given below')
            ->expectsOutput('Date: 16th December 2019, Raw Score: 6 out of 16')
            ->expectsOutput('Date: 16th December 2020, Raw Score: 10 out of 16')
            ->expectsOutput('Date: 16th December 2021, Raw Score: 15 out of 16')
            ->expectsOutput('Tony Stark got 9 more correct in the recent completed assessment than the oldest')
            ->assertExitCode(0);
    }

    public function testFeedbackReportFlow()
    {
        $this->artisan('assessment:report')
            ->expectsOutput('Please enter the following')
            ->expectsQuestion('Student ID', 'student1')
            ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback)', 3)
            ->expectsOutput('Tony Stark recently completed Numeracy assessment on 16th December 2021 10:46 AM')
            ->expectsOutput('He got 15 questions right out of 16. Feedback for wrong answers given below')
            ->expectsOutput("Question: What is the 'median' of the following group of numbers 5, 21, 7, 18, 9?")
            ->expectsOutput("Your answer: A with value 7")
            ->expectsOutput("Right answer: B with value 9")
            ->expectsOutput("Hint: You must first arrange the numbers in ascending order. The median is the middle term, which in this case is 9")
            ->assertExitCode(0);
    }
}