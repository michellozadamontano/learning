<template>
    <div class="container">
        <div class="row mt-5" >
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Cupones</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Adicionar <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-2">
                <div >
                    <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="actions" slot-scope="props">
                            <a href="#" @click="editModal(props.row)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a href="#" @click="deleteCoupon(props.row.id)">
                                <i class="fa fa-trash red"></i>
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
                <form @submit.prevent="editmode ? updateCoupon() : createCoupon()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.code" type="text" name="code"
                            placeholder="Codigo"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('code') }">
                        <has-error :form="form" field="code"></has-error>
                    </div>

                     <div class="form-group">
                        <input v-model="form.quantity" type="number" min="1" name="quantity"
                            placeholder="Cantidad"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }">
                        <has-error :form="form" field="quantity"></has-error>
                    </div>

                     <div class="form-group">
                         <input v-model="form.percent" type="number" min="0" max="1" step="0.1" name="percent"
                            placeholder="Porciento"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('percent') }">
                        <has-error :form="form" field="percent"></has-error>
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
    export default {
        data() {
            return {
                editmode: false,
                coupons : {},
                columns: ['id', 'code', 'quantity','percent','actions'],
                tableData:[],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                     headings: {
                        id: 'ID',
                        code: 'Codigo',
                        quantity: 'Cantidad',
                        percent: 'Porciento',
                        actions: "Acciones",    
                    },
                },                
                form: new Form({
                    id      :'',
                    code    : '',
                    quantity: '',
                    percent : '',                    
                })
            }
        },
        methods: {           
            updateCoupon(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('coupon/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(coupon){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(coupon);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteCoupon(id){
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
                                this.form.delete('coupon/'+id).then(()=>{
                                        swal.fire(
                                        'Eliminado!',
                                        'El coupon ha sido eliminado.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "Hubo un problema en la eliminación.", "warning");
                                });
                         }
                    })
            },
            loadCoupons(){
               axios.get("coupon").then((resp) => {
                   this.coupons = resp.data;
                   this.tableData = resp.data;
                });
            },

            createCoupon(){
                this.$Progress.start();

                this.form.post('coupon')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Cupon Creado Correctamente'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findUser?q=' + query)
                .then((data) => {
                    this.users = data.data
                })
                .catch(() => {

                })
            })
           this.loadCoupons();
           Fire.$on('AfterCreate',() => {
               this.loadCoupons();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>