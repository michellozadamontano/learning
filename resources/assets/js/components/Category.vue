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
                <h3 class="card-title">Categorias</h3>

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
                            <a href="#" @click="deleteCategory(props.row.id)">
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
                <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Nombre"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>

                     <div class="form-group">
                        <input v-model="form.description" type="text" name="description"
                            placeholder="Descripcion"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                        <has-error :form="form" field="description"></has-error>
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
                columns: ['id', 'name', 'description','actions'],
                tableData:[],
                options: {
                    filterByColumn: true,
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100, 500],
                     headings: {
                        id: 'ID',
                        name: 'Nombre',
                        description: 'Descripcion',                        
                        actions: "Acciones",    
                    },
                },                
                form: new Form({
                    id      :'',
                    name    : '',
                    description: '',                                      
                })
            }
        },
        methods: {           
            updateCategory(){               
                this.isLoading = true;               
                this.form.put('category/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal(
                        'Actualizado!',
                        'La categoria fue actualizada.',
                        'success'
                        )                        
                        this.isLoading = false;
                         Fire.$emit('AfterCreate');
                })
                .catch((error) => {
                    console.log(error);                    
                    this.isLoading = false;
                });

            },
            editModal(category){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(category);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteCategory(id){
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
                                this.form.delete('category/'+id).then(()=>{
                                        swal.fire(
                                        'Eliminado!',
                                        'La categoria ha sido eliminado.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "Hubo un problema en la eliminación.", "warning");
                                });
                         }
                    })
            },
            loadCategories(){
                this.isLoading = true;
               axios.get("category").then((resp) => {                  
                   this.tableData = resp.data;
                   this.isLoading = false;
                });
            },

            createCategory(){               
                this.isLoading = true;
                this.form.post('category')
                .then(()=>{
                    this.isLoading = false;
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Categoria Creado Correctamente'
                        })    

                })
                .catch(()=>{
                    this.isLoading = false;
                })
            }
        },
        created() {            
           this.loadCategories();
           Fire.$on('AfterCreate',() => {
               this.loadCategories();
           });        
        }

    }
</script>