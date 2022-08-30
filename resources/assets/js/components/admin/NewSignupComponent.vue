<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="page-header">
              <h2>Create New User account</h2>
            </div>
            <div class="col-md-12 text-center">
                <p v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                    <ul class="list-group">
                      <li v-for="error in errors" class="list-group-item list-group-item-danger" :key="error">{{ error }}</li>
                    </ul>
                </p>
            </div>
                <div class="col-md-6">
                <form @submit.prevent="createUser()" method="POST">
                    <div class="form-group">
                        <label for="name">Name <span class="required-sign">*</span></label>
                        <input v-model="form.name" type="text" name="name" required
                        placeholder="Name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="phone">Username(phone) <span class="required-sign">*</span></label>
                        <div class="telephoneformat">Example Format: ( 01712234678 )</div>
                        <!--VuePhoneNumberInput
                        default-country-code="BD"
                        name="telephone"
                        required
                        v-model="form.telephone"
                        :maxlength="max"
                         @update="updatePhoneNumber"
                         /-->
                          <input v-model="form.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required
                        placeholder="telephone" @keypress="onlyNumber"
                        class="form-control" >
                    </div>
                    <div class="form-group">
                       <label for="password">Password <span class="required-sign">*</span></label>
                        <input v-model="form.password" type="text" name="password" id="password" placeholder="Enter password" readonly="true" required=true minlength="4"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                    </div>
                    <div class="form-group">
                       <label for="email">Email <span class="required-sign">*</span></label>
                        <input v-model="form.email" type="email" name="email"  required
                        placeholder="Email Address"
                        class="form-control"  :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <!--div class="form-group">
                        <label for="business">Business Category</label>
                        <select name="businesscategory" v-model="form.businesscategory" id="businesscategory" class="form-control" :class="{ 'is-invalid': form.errors.has('branch_contact_person') }">
                          <option value="">Select Business Categories</option>
                          <option v-for="bcategory in categories" v-bind:value="bcategory.id" :key="bcategory.id">
                          {{ bcategory.categoryname }}
                          </option>
                        </select>
                        <has-error :form="form" field="usertype"></has-error>
                    </div-->
                      <div class="form-group">
                        <label for="business">Package <span class="required-sign">*</span></label>
                        <select name="usertype" v-model="form.usertype" id="usertype" class="form-control" :class="{ 'is-invalid': form.errors.has('branch_contact_person') }">
                           <option value="basic">Basic</option>
                          <option value="standard">Standard</option>
                           <option value="professional">Professional</option>
                        </select>
                        <has-error :form="form" field="usertype"></has-error>
                    </div>
                    <input type="hidden"   name="adminuserpassword" v-model="form.adminuserpassword">
                     <input type="hidden"   name="dialcode" v-model="form.dialcode">
                     <input type="hidden"   name="systemid" v-model="form.systemid">
                      <input type="hidden"   name="address" v-model="form.address">
                      <input type="hidden"   name="companyid" v-model="form.companyid">
                    <input type="hidden"   name="branchid" v-model="form.branchid">
                    <input type="hidden"   name="companylimit" v-model="form.companylimit">
                    <input type="hidden"   name="entrylimit" v-model="form.entrylimit">
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                </div>
                </div>
        </div>
    </div>
</template>
<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
    export default {
      // props: ['utype'],
         components: {  VuePhoneNumberInput},
          mounted() {
        console.log(Math.floor(100000 + Math.random() * 900000));
       // console.log((Math.random().toString(36).slice(-8)));
           console.log('Mounted');
        },
         data() {
            return {
                errors: [],
               categories: {},
               max: 11,
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
                    password: Math.floor(100000 + Math.random() * 900000),
                    usertype: 'basic',
                    adminuserpassword: '@ABCU789',
                   // businesscategory: '',
                    companyid: 0,
                    address: '',
                    systemid:0,
                    branchid:0,
                    companylimit:1,
                    entrylimit:10,
                })
            }
        },
        methods: {
                updatePhoneNumber(data) {
                    this.form.dialcode=data.countryCallingCode;
                    console.log("dialcode=", this.form.dialcode);
                  },
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
                          //console.log("response:",response.data);
                          if(response.data === '')
                          {
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                          Toast.fire({
                          icon: 'error',
                          title: 'Data Already Exists!!'
                          })
                          }
                          else{
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                          Toast.fire({
                          icon: 'success',
                          title: 'User created successfully'
                          })

                          this.$Progress.finish()

                          //this.$router.push({ name: "/userslist" });
                          this.$router.push({ name: 'userslist' });

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