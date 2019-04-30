<template>
    <PayPal
        :amount="amount"
        currency="USD"
        :client="credentials"        
        @payment-authorized="payment_authorized_cb" 
        @payment-completed="payment_completed_cb" 
        @payment-cancelled="payment_cancelled_cb"   
    >
    </PayPal>
</template>
<script>
import PayPal from 'vue-paypal-checkout'
 
export default {
    props: {            
            amount: '',
            sandbox: '',
            production: '',
            course_id:''
            
    },
  data() {
    return {
      credentials: {
        sandbox: this.sandbox,
        production: this.production
      },      
    }
  },
  components: {
    PayPal
  },  
  methods: {
    payment_completed_cb(res, planName){
           // toastr.success("Thank you! We'll send you a confirmation email soon with your invoice. ");
           axios.post('/courses/payment',{
               'course_id': this.course_id,
               'valor'    : this.amount
           }).then(resp => {
             console.log(resp);
              window.location.href = '/courses/payed';                
           })
           
    },
    payment_authorized_cb(res){
        console.log(res); 
    },
    payment_cancelled_cb(res){
        //toastr.error("The payment process has been canceled. No money was taken from your account.");
        console.log(res); 
    },
  },
}
</script>