import ApexCharts from "apexcharts";
const $sc_manageChart = {};

class scChart{
    chartLive = [false, {}];
    _element = null;
    _options = {};
    _chart = null;

    constructor(element, options){
        this._element = element;
        this._options = options;
        this._chart = new ApexCharts(element, options);
        (options.chart?.id ?? element.id) && ($sc_manageChart[options.chart?.id ?? element.id] = this._chart);

        this._chart.render();
        this.#realtimeUpdateData();
    }

    #realtimeUpdateData(){
        Livewire.hook('morph.updated', () => {
            if((!Array.isArray(this.chartLive) && !console.warn("scChart: The 'chartLive' property is invalid")) || !this.chartLive[0]) return void 0;

            (this.chartLive[1] || console.warn("scChart: The value of chartLive[1] is invalid")) && this.updateOptions(this.chartLive[1], false);
        })
    }

    getElement(){
        return this._element;
    }

    static callMethod(chartID, method, ...args){
        if(!chartID) chartID = this._options.chart?.id ?? this._element.id;
        if(!method) throw new Error("No method specified");

        if($sc_manageChart[chartID]){
            return $sc_manageChart[chartID][method](...args);
        }else{
            return ApexCharts.exec(chartID, method, ...args);
        }
    }
    getElement(){
        return this._element;
    }

    async appendData(newData){
        if(!newData || !Array.isArray(newData)) throw new Error("Invalid new data: expected an array");
        this._chart.appendData(newData);
    }

    async updateOptions(optionsNew, redraw = false, animate = true, updateSyncedCharts = true){
        if(!optionsNew || typeof optionsNew !== 'object') throw new Error("Invalid new options: expected an object");
        return this._chart.updateOptions(optionsNew, redraw, animate, updateSyncedCharts);
    }

    async updateSeries(newSeries, animate = true){
        if(!newSeries || !Array.isArray(newSeries)) throw new Error("Invalid new series: expected an array");
        return this._chart.updateSeries(newSeries, animate);
    }

    async appendSeries(newSerie, animate = true){
        if(!newSerie || typeof newSerie !== 'object') throw new Error("Invalid new serie: expected an object");
        return this._chart.appendSeries(newSerie, animate);
    }

    toggleSeries(seriesName){
        return this._chart.toggleSeries(seriesName);
    }

    toggleDataPointSelection(seriesIndexOrValue, dataPointIndex){
        const fnFindIndex = function(item, valueCompared){
            valueCompared = isNaN(parseInt(valueCompared)) ? valueCompared : parseInt(valueCompared);
            if(typeof item !== "object"){
                if(item === valueCompared) return true;
                else return false;
            }else{
                if(item.data.some(v => v === valueCompared) || item.name === valueCompared) return true;
                else return false;
            }
        }

        if(typeof seriesIndexOrValue !== "number"){
            seriesIndexOrValue = this._options?.series.findIndex(item => fnFindIndex(item, seriesIndexOrValue));
            seriesIndexOrValue === -1 && (seriesIndexOrValue = undefined);
        }

        if(typeof dataPointIndex !== "number"){
            dataPointIndex = this._options?.series[seriesIndexOrValue]?.data.findIndex(item => fnFindIndex(item, dataPointIndex));
            dataPointIndex === -1 && (dataPointIndex = undefined);
        }

        return this._chart.toggleDataPointSelection(seriesIndexOrValue, dataPointIndex);
    }

    showSeries(seriesName){
        this._chart.showSeries(seriesName);
    }

    hideSeries(seriesName){
        this._chart.hideSeries(seriesName);
    }

    highlightSeries(seriesName){
        this._chart.highlightSeries(seriesName);
    }

    resetSeries(shouldUpdateChart = true, shouldResetZoom = true){
        this._chart.resetSeries(shouldUpdateChart, shouldResetZoom);
    }

    zoomX(start, end){
        this._chart.zoomX(start, end);
    }

    async dataURI(options){
        return this._chart.dataURI(options);
    }

    destroy(){
        this._chart.destroy();
    }
}

document.addEventListener('livewire:init', () => {
    window.createScChart = function(element, options){
        return new scChart(element, options);
    }

    window.callMethodScChart = scChart.callMethod;
});
