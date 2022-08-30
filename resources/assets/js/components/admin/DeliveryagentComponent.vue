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
                <h3 class="card-title">Delivery Agent</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-truck" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      
                        <th>Agent</th>
                        <th>Rate Inside Dhaka</th>
                        <th>Inside COD Charge</th>
                        <th>Rate Outside Dhaka</th>
                        <th>Outside COD Charge</th>
                         <th>Company</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="deliveryrate in deliveries.data" :key="deliveryrate.id">
                     <td>{{ deliveryrate.agentname }}</td>
                     <td>{{ deliveryrate.insiderate }} TK</td>
                     <td>{{ deliveryrate.insidecodcharge }}%</td>
                     <td >{{ deliveryrate.outsiderate }} TK</td>
					           <td>{{ deliveryrate.outsidecodcharge }}%</td>
                      <td>{{ deliveryrate.companydata.companyname }}</td>
                    <td v-if="deliveryrate.isactive === 1" class="useractive">Active</td>
					<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/deliveryagent" data="deliveryrate.id" @click="editModalWindow(deliveryrate)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="deliveryrate.isactive === 1" href="/#/deliveryagent" @click="inactiveAgent(deliveryrate.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/deliveryagent" @click="activeAgent(deliveryrate.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="deliveries" @pagination-change-page="loadDeliveryRate"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Delivery Agent</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Delivery Agent</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateAgent() : createAgent()">
                  <div class="modal-body">
                    <div class="form-group">
                    <label for="">Agent Name</label> 
                    <input v-model="form.agentname" type="text" name="agentname"
                    placeholder="Agent Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('agentname') }"  required>
                    <has-error :form="form" field="agentname"></has-error>
                    </div>
                     
                    <div class="form-group">
                    <label for="">Rate Inside Dhaka </label><br />
                     <div class="formdiscount">
                    <input v-model="form.insiderate" type="text" name="insiderate"
                    placeholder="Rate Inside Dhaka"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('insiderate') }" @keypress="onlyNumber" required><span class="percentage">TK</span>
                    <has-error :form="form" field="insiderate"></has-error>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Inside COD Charge</label><br />
                     <div class="formdiscount">
                    <input v-model="form.insidecodcharge" type="text" name="insidecodcharge"
                    placeholder="Inside COD Charge"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('insidecodcharge') }" @keypress="onlyNumber" required><span class="percentage">%</span>
                    <has-error :form="form" field="insidecodcharge"></has-error>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Rate Outside Dhaka </label><br />
                     <div class="formdiscount">
                    <input v-model="form.outsiderate" type="text" name="outsiderate"
                    placeholder="Rate Outside Dhaka"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('outsiderate') }" @keypress="onlyNumber" required><span class="percentage">TK</span>
                    <has-error :form="form" field="outsiderate"></has-error>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Outside COD Charge</label><br />
                     <div class="formdiscount">
                    <input v-model="form.outsidecodcharge" type="text" name="outsidecodcharge"
                    placeholder="Outside COD Charge"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('outsidecodcharge') }" @keypress="onlyNumber" required><span class="percentage">%</span>
                    <has-error :form="form" field="outsidecodcharge"></has-error>
                     </div>
                    </div>
                 </div>
                  <div class="modal-footer">
                     <input type="hidden"   name="systemid" v-model="form.systemid">
                     <input type="hidden"   name="entryid" v-model="form.entryid">
                      <input type="hidden"   name="isactive" v-model="form.isactive">
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
                deliveries: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
                form: new Form({
                    id: '',
                    agentname: '',
                    insiderate: 0,
                    insidecodcharge : 0,
                    outsiderate: 0,
                    outsidecodcharge : 0,
                    isactive : 1,
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
        editModalWindow(deliveryrate){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(deliveryrate)
        },
        updateAgent(){
           this.form.put('api/deliveryagent/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedDeliveryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Agent Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Updated successfully'
                    })
                    Fire.$emit('AfterCreatedDeliveryLoadIt');
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
        loadDeliveryRate(page) {
           if (typeof page === 'undefined') {
             page = 1;
             }
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get('api/deliveryagent?page=' + page, {headers})
          .then( data => {
            this.deliveries = data.data;
            //console.log('data -> ', data);
          });
          //console.log("data",this.categories);
        },
        createAgent(){
          
            this.$Progress.start()
            this.form.post('api/deliveryagent')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedDeliveryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Agent Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedDeliveryLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Delivery Agent created successfully'
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
          inactiveAgent(id) {
               let headers = {
                "StatusKey": 'inactive',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to inactive this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Inactive it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/deliveryagent/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Agent Inactive successfully',
                              'success'
                            )
                    this.loadDeliveryRate();
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
          activeAgent(id) {
               let headers = {
                "StatusKey": 'active',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to active this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Active it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/deliveryagent/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Agent Active successfully',
                              'success'
                            )
                    this.loadDeliveryRate();
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
            this.loadDeliveryRate();
            this.loadSwitchCompany();
            Fire.$on('AfterCreatedDeliveryLoadIt',()=>{ //custom events fire on
                this.loadDeliveryRate();
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
