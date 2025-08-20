<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class scChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:chart {chartName : The name of the chart to be generated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new chart file with the specified name and type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chartPath = $this->argument('chartName');
        $chartName = basename($chartPath);
        $chartNamespace = dirname(str_replace("/", "\\", $chartPath));
        if($chartNamespace === ".") $chartNamespace = "";
        else $chartNamespace = "\\" . $chartNamespace;

        if(!file_exists(app_path("Charts"))) mkdir(app_path("Charts"), 0777, true);
        elseif(Storage::disk('chart')->exists("{$chartPath}.php")){
            $this->fail("The chart {$chartName}.php already exists.");
            return;
        }

        $contentFile = <<<PHP
        <?php

        namespace App\Charts{$chartNamespace};

        class {$chartName} {
            protected \$data;

            protected function queryData(?string \$filter = null){
                /* Viết truy vấn CSDL tại đây */

            }

            public function loadData(?string \$filter = null){
                \$this->data = \$this->queryData(\$filter);
            }

            protected function bindDataToElement(){
                return "document.getElementById('...')";
            }

            protected function buildChartConfig(){
                /* Viết cấu hình biểu đồ tại đây */
                return <<<JS
                {

                }
                JS;
            }

            public function getFilterText(string \$filterValue){
                return match (\$filterValue){
                    default => "N/A"
                };
            }

            public function getChartConfig(){
                return \$this->buildChartConfig();
            }

            public function getData(){
                return \$this->data;
            }

            public function getEventName(){
                return "updateData{$chartName}";
            }

            public function compileJavascript(){
                \$ctxText = "ctx{$chartName}";
                \$optionsText = "options{$chartName}";
                \$chartText = "chart{$chartName}";
                echo <<<JS
                Livewire.on("{\$this->getEventName()}", async function ([data]){
                    await new Promise(resolve => setTimeout(resolve));
                    const {\$ctxText} = {\$this->bindDataToElement()};
                    if(\$ctxText){
                        if(window.{\$chartText} && document.contains(window.{\$chartText}.getElement())) (window.{\$optionsText} = new Function("return " + data)()) && (window.{\$chartText}.updateOptions(window.{\$optionsText}));
                        else (window.{\$optionsText} = {\$this->buildChartConfig()}) &&  (window.{\$chartText} = createScChart({\$ctxText}, {\$optionsText}));
                    }
                });
                JS;
            }
        }
        PHP;

        if(Storage::drive("chart")->put("{$chartPath}.php", $contentFile)) $this->info("Chart file '{$chartName}.php' created successfully.");
        else $this->fail("Unable to write chart file '{$chartName}.php' to disk 'chart'.");
    }
}
