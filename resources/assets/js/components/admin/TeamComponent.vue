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
                <h3 class="card-title">Team</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" @click="openModalWindow">Add New <i class="fa fa-user-plus" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="teammember in teams" :key="teammember.id">
                    <td>{{ teammember.name }}</td>
                    <td>{{ teammember.email }}</td>
                    <td>{{ teammember.telephone }}</td>
                    <td >
                      <ul >
                      <li v-for="com in teammember.cominfo" :key="com.id">{{ com.companyname }}</li>
                      </ul>
                      </td>
                    <td v-if="teammember.isactive === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>In Active</td>
                    <td>{{ teammember.created_at | formatDate}}</td>
                    <td>
                        <a href="/#/team" title="Edit" data-id="teammember.id" @click="editModalWindow(teammember)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="teammember.isactive === 1" href="/#/team" @click="inactiveTeam(teammember.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/team" @click="activeTeam(teammember.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Team</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Team</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

<form @submit.prevent="editMode ? updateTeam() : createTeam()" id="createTeaminfo">
<div class="modal-body">
     <div class="form-group">
        <label for="phone">Name</label>
        <input v-model="form.name" type="text" name="name"
            placeholder="Name"
            class="form-control"  required>
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
        :disabled="phoneDisable"
         @update="updatePhoneNumber"
        /-->
         <input v-model="form.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required placeholder="telephone" @keypress="onlyNumber" class="form-control" >
    </div>
     <div class="form-group">
        <label for="email">Email</label>
        <input v-model="form.email" type="email" name="email"
            placeholder="Email Address"
            class="form-control"  required>
    </div>


    <div class="form-group" v-show="!editMode">
         <label for="phone">Password</label>
        <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter password"
        class="form-control"  required>
    </div>

    <div class="form-group">
        <label for="phone">Company</label>
        <select  name="companyid1" v-model="checkedCompanys"  class="form-control"  multiple  required>
            <option v-for="teamcompany in companies" v-bind:value="teamcompany.id" :key="teamcompany.id">
            {{ teamcompany.companyname }}
            </option>
        </select>
    </div>

