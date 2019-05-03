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
                <h3 class="card-title">Pagos cursos puntuales</h3>
                <download-excel            
                    :data="array_excel"
                    :before-generate = "startDownload"
                    :before-finish = "finishDownload"
                    class="hover-excel ">
                     Datos Excel
                     <i class="fas fa-file-excel green"></i>                    
                </download-excel> 

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
                            <a href="#" @click="destroy(props.row)">
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
                <form @submit.prevent="editmode ? updatePay() : createPay()">
                <div class="modal-body">
                     <div class="form-group">
                         <label for="user_id">Usuarios</label>
                         <select v-model="form.user_id"  name="user_id" id="user_id" class="form-control" 
                         :class="{ 'is-invalid': form.errors.has('user_id') }" @change="userChange()">
                            <option v-for="u in users" :key="u.id" v-bind:value="u.id">{{u.name}}</option> 
                         </select>                        
                        <has-error :form="form" field="user_id"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                         <input v-model="email" type="email" name="email"                            
                            class="form-control" readonly>                        
                    </div> 

                     <div class="form-group">   
                         <label for="plan">Cursos de Pagos</label>                     
                        <select v-model="form.course_id"  name="course_id" id="course_id" 
                        class="form-control" placeholder="Cursos" :class="{ 'is-invalid': form.errors.has('course_id') }">
                            <option v-for="c in courses" :key="c.id" v-bind:value="c.id">{{c.name}}</option>
                        </select>                    
                        <has-error :form="form" field="course_id"></has-error>                        
                    </div>

                     <div class="form-group">
                         <label for="">Costo</label>
                         <input v-model="form.valor" type="number" min="1"  step="0.01" name="valor"
                            placeholder="Costo"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('valor') }">
                        <has-error :form="form" field="valor"></has-error>
                    </div> 
                    <div class="form-group">
                      <label for="">Coupon</label>
                      <select v-model="form.coupon"  name="coupon" id="coupon" class="form-control" >
                            <option value=""></option>
                            <option v-for="c in coupons" :key="c.id" v-bind:value="c.code">{{c.code}}</option> 
                         </select>
                      <small id="helpId" class="form-text text-muted">Si tiene un cupon por fovor intruduzcalo aqui</small>
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
                editmode    : false,
                isLoading   : false,
                users       : [],
                courses     : [],
                coupons     : [],
                email       : '',
                columns     : ['id', 'name', 'curso','valor','coupon','actions'],
                tableData   : [],
                array_excel : [],
                options     : {
                    filterByColumn: false,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100],
                     headings: {
                        id          : 'ID',
                        name        : 'Usuario',
                        curso       : 'Curso',
                        valor       : 'Costo',                       
                        actions     : "Acciones",    
                    },
                    templates:{
                                              
                        curso: function(h,row){      
                           
                            return row.courses.name;
                            
                        },
                        name: function(h,row){                            
                            return row.users.name;               
                        },                                           
                    }
                },                
                form: new Form({
                    id          : '',
                    user_id     : '',
                    course_id   : '',
                    valor       : '',
                    coupon      : '',    
                              
                }),
                
            }
        },
        methods: {           
            updatePay(){              
                this.isLoading = true;               
                this.form.post('payment/update/'+this.form.id)
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
            editModal(pay){    
                
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(pay);
                this.email = pay.users.email;
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },            
            loadUsers(){
                this.isLoading = true;
               axios.get("payment").then((resp) => {                     
                   this.tableData = resp.data;
                   resp.data.forEach(x => {
                       let obj = {
                           nombre : x.users.name,
                           email  : x.users.email,
                           costo  : x.valor,
                           coupon : x.coupon
                       }
                       this.array_excel.push(obj);
                   });
                   this.isLoading = false;
                });
            },
            loadCoupons(){               
               axios.get("coupon").then((resp) => {
                   this.coupons = resp.data;                   
                });
            },
            Users(){
                axios.get('payment/users').then(resp => {
                    this.users = resp.data;    
                    
                })
            },
            Courses(){
                    axios.get('payment/courses').then(resp => {
                    this.courses = resp.data;                    
                    
                })
            },

            createPay(){ 
                console.log(this.form);
                             
                this.isLoading = true;
                this.form.post('payment/create')
                .then(()=>{
                    this.isLoading = false;
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Datos insertados correctamente'
                        })                    

                })
                .catch(()=>{
                    this.isLoading = false;
                })
            },
            userChange() {
                console.log(this.form.user_id);               
                let obj = this.users.find(o => o.id === this.form.user_id);
                this.email = obj.email;    
                
            },
            destroy(row) {           
                             
                this.isLoading = true;
                swal.fire({
                    title: 'Estas Seguro?',
                    text: "Se borrará para siempre!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, elimínalo!'
                    }).then(result => {
                        if (result.value) {
                            this.form.post('payment/delete/'+row.id)
                            .then(()=>{
                                this.isLoading = false;
                                Fire.$emit('AfterCreate');                    

                                toast.fire({
                                    type: 'success',
                                    title: 'Registro eliminado'
                                    })                    

                            })
                            .catch(()=>{
                                this.isLoading = false;
                            })
                        }
                        else{
                            this.isLoading = false;
                        }
                    })
                
            },
            startDownload(){
                this.isLoading = true;
            },
            finishDownload(){
                this.isLoading = false;
            },             
        },
        created() {            
           this.loadUsers();
           this.loadCoupons();
           this.Users();
           this.Courses();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
              // this.Users();
           });
        
        }

    }
</script>