<template>
    <div class="container">
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true">
        </loading>
        <div class="row">
            <div class="col-3">
                <div class="profile">
                    <img v-show="!photo" src="images/users/profile.jpg" alt="avatar" class="img-thumbnail" style="height:226px;width:226px" srcset="">
                    <img v-show="photo" :src="`images/users/${pic}`" alt="avatar" class="img-thumbnail" style="height:226px;width:226px" >
                </div> 
            </div>
            <div class="col-9">
                <form @submit.prevent="saveImage()">
                    <div class="form-group">
                        <input type="file" v-on:change="onImageChange" >  
                        <has-error :form="form" field="picture"></has-error>              
                    </div>
                    <div class="form-group">
                    <label for="title">Profesion</label>
                    <input type="text" v-model="form.title"
                        class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="Profesion">
                    <small id="helpId" class="form-text text-muted">Titulo de su profesion</small>
                    </div>
                    <button type="submit" class="btn btn-primary">subir</button>
                </form>
            </div>
        </div>
        
                       
        
          
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: {
            user_id: '',    
        },
        components: {
        Loading
    },
    data() {
        return {
            isLoading   : false,
            pic         : "",
            photo       : false,
            form: new Form({      
                picture     : '',
                title       : ''
                    
            }),
        }
    },
    methods: {
        saveImage() {
            this.isLoading = true;
            this.form.post('/profile/updatephoto').then(resp => {
                console.log(resp.data);
                this.pic = resp.data.picture;
                this.isLoading = false;
                swal.fire(
                        'Actualizado!',
                        'La informacion ha sido actualizada.',
                        'success'
                        )      
            })

        },
        loadImage() {
            axios.post('/profile/getuser',{
                user_id:this.user_id
            }).then(resp => {
                console.log(resp.data);
                this.pic = resp.data.picture;
                if(resp.data.picture != null)
                {
                    this.photo = true;
                }
                this.form.title = resp.data.student.title;
                
            }).catch(error => {
                console.log(error);
                
            })
        },
        onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
        createImage(file) {            
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.form.picture = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        
    },
    created() {
       this.loadImage();
        
    },
}
</script>
<style>
    .profile {
        width: 266px;
        height: 266px;
        overflow: hidden;
    }
</style>