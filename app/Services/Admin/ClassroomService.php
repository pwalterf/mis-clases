<?php

namespace App\Services\Admin;

use App\Models\Classroom;
use Illuminate\Support\Arr;

class ClassroomService
{
    public function createClassroom(array $classroomData): Classroom
    {
        // $classroom = Classroom::create($classroomData);

        $classroom = Classroom::create(Arr::only($classroomData, ['name', 'description']));
        $this->createSubscription($classroom, Arr::only($classroomData, ['price_hr', 'started_at']));
        $this->addStudents($classroom, Arr::only($classroomData, ['students'])['students']);

        return $classroom;
    }

    public function updateClassroom(Classroom $classroom, array $classroomData): Classroom
    {
        $classroom->update($classroomData);

        return $classroom->refresh();
    }

    public function destroyClassroom(Classroom $classroom): void
    {
        $this->removeStudents($classroom);
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
            $classroom->classroomUsers()->whereIn('id', $students)->delete();
        } else {
            $classroom->classroomUsers()->delete();
        }
    }

    public function restoreStudents(Classroom $classroom, array $students): void
    {
        $classroom->classroomUsers()->whereIn('id', $students)->restore($students);
    }
}