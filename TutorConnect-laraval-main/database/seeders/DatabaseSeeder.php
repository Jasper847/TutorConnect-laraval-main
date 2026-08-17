<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\StudentProfile;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Subject;
use App\Models\Message;
use App\Models\StudyMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. Core Subjects Taxonomy
        // -------------------------------------------------------------
        $subjectsData = [
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'description' => 'Algebra, Geometry, Trigonometry, and Advanced Calculus.'],
            ['name' => 'Physics', 'slug' => 'physics', 'description' => 'Mechanics, Electromagnetism, Thermodynamics, and Quantum Theory.'],
            ['name' => 'Chemistry', 'slug' => 'chemistry', 'description' => 'Organic, Inorganic, Physical Chemistry, and Lab problem solving.'],
            ['name' => 'Computer Science', 'slug' => 'computer-science', 'description' => 'Python, Data Structures, Web Development, and Algorithms.'],
            ['name' => 'English', 'slug' => 'english', 'description' => 'Grammar, Literature, Essay Writing, and IELTS / TOEFL preparation.'],
            ['name' => 'Biology', 'slug' => 'biology', 'description' => 'Cell Biology, Genetics, Physiology, and Medical Exam Prep.'],
        ];

        $subjectMap = [];
        foreach ($subjectsData as $s) {
            $subjectMap[$s['name']] = Subject::updateOrCreate(['slug' => $s['slug']], $s);
        }

        // -------------------------------------------------------------
        // 2. Admin User
        // -------------------------------------------------------------
        $admin = User::updateOrCreate(
            ['email' => 'admin@tutorconnect.com'],
            [
                'name' => 'TutorConnect Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+92 300 1234567',
                'city' => 'Islamabad',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // -------------------------------------------------------------
        // 3. 5 Tutor Users with Full Profiles
        // -------------------------------------------------------------
        $tutorsData = [
            [
                'name' => 'Ahmed Khan',
                'email' => 'ahmed.khan@tutorconnect.com',
                'password' => 'password',
                'phone' => '+92 301 5551101',
                'city' => 'Lahore',
                'headline' => 'Senior Mathematics & Calculus Specialist',
                'bio' => "Hello! I am a passionate mathematics educator with over 8 years of teaching experience. I specialize in making abstract calculus and algebra concepts intuitive through real-world problem breakdowns.",
                'subjects' => ['Mathematics', 'Physics'],
                'hourly_rate' => 3500.00,
                'experience_years' => 8,
                'education' => 'M.Sc. in Applied Mathematics (NUST)',
                'location' => 'Lahore (Online & In-Person)',
                'is_verified' => true,
            ],
            [
                'name' => 'Dr. Sarah Siddiqui',
                'email' => 'dr.sarah@tutorconnect.com',
                'password' => 'password',
                'phone' => '+92 302 5551102',
                'city' => 'Karachi',
                'headline' => 'Organic & Physical Chemistry Ph.D. Mentor',
                'bio' => "Doctorate in Organic Chemistry with 10 years of college lecture experience. I help high school and university students master chemical kinetics, synthesis pathways, and exam strategies.",
                'subjects' => ['Chemistry'],
                'hourly_rate' => 4500.00,
                'experience_years' => 10,
                'education' => 'Ph.D. in Chemistry (University of Karachi)',
                'location' => 'Karachi (Online)',
                'is_verified' => true,
            ],
            [
                'name' => 'Fatima Noor',
                'email' => 'fatima.noor@tutorconnect.com',
                'password' => 'password',
                'phone' => '+92 303 5551103',
                'city' => 'Islamabad',
                'headline' => 'IELTS 8.5 Master Coach & English Literature Specialist',
                'bio' => "Certified English Language Trainer with an IELTS 8.5 score. Dedicated to helping students improve essay cohesion, vocabulary, academic writing, and speaking fluency.",
                'subjects' => ['English'],
                'hourly_rate' => 3000.00,
                'experience_years' => 5,
                'education' => 'M.A. in English Linguistics (QAU Islamabad)',
                'location' => 'Islamabad (Online & In-Person)',
                'is_verified' => true,
            ],
            [
                'name' => 'Dr. Usman Farooq',
                'email' => 'usman.farooq@tutorconnect.com',
                'password' => 'password',
                'phone' => '+92 304 5551104',
                'city' => 'Rawalpindi',
                'headline' => 'Physics & Engineering Mechanics Expert',
                'bio' => "Ph.D. in Physics with extensive background teaching AP Physics, classical mechanics, and electromagnetism. I emphasize conceptual clarity and step-by-step problem solving.",
                'subjects' => ['Physics', 'Mathematics'],
                'hourly_rate' => 4000.00,
                'experience_years' => 9,
                'education' => 'Ph.D. in Physics (PIEAS)',
                'location' => 'Rawalpindi (Online)',
                'is_verified' => true,
            ],
            [
                'name' => 'Bilal Tariq',
                'email' => 'bilal.tariq@tutorconnect.com',
                'password' => 'password',
                'phone' => '+92 305 5551105',
                'city' => 'Lahore',
                'headline' => 'Full-Stack Software Engineer & CS Tutor',
                'bio' => "Professional software engineer with 6 years of industry and tutoring experience. I guide beginners and college students in Python, Data Structures, OOP, and web architecture.",
                'subjects' => ['Computer Science', 'Mathematics'],
                'hourly_rate' => 4000.00,
                'experience_years' => 6,
                'education' => 'B.S. in Computer Science (FAST-NUCES)',
                'location' => 'Lahore (Online)',
                'is_verified' => true,
            ],
        ];

        $tutorUsers = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        foreach ($tutorsData as $tData) {
            $user = User::updateOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make($tData['password']),
                    'role' => 'tutor',
                    'phone' => $tData['phone'],
                    'city' => $tData['city'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            TutorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'headline' => $tData['headline'],
                    'bio' => $tData['bio'],
                    'subjects' => $tData['subjects'],
                    'hourly_rate' => $tData['hourly_rate'],
                    'experience_years' => $tData['experience_years'],
                    'education' => $tData['education'],
                    'location' => $tData['location'],
                    'is_verified' => $tData['is_verified'],
                    'is_available' => true,
                    'avg_rating' => 5.00,
                    'reviews_count' => 0,
                ]
            );

            // Availability Slots
            foreach ($days as $day) {
                AvailabilitySlot::updateOrCreate(
                    ['tutor_id' => $user->id, 'day_of_week' => $day],
                    [
                        'start_time' => '10:00:00',
                        'end_time' => '18:00:00',
                        'is_booked' => false,
                    ]
                );
            }

            $tutorUsers[] = $user;
        }

        // -------------------------------------------------------------
        // 4. 10 Student Users with Profiles
        // -------------------------------------------------------------
        $studentsData = [
            ['name' => 'Demo Student', 'email' => 'student@tutorconnect.com', 'grade' => 'A-Levels (Year 2)', 'subjects' => ['Mathematics', 'Physics'], 'about' => 'Preparing for upcoming Cambridge A2 examinations and university entrance tests.'],
            ['name' => 'Ali Raza', 'email' => 'ali.raza@tutorconnect.com', 'grade' => 'Grade 12', 'subjects' => ['Mathematics'], 'about' => 'Looking to excel in Calculus and Pre-Engineering mathematics.'],
            ['name' => 'Ayesha Malik', 'email' => 'ayesha.malik@tutorconnect.com', 'grade' => 'Undergraduate', 'subjects' => ['Computer Science'], 'about' => 'Learning Python programming and algorithms for my degree coursework.'],
            ['name' => 'Hamza Sheikh', 'email' => 'hamza.sheikh@tutorconnect.com', 'grade' => 'IELTS Aspirant', 'subjects' => ['English'], 'about' => 'Targeting IELTS Band 8.0 for study abroad applications.'],
            ['name' => 'Zainab Bibi', 'email' => 'zainab.bibi@tutorconnect.com', 'grade' => 'O-Levels (Year 11)', 'subjects' => ['Chemistry', 'Physics'], 'about' => 'Striving for straight A* in science subjects.'],
            ['name' => 'Omer Tariq', 'email' => 'omer.tariq@tutorconnect.com', 'grade' => 'F.Sc. Pre-Medical', 'subjects' => ['Chemistry'], 'about' => 'MDCAT entry test preparation and organic chemistry mastery.'],
            ['name' => 'Mariam Hassan', 'email' => 'mariam.hassan@tutorconnect.com', 'grade' => 'Grade 10', 'subjects' => ['Mathematics', 'English'], 'about' => 'Seeking regular 1-on-1 tutoring support with school syllabus.'],
            ['name' => 'Bilal Ahmed', 'email' => 'bilal.ahmed@tutorconnect.com', 'grade' => 'Undergraduate', 'subjects' => ['Computer Science'], 'about' => 'Full-stack web development and database management.'],
            ['name' => 'Sana Javed', 'email' => 'sana.javed@tutorconnect.com', 'grade' => 'A-Levels', 'subjects' => ['Physics'], 'about' => 'A-Level physics mechanics and circular motion problem solving.'],
            ['name' => 'Noor Fatima', 'email' => 'noor.fatima@tutorconnect.com', 'grade' => 'College Sophomore', 'subjects' => ['English'], 'about' => 'Academic writing and research paper formatting skills.'],
        ];

        $studentUsers = [];
        foreach ($studentsData as $sData) {
            $sUser = User::updateOrCreate(
                ['email' => $sData['email']],
                [
                    'name' => $sData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'phone' => '+92 321 ' . rand(1000000, 9999999),
                    'city' => 'Lahore',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            StudentProfile::updateOrCreate(
                ['user_id' => $sUser->id],
                [
                    'grade_level' => $sData['grade'],
                    'subjects_needed' => $sData['subjects'],
                    'about' => $sData['about'],
                ]
            );

            $studentUsers[] = $sUser;
        }

        // -------------------------------------------------------------
        // 5. Sample Bookings in Different Statuses
        // -------------------------------------------------------------
        $bookingConfigs = [
            [
                'student' => $studentUsers[0],
                'tutor' => $tutorUsers[0], // Ahmed Khan (Math)
                'subject' => 'Mathematics',
                'date' => now()->addDays(2)->format('Y-m-d'),
                'start' => '14:00:00',
                'end' => '15:00:00',
                'status' => 'confirmed',
                'notes' => 'Exam review of Integration by parts and differential equations.',
                'amount' => 3500.00,
            ],
            [
                'student' => $studentUsers[1],
                'tutor' => $tutorUsers[1], // Dr. Sarah (Chemistry)
                'subject' => 'Chemistry',
                'date' => now()->addDays(3)->format('Y-m-d'),
                'start' => '16:00:00',
                'end' => '17:30:00',
                'status' => 'pending',
                'notes' => 'Stereochemistry and reaction mechanism practice.',
                'amount' => 6750.00,
            ],
            [
                'student' => $studentUsers[2],
                'tutor' => $tutorUsers[4], // Bilal Tariq (CS)
                'subject' => 'Computer Science',
                'date' => now()->subDays(3)->format('Y-m-d'),
                'start' => '11:00:00',
                'end' => '12:00:00',
                'status' => 'completed',
                'notes' => 'Python OOP, classes and inheritance deep dive.',
                'amount' => 4000.00,
            ],
            [
                'student' => $studentUsers[3],
                'tutor' => $tutorUsers[2], // Fatima Noor (English)
                'subject' => 'English',
                'date' => now()->subDays(5)->format('Y-m-d'),
                'start' => '17:00:00',
                'end' => '18:00:00',
                'status' => 'completed',
                'notes' => 'IELTS Task 2 academic essay writing correction.',
                'amount' => 3000.00,
            ],
            [
                'student' => $studentUsers[4],
                'tutor' => $tutorUsers[3], // Dr. Usman (Physics)
                'subject' => 'Physics',
                'date' => now()->subDays(2)->format('Y-m-d'),
                'start' => '15:00:00',
                'end' => '16:00:00',
                'status' => 'cancelled',
                'notes' => 'Electromagnetism flux calculation.',
                'amount' => 4000.00,
            ],
            [
                'student' => $studentUsers[0],
                'tutor' => $tutorUsers[4], // Bilal Tariq (CS)
                'subject' => 'Computer Science',
                'date' => now()->subDays(7)->format('Y-m-d'),
                'start' => '18:00:00',
                'end' => '19:00:00',
                'status' => 'completed',
                'notes' => 'Binary search tree traversal implementation.',
                'amount' => 4000.00,
            ],
        ];

        foreach ($bookingConfigs as $idx => $bData) {
            $booking = Booking::updateOrCreate(
                ['booking_code' => 'TC-' . strtoupper(Str::random(6))],
                [
                    'student_id' => $bData['student']->id,
                    'tutor_id' => $bData['tutor']->id,
                    'subject' => $bData['subject'],
                    'booking_date' => $bData['date'],
                    'start_time' => $bData['start'],
                    'end_time' => $bData['end'],
                    'status' => $bData['status'],
                    'mode' => 'online',
                    'notes' => $bData['notes'],
                    'total_amount' => $bData['amount'],
                    'cancellation_reason' => $bData['status'] === 'cancelled' ? 'Rescheduled due to family commitment.' : null,
                ]
            );

            // Payment for confirmed & completed
            if (in_array($bData['status'], ['confirmed', 'completed'])) {
                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'stripe_payment_intent_id' => 'pi_demo_' . Str::random(16),
                        'amount' => $bData['amount'],
                        'currency' => 'PKR',
                        'status' => 'paid',
                        'is_demo' => true,
                    ]
                );
            }

            // ---------------------------------------------------------
            // 6. Sample Reviews with Ratings 3-5 for Completed Sessions
            // ---------------------------------------------------------
            if ($bData['status'] === 'completed') {
                $comments = [
                    "Exceptional tutor! The concepts were explained with incredible clarity. Highly recommend to any student preparing for exams.",
                    "Very thorough explanation of topics and answered all my practice questions patiently. 5 stars!",
                    "Great session overall. The pacing was good and the problem sets we solved were very relevant to the exam.",
                ];

                $rating = rand(4, 5);
                Review::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'student_id' => $bData['student']->id,
                        'tutor_id' => $bData['tutor']->id,
                        'rating' => $rating,
                        'comment' => $comments[$idx % count($comments)],
                    ]
                );
            }
        }

        // -------------------------------------------------------------
        // 7. Recalculate Rating Caches for all Tutors
        // -------------------------------------------------------------
        foreach ($tutorUsers as $tUser) {
            $profile = TutorProfile::where('user_id', $tUser->id)->first();
            if ($profile) {
                $profile->updateRatingCache();
            }
        }
    }
}
