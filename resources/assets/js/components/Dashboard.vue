<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <div class="row justify-content-left">
            <div class="col-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{student}}</h3>
                  <p>Alumnnos suscritos</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <router-link to="/subscribed" class="small-box-footer blue">
                 Mas info
                <i class="fa fa-arrow-circle-right purple"></i>  
              </router-link>  
              </div>
            </div><!-- ./col -->
            <div class="col-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{student_course}}</h3>
                  <p>Alumnnos inscritos a cursos</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <router-link to="/students" class="small-box-footer blue">
                 Mas info
                <i class="fa fa-arrow-circle-right red"></i>  
              </router-link>               
              </div>
            </div><!-- ./col -->  
            <div class="col-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{student_registered}}</h3>
                  <p>Alumnnos solo registrados</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <router-link to="/registered" class="small-box-footer blue">
                 Mas info
                <i class="fa fa-arrow-circle-right indigo"></i>  
              </router-link>               
              </div>
            </div><!-- ./col --> 
            <div class="col-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3> <i class="fas fa-dollar-sign"></i> {{ingresos}}</h3>
                  <p>Ingresos Totales</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <router-link to="" class="small-box-footer blue">
                 
                <i class="fa fa-arrow-circle-right red"></i>  
              </router-link>               
              </div>
            </div><!-- ./col -->          
        </div> 
        <div class="row justify-content-left">
            <div class="col-6">
                <div class="card">                      
                    <div class="card-body">
                    <h4 class="card-title">Suscripciones Mensuales</h4>                    
                    <div class="small">
                        <line-chart :chart-data="datacollection"></line-chart>    
                    </div>
                    </div>
                </div>
            </div>
        </div>   
       
    </div>

</template>

<script>
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';
import LineChart from './Graficas';
    export default {       
        data() {
            return {
                isLoading: false,
                student:'',
                student_course:"",
                student_registered:"",
                ingresos:'',
                url: 'admin/student_data',
                datacollection: null,
                subscribed_per_month:'',
            }
        },
        components: {
            Loading,
            LineChart
        },
        methods: {
            registeredStudent() {
                axios.get('/subscriptions/users_subscribed').then(resp => {
                    this.student = resp.data.length;
                    console.log(resp.data);
                    let sum = 0;
                    resp.data.forEach(element => {
                        if(element.user.paypal_subscription != null){
                            sum += parseFloat(element.user.paypal_subscription.amount)
                        }
                    });                    
                    this.ingresos = sum.toFixed(2);                   
                    
                })
            },
            unsubscribedUsers(){
                axios.get('/subscriptions/users_registered').then(resp => {
                    this.student_registered = resp.data.length;
                    console.log(resp.data);
                    
                })
            },
            inscribedStudent(){
                axios.get(this.url).then(res => {
                this.student_course = res.data.length;                               
                    
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                    
                })
            },
            fillData () {
                this.datacollection = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
                datasets: [
                    {
                        label: 'suscripciones',
                        backgroundColor: '#f87979',
                        data: this.subscribed_per_month,
                    }
                ]
                }
            },
            async getSubscribedPerMonth () {
                axios.get('/subscriptions/users_subscribed_count').then(res => {
                this.subscribed_per_month = res.data;               
                this.fillData();                            
                    
                }).catch(error => {
                    console.log(error);
                    this.isLoading = false;
                    
                })
            }
        },
        mounted() {
            try {
                this.isLoading = true;
                this.registeredStudent(); 
                this.inscribedStudent();
                this.getSubscribedPerMonth();
                this.unsubscribedUsers();                
               
                this.isLoading = false;
            } catch (error) {
                console.log(error);
                
                this.isLoading = false;
            }
            
            
        }
    }
</script>
<style>
  .small {
    max-width: 600px;
   
  }
</style>