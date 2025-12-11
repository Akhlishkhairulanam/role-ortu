<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupAkademikCommand extends Command
{
    protected $signature = 'akademik:setup {--fresh : Fresh installation dengan drop semua table}';
    protected $description = 'Setup Sistem Akademik Sekolah';

    public function handle()
    {
        $this->info('🎓 Memulai Setup Sistem Akademik Sekolah...');
        $this->newLine();

        // Cek database connection
        if (!$this->checkDatabaseConnection()) {
            $this->error('❌ Tidak dapat terhubung ke database!');
            $this->error(' Pastikan database sudah dibuat dan konfigurasi .env sudah benar.');
            return 1;
        }

        $this->info('✅ Database connection OK');
        $this->newLine();

        // Run migrations
        if ($this->option('fresh')) {
            $this->warn('⚠️ Fresh installation mode - Semua data akan dihapus!');
            if ($this->confirm('Lanjutkan?', false)) {
                $this->call('migrate:fresh', ['--force' => true]);
            } else {
                return 0;
            }
        } else {
            $this->call('migrate');
        }

        $this->info('✅ Migrations completed');
        $this->newLine();

        // Run seeders
        if ($this->confirm('Jalankan seeders untuk data awal?', true)) {
            $this->call('db:seed');
            $this->info('✅ Seeders completed');
        }

        $this->newLine();

        // Create storage link
        if (!File::exists(public_path('storage'))) {
            $this->call('storage:link');
            $this->info('✅ Storage linked');
        }

        $this->newLine();

        // Clear cache
        $this->info('🧹 Membersihkan cache...');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->info('✅ Cache cleared');

        $this->newLine();

        // Show credentials
        $this->displayCredentials();

        $this->newLine();
        $this->info('🎉 Setup selesai! Sistem siap digunakan.');
        $this->info(' Jalankan: php artisan serve');

        return 0;
    }

    private function checkDatabaseConnection()
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function displayCredentials()
    {
        $this->info('═══════════════════════════════════════');
        $this->info(' AKUN DEFAULT SISTEM');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        $this->line('👨‍💼 <fg=cyan>ADMIN</>');
        $this->line(' Username: <fg=green>admin</>');
        $this->line(' Password: <fg=green>admin123</>');
        $this->newLine();

        $this->line('👨‍👩‍👧 <fg=cyan>ORANG TUA (untuk testing)</>');
        $this->line(' Username/NIS: <fg=green>2024001</> (Ahmad Rizki)');
        $this->line(' Username/NIS: <fg=green>2024002</> (Siti Nurhaliza)');
        $this->line(' Username/NIS: <fg=green>2024003</> (Budi Santoso)');
        $this->line(' Password: <fg=green>password123</> (semua akun)');
        $this->newLine();

        $this->info('═══════════════════════════════════════');
    }
}
