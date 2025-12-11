<?php

if (!function_exists('activity')) {
    /**
     * Log activity helper function
     * Jika package spatie/laravel-activitylog belum diinstall,
     * ini akan membuat log sederhana
     */
    function activity($log = null)
    {
        // Cek apakah package Spatie Activity Log terinstall
        if (class_exists(\Spatie\Activitylog\ActivitylogServiceProvider::class)) {
            return \Spatie\Activitylog\Facades\Activity::log($log);
        }

        // Fallback: Simple logging ke Laravel log
        return new class($log) {
            private $message;
            private $user;
            private $subject;

            public function __construct($log = null)
            {
                $this->message = $log;
            }

            public function causedBy($user)
            {
                $this->user = $user;
                return $this;
            }

            public function performedOn($subject)
            {
                $this->subject = $subject;
                return $this;
            }

            public function log($message)
            {
                $logMessage = "[Activity] ";
                if ($this->user) {
                    $logMessage .= "User: {$this->user->name} - ";
                }
                $logMessage .= $message;

                \Illuminate\Support\Facades\Log::info($logMessage);
                return $this;
            }
        };
    }
}
