<?php

namespace App\Services\Admin;

use App\Models\ClassroomUser;
use App\Models\Lesson;

class LessonService
{
    public function createLesson(array $lessonData): Lesson
    {
        $lesson = Lesson::create($lessonData);
        return $lesson;
    }

    public function updateLesson(Lesson $lesson, array $lessonData): Lesson
    {
        $lesson->update($lessonData);

        return $lesson->refresh();
    }

    public function destroyLesson(Lesson $lesson): void
    {
        $lesson->delete();
    }

    public function classroomChanged(int $oldClassroom, int $newClassroom): array
    {
        $studentsCredit = [];
        if ($oldClassroom !== $newClassroom) {
            $students = ClassroomUser::whereIn('classroom_id', [$oldClassroom, $newClassroom])->get(['id', 'classroom_id']);

            foreach ($students as $student) {
                $studentsCredit[$student->id] = $student->classroom_id === $oldClassroom ? 1 : -1;
            }
        }

        return $studentsCredit;
    }
}