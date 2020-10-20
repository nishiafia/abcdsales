<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fa fa-user-plus" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.usertype | strToUpper}}</td>
                    <td v-if="user.isactive === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>In Active</td>
                    <td>{{ user.created_at | formatDate}}</td>
                    <td>
                        <a href="/#/users" title="Edit" data-id="user.id" @click="editModalWindow(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a href="/#/users" title="Delete" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                         

                    </td>
                  </tr>
                </tbody></table>
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
        <input v-model="form.name" type="text" name="name"
            placeholder="Name"
            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
        <has-error :form="form" field="name"></has-error>
    </div>
<div class="form-group">
                        <label for="phone">Username(phone)</label>
                         <!--vue-tel-input 
                          v-model="form.telephone"
                          defaultCountry="BD"
                          >
                         </vue-tel-input-->
                        <div class="row">
                         <input  type="text" v-model="form.dialcode"  name="dialcode"    
                        class="form-control form-control-dialcode" readonly>
                        <input v-model.number="form.telephone" type="number" name="telephone" required @keypress="onlyNumber"
                        placeholder="Phone Number" :maxlength="11" :minlength="11"
                        class="form-control form-control-dialcode1" :class="{ 'is-invalid': form.errors.has('telephone') }">
                         
                        <has-error :form="form" field="telephone"></has-error>
                        </div>
                          
                    </div>
     <div class="form-group">
        <input v-model="form.email" type="email" name="email" 
            placeholder="Email Address"
            class="form-control" :readonly="shouldDisable" :class="{ 'is-invalid': form.errors.has('email') }">
        <has-error :form="form" field="email"></has-error>
    </div>


    <div class="form-group" v-show="!editMode">
        <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter password"
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
        <has-error :form="form" field="password"></has-error>
    </div>

    <div class="form-group">
        <select name="usertype" v-model="form.usertype" id="usertype" class="form-control" :class="{ 'is-invalid': form.errors.has('usertype') }">
            <option value="">Select User Role</option>
             <option value="superadmin">SuperAdmin</option>
            <option value="admin">Admin</option>
            <option value="company">Company</option>
            <option value="branch">Branch</option>
        </select>
        <has-error :form="form" field="usertype"></has-error>
    </div>
    <div class="form-group">
        <select name="isactive" v-model="form.isactive" id="isactive" class="form-control" :class="{ 'is-invalid': form.errors.has('isactive') }">
            <option value="">Select User Status</option>
             <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <has-error :form="form" field="isactive"></has-error>
    </div>

</div>
<div class="modal-footer">
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
    export default {
        data() {
            return {
                editMode: false,
                users: {},
                shouldDisable: false,
                form: new Form({
                    id: '',
                    name : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    password: '',
                    usertype: '',
                    isactive: '',

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

        loadUsers() {

        axios.get("api/user").then( data => (this.users = data.data));
          //data ene users object a rakhlam then loop a users print

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