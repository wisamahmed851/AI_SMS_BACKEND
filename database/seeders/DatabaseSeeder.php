<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Notification;
use App\Models\ParentModel;
use App\Models\Result;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. ROLES ───────────────────────────────────────────────
        $roles = [];
        foreach (['Super Admin' => 'super_admin', 'Admin' => 'admin', 'Teacher' => 'teacher', 'Student' => 'student', 'Parent' => 'parent'] as $name => $slug) {
            $roles[$slug] = Role::create(['name' => $name, 'slug' => $slug]);
        }

        // ─── 2. USERS ──────────────────────────────────────────────
        $password = Hash::make('password');

        $superAdmin = User::create(['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'password' => $password, 'role_id' => $roles['super_admin']->id]);
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => $password, 'role_id' => $roles['admin']->id]);

        $teacherUser1 = User::create(['name' => 'John Smith', 'email' => 'teacher1@example.com', 'password' => $password, 'role_id' => $roles['teacher']->id]);
        $teacherUser2 = User::create(['name' => 'Jane Doe', 'email' => 'teacher2@example.com', 'password' => $password, 'role_id' => $roles['teacher']->id]);

        $studentUsers = [];
        $studentNames = ['Ali Ahmed', 'Sara Khan', 'Omar Farooq', 'Fatima Noor', 'Hassan Raza'];
        for ($i = 1; $i <= 5; $i++) {
            $studentUsers[$i] = User::create([
                'name' => $studentNames[$i - 1],
                'email' => "student{$i}@example.com",
                'password' => $password,
                'role_id' => $roles['student']->id,
            ]);
        }

        $parentUser1 = User::create(['name' => 'Ahmed Ali', 'email' => 'parent1@example.com', 'password' => $password, 'role_id' => $roles['parent']->id]);
        $parentUser2 = User::create(['name' => 'Khalid Khan', 'email' => 'parent2@example.com', 'password' => $password, 'role_id' => $roles['parent']->id]);
        $parentUser3 = User::create(['name' => 'Nadia Farooq', 'email' => 'parent3@example.com', 'password' => $password, 'role_id' => $roles['parent']->id]);

        // ─── 3. CLASSES ─────────────────────────────────────────────
        $class1 = SchoolClass::create(['name' => 'Class 1']);
        $class2 = SchoolClass::create(['name' => 'Class 2']);
        $class3 = SchoolClass::create(['name' => 'Class 3']);

        // ─── 4. SUBJECTS ────────────────────────────────────────────
        $math = Subject::create(['name' => 'Mathematics']);
        $english = Subject::create(['name' => 'English']);
        $science = Subject::create(['name' => 'Science']);

        // ─── 5. CLASS-SUBJECT PIVOT ─────────────────────────────────
        $class1->subjects()->attach([$math->id, $english->id, $science->id]);
        $class2->subjects()->attach([$math->id, $english->id, $science->id]);
        $class3->subjects()->attach([$math->id, $english->id, $science->id]);

        // ─── 6. TEACHERS ────────────────────────────────────────────
        $teacher1 = Teacher::create(['user_id' => $teacherUser1->id]);
        $teacher2 = Teacher::create(['user_id' => $teacherUser2->id]);

        // Teacher 1 teaches Math & Science, Teacher 2 teaches English
        $teacher1->subjects()->attach([$math->id, $science->id]);
        $teacher2->subjects()->attach([$english->id]);

        // ─── 7. STUDENTS ────────────────────────────────────────────
        $classAssignment = [1 => $class1->id, 2 => $class1->id, 3 => $class2->id, 4 => $class2->id, 5 => $class3->id];
        $students = [];
        foreach ($studentUsers as $i => $su) {
            $students[$i] = Student::create(['user_id' => $su->id, 'class_id' => $classAssignment[$i]]);
        }

        // ─── 8. PARENTS ─────────────────────────────────────────────
        $parent1 = ParentModel::create(['user_id' => $parentUser1->id]);
        $parent2 = ParentModel::create(['user_id' => $parentUser2->id]);
        $parent3 = ParentModel::create(['user_id' => $parentUser3->id]);

        // Link parents to students
        $parent1->students()->attach([$students[1]->id, $students[2]->id]); // parent1 → student1, student2
        $parent2->students()->attach([$students[3]->id, $students[4]->id]); // parent2 → student3, student4
        $parent3->students()->attach([$students[5]->id]);                    // parent3 → student5

        // ─── 9. ATTENDANCE (5+ days for all students) ───────────────
        $startDate = Carbon::now()->subDays(6);
        foreach ($students as $student) {
            for ($d = 0; $d < 7; $d++) {
                $date = $startDate->copy()->addDays($d)->toDateString();
                Attendance::create([
                    'student_id' => $student->id,
                    'date' => $date,
                    'status' => fake()->randomElement(['present', 'present', 'present', 'absent']), // ~75% present
                ]);
            }
        }

        // ─── 10. EXAMS ──────────────────────────────────────────────
        $midTerm = Exam::create(['name' => 'Mid Term']);
        $finalExam = Exam::create(['name' => 'Final Exam']);
        $exams = [$midTerm, $finalExam];

        // ─── 11. RESULTS (every student × every subject × every exam)
        $subjects = [$math, $english, $science];
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                foreach ($exams as $exam) {
                    Result::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'exam_id' => $exam->id,
                        'marks' => fake()->numberBetween(50, 100),
                    ]);
                }
            }
        }

        // ─── 12. FEES (mixed paid/unpaid) ───────────────────────────
        $feeStatuses = ['paid', 'paid', 'unpaid', 'paid', 'unpaid'];
        foreach ($students as $i => $student) {
            Fee::create([
                'student_id' => $student->id,
                'amount' => fake()->randomElement([5000, 7500, 10000, 12000]),
                'status' => $feeStatuses[$i - 1] ?? 'unpaid',
            ]);
        }

        // ─── 13. NOTIFICATIONS ──────────────────────────────────────
        $notifData = [
            [$superAdmin->id, 'System Update', 'New system version deployed successfully.', 'info'],
            [$admin->id, 'New Student Enrolled', 'A new student has been enrolled in Class 1.', 'success'],
            [$teacherUser1->id, 'Attendance Reminder', 'Please mark attendance for today.', 'warning'],
            [$teacherUser2->id, 'Result Submission', 'Final exam results are due by Friday.', 'warning'],
            [$studentUsers[1]->id, 'Fee Due', 'Your tuition fee is due. Please pay before the deadline.', 'danger'],
            [$studentUsers[2]->id, 'Result Published', 'Mid Term results have been published.', 'info'],
            [$studentUsers[3]->id, 'Result Published', 'Mid Term results have been published.', 'info'],
            [$parentUser1->id, 'Child Attendance', 'Your child was absent yesterday.', 'warning'],
            [$parentUser2->id, 'Fee Receipt', 'Fee payment received. Thank you!', 'success'],
            [$parentUser3->id, 'Parent Meeting', 'Parent-teacher meeting scheduled for next Monday.', 'info'],
        ];

        foreach ($notifData as $n) {
            Notification::create([
                'user_id' => $n[0],
                'title' => $n[1],
                'message' => $n[2],
                'type' => $n[3],
                'data' => null,
            ]);
        }

        // ─── 14. TASKS & SUBMISSIONS ─────────────────────────────────
        $task1 = \App\Models\Task::create([
            'teacher_id' => $teacher1->id,
            'class_id' => $class1->id,
            'subject_id' => $math->id,
            'title' => 'Algebra Worksheet 1',
            'description' => 'Complete the attached math worksheet focusing on linear equations.',
            'deadline' => Carbon::now()->addDays(2),
        ]);

        $task2 = \App\Models\Task::create([
            'teacher_id' => $teacher1->id,
            'class_id' => $class2->id,
            'subject_id' => $science->id,
            'title' => 'Biology Lab Report',
            'description' => 'Write a short report on the cell structure observed in class.',
            'deadline' => Carbon::now()->subDay(), // Past deadline
        ]);

        // Student 1 submits Task 1
        \App\Models\TaskSubmission::create([
            'task_id' => $task1->id,
            'student_id' => $students[1]->id,
            'content' => 'Here is my submission: https://docs.google.com/xyz',
            'submitted_at' => Carbon::now()->subHours(2),
        ]);

        // ─── 15. LEAVES ───────────────────────────────────────────────
        \App\Models\Leave::create([
            'user_id' => $teacherUser1->id,
            'role' => 'teacher',
            'reason' => 'Feeling unwell today, need to visit the doctor.',
            'from_date' => Carbon::now()->subDays(2),
            'to_date' => Carbon::now()->subDays(1),
            'status' => 'approved',
        ]);
        
        \App\Models\Leave::create([
            'user_id' => $parentUser1->id,
            'role' => 'parent',
            'student_id' => $students[1]->id,
            'reason' => 'Family emergency out of town.',
            'from_date' => Carbon::today(),
            'to_date' => Carbon::today()->addDays(3),
            'status' => 'pending',
        ]);

        \App\Models\Leave::create([
            'user_id' => $studentUsers[3]->id,
            'role' => 'student',
            'student_id' => $students[3]->id,
            'reason' => 'Going to participate in a math olympiad.',
            'from_date' => Carbon::tomorrow(),
            'to_date' => Carbon::tomorrow()->addDays(1),
            'status' => 'rejected',
        ]);

        // ─── 16. TEACHER ATTENDANCE ──────────────────────────────────
        $tDate = Carbon::yesterday()->toDateString();
        \App\Models\TeacherAttendance::create([
            'teacher_id' => $teacher1->id,
            'date' => $tDate,
            'status' => 'present',
        ]);
        \App\Models\TeacherAttendance::create([
            'teacher_id' => $teacher2->id,
            'date' => $tDate,
            'status' => 'late',
        ]);

        // ─── 17. FEE PAYMENTS ────────────────────────────────────────
        $allFees = Fee::all();
        foreach($allFees as $fee) {
            if($fee->status === 'paid') {
                \App\Models\FeePayment::create([
                    'fee_id' => $fee->id,
                    'amount' => $fee->amount,
                    'method' => 'Bank Transfer',
                    'paid_at' => Carbon::now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
