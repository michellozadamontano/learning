<template>
    <div class="container">
         <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="card">          
          <div class="card-header">
            <h4 class="card-title">Profile</h4>
                <form @submit.prevent="changePassword()" novalidate>             
                      
                        <div class="form-group row">
                            <label
                                for="password"
                                class="col-md-4 col-form-label text-md-right"
                            >
                                Contraseña
                            </label>

                            <div class="col-md-6">
                                <input
                                    v-model="form.password"
                                    id="password"
                                    type="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }"
                                    name="password"
                                    required
                                />
                               <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                for="password-confirm"
                                class="col-md-4 col-form-label text-md-right"
                            >
                                Confirma la contraseña
                            </label>

                            <div class="col-md-6">
                                <input
                                    v-model="form.password_confirmation"
                                    id="password-confirm"
                                    type="password"
                                    class="form-control"
                                    name="password_confirmation"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar datos
                                </button>
                            </div>
                        </div>
                </form>
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
    data(){
        return{
            isLoading: false,
            form: new Form({    
                password    : '',
                password_confirmation    : '',
                                      
            })

        }
    },
    methods: {
        changePassword(){
            this.isLoading = true;
            if((this.form.password != this.form.password_confirmation)||(this.form.password == "")|| this.form.password_confirmation == ""){
                swal.fire(
                    'Error!',
                    'Las contraseñas tienen que ser iguales y mayores de 8 caracteres',
                    'success'
                )
                this.isLoading = false;
                return;
            }
            this.form.post('/profile/change')
            .then(resp => {
                this.isLoading = false;
                toast.fire({
                    type: 'success',
                    title: resp.data
                })

            })
            .catch(()=>{
                this.isLoading = false;
            })
        }
    },
}
</script>