</div>
<div class="modal-footer">
       <input type="hidden"   name="usertype" v-model="form.usertype">
       <input type="hidden"   name="dialcode" v-model="form.dialcode">
       <input type="hidden"   name="systemid" v-model="form.systemid">
       <input type="hidden"   name="isactive" v-model="form.isactive">
       <input v-show="editMode" type="hidden"   name="branchid" v-model="form.branchid">
       <input v-show="editMode" type="hidden"   name="companyid" v-model="form.companyid">
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
import moment from 'moment';
    export default {
         props: ['userData'],
         components: {VuePhoneNumberInput},
        data() {
            return {
                editMode: false,
                teams: {},
                shouldDisable: false,
                max:10,
                phoneDisable: false,
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                companies: {},
                checkedCompanys:[],
                companyid1: 0,
                currentdate:  moment().format("Y/M/D"),
                subsdate: moment(this.userData.subscriptiondate).format("Y/M/D"),
                 form: new Form({
                    id: '',
                    name : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    password: '',
                    usertype: 'team',
                    isactive: '1',
                    branchid: 0,
                    companyid: 0,
                    systemid: this.userData.systemid,
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

          editModalWindow(teammember){
          console.log("teaminfo=",teammember.cominfo);
          if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
          {
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
            this.phoneDisable=true
           this.form.reset();
           this.checkedCompanys=[];
           $('#addNew').modal('show');
           this.form.fill(teammember);
           for (let i = 0; i < teammember.cominfo.length; i++) {
            console.log("com=",teammember.cominfo[i].id);
            let comId=parseInt(teammember.cominfo[i].id,10);
            this.checkedCompanys.push(comId);
          }
          }
          else{
             Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events
                Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !!  \n Please contact our support 09638010100 \n  to unlock your access!'
                })
          }
        },
        updateTeam(){
           let headers = {
             "StatusKey": 'team',
            }
           this.form.put('api/user/'+this.form.id,{headers})
               .then(()=>{
                 axios.post('/saveTeamCompany', {teamId: this.form.id, companyInfo: this.checkedCompanys})
                      .then((response) =>{
                      console.log('response =', response.data);
                        Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                        Toast.fire({
                        icon: 'success',
                        title: 'Team Update successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');
                      });
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
             this.totalcompany=this.teams.length;
             console.log("totalcompany=",this.userData.usertype);
           if(this.userData.usertype === 'basic'){
           if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
           {
              console.log("usertype=",this.userData.usertype);
              console.log("team=",this.userData.teamlimit);
              console.log("teamtotal=",this.totalcompany);
              if(this.totalcompany < this.userData.teamlimit){
              this.editMode = false
              this.shouldDisable =false
              this.phoneDisable=false
              this.form.reset();
              this.checkedCompanys=[];
              console.log("com1=",this.userData.teamlimit);
              $('#addNew').modal('show');
           }
           else
           {
            $('#addNew').modal('hide');
            console.log("com2=",this.userData.teamlimit);
            Fire.$emit('AfterCreatedUserLoadIt'); //custom events
            Toast.fire({
            icon: 'error',
            title: 'Sorry you have no authorization!!  \n Please contact our support 09638010100 \n  to unlock your access.'
            })
           }
           }
           else{
              $('#addNew').modal('hide');
              console.log("com2=",this.userData.teamlimit);
              Fire.$emit('AfterCreatedUserLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !!  \n Please contact our support 09638010100 \n to advance access!'
              })
           }
           }
          if(this.userData.usertype === 'professional' || this.userData.usertype === 'standard' ){
           if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1){
            this.editMode = false
            this.shouldDisable =false
            this.phoneDisable=false
            this.form.reset();
            this.checkedCompanys=[];
            console.log("com1=",this.userData.teamlimit);
            $('#addNew').modal('show');
           }
            else{
              $('#addNew').modal('hide');
              console.log("com2=",this.userData.teamlimit);
              Fire.$emit('AfterCreatedUserLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !!  \n Please contact our support 09638010100 \n to unlock your access.'
              })
           }
        }},

        loadTeam() {
         let headers = {
            "Sessionkey": this.userData.remember_token,
            }
       axios.get("/getteam", {headers})
          .then( data =>{
              console.log("teams =>", data);
              this.teams = data.data
          });
        },
        loadCompany() {
        let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            axios.get('/getteamcompany',{headers}).then( data => (this.companies = data.data));
            console.log("company=", this.companies);
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

        createTeam(){
            console.log("company:",this.checkedCompanys);
             var formContents = jQuery("#createTeaminfo").serialize();
             this.$Progress.start()
            // console.log("formcontent=", formContents);

              axios.post('/createteam',formContents)
                .then((response) => {
                    console.log("response:",response.data);

                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Phone Already Exists!!'
                        })
                    }
                    else{
                      axios.post('/saveTeamCompany', {teamId: response.data, companyInfo: this.checkedCompanys})
                      .then((response) =>{
                      console.log('response =', response.data);
                        Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                        Toast.fire({
                        icon: 'success',
                        title: 'Team created successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');
                      });


                }})
                .catch(() => {
                   console.log("Error......")
                })


            //this.loadUsers();
          },
          inactiveTeam(id) {
            let headers = {
             "StatusKey": 'inactive',
            }

            if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
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
                this.form.delete('api/user/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Team Inactive successfully',
                              'success'
                            )
                   this.loadTeam();
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
          activeTeam(id) {
                let headers = {
                "StatusKey": 'active',
                }
            if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
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
                this.form.delete('api/user/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Team Active successfully',
                              'success'
                            )
                    this.loadTeam();
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
          deleteTeam(id) {
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
                this.form.delete('api/user/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                   this.loadTeam();

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
            this.loadTeam();
            this.loadSwitchCompany();
            this.loadCompany();
            Fire.$on('AfterCreatedUserLoadIt',()=>{ //custom events fire on
                this.loadTeam();
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