<?php

namespace App\Services\Admin;

use App\Models\payment;

class PaymentService
{
    public function createPayment(array $paymentData): Payment
    {
        $payment = Payment::create($paymentData);
        return $payment;
    }

    public function updatePayment(Payment $payment, array $paymentData): Payment
    {
        $payment->update($paymentData);
        return $payment;
    }

    public function destroyPayment(Payment $payment): void
    {
        $payment->delete();
    }

    public function syncStudents(Payment $payment, array $students): array
    {
        $synced = $payment->classroomUsers()->sync($students);
        return $synced;
    }

    public function calculateStudentsCredit(array $oldStudentsCredit, array $newStudentsCredit): array
    {
        $studentsCredit = [];
        foreach (array_keys($newStudentsCredit + $oldStudentsCredit) as $key) {
            $studentsCredit[$key] = ($newStudentsCredit[$key]['new_credit'] ?? 0) - ($oldStudentsCredit[$key]['new_credit'] ?? 0);
        }
        return $studentsCredit;
    }

    public function formatStudentsCreditArray(Payment $payment): array
    {
        $studentsCredit = $payment->classroomUsers()->pluck('new_credit', 'classroom_user_id')->map(function ($value) {
            return ['new_credit' => $value];
        });
        return $studentsCredit->all();
    }
}