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
                <h3 class="card-title">Expense Category</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      
                        <th>Category</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="excat in excategories.data" :key="excat.id">
                
                    <td>{{ excat.ecategoryname }}</td>
                    <td>{{ excat.companydata.companyname }}</td>
                    <td v-if="excat.isactive === 1" class="useractive">Active</td>
					<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/expensecategory" data="excat.id" @click="editModalWindow(excat)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="excat.isactive === 1" href="/#/expensecategory" @click="inactiveExcat(excat.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/expensecategory" @click="activeExcat(excat.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="excategories" @pagination-change-page="loadExpensecategory"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Expense Category</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Expense Category</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateExcategory() : createExcategory()">
                  <div class="modal-body">
                    <div class="form-group">
                    <label for="">Expense Category</label>
                    <input v-model="form.ecategoryname" type="text" name="ecategoryname"
                    placeholder="Category Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ecategoryname') }" required>
                    <has-error :form="form" field="ecategoryname"></has-error>
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
                excategories: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
                form: new Form({
                    id: '',
                    ecategoryname : '',
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid, 
                })
            }
        },
        methods: {
     
        editModalWindow(excat){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(excat)
        },
        updateExcategory(){
           this.form.put('api/expensecategory/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedExcategoryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Expense Category Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Updated successfully'
                    })
                    Fire.$emit('AfterCreatedExcategoryLoadIt');
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
        loadExpensecategory(page) {
           if (typeof page === 'undefined') {
             page = 1;
             }
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get('api/expensecategory?page=' + page, {headers})
          .then( data => {
            this.excategories = data.data;
            //console.log('data -> ', data);
          });
          //console.log("data",this.categories);
        },
        createExcategory(){
          
            this.$Progress.start()
            this.form.post('api/expensecategory')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedExcategoryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Expense Category Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedExcategoryLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Expense Category created successfully'
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
          inactiveExcat(id) {
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
                this.form.delete('api/expensecategory/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Expensecategoy Inactive successfully',
                              'success'
                            )
                    this.loadExpensecategory();
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
          activeExcat(id) {
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
                this.form.delete('api/expensecategory/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Expensecategoy Active successfully',
                              'success'
                            )
                    this.loadExpensecategory();
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
            this.loadExpensecategory();
            this.loadSwitchCompany();
            Fire.$on('AfterCreatedExcategoryLoadIt',()=>{ //custom events fire on
                this.loadExpensecategory();
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
