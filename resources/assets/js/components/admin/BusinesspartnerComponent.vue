<template>
    <div class="container" >
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
                <h3 class="card-title">Business Partner</h3>
                 <div>
                    <form  @submit.prevent="searchPartner()">
                 <table class="tablesearch">
                   <tbody>
                     <tr>
                       <td>
                                <select name="partnertype" v-model="formsearch.partnertype" id="partnertype" class="form-control" :class="{ 'is-invalid': form.errors.has('partnertype') }" required>
                                    <option value="">Select Partner Type</option>
                                    <option v-for="ptype in partnertypedata" v-bind:value="ptype.id" :key="ptype.id">
                                    {{ ptype.prtnername }}
                                    </option>
                                </select>
                        </td>
                        <td>
                          <button  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button>
 </td>
                     </tr>
                   </tbody>
                 </table>
                    </form>
                 </div>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-handshake-o" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Partner Type</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="partnermember in partners.data" :key="partnermember.id">
                    <td>{{ partnermember.pname.prtnername }}</td>
                    <td>{{ partnermember.customername }}</td>
                    <td>{{ partnermember.address }}</td>
                    <td>{{ partnermember.telephone }}</td>
                    <td v-if="partnermember.isactive === 1" class="useractive">Active</td>
                    <td class="userinactive" v-else>In Active</td>
                    <td>{{ partnermember.email }}</td>
                    <td>
                        <a href="/#/businesspartner" title="Edit" data-id="partnermember.id" @click="editModalWindow(partnermember)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="partnermember.isactive === 1" href="/#/businesspartner" @click="inactivePartner(partnermember.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/businesspartner" @click="activePartner(partnermember.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>
                       <router-link :to="'/activitydetail/' + partnermember.id"  title="Activity Detail">
                              <i class="fa fa-commenting" aria-hidden="true"></i>
                        </router-link>


                    </td>
                  </tr>
                </tbody></table>
                 <pagination :data="partners" @pagination-change-page="loadCustomer"></pagination>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Partner</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Partner</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <form @submit.prevent="editMode ? updatePartner() : createPartner()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="phone">Partner Type</label>
                                <select name="partnertype" v-model="form.partnertype" id="partnertype" class="form-control" :class="{ 'is-invalid': form.errors.has('partnertype') }" required>
                                    <option value="">Select Partner Type</option>
                                    <option v-for="ptype in partnertypedata" v-bind:value="ptype.id" :key="ptype.id">
                                    {{ ptype.prtnername }}
                                    </option>
                                </select>
                                <has-error :form="form" field="partnertype"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="phone">Name</label>
                                <input v-model="form.customername" type="text" name="customername"
                                    placeholder="Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('customername') }" required>
                                <has-error :form="form" field="customername"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                 <div class="telephoneformat">Example Format: ( 01712234678 )</div>
                                <!--VuePhoneNumberInput
                                default-country-code="BD"
                                name="telephone"
                                required
                                v-model="form.telephone"
                                :maxlength="max"
                               @update="updatePhoneNumber"
                                /-->
                                <input v-model="form.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required placeholder="telephone" @keypress="onlyNumber" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="form.email" type="email" name="email"
                                    placeholder="Email Address"
                                    class="form-control"  >
                            </div>
                            <div class="form-group">
                            <label for="address">Address</label>
                            <textarea v-model="form.address" type="textarea" name="address"
                            class="form-control" required />
                        </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden"   name="description" v-model="form.description">
                            <input type="hidden"   name="dialcode" v-model="form.dialcode">
                            <input type="hidden"   name="systemid" v-model="form.systemid">
                            <input type="hidden"   name="isactive" v-model="form.isactive">
                            <input type="hidden"   name="companyid" v-model="form.companyid">
                            <input type="hidden"   name="branchid" v-model="form.branchid">
                            <input type="hidden"   name="country" v-model="form.country">
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
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
    export default {
         props: ['userData'],
         components: {VuePhoneNumberInput},
        data() {
            return {
                editMode: false,
                partners: {},
                shouldDisable: false,
                phoneDisable: false,
                max:10,
                partnertypedata: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                currentdate: moment().format("Y/M/D"),
                subsdate: moment(this.userData.subscriptiondate).format("Y/M/D"),
                form: new Form({
                    id: '',
                    customername : '',
                    dialcode: '+88',
                    telephone : '',
                    email: '',
                    isactive: 1,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid,
                    address: '',
                    partnertype: '',
                    country:'Bangladesh',
                    description: '',
                }),
                formsearch: new Form({
                partnertype: '',
               }),
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
        editModalWindow(partnermember){
          if(this.currentdate <= this.subsdate&& this.userData.subscriptionstatus === 1)
          {
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
            this.phoneDisable=true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(partnermember)
           }
          else{
             Fire.$emit('AfterCreatedCompanyLoadIt'); //custom events
                Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
                })
             /* this.form.clear();
              this.editMode = true
              this.shouldDisable =true
              this.form.reset();
              $('#addNew').modal('hide');
              this.form.fill(comp)*/
          }
        },
        searchPartner(){
             /* let form = this.form;
              let fpd = new Date(this.form.deliverydate);
              let m = parseInt(fpd.getMonth())+1;
              form.deliverydate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();

              let duefpd = new Date(this.form.duedeliverydate);
              let duem = parseInt(fpd.getMonth())+1;
              form.duedeliverydate = duefpd.getFullYear() + '-' + duem + '-' + duefpd.getDate();*/
             let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            this.formsearch.post('/searchPartner',{headers})
               .then((response)=>{
                   this.partners = response.data;
                    console.log("orders =>", this.porders);
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        updatePartner(){
           this.form.put('api/customer/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Partner updated successfully'
                    })

                    Fire.$emit('AfterCreatedUserLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1) 
            {
              this.editMode = false
              this.shouldDisable =false
              this.phoneDisable=false
              this.form.reset();
              $('#addNew').modal('show');
            }
            else{
              Fire.$emit('AfterCreatedUserLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
              })
            }
        },

        loadCustomer(page) {
            if (typeof page === 'undefined') {
            page = 1;
            }
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            axios.get('api/customer?page=' + page, {headers})
            .then( response =>{
                this.partners = response.data
                 console.log("partners =>", this.partners);
            });
        },
         loadPartnertype() {
       axios.get("/getpartnertype")
          .then( data =>{
              console.log("partner =>", data);
              this.partnertypedata = data.data
          });
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
        createPartner(){
            this.$Progress.start()
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            this.form.post('api/customer',{headers})
                .then((response) => {
                    console.log("response:",response.data.message);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Already Exists'
                        })
                    }
                    if(response.data.message === 'expired')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
                        })
                    }
                    if(response.data.message === 'not')
                    {   // alert("not");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Sorry you have no authorization!! \n Please contact our support 09638010100 \n to unlock your access.'
                        })
                    }
                    if(response.data.message === 'done'){
                    Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Partner created successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          inactivePartner(id) {
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
                this.form.delete('api/customer/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Partner Inactive successfully',
                              'success'
                            )
                   this.loadCustomer();
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
          activePartner(id) {
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
                this.form.delete('api/customer/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Partner Active successfully',
                              'success'
                            )
                    this.loadCustomer();
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
                   this.loadCustomer();

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
            this.loadPartnertype();
            this.loadCustomer();
            this.loadSwitchCompany();
            Fire.$on('AfterCreatedUserLoadIt',()=>{ //custom events fire on
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