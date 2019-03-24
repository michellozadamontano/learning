<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="pl-5 pr-5">
            <div class="row justify-content-center">
                <div class="row">
                    <div class="col-md-3">                           
                            <router-link :to="'/content/' + course_id" class="btn btn-success"> 
                            <i class="fa fa-backward" aria-hidden="true"></i>
                            Regresar</router-link>
                    </div>
                </div>
                
                <br>
                <div class="row">
                    <div class="col-md-12" v-if="file.path">                   
                            
                        <iframe v-if="file.path.length > 9" width="620" height="415" frameborder="0"
                            :src="`https://www.youtube.com/embed/${file.path}`">
                        </iframe>
                        
                        <iframe v-else width="620" height="415" frameborder="0"
                            :src="`https://player.vimeo.com/video/${file.path}`">
                        </iframe> 
                               
                    </div>
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
            isLoading: false,
            file: {},
            course_id: ""
        }
    },
    created(){
        this.isLoading = true;
        let id = this.$route.params.id;        
        this.course_id = this.$route.params.course;        
        
        let url = '/courses/video_ajax/' + id;
        axios.get(url)
        .then(res => {
            this.isLoading = false;            
            this.file = res.data;
            
        })
        .catch((error)=> {
            // handle error
            console.log(error);
            this.isLoading = false;
        })
    }
}
</script>