<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div>
                    <form  @submit.prevent="searchUser()">
                    <table class="table">
                   <tbody>
                     <tr>
                       <td>
                         User Type:
                       </td>
                       <td>
                         <select name="usertype" v-model="formsearch.usertype" id="usertype" class="form-control" required>
                          <option value="">Select User Role</option>
                          <option value="basic">Basic</option>
                          <option value="standard">Standard</option>
                          <option value="professional">Professional</option>
                      </select>
                        </td>
                        <td>Phone:
                        </td>
                        <td>
                        <!--VuePhoneNumberInput
                        default-country-code="BD"
                        name="telephone"
                        v-model="formsearch.telephone"
                        :maxlength="max"
                         @update="updatePhoneNumber"
                         /-->
                         <input v-model="formsearch.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required placeholder="telephone" @keypress="onlyNumber" class="form-control" >
                        </td>
                      <td colspan="2">
                          <button :disabled='isDisabled'  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button></td>
                     </tr>
                   </tbody>
                 </table>
                    </form>
                 </div>
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
                <!--div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fa fa-user-plus" aria-hidden="true"></i></button>
                </div-->
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Limit(Company, Team,Order,Entry)</th>
                         <th>Last Login Time</th>
                        <th>Status</th>
                        <th>Subscription End Date</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.telephone }}</td>
                    <td>{{ user.usertype | strToUpper}}</td>
                    <td>C: {{ user.companylimit }}, T: {{ user.teamlimit }}, O: {{ user.orderlimit }}, E: {{ user.entrylimit }}</td>
                    <td>{{isToday(user.logintime)}} </td>
                    <td v-if="user.subscriptionstatus === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>In Active</td>
                    <td>{{ user.subscriptiondate | formatDate}} </td>
                    <td>
                        <a href="/#/userslist" title="Edit" data-id="user.id" @click="editModalWindow(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        <a href="/#/userslist" title="Change Password" data-id="user.id" @click="changeModalWindow(user)">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        </a>
                        <a href="/#/userslist" title="Delete" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="users" @pagination-change-page="loadUsers"></pagination>
              </div>

              <div class="card-footer">

              </div>
            </div>

          </div>
        </div>


            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New User</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update User</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                  <form @submit.prevent="editMode ? updateUser() : createUser()">
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="form.name" type="text" name="name" required
                        placeholder="Name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="phone">Username(phone)</label>
                        <div class="telephoneformat">Example Format: ( 01712234678 )</div>
                        <!--VuePhoneNumberInput
                        default-country-code="BD"
                        name="telephone"
                        required
                        v-model="form.telephone"
                        :maxlength="max"
                        disabled
                        @update="updatePhoneNumber"
                         /-->
                          <input v-model="form.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required placeholder="telephone" @keypress="onlyNumber" class="form-control" >
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                        <input v-model="form.email" type="email" name="email"
                            placeholder="Email Address"
                            class="form-control">
                    </div>

                  <div class="form-group">
                     <label for="phone">Usertype</label>
                      <select name="usertype" v-model="form.usertype" id="usertype" class="form-control" :class="{ 'is-invalid': form.errors.has('usertype') }">
                          <option value="basic">Basic</option>
                          <option value="standard">Standard</option>
                          <option value="professional">Professional</option>
                      </select>
                      <has-error :form="form" field="usertype"></has-error>
                  </div>
                  <div class="form-group">
                  <label for="phone">Company Limit(Set 0 for Unlimited)</label>
                  <input type="text"  class="form-control" v-model="form.companylimit" name="companylimit" @keypress="onlyNumber"    required/>
                  </div>
                    <div class="form-group">
                  <label for="phone">Team Limit(Set 0 for Unlimited)</label>
                  <input type="text"  class="form-control" v-model="form.teamlimit" name="teamlimit" @keypress="onlyNumber"    required/>
                  </div>
                  <div class="form-group">
                  <label for="phone">Order Limit(Set 0 for Unlimited)</label>
                  <input type="text"  class="form-control" v-model="form.orderlimit" name="orderlimit" @keypress="onlyNumber"    required/>
                  </div>
                   <div class="form-group">
                  <label for="phone">Entry Limit(Set 0 for Unlimited)</label>
                  <input type="text"  class="form-control" v-model="form.entrylimit" name="entrylimit" @keypress="onlyNumber"    required/>
                  </div>
                   <div class="form-group">
                       <label for="phone">Subscription Start Date</label>
                       <datepicker v-model="form.subscriptionstartdate" name="subscriptionstartdate" id="subscriptionstartdate"  required></datepicker>
                    </div>
                    <div class="form-group">
                       <label for="phone">Subscription End Date</label>
                       <datepicker v-model="form.subscriptiondate" name="subscriptiondate" id="subscriptiondate"  required></datepicker>
                    </div>
                    <div class="form-group">
                       <label for="phone">Subscription Status</label>
                        <select name="subscriptionstatus" v-model="form.subscriptionstatus" id="subscriptionstatus" class="form-control" :class="{ 'is-invalid': form.errors.has('isactive') }">
                            <option value="">Select Subscription Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <has-error :form="form" field="subscriptionstatus"></has-error>
                    </div>
                    <div class="form-group">
                       <label for="phone">Status</label>
                        <select name="isactive" v-model="form.isactive" id="isactive" class="form-control" :class="{ 'is-invalid': form.errors.has('isactive') }">
                            <option value="">Select User Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <has-error :form="form" field="isactive"></has-error>
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
                            <label for="phone">Address</label>
                            <input v-model="form.address" type="text" name="address"
                            class="form-control" >
                        </div>
                </div>
                <div class="modal-footer">
                   <input type="hidden"   name="dialcode" v-model="form.dialcode"> 
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                </div>

