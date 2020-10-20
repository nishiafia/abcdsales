<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="page-header">
              <h2>Please Login Here</h2>
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
                         <vue-tel-input 
                          v-model="telephone"
                          id="telephone"
                          defaultCountry="BD"
                          required
                         class="form-control"
                          >
                         </vue-tel-input>
                       
                    </div>
                  <!--div class="form-group">
                    <label for="email">Email address:</label>
                    <input v-model="email" type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                  </div-->
                  <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input v-model="password" type="password" class="form-control" id="password" placeholder="********" name="password" required>
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                </form>
                 <div class="signup">Don't have an account yet? <router-link to="/signup" class="singup-link">Signup Here</router-link></div>
            </div>
           
        </div>
    </div>
</template>

<script>

    export default {
       props: ['userData'],
        mounted() {
            console.log(this.userData)
             console.log('Mounted')
        },

         data() {
            return {
                errors: [],
                   // email: '',
                    //dialcode: '+88',
                    telephone: '',
                    password: ''
               
            }
        },
        methods:{
           
        checkForm: function (e) {
         
          this.errors = [];
          if (!this.telephone) {
            this.errors.push('Phone required.');
          }
          if (!this.password) {
            this.errors.push('Password required.');
          }
        else
        {
         // var navigate = this.$router;
        var formContents = jQuery("#createAdministrator").serialize();
        
        
          axios.post('/vuelogin', formContents).then(function(response, status, request) {  
                            alert(response.data.user);
                             console.log('data',response.data.user);
                             location.reload();
                         //navigate.push({ path: '/dashboard' });
                        this.$router.push({ path: "/dashboard" });
                        
                    }, function() {
                        console.log('failed');
                    });
        }
        
          e.preventDefault();
        }
      }
    }
</script>