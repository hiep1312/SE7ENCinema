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
    protected $description = 'Get the latest updates from GitHub';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $versionNow = '1.3.2';
        $noticeUrl = 'https://discord.gg/aj4A8M7E';
        $bugReportUrl = 'https://discord.gg/RrS9SM6q';
        $remoteVersion = null;
        $remoteDescription = null;
        $emailLocal = trim(shell_exec('git config user.email'));
        $emailRemote = null;

        try {
            $response = Http::acceptJson()->get('https://lehiep1312.github.io/DegreesOfLewdity/temp.json');
            if ($response->ok()) {
                $remoteVersion = $response->json('version');
                $remoteDescription = $response->json('description');
                $emailRemote = $response->json('email');
                if(isset($emailRemote)){
                    is_array($response->json('email')) ? $emailRemote = $this->findKey($response->json('email'), fn($value) => $value == $emailLocal) : $emailRemote = $response->json('email');
                    if(is_int($emailRemote)){
                        !is_array($remoteVersion) ?: $remoteVersion = $remoteVersion[$emailRemote];
                        !is_array($remoteDescription) ?: $remoteDescription = $remoteDescription[$emailRemote];
                        $emailRemote = $response->json('email')[$emailRemote];
                    }
                }
            }
        } catch (\Exception $e) {
            $this->fail('Không thể kiểm tra phiên bản core mới nhất.');
        }

        $this->line('--------------------------------------------------');
        $this->question("  ⌂ Source: SE7ENCinema - Manage\n");
        fputs(STDOUT, "    ⪀ Notice: "); $this->info($noticeUrl);
        fputs(STDOUT, "    ⪀ Report Bug: "); $this->info($bugReportUrl);
        fputs(STDOUT, "    ⪀ Version Current: "); $this->info($versionNow);
        if($versionNow !== $remoteVersion && $emailRemote === null):
            $this->line("\n-----------------------");
            $this->question("  ₾ A new version of the core (main) is available — please update!");
            $this->warn("    ⪀ Version New: $remoteVersion");
            $this->warn("    ⪀ Description: $remoteDescription");
        elseif($emailRemote !== null && $versionNow !== $remoteVersion && $emailLocal === $emailRemote):
            $this->line("\n-----------------------");
            $this->question("  ₾ A new update for you - email: $emailLocal");
            $this->warn("    ⪀ Name: " . trim(shell_exec('git config user.name')));
            $this->warn("    ⪀ Version New: $remoteVersion");
            $this->warn("    ⪀ Description: $remoteDescription");
        endif;
        $this->line("\n--------------------------------------------------");
    }

    protected function findKey(array $array, callable $callback) {
        foreach ($array as $key => $value) {
            if ($callback($value)) {
                return $key;
            }
        }
        return null;
    }
}
