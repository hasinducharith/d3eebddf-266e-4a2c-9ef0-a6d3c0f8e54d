<?php

namespace Tests\Unit;

use App\Services\DataService;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    public function testGetStudentDataReturnsDataForExistingId()
    {
        $studentId = 'student1';
        $studentData = $this->dataService->getStudent($studentId);

        $this->assertNotNull($studentData);
        $this->assertEquals($studentId, $studentData->id);
        $this->assertEquals('Tony', $studentData->firstName);
    }

    public function testGetStudentDataReturnsNullForNonExistentId()
    {
        $studentId = 'student5';
        $studentData = $this->dataService->getStudent($studentId);

        $this->assertNull($studentData);
    }

    public function testGetAssessmentDataReturnForExistingId()
    {
        $assessmentId = 'assessment1';
        $assessmentData = $this->dataService->getAssessment($assessmentId);

        $this->assertNotNull($assessmentData);
        $this->assertEquals($assessmentId, $assessmentData->id);
        $this->assertEquals('Numeracy', $assessmentData->name);
    }

    public function testGetAssessmentDataReturnNullForNonExistingId()
    {
        $assessmentId = 'assessment10';
        $assessmentData = $this->dataService->getAssessment($assessmentId);

        $this->assertNull($assessmentData);
    }
}
