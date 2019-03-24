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
                            <li>latestPrice             : <span class="yellow">{{api.latestPrice}}</span></li>
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