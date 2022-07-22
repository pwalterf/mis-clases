<?php

namespace App\Services\Admin;

use App\Models\Classroom;

class ClassroomService
{
    protected Classroom $classroom;

    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;
    }

    public function createClassroom(array $classroomData): Classroom
    {
        $classroom = Classroom::create($classroomData);

        return $classroom;
    }

    public function updateClassroom(Classroom $classroom, array $classroomData): Classroom
    {
        $classroom->update($classroomData);

        return $classroom->refresh();
    }

    public function destroyClassroom(Classroom $classroom): void
    {
        $classroom->delete();
    }

    public function restoreClassroom(Classroom $classroom): void
    {
        $classroom->restore();
    }

    public function createSubscription(Classroom $classroom, array $subscriptionData): void
    {
        $classroom->subscriptions()->create($subscriptionData);
    }

    public function addStudents(Classroom $classroom, array $students): void
    {
        $classroom->classroomUsers()->createMany($students);
    }

    public function removeStudents(Classroom $classroom, array $students = null): void
    {
        if ($students) {
            $classroom->classroomUsers()->detach($students);
        } else {
            $classroom->classroomUsers()->delete();
        }
    }
}