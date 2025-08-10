<?php
    namespace SE7ENCinema;

    use RuntimeException;
    use Throwable;

    trait scChart {
        protected function loadDataCharts(array|object ...$charts){
            for($i = 0; $i < count($charts); $i++)
                try{ is_array($charts[$i]) ? $charts[$i][0]->loadData($charts[$i][1]) : $charts[$i]->loadData(); }
                catch(Throwable $e){ throw new RuntimeException("Load data error at chart index {$i}"); }
        }

        protected function getDataCharts(object ...$charts){
            $data = [];
            for($i = 0; $i < count($charts); $i++)
                try{ $data[] = $charts[$i]->getData(); }
                catch(Throwable $e){ throw new RuntimeException("Get data error at chart index {$i}"); }
            return $data;
        }

        protected function getDataChartsConfig(object ...$charts){
            $data = [];
            for($i = 0; $i < count($charts); $i++)
                try{ $data[] = $charts[$i]->getChartConfig(); }
                catch(Throwable $e){ throw new RuntimeException("Get chart config error at chart index {$i}"); }
            return $data;
        }

        protected function realtimeUpdateCharts(array|object ...$charts){
            $this->loadDataCharts(...$charts);
            for($i = 0; $i < count($charts); $i++)
                try{ $this->dispatch(is_array($charts[$i]) ? $charts[$i][0]->getEventName() : $charts[$i]->getEventName(), is_array($charts[$i]) ? $charts[$i][0]->getChartConfig() : $charts[$i]->getChartConfig()); }
                catch(Throwable $e){ throw new RuntimeException("Chart data retrieval error at index {$i}"); }
        }
    }
