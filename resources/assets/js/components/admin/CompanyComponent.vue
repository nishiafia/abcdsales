<template>
    <div class="container">
        <div class="row mt-5">
          <div class="scom"> 
              <table class="switchcompany">
                <tbody>
                  <tr>
                    <td class="switchlabel">Switch Company:</td>
                    <td> <select  name="teamcompanyid" v-model="teamcompanyid"  class="form-control" v-on:change="switchCompany"> 
                    <option v-for="scompany in teamcompanies" v-bind:value="scompany.teamcompanyname.id" :key="scompany.teamcompanyname.id">
                    {{ scompany.teamcompanyname.companyname }}
                    </option>
                    </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Company </h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal"  @click="openModalWindow">Add New <i class="fa fa-building-o" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>B. Category</th>
                        <th>Country</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Company Type</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="comp in companies" :key="comp.id">
                    <td>{{ comp.companyname }}</td>
                    <td>{{ comp.businesscategoryname.categoryname }}</td>
                    <td>{{ comp.country }}</td>
                    <td>{{ comp.city.thananame}}</td>
                    <td>{{ comp.address}}</td>
                    <td >{{comp.companytypename.typename}}</td>
                    <td>
                        <a href="/#/company" title="Edit" data-id="comp.id" @click="editModalWindow(comp)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a href="/#/company" title="Delete" @click="deleteCompany(comp.id)">
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Company</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Company</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editMode ? updateCompany() : createCompany()">
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="business">Company Name</label>
                      <input v-model="form.companyname" type="text" name="companyname"
                      placeholder="Company Name"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('companyname') }" required>
                      <has-error :form="form" field="companyname"></has-error>
                    </div>
                    <div class="form-group">
                      <label for="business">Business Category</label>
                      <select name="businesscategory" v-model="form.businesscategory" id="businesscategory" class="form-control" :class="{ 'is-invalid': form.errors.has('businesscategory') }" required>
                      <option v-for="bcategory in categories" v-bind:value="bcategory.id" :key="bcategory.id">
                      {{ bcategory.categoryname }}
                      </option>
                      </select>
                      <has-error :form="form" field="businesscategory"></has-error>
                    </div>
                    <div class="form-group" >
                      <label for="country">Country</label>
                      <input v-model="form.country" type="text" name="country" id="country" 
                      class="form-control" :class="{ 'is-invalid': form.errors.has('country') }" readonly required>
                      <has-error :form="form" field="country"></has-error>
                    </div>


                    <div class="form-group">
                      <label for="city">Area</label>
                      <select name="thanaid" v-model="form.thanaid" id="thanaid" class="form-control" :class="{ 'is-invalid': form.errors.has('thanaid') }" required>
                      <option v-for="thana in cities" v-bind:value="thana.id" :key="thana.id">
                      {{ thana.thananame }}
                      </option>
                      </select>
                      <has-error :form="form" field="thanaid"></has-error>
                    </div>
                    <div class="form-group">
                      <label for="address">Address Detail</label>
                      <textarea v-model="form.address" name="address" class="form-control"  :class="{ 'is-invalid': form.errors.has('address') }" required ></textarea>
                      <has-error :form="form" field="address"></has-error>
                    </div>
                    <div class="form-group">
                      <label for="city">Type of Company</label>
                      <select name="companytype" v-model="form.companytype" id="companytype" class="form-control" :class="{ 'is-invalid': form.errors.has('companytype') }" required>
                      <option v-for="ctype in types" v-bind:value="ctype.id" :key="ctype.id">
                      {{ ctype.typename }}
                      </option>
                      </select>
                      <has-error :form="form" field="companytype"></has-error>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <input type="hidden"   name="systemid" v-model="form.systemid">
                     <input type="hidden"   name="entryid" v-model="form.entryid">
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
import moment from 'moment';
    export default {
        props: ['userData'],
        data() {
            return {
                editMode: false,
                categories: {},
                companies: {},
                cities: {},
                types: {},
                shouldDisable: false,
                totalcompany: 0,
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                currentdate:  moment().format("Y/M/D"),
                subsdate: moment(this.userData.subscriptiondate).format("Y/M/D"),
                form: new Form({
                    id: '',
                    companyname : '',
                    businesscategory: '',
                    country : 'Bangladesh',
                    thanaid: '',
                    address: '',
                    companytype: 1,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                })
            }
        },
        methods: {

        editModalWindow(comp){
           if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              this.form.clear();
              this.editMode = true
              this.shouldDisable =true
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(comp)
          }
          else{
             Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events
                Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n  to unlock your access!'
                })
             /* this.form.clear();
              this.editMode = true
              this.shouldDisable =true
              this.form.reset();
              $('#addNew').modal('hide');
              this.form.fill(comp)*/
          }
        },
        updateCompany(){
           this.form.put('api/companylist/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Company updated successfully'
                    })

                    Fire.$emit('AfterCreatedCompanyLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
             console.log("com=",this.userData.companylimit);
             this.totalcompany=this.companies.length;
             console.log("totalcompany=",this.totalcompany);
             if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              if(this.totalcompany < this.userData.companylimit){
                this.editMode = false
                this.shouldDisable =false
                this.form.reset();
                console.log("com1=",this.userData.companylimit);
                $('#addNew').modal('show');
              }
              else
              {
                $('#addNew').modal('hide');
                console.log("com2=",this.userData.companylimit);
                Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events
                Toast.fire({
                icon: 'error',
                title: 'Sorry you have no authorization!! \n Please contact our support 09638010100 \n to advance access!'
                })
              }
          }
          else{
           $('#addNew').modal('hide');
              console.log("com2=",this.userData.teamlimit);
              Fire.$emit('AfterCreatedUserLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n  to unlock your access!'
              })
          }
        },

        loadCompany() {
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get("api/companylist", {headers})
          .then( data =>{
              console.log("company =>", data);
              this.companies = data.data
          });
            //debugger;
            //alert("adasds",data);
        },
        loadBusinesscategory() {
                axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
                //console.log("category=", this.categories);
        },
        loadCity() {
                axios.get('/getcity').then( data => (this.cities = data.data));
               // console.log("category=", this.cities);
        },
        loadCompanytype() {
                axios.get('/getcompanytype').then( data => (this.types = data.data));
               // console.log("category=", this.types);
        },

        createCompany(){

            this.$Progress.start()

            this.form.post('api/companylist')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Name Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Company created successfully'
                        })

                        this.$Progress.finish()
                        location.reload();
                        $('#addNew').modal('hide');

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          deleteCompany(id) {
           if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
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
                this.form.delete('api/companylist/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Company deleted successfully',
                              'success'
                            )
                    this.loadCompany();

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
            else{
                  Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                  Toast.fire({
                  icon: 'error',
                  title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n  to unlock your access!'
                  })
            }
          },
           loadSwitchCompany() {
                let headers = {
                "Sessionkey": this.userData.remember_token,
                }
                axios.get('/getswitchcompany', {headers})
                .then( response =>{
                this.teamcompanies = response.data
                console.log("teamcompany =>", this.teamcompanies);
                });
            },
            switchCompany(event){
                let headers = {
                "Sessionkey": this.userData.remember_token,
                }
                let target = parseInt(event.target.value);
                axios.get("/updateSwitchCompany/"+target, {headers})
                .then( response =>{
                location.reload();
                // this.$router.go();
                Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                });
            },
        },
        created() { //Like Mounted this method
          //console.log("this.userData ", this.userData);
          this.loadBusinesscategory();
          this.loadCity();
          this.loadCompanytype();
          this.loadCompany();
           this.loadSwitchCompany();
          Fire.$on('AfterCreatedCompanyLoadIt',()=>{ //custom events fire on
              this.loadCompany();
          });
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}

</style>