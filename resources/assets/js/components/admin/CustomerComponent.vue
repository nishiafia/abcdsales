<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fa fa-universal-access" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Customer Name</th>
						<th>Phone</th>
                        <th>Email</th>
                        
                        <th>Address</th>
                        <th>Description</th>
                        
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="customer in customers.data" :key="customer.id">
                    <td>{{ customer.customername }}</td>
					 <td>{{ customer.dialcode }}{{ customer.telephone }}</td>
                    <td>{{ customer.email }}</td>
                   
                    <td>{{ customer.address }}</td>
                     <td>{{ customer.description }}</td>
                    <td>
						  <router-link :to="`/orderlist/${customer.id}`"  title="Order List">
                             <i class="fa fa-sort" aria-hidden="true"></i>
                        </router-link>
						|
                        <a href="/#/customer" title="Edit" data-id="customer.id" @click="editModalWindow(customer)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        
                          <!--a href="/#/vendor" title="Delete" @click="deleteVendor(vendor.id)">
                            <i class="fa fa-trash red"></i>
                        </a-->
                        
                         

                    </td>
                  </tr>
                </tbody></table>
                 <pagination :data="customers" @pagination-change-page="loadCustomer"></pagination>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Customer</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Customer</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <form @submit.prevent="editMode ? updateCustomer() : createCustomer()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="vendorname">Customer Name</label>
                            <input v-model="form.customername" type="text" name="customername"
                                placeholder="Customer Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('vendorname') }" required>
                            <has-error :form="form" field="vendorname"></has-error>
                        </div>
                       
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <VuePhoneNumberInput 
                                default-country-code="BD"
                                name="telephone"
                                required
                                v-model="form.telephone"
                                :maxlength="max"
                                :disabled="phoneDisable"
                                />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input v-model="form.email" type="email" name="email" 
                                placeholder="Email Address"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea v-model="form.address" name="address" class="form-control"  :class="{ 'is-invalid': form.errors.has('address') }" required ></textarea>
                            <has-error :form="form" field="address"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea v-model="form.description" name="description" class="form-control"  :class="{ 'is-invalid': form.errors.has('description') }" required ></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>
                  

                    </div>
                    <div class="modal-footer">
                        <input type="hidden"   name="dialcode" v-model="form.dialcode">
						 <input type="hidden"   name="country" v-model="form.country">
                        <input type="hidden"   name="systemid" v-model="form.systemid">
                        <input type="hidden"   name="entryid" v-model="form.entryid">
                        <input type="hidden"   name="isactive" v-model="form.isactive">
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
          components: {VuePhoneNumberInput},
        data() {
            return {
                editMode: false,
                customers: {},
                shouldDisable: false,
                companies: {},
                phoneDisable: false,
                max:12,
                form: new Form({
                    id: '',
					customername : '',
					dialcode: '+88',
                    telephone : '',
                    email: '',
                    isactive: 1,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    address: '',
					description: '',
					country:'Bangladesh',
                })
            }
        },
        methods: {

        editModalWindow(customer){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
            this.phoneDisable=true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(customer)
           
        },
        updateCustomer(){
           this.form.put('/customer/'+this.form.id)
               .then((response)=>{

                  if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedCustomerLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Vendor Already Exists!!'
                        })
                    }
                    else{
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated successfully'
                          })
                          Fire.$emit('AfterCreatedCustomerLoadIt');
                          $('#addNew').modal('hide');
               }})
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.shouldDisable =false
            this.phoneDisable=false
           this.form.reset();
           $('#addNew').modal('show');
        },

        loadCustomer(page) {
            if (typeof page === 'undefined') {
            page = 1;
            }
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
            axios.get('/customer?page=' + page, {headers})
            .then( response =>{
                this.customers = response.data
            });
        },

        createCustomer(){

            this.$Progress.start()

            this.form.post('/customer')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedCustomerLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedCustomerLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Customer created successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          inactiveVendor(id) {
            let headers = {
             "StatusKey": 'inactive',
            }
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Inactive it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('/vendor/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Vendor Inactive successfully',
                              'success'
                            )
                   this.loadVendor();
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
          },
          activeVendor(id) {
                let headers = {
                "StatusKey": 'active',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Active it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('/vendor/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Vendor Active successfully',
                              'success'
                            )
                    this.loadVendor();
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
          },
          deleteVendor(id) {
               let headers = {
                "StatusKey": 'del',
                }
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
                this.form.delete('/vendor/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Vendor deleted successfully',
                              'success'
                            )
                   this.loadVendor();

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
            this.loadCustomer();
            Fire.$on('AfterCreatedCustomerLoadIt',()=>{ //custom events fire on
                this.loadCustomer();
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