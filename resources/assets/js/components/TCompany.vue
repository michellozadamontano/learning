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
                <h3 class="card-title">Company Traiding</h3>

                <div class="card-tools">
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                  <div class="col-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="ticker" placeholder="Intrduzca el symbol" aria-label="Intrduzca el symbol" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary green" type="button" id="button-addon2" @click="getCompany()">Mostrar</button>
                        </div>
                  </div>                  
                </div>
                <div class="row">
                    <div class="col-6">
                        <ul>
                            <li>symbol      : <span class="red">{{company.symbol}}</span></li>
                            <li>CEO         : <span class="indigo">{{company.CEO}}</span></li>
                            <li>companyName : <span class="purple">{{company.companyName}}</span></li>
                            <li>industry    : <span class="green">{{company.industry}}</span></li>
                            <li>sector      : <span class="yellow">{{company.sector}}</span></li>
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
                company:{},
                ticker:"",
                isLoading: false,   
            }
        },
        methods: {
            getCompany() {
                this.isLoading = true;
                iex.stockCompany(this.ticker)
                .then(company => {
                    this.company = company
                    this.isLoading = false;
                    console.log(company)
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