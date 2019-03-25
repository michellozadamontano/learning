<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">KeyStats Traiding</h3>

                <div class="card-tools">
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                  <div class="col-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="ticker" placeholder="Intrduzca el symbol" aria-label="Intrduzca el symbol" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary green" type="button" id="button-addon2" @click="getTrade()">Mostrar</button>
                        </div>
                  </div>                  
                </div>
                <div class="row">
                    <div class="col-6">
                        <ul>
                            <li>companyName             : <span class="red">{{api.companyName}}</span></li>                            
                            <li>marketcap               : <span class="purple">{{api.marketcap}}</span></li>                          
                            <li>beta                    : <span class="red">{{api.beta}}</span></li>                            
                            <li>week52high              : <span class="red">{{api.week52high}}</span></li>
                            <li>week52low               : <span class="purple">{{api.week52low}}</span></li>
                            <li>week52change            : <span class="yellow">{{api.week52change}}</span></li>
                            <li>shortInterest           : <span class="cyan">{{api.shortInterest}}</span></li>
                            <li>shortDate               : <span class="red">{{api.shortDate}}</span></li>
                            <li>dividendRate            : <span class="purple">{{api.dividendRate}}</span></li>
                            <li>dividendYield           : <span class="yellow">{{api.dividendYield}}</span></li>
                            <li>exDividendDate          : <span class="cyan">{{api.exDividendDate}}</span></li> 
                            <li>latestEPS               : <span class="red">{{api.latestEPS}}</span></li>
                            <li>latestEPSDate           : <span class="purple">{{api.latestEPSDate}}</span></li>
                            <li>sharesOutstanding       : <span class="yellow">{{api.sharesOutstanding}}</span></li>
                            <li>float                   : <span class="cyan">{{api.float}}</span></li>  
                            <li>returnOnEquity          : <span class="red">{{api.returnOnEquity}}</span></li>
                            <li>consensusEPS            : <span class="purple">{{api.consensusEPS}}</span></li>
                            <li>numberOfEstimates       : <span class="yellow">{{api.numberOfEstimates}}</span></li>
                            <li>EPSSurpriseDollar       : <span class="cyan">{{api.EPSSurpriseDollar}}</span></li> 
                            <li>EPSSurprisePercent      : <span class="red">{{api.EPSSurprisePercent}}</span></li>
                            <li>symbol                  : <span class="purple">{{api.symbol}}</span></li>
                            <li>EBITDA                  : <span class="yellow">{{api.EBITDA}}</span></li>
                            <li>revenue                 : <span class="cyan">{{api.revenue}}</span></li>
                        </ul>
                    </div> 
                    <div class="col-6">
                        <ul>
                            <li>grossProfit             : <span class="red">{{api.grossProfit}}</span></li>                            
                            <li>cash                    : <span class="purple">{{api.cash}}</span></li>                          
                            <li>debt                    : <span class="red">{{api.debt}}</span></li>                            
                            <li>ttmEPS                  : <span class="red">{{api.ttmEPS}}</span></li>
                            <li>revenuePerShare         : <span class="purple">{{api.revenuePerShare}}</span></li>
                            <li>revenuePerEmployee      : <span class="yellow">{{api.revenuePerEmployee}}</span></li>
                            <li>peRatioHigh             : <span class="cyan">{{api.peRatioHigh}}</span></li>
                            <li>peRatioLow              : <span class="red">{{api.peRatioLow}}</span></li>
                            <li>returnOnAssets          : <span class="purple">{{api.returnOnAssets}}</span></li>
                            <li>returnOnCapital         : <span class="yellow">{{api.returnOnCapital}}</span></li>
                            <li>profitMargin            : <span class="cyan">{{api.profitMargin}}</span></li> 
                            <li>priceToSales            : <span class="red">{{api.priceToSales}}</span></li>
                            <li>priceToBook             : <span class="purple">{{api.priceToBook}}</span></li>
                            <li>day200MovingAvg         : <span class="yellow">{{api.day200MovingAvg}}</span></li>
                            <li>day50MovingAvg          : <span class="cyan">{{api.day50MovingAvg}}</span></li>  
                            <li>institutionPercent      : <span class="red">{{api.institutionPercent}}</span></li>
                            <li>insiderPercent          : <span class="purple">{{api.insiderPercent}}</span></li>
                            <li>shortRatio              : <span class="yellow">{{api.shortRatio}}</span></li>
                            <li>year5ChangePercent      : <span class="cyan">{{api.year5ChangePercent}}</span></li> 
                            <li>year2ChangePercent      : <span class="red">{{api.year2ChangePercent}}</span></li>
                            <li>year1ChangePercent      : <span class="purple">{{api.year1ChangePercent}}</span></li>
                            <li>ytdChangePercent        : <span class="yellow">{{api.ytdChangePercent}}</span></li>
                            <li>month6ChangePercent     : <span class="cyan">{{api.month6ChangePercent}}</span></li>
                            <li>month3ChangePercent     : <span class="red">{{api.month3ChangePercent}}</span></li>
                            <li>month1ChangePercent     : <span class="purple">{{api.month1ChangePercent}}</span></li>
                            <li>day5ChangePercent       : <span class="yellow">{{api.day5ChangePercent}}</span></li>
                            <li>day30ChangePercent      : <span class="cyan">{{api.day30ChangePercent}}</span></li>
                        </ul>
                    </div>                         
                </div>
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">                  
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>        
    </div>
</template>
<script>
    import Loading from 'vue-loading-overlay';    
    import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    components: {
           Loading
        }, 
        data() {
            return {
                api:{},
                ticker:"",
                isLoading: false,                
            }
        },
        methods: {
            getTrade() {
                this.isLoading = true;
               iex.stockKeyStats(this.ticker)                    
                .then(res => {
                    this.isLoading = false;
                    this.api = res;                    
                    
                }).catch(error=>{
                    console.log(error); 
                    this.isLoading = false;   
                })
            }
        },
        mounted() { 
           
            
        },
}
</script>