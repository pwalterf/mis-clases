<?php

namespace App\Services\Admin;

use App\Models\ClassroomUser;

class StudentService
{
    public function updateStudent(ClassroomUser $student, array $studentData): ClassroomUser
    {
        $student->update($studentData);

        return $student->refresh();
    }

    public function updateCredit(ClassroomUser $student, int $newCredit): void
    {
        $student->update(['credit' => $student->credit + $newCredit]);
    }

    public function syncCredit(array $studentsCredit): void
    {
        foreach ($studentsCredit as $studentId => $newCredit) {
            if ($newCredit !== 0) {
                $this->updateCredit(ClassroomUser::find($studentId), $newCredit);
            }
        }
    }
}