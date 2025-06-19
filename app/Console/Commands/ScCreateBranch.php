<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScCreateBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:branch {branchName : The name of the new branch to create on both GitHub and locally}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '🌿 Creates a new branch from the main branch on GitHub (via API) and locally using Git.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $branchName = $this->argument('branchName');

            $headers = [
                "Accept" => "application/vnd.github+json",
                "Authorization" => "Bearer " . config('services.github.token'),
                "X-GitHub-Api-Version" => "2022-11-28"
            ];

            $this->info('🔎 Fetching main branch reference from GitHub...');
            $getInfoMain = Http::withHeaders($headers)->get('https://api.github.com/repos/hiep1312/SE7ENCinema/git/refs/heads/main');

            if(!$getInfoMain->ok()) $this->fail("❌ Failed to fetch the main branch reference from GitHub!\n\t⛔ {$getInfoMain->body()}!");

            $sha = $getInfoMain->json('object.sha');

            $this->info("🌐 Creating new branch '{$branchName}' on GitHub...");
            $createBranch = Http::withHeaders($headers)->post('https://api.github.com/repos/hiep1312/SE7ENCinema/git/refs', [
                'ref' => 'refs/heads/' . $branchName,
                'sha' => $sha
            ]);
            if(!$createBranch->created()) $this->fail("❌ Failed to create branch '{$branchName}' on GitHub!\n\t⛔ {$getInfoMain->body()}!");

            $this->info("💻 Creating local branch '{$branchName}'...");
            shell_exec('git checkout -b ' . $branchName);
            $this->question("✅ Branch '{$branchName}' has been successfully created on both GitHub and locally.");

            return self::SUCCESS;
        }catch(\Throwable $e){
            $this->error("❌ Unexpected error: \n\t{$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
