<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div>
                    <form  @submit.prevent="searchOrder()">
                 <table class="table">
                   <tbody>
                     <tr>
                       <td>
                         
                         User Type:  
                       </td>
                       <td>
                         <select name="usertype" v-model="formsearch.usertype" id="usertype" class="form-control" :class="{ 'is-invalid': form.errors.has('usertype') }">
                          <option value="">Select User Role</option>
                          <option value="standard">Standard</option>
                          <option value="team">Team Member</option>
                          <option value="business partner">Business Partner</option>
                      </select>
                     
                        </td>
                        <td>Phone:
                        </td>
                        <td>
                          <VuePhoneNumberInput 
                        default-country-code="BD"
                        name="telephone"
                        v-model="formsearch.telephone"
                        :maxlength="max"
                         />
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
                        <th>Limit(Company, Team, Entry)</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="user in users.data" :key="user.id">
                   
                    <td>{{ user.name }}</td>
                    <td>{{ user.dialcode }}{{ user.telephone }}</td>
                    <td>{{ user.usertype | strToUpper}}</td>
                    <td>C: {{ user.companylimit }}, T: {{ user.teamlimit }}, E: {{ user.entrylimit }}</td>
                    <td v-if="user.isactive === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>In Active</td>
                    <td>{{ user.created_at | formatDate}}</td>
                    <td>
                        <a href="/#/userslist" title="Edit" data-id="user.id" @click="editModalWindow(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
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
                        <VuePhoneNumberInput 
                        default-country-code="BD"
                        name="telephone"
                        required
                        v-model="form.telephone"
                        :maxlength="max"
                         />
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                        <input v-model="form.email" type="email" name="email" 
                            placeholder="Email Address"
                            class="form-control" :readonly="shouldDisable" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                  <div class="form-group" v-show="!editMode">
                    <label for="pass">Password</label>
                      <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter password"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                      <has-error :form="form" field="password"></has-error>
                  </div>

                  <div class="form-group">
                     <label for="phone">Usertype</label>
                      <select name="usertype" v-model="form.usertype" id="usertype" class="form-control" :class="{ 'is-invalid': form.errors.has('usertype') }">
                          <option value="">Select User Role</option>
                          <option value="superadmin">SuperAdmin</option>
                          <option value="admin">Admin</option>
                          <option value="standard">Standard</option>
                          <option value="team">Team Member</option>
                          <option value="business partner">Business Partner</option>
                      </select>
                      <has-error :form="form" field="usertype"></has-error>
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
                     <div class="form-group">
                            <label for="phone">Address</label>
                            <input v-model="form.address" type="text" name="address"   
                            class="form-control" >   
                        </div>
                </div>
                <div class="modal-footer">
                   <input type="hidden"   name="dialcode" v-model="form.dialcode">
                      <input type="hidden"   name="systemid" v-model="form.systemid">
                      <input type="hidden"   name="companyid" v-model="form.companyid">
                       <input type="hidden"   name="partnertype" v-model="form.partnertype">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
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
    export default {
        props: ['userData'],
        components: {  VuePhoneNumberInput},
        data() {
            return {
                editMode: false,
                users: {},
                adminusers: {},
                categories:{},
                isDisabled: false,
               max: 12,
                shouldDisable: false,
                form: new Form({
                    id: '',
                    name : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    password: '',
                    usertype: 'standard',
                    isactive: 1,
                    businesscategory: '',
                    companyid: 0,
                    partnertype: 0,
                    systemid: 0,

                }),
                formsearch: new Form({
                  usertype: '',
                  telephone: ''
                })
            }
        },
        methods: {

        editModalWindow(user){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(user)
           
        },
        updateUser(){
           this.form.put('api/user/'+this.form.id)
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
       .then( data =>{
            this.users = data.data;
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