</form>

                </div>
            </div>
            </div>



             <div class="modal fade" id="Change" tabindex="-2" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="addNewLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="submitPassword()" id="changepass" >
                        <div class="modal-body">
                           <div class="form-group">
                            <label for="oldpass">New Password</label>
                               <input v-model="formchange.newpassword" type="password" class="form-control" id="newpassword" placeholder="********" name="newpassword" required>
                         <input type="hidden" v-model="formchange.id" name="id" >
                          </div>
                          <div class="form-group">
                            <label for="oldpass">Confirm Password</label>
                               <input v-model="formchange.confirmpassword" type="password" class="form-control" id="confirmpassword" placeholder="********" name="confirmpassword" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button  type="submit" class="btn btn-primary">Change</button>
                        </div>
                      </form>

                </div>
            </div>
            </div>
    </div>

</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import Datepicker from 'vuejs-datepicker'
import moment from 'moment';
    export default {
        props: ['userData'],
        components: {  VuePhoneNumberInput,Datepicker},
        data() {
            return {
                editMode: false,
                users: {},
                adminusers: {},
                categories:{},
                isDisabled: false,
                max: 10,
                shouldDisable: false,
                form: new Form({
                    id: '',
                    name : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    password: '',
                   // adminuserpassword: '@ABCU789',
                    usertype: '',
                    isactive: 1,
                   // businesscategory: '',
                    companylimit:0,
                    teamlimit:0,
                    entrylimit:0,
                    orderlimit:0,
                    subscriptiondate: '',
                    subscriptionstartdate: '',
                    subscriptionstatus:'',

                }),
                formsearch: new Form({
                  usertype: '',
                  telephone: ''
                }),
                 formchange: new Form({
                id: '',
                oldpassword:'',
                newpassword: '',
                confirmpassword: '',
            })

            }
        },
        methods: {
           isToday(date) {
           // return moment(date).isSame(moment().clone().startOf('day'), 'd');
           //console.log("dateformat=",date);
           if(date != null)
           {
            return moment.utc(date).local().format("D-MMM-Y hh:mm:ss");
           }
        },
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
        editModalWindow(user){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(user)
        },
        changeModalWindow(user){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
           this.form.reset();
           $('#Change').modal('show');
          this.formchange.fill(user)
        },
        updateUser(){
           let headers = {
             "StatusKey": 'basic',
            }
             let form = this.form;
              let fpd = new Date(this.form.subscriptiondate);
              let m = parseInt(fpd.getMonth())+1;
              form.subscriptiondate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();

              let fpd1 = new Date(this.form.subscriptionstartdate);
              let m1 = parseInt(fpd1.getMonth())+1;
              form.subscriptionstartdate = fpd1.getFullYear() + '-' + m1 + '-' + fpd1.getDate();
              form.put('api/user/'+this.form.id,{headers})
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'User updated successfully'
                    })

                    Fire.$emit('AfterCreatedUserLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.shouldDisable =false
           this.form.reset();
           $('#addNew').modal('show');
        },

        loadUsers(page) {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          if (typeof page === 'undefined') {
             page = 1;
             }
        axios.get('api/user?page=' + page,{headers})
       .then( response =>{
            this.users = response.data;
         console.log("userlist=",this.users);
          });

        },
        loadBusinesscategory() {
        axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
        console.log("category=", this.categories);
        },
        loadAdminuser() {
        axios.get('/getadminuser').then( data => (this.adminusers = data.data));
        console.log("user=", this.adminusers);
        },
        createUser(){

            this.$Progress.start()

            this.form.post('api/user')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
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

                        $('#addNew').modal('hide');

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          searchUser(){
             let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            this.$Progress.start()

            this.formsearch.post('/searchUser',{headers})
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'No record found!!'
                        })
                    }
                    else{
                     this.users = response.data;
                    //this.loadProduct();
                    console.log("users =>", this.users);

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          submitPassword: function (e){
           var formContents = jQuery("#changepass").serialize();
           //axios.post('/changeUserPasswordadmin',formContents)
            axios.post('/changeUserPasswordadmin1',formContents)
               .then((response)=>{
                  console.log("response=", response.data.mgs);
                  if(response.data.mgs === 'success')
                  {
                   Toast.fire({
                      icon: 'success',
                      title: 'Password Changed successfully'
                    })
                     this.$Progress.finish()

                        $('#Change').modal('hide');
                      /*axios.get('/logout').then(response => {
                        console.log("response1=", response);
                        this.$router.push({ path: "/" });
                        location.reload();

                    })*/
                  }
                   else if(response.data.mgs === 'not')
                  {
                   Toast.fire({
                      icon: 'error',
                      title: 'Wrong old Password'
                    })
                  }
                    else if(response.data.mgs === 'notmatch')
                  {
                   Toast.fire({
                      icon: 'error',
                      title: 'Confirmed Password not Match'
                    })
                  }
                   // $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
          deleteUser(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.form.delete('api/user/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                    this.loadUsers();

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                          footer: '<a href>Why do I have this issue?</a>'
                        })
                    })
                }

            })
          }
        },
        created() { //Like Mounted this method
            this.loadUsers();
            this.loadBusinesscategory();
            this.loadAdminuser();
            Fire.$on('AfterCreatedUserLoadIt',()=>{ //custom events fire on
                this.loadUsers();
            });

            // setInterval(() =>
            //     this.loadUsers()
            // ,3000); //Every 3 seconds loadUsers call
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}

</style>