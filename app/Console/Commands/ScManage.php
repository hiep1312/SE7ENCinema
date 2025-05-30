<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScManage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $versionNow = '1.2.6';
        $noticeUrl = 'https://discord.gg/aj4A8M7E';
        $bugReportUrl = 'https://discord.gg/RrS9SM6q';
        $remoteVersion = null;
        $remoteDescription = null;

        try {
            $response = Http::acceptJson()->get('https://lehiep1312.github.io/DegreesOfLewdity/temp.json');
            if ($response->ok()) {
                $remoteVersion = $response->json('version');
                $remoteDescription = $response->json('description');
            }
        } catch (\Exception $e) {
            $this->fail('Không thể kiểm tra phiên bản core mới nhất.');
        }

        $this->line('--------------------------------------------------');
        $this->question("  ⌂ Source: SE7ENCinema - Manage\n");
        fputs(STDOUT, "    ⪀ Notice: "); $this->info($noticeUrl);
        fputs(STDOUT, "    ⪀ Report Bug: "); $this->info($bugReportUrl);
        fputs(STDOUT, "    ⪀ Version Current: "); $this->info($versionNow);
        if($versionNow !== $remoteVersion):
            $this->line("\n-----------------------");
            $this->question("  ₾ A new version of the core (main) is available — please update!");
            $this->warn("    ⪀ Version New: $remoteVersion");
            $this->warn("    ⪀ Description: $remoteDescription");
        endif;
        $this->line("\n--------------------------------------------------");
    }
}
