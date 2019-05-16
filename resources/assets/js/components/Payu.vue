<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="row mt-5" >
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pagos Membresia</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Adicionar <i class="fas fa-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                <div >
                    <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="actions" slot-scope="props">
                            <a href="#" @click="editModal(props.row)">
                                <i class="fa fa-edit blue fa-2x"></i>
                            </a>  
                            /
                            <a href="#" @click="deletePayu(props.row)">
                                <i class="fa fa-trash red fa-2x"></i>
                            </a>                          
                        </div>
                    </v-client-table>
                </div>                  
                
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>     

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Adicionar</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updatePayu() : createPayu()">
                <div class="modal-body">
                     <div class="form-group">
                         <label for="user">Usuarios no suscritos</label>
                         <select v-model="form.user"  name="user" id="user" class="form-control" @change="userChange()">
                            <option v-for="u in users" :key="u.id" v-bind:value="u.id">{{u.name}}</option> 
                         </select>
                        
                        <has-error :form="form" field="user"></has-error>
                    </div>

                     <div class="form-group">   
                         <label for="plan">Plan</label>                     
                        <select v-model="form.plan"  name="plan" id="plan" class="form-control" placeholder="Plan">
                            <option>MENSUAL</option> 
                            <option>TRIMESTRAL</option>
                            <option>ANUAL</option>
                        </select>                    
                        <has-error :form="form" field="plan"></has-error>                        
                    </div>

                     <div class="form-group">
                         <label for="">Costo</label>
                         <input v-model="form.amount" type="number" min="1"  step="0.01" name="amount"
                            placeholder="Costo"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                        <has-error :form="form" field="amount"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="">Pais</label>
                         <input v-model="form.country" type="text" name="country"                            
                            class="form-control" value="CO" readonly>
                        <has-error :form="form" field="country"></has-error>
                    </div>   
                    <div class="form-group">
                        <label for="">Email</label>
                         <input v-model="form.email" type="email" name="email"                            
                            class="form-control" >
                        <has-error :form="form" field="email"></has-error>
                    </div>    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Adicionar</button>
                </div>

                </form>

                </div>
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
                editmode: false,
                isLoading: false,
                users : [],
                columns: ['id', 'name', 'plan','amount','country','paypal_email','actions'],
                tableData:[],
                options: {
                    filterByColumn: false,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100],
                     headings: {
                        id          : 'ID',
                        name        : 'Usuario',
                        plan        : 'Plan',
                        amount      : 'Costo',
                        country     : 'Pais',
                        paypal_email: 'Email',
                        actions     : "Acciones",    
                    },
                    templates:{
                                              
                        plan: function(h,row){   
                            
                            let plan = ""
                            if(row.paypal_subscription != null)
                            {
                                plan =  row.paypal_subscription.plan
                            }
                            return plan;
                            
                        },
                        amount: function(h,row){
                            let costo = ""
                            if(row.paypal_subscription != null)
                            {
                                costo =  row.paypal_subscription.amount
                            }
                            return costo;
                            
                        },
                        paypal_email: function(h,row){
                            let paypal_email = ""
                            if(row.paypal_subscription != null)
                            {
                                paypal_email =  row.paypal_subscription.paypal_email
                            }
                            return paypal_email;
                            
                        },
                        country: function(h,row){
                            let country = ""
                            if(row.paypal_subscription != null)
                            {
                                country =  row.paypal_subscription.country
                            }
                            return country;
                            
                        },           
                   
                    }
                },                
                form: new Form({
                    id      : '',
                    user    : '',
                    plan    : 'MENSUAL',
                    amount  : '', 
                    country : 'CO',
                    email   : '',                   
                }),
                
            }
        },
        methods: {           
            updatePayu(){              
                this.isLoading = true;               
                this.form.post('admin/payuupdate/'+this.form.id)
                .then(() => {
                    // success
                    this.isLoading = false;
                     Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Actualizado!',
                        'La informacion ha sido actualizada.',
                        'success'
                        )                       
                        
                        
                })
                .catch(() => {                   
                    this.isLoading = false;
                });

            },
            editModal(payu){
                console.log(payu);
                
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                axios.get("admin/payumember").then((resp) => {                     
                   this.users = resp.data;
                   
                });
                this.form.id = payu.id;
                this.form.user = payu.id;
                this.form.plan = payu.paypal_subscription.plan;
                this.form.amount = payu.paypal_subscription.amount;
                this.form.email = payu.paypal_subscription.paypal_email
               // this.form.fill(payu);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deletePayu(row){
                swal.fire({
                    title: 'Estas Seguro?',
                    text: "Se borrará para siempre!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, elimínalo!'
                    }).then((result) => {    

                        // Send request to the server
                         if (result.value) {
                               this.form.post('admin/payudelete/'+row.id).then(()=>{
                                        swal.fire(
                                        'Eliminado!',
                                        'El Usuario ha sido eliminado.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "Hubo un problema en la eliminación.", "warning");
                                });
                         }
                    })
            },
            loadUsers(){
                this.isLoading = true;
               axios.get("admin/payumember").then((resp) => {                     
                   this.tableData = resp.data;
                   this.isLoading = false;
                });
            },
            unsubscribedUsers(){
                axios.get('/subscriptions/users_registered').then(resp => {
                    this.users = resp.data;
                    console.log(resp.data);
                    
                })
            },

            createPayu(){ 
                console.log(this.form);
                             
                this.isLoading = true;
                this.form.post('admin/payucreate')
                .then(()=>{
                    this.isLoading = false;
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Suscriccion Creada Correctamente'
                        })
                    //this.$Progress.finish();

                })
                .catch(()=>{
                    this.isLoading = false;
                })
            },
            userChange() {
                console.log(this.form.user);               
                let obj = this.users.find(o => o.id === this.form.user);
                this.form.email = obj.email;
                
                
            }
        },
        created() {            
           this.loadUsers();
           this.unsubscribedUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
               this.unsubscribedUsers();
           });
        
        }

    }
</script>