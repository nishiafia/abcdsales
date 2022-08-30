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
                <h3 class="card-title">Tax</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-usd" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      
                        <th>Title</th>
                        <th>Tax Rate</th>
                        <th>Abbreviation</th>
                        <th>Recoverable</th>
                         <th>Company</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="taxdata in taxrates.data" :key="taxdata.id">
                   
                    <td>{{ taxdata.taxtitle }}</td>
                    <td>{{ taxdata.taxrate }} %</td>
                    <td>{{ taxdata.abbreviation }}</td>
                     <td v-if="taxdata.recoverable === 1" >Yes</td>
					<td  v-else>No</td>
           <td>{{ taxdata.companydata.companyname }}</td>
                    <td v-if="taxdata.isactive === 1" class="useractive">Active</td>
					<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/tax" data="taxdata.id" @click="editModalWindow(taxdata)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="taxdata.isactive === 1" href="/#/tax" @click="inactiveTax(taxdata.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/tax" @click="activeTax(taxdata.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="taxrates" @pagination-change-page="loadTaxRate"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Tax Rate</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Tax Rate</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateTax() : createTax()">
                  <div class="modal-body">
                    <div class="form-group">
                    <label for="">Tax Title</label> 
                    <input v-model="form.taxtitle" type="text" name="taxtitle"
                    placeholder="Tax Title"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('taxtitle') }"  required>
                    <has-error :form="form" field="taxtitle"></has-error>
                    </div>
                     <div class="form-group">
                    <label for="">Abbreviation</label> 
                    <input v-model="form.abbreviation" type="text" name="abbreviation"
                    placeholder="Abbreviation"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('abbreviation') }" >
                    <has-error :form="form" field="abbreviation"></has-error>
                    </div>
                    <div class="form-group">
                    <label for="">Tax Rate</label><br />
                     <div class="formdiscount">
                    <input v-model="form.taxrate" type="text" name="taxrate"
                    placeholder="Discount Rate"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('taxrate') }" @keypress="onlyNumber" required><span class="percentage">%</span>
                    <has-error :form="form" field="taxrate"></has-error>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Is this tax recoverable?</label> 
                        <label for="windows">
                        <input type="radio" id="recoverable" name="recoverable" value="1" v-model="form.recoverable" >
                        Yes
                        </label>
                        <label for="windows">
                        <input type="radio" id="recoverable" name="recoverable" value="2" v-model="form.recoverable">
                        No
                        </label>
                    </div>
                 </div>
                  <div class="modal-footer">
                     <input type="hidden"   name="systemid" v-model="form.systemid">
                     <input type="hidden"   name="entryid" v-model="form.entryid">
                     <input type="hidden"   name="companyid" v-model="form.companyid">
                      <input type="hidden"   name="branchid" v-model="form.branchid">
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
       props: ['userData'],
        data() {
            return {
                editMode: false,
                taxrates: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
                form: new Form({
                    id: '',
                    taxtitle: '',
                    abbreviation: '',
                    taxrate : 0,
                    recoverable: 2,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid, 
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
        editModalWindow(taxdata){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(taxdata)
        },
        updateTax(){
           this.form.put('api/tax/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedTaxLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Tax Rate Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Updated successfully'
                    })
                    Fire.$emit('AfterCreatedTaxLoadIt');
                    $('#addNew').modal('hide');
               }})
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.form.reset();
           $('#addNew').modal('show');
        },
        loadTaxRate(page) {
           if (typeof page === 'undefined') {
             page = 1;
             }
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get('api/tax?page=' + page, {headers})
          .then( data => {
            this.taxrates = data.data;
            //console.log('data -> ', data);
          });
          //console.log("data",this.categories);
        },
        createTax(){
          
            this.$Progress.start()
            this.form.post('api/tax')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedTaxLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Tax Rate Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedTaxLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Tax created successfully'
                        })
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                         //location.reload();
                    }
                })
                .catch(() => {
                   console.log("Error......")
                })
            //this.loadUsers();
          },
          inactiveTax(id) {
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
                this.form.delete('api/tax/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Tax Inactive successfully',
                              'success'
                            )
                    this.loadTaxRate();
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
          activeTax(id) {
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
                this.form.delete('api/tax/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Tax Active successfully',
                              'success'
                            )
                    this.loadTaxRate();
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
            this.loadTaxRate();
            this.loadSwitchCompany();
            Fire.$on('AfterCreatedTaxLoadIt',()=>{ //custom events fire on
                this.loadTaxRate();
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
.pagination{
  margin:15px;
}
</style>
