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
                <h3 class="card-title">Quote Traiding</h3>

                <div class="card-tools">
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                  <div class="col-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="ticker" placeholder="Intrduzca el symbol" aria-label="Intrduzca el symbol" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary green" type="button" id="button-addon2" @click="getQuote()">Mostrar</button>
                        </div>
                  </div>                  
                </div>
                <div class="row">
                    <div class="col-6">
                        <ul>
                            <li>symbol                  : <span class="red">{{api.symbol}}</span></li>                            
                            <li>companyName             : <span class="purple">{{api.companyName}}</span></li>                            
                            <li>sector                  : <span class="yellow">{{api.sector}}</span></li>
                            <li>calculationPrice        : <span class="cyan">{{api.calculationPrice}}</span></li>
                            <li>open                    : <span class="red">{{api.open}}</span></li>
                            <li>openTime                : <span class="purple">{{api.openTime}}</span></li>
                            <li>close                   : <span class="yellow">{{api.close}}</span></li>
                            <li>closeTime               : <span class="cyan">{{api.closeTime}}</span></li>
                            <li>high                    : <span class="red">{{api.high}}</span></li>
                            <li>low                     : <span class="purple">{{api.low}}</span></li>
                            <li>latestPrice             : <span class="yellow">{{api.latestPrice}}</span></li>
                            <li>latestSource            : <span class="cyan">{{api.latestSource}}</span></li>
                            <li>latestTime              : <span class="red">{{api.latestTime}}</span></li>
                            <li>latestUpdate            : <span class="purple">{{api.latestUpdate}}</span></li>
                            <li>latestVolume            : <span class="yellow">{{api.latestVolume}}</span></li>
                            <li>iexRealtimePrice        : <span class="cyan">{{api.iexRealtimePrice}}</span></li>
                            <li>iexRealtimeSize         : <span class="red">{{api.iexRealtimeSize}}</span></li>
                            <li>iexLastUpdated          : <span class="purple">{{api.iexLastUpdated}}</span></li>
                            <li>delayedPrice             : <span class="yellow">{{api.delayedPrice}}</span></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul>
                            <li>delayedPriceTime        : <span class="red">{{api.delayedPriceTime}}</span></li>                            
                            <li>extendedPrice           : <span class="purple">{{api.extendedPrice}}</span></li>                            
                            <li>extendedChange          : <span class="yellow">{{api.extendedChange}}</span></li>
                            <li>extendedChangePercent   : <span class="cyan">{{api.extendedChangePercent}}</span></li>
                            <li>extendedPriceTime       : <span class="red">{{api.extendedPriceTime}}</span></li>
                            <li>previousClose           : <span class="purple">{{api.previousClose}}</span></li>
                            <li>change                  : <span class="yellow">{{api.change}}</span></li>
                            <li>changePercent           : <span class="cyan">{{api.changePercent}}</span></li>
                            <li>iexMarketPercent        : <span class="red">{{api.iexMarketPercent}}</span></li>
                            <li>iexVolume               : <span class="purple">{{api.iexVolume}}</span></li>
                            <li>avgTotalVolume          : <span class="yellow">{{api.avgTotalVolume}}</span></li>
                            <li>iexBidPrice             : <span class="cyan">{{api.iexBidPrice}}</span></li>
                            <li>iexBidSize              : <span class="red">{{api.iexBidSize}}</span></li>
                            <li>iexAskPrice             : <span class="purple">{{api.iexAskPrice}}</span></li>
                            <li>iexAskSize              : <span class="yellow">{{api.iexAskSize}}</span></li>
                            <li>marketCap               : <span class="cyan">{{api.marketCap}}</span></li>
                            <li>peRatio                 : <span class="red">{{api.peRatio}}</span></li>
                            <li>week52High              : <span class="purple">{{api.week52High}}</span></li>
                            <li>week52Low               : <span class="yellow">{{api.week52Low}}</span></li>
                            <li>ytdChange               : <span class="cyan">{{api.ytdChange}}</span></li>
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
                url:"https://api.iextrading.com/1.0/stock/"
            }
        },
        methods: {
            getQuote() {
                this.isLoading = true;
               iex.stockQuote(this.ticker)                    
                .then(res => {
                    this.isLoading = false;
                    this.api = res;
                    console.log(res.quote);
                    
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