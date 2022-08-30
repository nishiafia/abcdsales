<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="page-header">
              <h2>Please Login Here To User Account</h2>
            </div>
            <div class="col-md-12 text-center">
                <p v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                    <ul class="list-group">
                      <li v-for="error in errors" class="list-group-item list-group-item-danger" :key="error">{{ error }}</li>
                    </ul>
                </p>
            </div>
            <div class="col-md-6" v-if="loginfalse = true">
                <form @submit="checkForm" id="createAdministrator">
                   <div class="form-group">
                        <label for="phone">Username(phone)</label>
                        <div class="telephoneformat">Example Format: ( 01712234678 )</div>
                        <!--VuePhoneNumberInput
                        default-country-code="BD"
                        name="telephone"
                        required
                        v-model="telephone"
                         :maxlength="max"
                         /-->
                         <input v-model="telephone" type="tel" name="telephone" maxlength="11" minlength="11" required
                        placeholder="telephone" @keypress="onlyNumber"
                        class="form-control">
                    </div>
                  <!--div class="form-group">
                    <label for="email">Email address:</label>
                    <input v-model="email" type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                  </div-->
                  <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input v-model="adminuserpassword" type="password" class="form-control" id="adminuserpassword" placeholder="********" name="adminuserpassword" required>
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

    export default {
       props: ['userData'],
        components: {  VuePhoneNumberInput},
        mounted() {
            console.log(this.userData)
             console.log('Mounted')
        },
         data() {
            return {
                errors: [],
                   max: 10,
                    //dialcode: '880',
                    telephone: '',
                    adminuserpassword: '',
                    phone_number: '',
                    utype:'basic',
            }
        },
        methods:{
            onlyNumber ($event) {
                        let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                        if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                        $event.preventDefault();
                        }
                    },
                checkForm: function (e) {
                    this.errors = [];
                    if (!this.telephone) {
                        this.errors.push('Phone required.');
                    }
                    if (!this.adminuserpassword) {
                       this.errors.push('Password required.');
                    }
                    else
                    {
                        // var navigate = this.$router;
                        var formContents = jQuery("#createAdministrator").serialize();
                        axios.post('/adminuserlogin', formContents)
                        .then(function(response, status, request) {
                          if(response.data.status=='error'){
                          Toast.fire({
                          icon: 'error',
                          title: 'Unauthorized Access'
                          })
                          }
                          else{
                        console.log('data',response.data.user);
                        location.reload();
                        //navigate.push({ path: '/dashboard' });
                       //this.$router.push({ path: "/dashboard" });
                         this.$router.push({ name: 'dashboard' });
                        }}, function() {
                          console.log('failed');
                        });
                    }

                    e.preventDefault();
                }
      }
}
</script>