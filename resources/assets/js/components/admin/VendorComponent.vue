<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Vendor</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fa fa-user-plus" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Vendor Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="vendor in vendors.data" :key="vendor.id">
                    <td>{{ vendor.vendorname }}</td>
                    <td>{{ vendor.contactperson }}</td>
                    <td>{{ vendor.email }}</td>
                    <td>{{ vendor.dialcode }}{{ vendor.telephone }}</td>
                    <td>{{ vendor.address }}</td>
                     <td>{{ vendor.description }}</td>
                    <td v-if="vendor.isactive === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>Inactive</td>
                   
                    <td>
                        <a href="/#/vendor" title="Edit" data-id="vendor.id" @click="editModalWindow(vendor)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="vendor.isactive === 1" href="/#/vendor" @click="inactiveVendor(vendor.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/vendor" @click="activeVendor(vendor.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>
                        
                          <!--a href="/#/vendor" title="Delete" @click="deleteVendor(vendor.id)">
                            <i class="fa fa-trash red"></i>
                        </a-->
                        
                         

                    </td>
                  </tr>
                </tbody></table>
                 <pagination :data="vendors" @pagination-change-page="loadVendor"></pagination>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Vendor</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Vendor</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <form @submit.prevent="editMode ? updateVendor() : createVendor()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="vendorname">Vendor Name</label>
                            <input v-model="form.vendorname" type="text" name="vendorname"
                                placeholder="Vendor Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('vendorname') }" required>
                            <has-error :form="form" field="vendorname"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="contactperson">Contact Person</label>
                            <input v-model="form.contactperson" type="text" name="contactperson"
                                placeholder="Contact Person"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('contactperson') }" required>
                            <has-error :form="form" field="contactperson"></has-error>
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
                                class="form-control" :readonly="shouldDisable" :class="{ 'is-invalid': form.errors.has('email') }">
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
                        <div class="form-group">
                          <label for="business">Business Category</label>
                          <select name="businesscategory" v-model="form.businesscategory" id="businesscategory" class="form-control" :class="{ 'is-invalid': form.errors.has('businesscategory') }" required>
                          <option value="">Select Business Category</option>
                          <option v-for="bcategory in categories" v-bind:value="bcategory.id" :key="bcategory.id">
                          {{ bcategory.categoryname }}
                          </option>
                          </select>
                          <has-error :form="form" field="businesscategory"></has-error>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden"   name="dialcode" v-model="form.dialcode">
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
                vendors: {},
                shouldDisable: false,
                categories : {},
                companies: {},
                phoneDisable: false,
                max:12,
                form: new Form({
                    id: '',
                    vendorname : '',
                    contactperson : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    isactive: 1,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    address: '',
                    description: '',
                    businesscategory: '',

                })
            }
        },
        methods: {

        editModalWindow(vendor){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
            this.phoneDisable=true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(vendor)
           
        },
        updateVendor(){
           this.form.put('api/vendor/'+this.form.id)
               .then((response)=>{

                  if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
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
                          Fire.$emit('AfterCreatedVendorLoadIt');
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

        loadVendor(page) {
            if (typeof page === 'undefined') {
            page = 1;
            }
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            axios.get('api/vendor?page=' + page, {headers})
            .then( data =>{
                this.vendors = data.data
            });
        },
        loadBusinesscategory() {
                axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
                //console.log("category=", this.categories);
        },

        createVendor(){

            this.$Progress.start()

            this.form.post('api/vendor')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Phone Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedVendorLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Vendor created successfully'
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
                this.form.delete('api/vendor/'+id,{headers})
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
                this.form.delete('api/vendor/'+id,{headers})
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
                this.form.delete('api/vendor/'+id,{headers})
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
            this.loadBusinesscategory();
            this.loadVendor();
            Fire.$on('AfterCreatedVendorLoadIt',()=>{ //custom events fire on
                this.loadVendor();
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