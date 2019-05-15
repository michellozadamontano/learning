<template>
    <div class="container">
        <loading :active.sync="isLoading" 
            :can-cancel="true"           
            :is-full-page="true">
        </loading>
        <form>
            <script type="application/javascript"
                src="https://checkout.epayco.co/checkout.js"
                class="epayco-button"
                :data-epayco-key="p_key"
                :data-epayco-amount="amount"
                :data-epayco-name="name"
                :data-epayco-description="name"
                data-epayco-currency="cop"
                data-epayco-country="co"
                :data-epayco-test="test"
                data-epayco-external="false"
                :data-epayco-acepted="url_acepted"
                :data-epayco-rejected="url_rejected"
                :data-epayco-pending="url_pending">
            </script>
        </form>
        
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';   
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    components: {
        Loading
    }, 
    props: {
        p_key       : "",
        amount      : "",
        name        : "",
        url_acepted : "",
        url_rejected: "",
        url_pending : "",
        test        : "",
    },
    data() {
        return {
            isLoading: false,
        }
    },
    methods: {    
       
    },
    created() {        
    },
}
</script>