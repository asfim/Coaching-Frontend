<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesTest extends TestCase
{
    public function test_all_frontend_routes_return_ok(): void
    {
        $routes = [
            '/',
            '/about',
            '/courses',
            '/course-detail',
            '/teachers',
            '/results',
            '/notice',
            '/blog',
            '/contact',
            '/admin',
            '/admin/students',
            '/admin/student-profile',
            '/admin/teachers',
            '/admin/courses',
            '/admin/batches',
            '/admin/attendance',
            '/admin/exams',
            '/admin/results',
            '/admin/routine',
            '/admin/fees',
            '/admin/invoice',
            '/admin/notices',
            '/admin/sms',
            '/admin/reports',
            '/admin/settings',
            '/student/dashboard',
            '/parent/dashboard',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
