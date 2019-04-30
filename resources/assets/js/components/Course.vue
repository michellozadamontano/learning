<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="pl-5 pr-5">
            <form @submit.prevent="updateCourse()" novalidate>
                <div class="form-group">
                  <label for="">Nombre</label>
                  <input type="text" v-model="content.name"
                    class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="" required>                  
                </div>
                <div class="form-group">
                  <label for="">Descriccion</label>
                  <input type="text" v-model="content.description"
                    class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="" required>                  
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="" id="" @change="setSuscription($event)" value="checkedValue" v-model="suscription"> Suscricion
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="" id="" @change="setFree($event)" value="checkedValue" v-model="gratis"> Gratis
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="" id="" @change="setPay($event)" value="checkedValue" v-model="pago"> Pago
                    </label>
                </div>
                <div class="form-group" v-show="pago">
                  <label for="">Valor del curso en USD</label>
                  <input type="number" min="0" max="1" step="0.01" v-model="value" 
                    class="form-control" name="" id="" aria-describedby="helpId" placeholder="" >                  
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Guadar</button>
            </form>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    data() {
        return {
            isLoading   : false,
            course_id   : "",
            content     : "",
            gratis      : "",
            pago        : "",   
            value       : "",
            suscription : "" 
        }
    },
    components: {
        Loading
    }, 
    methods: {
        updateCourse() {
            this.isLoading = true;
            let id = this.$route.params.id;
            this.course_id = id;
            if(this.pago == 1){
                if(this.value == "")
                {
                    swal.fire(
                    'Error!',
                    'El campo valor no puede estar vacío.',
                    'error'
                    )
                    this.isLoading = false;
                    return
                }
            }
            console.log(this.pago);
            
            axios.post('/courses/update_ajax', {
                course_id   : id,
                name        : this.content.name,
                description : this.content.description,
                free        : this.gratis,
                pay         : this.pago,
                value       : this.value,
                suscription : this.suscription
                })
            .then(res => {
                this.isLoading = false;   
                swal.fire(
                    'Actualizado!',
                    'El curso ha sido actualizado.',
                    'success'
                )
            
                
            })
            .catch((error)=> {
                // handle error
                console.log(error);
                this.isLoading = false;
            })
            
        },
        setPay(event) {
            if(event.target.checked){
                this.gratis         = 0;
                this.pago           = 1;
                this.suscription    = 0
            }            
        },
        setFree(event) {
            if(event.target.checked){
                this.gratis         = 1;
                this.pago           = 0;
                this.suscription    = 0
            }
            
        },
        setSuscription(event) {
            if(event.target.checked){
                this.gratis         = 0;
                this.pago           = 0;
                this.suscription    = 1
            }
            
        }

    },
    created() {
        this.isLoading = true;
        let id = this.$route.params.id;
        this.course_id = id;
        axios.post('/courses/course_details', {course_id: id})
        .then(res => {
            this.isLoading = false;   
            console.log(res.data);         
            this.content    = res.data;
            this.gratis     = res.data.free;
            this.pago       = res.data.pay;
            this.value      = res.data.value;
            if(res.data.free == 0 && res.data.pay == 0){
                this.suscription = 1;
            }
            
        })
        .catch((error)=> {
            // handle error
            console.log(error);
            this.isLoading = false;
        })
    },
}
</script>