<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-3">
              <!-- small box -->
              <div class="small-box bg-aqua red">
                <div class="inner">
                  <h3>{{student}}</h3>
                  <p>Alumnnos subscritos</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
        </div>    
        <grafica></grafica>
    </div>

</template>

<script>
    export default {       
        data() {
            return {
                student:''
            }
        },
        methods: {
            registeredStudent() {
                axios.get('/subscriptions/users_subscribed').then(resp => {
                    this.student = resp.data;
                    console.log(resp.data);
                    
                })
            }
        },
        mounted() {
            this.registeredStudent(); 
            
        }
    }
</script>