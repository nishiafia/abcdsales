<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="page-header">
              <h2>Create your account</h2>
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
                <form @submit.prevent="createUser()" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="form.name" type="text" name="name" required
                        placeholder="Name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="phone">Username(phone)</label>
                         <vue-tel-input 
                          v-model="form.telephone"
                          defaultCountry="BD"
                          >
                         </vue-tel-input>
                        <!--div class="row">
                         <input  type="text" v-model="form.dialcode"  name="dialcode"    
                        class="form-control form-control-dialcode" readonly>
                        <input v-model.number="form.telephone" type="number" name="telephone" required @keypress="onlyNumber"
                        placeholder="Phone Number" :maxlength="11" :minlength="11"
                        class="form-control form-control-dialcode1" :class="{ 'is-invalid': form.errors.has('telephone') }">
                         
                        <has-error :form="form" field="telephone"></has-error>
                        </div-->
                          
                    </div>
                    <div class="form-group">
                       <label for="password">Password</label>
                        <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter password" required=true minlength="4"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                    </div>
                    <div class="form-group">
                       <label for="email">Email</label>
                        <input v-model="form.email" type="email" name="email"  required
                        placeholder="Email Address"
                        class="form-control"  :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="business">Business Category</label>
                        <select name="businesscategory" v-model="form.businesscategory" id="businesscategory" class="form-control" :class="{ 'is-invalid': form.errors.has('branch_contact_person') }">
                          <option value="">Select Business Categories</option>
                          <option v-for="bcategory in categories" v-bind:value="bcategory.id" :key="bcategory.id">
                          {{ bcategory.categoryname }}
                          </option>
                        </select>
                        <has-error :form="form" field="usertype"></has-error>
                    </div>
                    <input type="hidden"   name="usertype" v-model="form.usertype">
                     <input type="hidden"   name="dialcode" v-model="form.dialcode">
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <div class="signup">Already have an account? <router-link to="/login" class="singup-link">Signin Here</router-link></div>
                </div>
           
        </div>
    </div>
</template>

<script>
    export default {
         data() {
            return {
                errors: [],
               categories: {},
               form: new Form({
                    name : '',
                    email: '',
                   /* phone: {
                      number: '',
                      valid: false,
                      country: undefined,
                    },*/
                    dialcode: '+88',
                    telephone: '',
                    password: '',
                    usertype: 'standard',
                    businesscategory: '',

                })
               
            }
        },
        methods: {
                    onlyNumber ($event) {
                        let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                        if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                        $event.preventDefault();
                        }
                    },
                  /*onInput(formattedNumber, { number, valid, country }) {
                      
                        this.phone.number = number.international;
                        console.log("dialcode",this.phone.number);
                        this.phone.valid = valid;
                        this.phone.country = country && country.name;
                        console.log("dialcode",this.phone.country);
                      
                      },*/
                      loadBusinesscategory() {
                        axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
                        console.log("category=", this.categories);
                      },

                    createUser(){

                          this.$Progress.start()

                          this.form.post('api/user')
                          .then((response) => {
                          console.log("response:",response.data);
                          if(response.data === '')
                          {     alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                          Toast.fire({
                          icon: 'error',
                          title: 'Email Already Exists!!'
                          })
                          }
                          else{
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                          Toast.fire({
                          icon: 'success',
                          title: 'User created successfully'
                          })

                          this.$Progress.finish()

                          this.$router.push({ path: "/login" });

                          }
                          })
                          .catch(() => {
                          console.log("Error......")
                          })
                   
                    }
        },
        created() { //Like Mounted this method
            this.loadBusinesscategory();
           // this.usertype='standard';
           // this.dialcode='+88';
        }
    }
</script>