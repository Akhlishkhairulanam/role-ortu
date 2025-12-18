<?php
// app/Policies/StudentPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function view(User $user, Student $student)
    {
        // Student hanya bisa melihat datanya sendiri
        if ($user->role === 'student') {
            return $user->student && $user->student->id === $student->id;
        }

        // Parent hanya bisa melihat data anaknya
        if ($user->role === 'parent') {
            return $user->parentStudent && $user->parentStudent->id === $student->id;
        }

        return $user->role === 'admin' || $user->role === 'guru';
    }

    public function update(User $user, Student $student)
    {
        // Student tidak bisa mengubah data akademik
        return $user->role === 'admin';
    }
}
