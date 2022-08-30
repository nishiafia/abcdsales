<template>
    <div class="container" >
        <div class="row mt-5">
            <div class="scom">
              <table class="switchcompany">
                <tbody>
                  <tr>
                    <td class="switchlabel">Switch Company:</td>
                    <td> <select  name="teamcompanyid" v-model="teamcompanyid"  class="form-control" v-on:change="switchCompany" >
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
                <h3 class="card-title">Activity List</h3>

                <!--div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-commenting" aria-hidden="true"></i></button>
                </div-->
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Customer/Vendor Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Communication Source</th>
                        <th>Meeting/Call Date & Time</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="commdata in activities.data" :key="commdata.id">
                    <td>{{ commdata.vendordata.customername }}</td>
                    <td>{{ commdata.vendordata.telephone }}</td>
                    <td>{{ commdata.cmessage }}</td>
                    <td>{{ commdata.csourse | strToUpper}}</td>
                    <td>{{ commdata.appointmentdate }}</td>
                     <td v-if="commdata.activitystatus === 1" class="useractive">Open</td>
                    <td class="userinactive" v-else>Close</td>
                    <td>
                         <router-link :to="'/activitydetail/' + commdata.vendorid" data-id="commdata.id" @click.native="editModalWindow(commdata)"  title="Activity Detail">
                               <i class="fa fa-edit blue"></i>
                        </router-link>
                    </td>
                  </tr>
                </tbody></table>
                 <pagination :data="activities" @pagination-change-page="loadActivity"></pagination>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Activity</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Activity</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <form @submit.prevent="editMode ? updateActivity() : createActivity()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group pst">
                                <label for="email">Partner Type <span class="required-sign">*</span></label><br/>
                                <label for="windows">
                                <input type="radio" id="partnertype" name="partnertype" value="2" v-model="form.partnertype" v-on:change="onChange($event)" checked  >
                                Vendor
                                </label>
                                <label for="windows">
                                <input type="radio" id="partnertype" name="partnertype" value="1" v-model="form.partnertype" v-on:change="onChange($event)" >
                                Customer
                                </label>
                                </div>
                                <div class="form-group activityst">
                                <label for="email">Activity Status <span class="required-sign">*</span></label><br/>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="1" v-model="form.activitystatus"  checked  >
                                Open
                                </label>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="2" v-model="form.activitystatus"  >
                                Close
                                </label>
                                </div>
                            </div>
                            <div class="form-group">
                            <div>
                            Vendor/Customer Name:<span class="required-sign">*</span>
                            <div class="dropdownsearch">
                            <input v-if="Object.keys(selectedItem).length === 0" ref="dropdowninput" v-model="inputValue" name="inputValue" class="dropdown-input" type="text" placeholder="Find Vendor/Customer Name" required />
                            <div v-else  @click="resetSearchItem" class="dropdown-selected">
                            <input type="hidden"   name="vendorid" v-model="form.vendorid">
                            <input type="hidden"   name="cname" v-model="form.cname">
                            {{ selectedItem.customername }} [{{ selectedItem.telephone }} ]
                            </div>

                            <div v-show="inputValue && apiLoaded" id="lsearch" class="dropdown-listsearch">
                            <div @click="selectSearchItem(item)" v-show="itemSearchVisible(item)" v-for="item in itemList" :key="item.id" class="dropdown-itemserach">
                             {{ item.customername }} [ {{ item.telephone }} ]
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Communication Messsage <span class="required-sign">*</span></label>
                                <textarea v-model="form.cmessage" name="cmessage" placeholder="Message" class="form-control" maxlength="300" required></textarea>
                                <span class="limiter">Max limit 300 characters and now shows ( {{charactersLeft}} )</span>
                            </div>
                            <div class="form-group">
                            <label for="address">Communication Source <span class="required-sign">*</span></label>
                            <select name="csourse" v-model="form.csourse" id="csourse" class="form-control" required>
                                    <option value="phone">Phone</option>
                                    <option value="sms">SMS</option>
                                    <option value="email">Email</option>
                                </select>
                        </div>
                        <div class="form-group">
                        <label for="email">Next Meeting Date & Time <span class="required-sign">*</span></label>
                        <datetime  v-model="form.appointmentdate" name="appointmentdate" id="appointmentdate" required></datetime>
                        </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden"   name="systemid" v-model="form.systemid">
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
import Datepicker from 'vuejs-datepicker'
import datetime from 'vuejs-datetimepicker';
import moment from 'moment';
    export default {
         props: ['userData','vid'],
         components: {Datepicker,datetime},
        data() {
            return {
                editMode: false,
                activities: {},
                shouldDisable: false,
                phoneDisable: false,
                max:10,
                partnertypedata: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                currentdate: moment().unix(),
                subsdate: moment(this.userData.subscriptiondate).unix(),
                form: new Form({
                    id: '',
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid,
                    csourse: 'phone',
                    vendorid: '',
                    cmessage:'',
                    appointmentdate: '',
                    partnertype: 2,
                    cname:'',
                    activitystatus: 1,
                }),
                formsearch: new Form({
                partnertype: '',
               }),
               inputValue: '',
              itemList: [],
              apiLoaded: false,
              selectedItem: {},
            }
        },
        computed: {
        charactersLeft() {
        var char = this.form.cmessage.length,
        limit = 300;

       // return (limit - char) + " / " + limit + "characters remaining";
       return char;
        }
        },
        methods: {
           onChange(event) {
              this.form.partnertype = event.target.value;
                  let headers = {
                  "Sessionkey": this.userData.remember_user,
                  "PartnerType": this.form.partnertype,
                  }

                  console.log("ptype=", this.form.partnertype);
                  axios.get("/getactivityvendor",{headers}).then( response => {
                  console.log("vendorlist=",response.data)
                  console.log("radio2=",this.form.partnertype);
                  this.itemList = response.data
                  this.apiLoaded = true
                  })
          },
            itemSearchVisible (item) {
                let currentName = item.customername.toLowerCase()
                let currentPhone = item.telephone
                let currentInput = this.inputValue.toLowerCase()
                return currentName.includes(currentInput)
                },

            getSearchList () {
             /* let headers = {
              "Sessionkey": this.userData.remember_user,
              "PartnerType": this.form.partnertype,
              }

              console.log("ptype=",headers);
              axios.get("/getactivityvendor",{headers}).then( response => {
              console.log("vendorlist=",response.data)
              console.log("radio2=",this.form.partnertype);
              this.itemList = response.data
              this.apiLoaded = true
              })*/
            },

          selectSearchItem (theItem) {
          console.log("category=",theItem.id)
           console.log("partnertype=",this.form.partnertype)
          this.selectedItem = theItem
          this.inputValue = ''
          this.form.cname=theItem.customername
           this.form.vendorid=theItem.id
          this.$emit('on-item-selected', theItem)
          },
          resetSearchItem () {
          this.selectedItem = {}
          this.$nextTick( () => this.$refs.dropdowninput.focus() )
          this.$emit('on-item-reset')
          },
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
        editModalWindow(commdata){
          if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
          {
            this.form.clear();
            this.editMode = true
            this.shouldDisable =true
            this.phoneDisable=true
            this.apiLoaded = false
            this.form.reset();
            $('#addNew').modal('show');
            console.log("comdata=",commdata.partnertype);
            let p=commdata.partnertype;
            this.inputValue=commdata.cname;
            this.form.fill(commdata)

            let headers = {
            "Sessionkey": this.userData.remember_user,
            "PartnerType": p,
            }

            console.log("ptype=",headers);
            axios.get("/getactivityvendor",{headers}).then( response => {
            console.log("vendorlist=",response.data)
            console.log("inputValue=",this.inputValue);
            this.itemList = response.data
            this.apiLoaded = true
          })
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
            "Sessionkey": this.userData.remember_user,
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
        updateActivity(){
           this.form.put('/communication/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Activity updated successfully'
                    })

                    Fire.$emit('AfterCreatedUserLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              this.editMode = false
              this.shouldDisable =false
              this.phoneDisable=false
               //this.apiLoaded = false
              this.form.reset();
              $('#addNew').modal('show');



              let headers = {
              "Sessionkey": this.userData.remember_user,
              "PartnerType": this.form.partnertype,
              }

              console.log("ptype=",headers);
              axios.get("/getactivityvendor",{headers}).then( response => {
              console.log("vendorlist=",response.data)
              console.log("radio2=",this.form.partnertype);
              this.itemList = response.data
              this.apiLoaded = true
              })

            }
            else{
              Fire.$emit('AfterCreatedUserLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
              })
            }
        },

        loadActivity(page) {
            if (typeof page === 'undefined') {
            page = 1;
            }
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
            axios.get('/getcustomercommunication/'+this.vid+'?page=' + page, {headers})
            .then( response =>{
                this.activities = response.data
                 console.log("activities =>", this.activities);
            });
        },
        loadSwitchCompany() {
          let headers = {
          "Sessionkey": this.userData.remember_user,
          }

           axios.get('/getswitchcompany', {headers})
            .then( response =>{
                this.teamcompanies = response.data
                 console.log("teamcompany =>", this.teamcompanies);
            });
        },
          switchCompany(event){
           let headers = {
            "Sessionkey": this.userData.remember_user,
            }
           let target = parseInt(event.target.value);
            axios.get("/updateSwitchCompany/"+target, {headers})
            .then( response =>{
            location.reload();
           // this.$router.go();
           Fire.$emit('AfterCreatedUserLoadIt'); //custom events
            });
         },
        createActivity(){
            this.$Progress.start()
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
            this.form.post('/communication',{headers})
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
                          title: 'Activity created successfully'
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
                this.form.delete('/customer/'+id,{headers})
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
                this.form.delete('/customer/'+id,{headers})
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
                this.form.delete('/user/'+id,{headers})
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

            this.loadActivity();
            this.loadSwitchCompany();
            this.getSearchList();
            Fire.$on('AfterCreatedUserLoadIt',()=>{ //custom events fire on
                this.loadActivity();
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