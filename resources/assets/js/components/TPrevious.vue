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
                <h3 class="card-title">Previous Traiding</h3>

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
                            <li>symbol                  : <span class="red">{{api.symbol}}</span></li>                            
                            <li>date                    : <span class="purple">{{api.date}}</span></li>                          
                            <li>open                    : <span class="red">{{api.open}}</span></li>                            
                            <li>high                    : <span class="red">{{api.high}}</span></li>
                            <li>low                     : <span class="purple">{{api.low}}</span></li>
                            <li>close                   : <span class="yellow">{{api.close}}</span></li>
                            <li>volume                  : <span class="cyan">{{api.volume}}</span></li>
                            <li>unadjustedVolume        : <span class="red">{{api.unadjustedVolume}}</span></li>
                            <li>change                  : <span class="purple">{{api.change}}</span></li>
                            <li>changePercent           : <span class="yellow">{{api.changePercent}}</span></li>
                            <li>vwap                    : <span class="cyan">{{api.vwap}}</span></li>    
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
               iex.stockPrevious(this.ticker)                    
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