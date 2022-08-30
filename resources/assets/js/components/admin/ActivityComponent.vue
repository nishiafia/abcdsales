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
                <h3 class="card-title">Activity</h3><br/>
                  <div>
                  <form  @submit.prevent="searchActivity()">
                  <table class="table">
                  <tbody>
                  <tr>
                  <td>Partner Type
                  <select name="partnertype" v-model="formsearch.partnertype" id="partnertype" class="form-control" :class="{ 'is-invalid': form.errors.has('partnertype') }">
                  <option value="">Select Partner Type</option>
                  <option v-for="ptype in partnertypedata" v-bind:value="ptype.id" :key="ptype.id">
                  {{ ptype.prtnername }}
                  </option>
                  </select>
                  </td>
                   <td>By Name/phone/Follow-up-date</span>
                  <input type="text" name="searchvalue" v-model="formsearch.searchvalue" id="searchvalue" class="form-control"  />
                  </td>
                  <td>
                  <label for="email">Activity Status </label><br/>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="1" v-model="formsearch.activitystatus"  checked  >
                                Open
                                </label>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="2" v-model="formsearch.activitystatus"  >
                                Close
                                </label>
                  </td>
                  <td class="activitysearchbutton">
                  <button  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
                  Search </button>
                  </td>

                  </tr>
                  </tbody>
                  </table>
                  </form>
                  </div>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-commenting" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Customer/Vendor Name</th>
                        <th>Partner Type</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Communication Source</th>
                        <th>Next Follow Up Date & Tim</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="commdata in activities.data" :key="commdata.id">
                    <td>{{ commdata.cname }}</td>
                     <td v-if="commdata.partnertype == 1">Customer</td>
                     <td v-else>Vendor</td>
                    <td>{{ commdata.telephone }}</td>
                    <td>{{ commdata.cmessage }}</td>
                    <td>{{ commdata.csourse | strToUpper}}</td>
                    <td>{{ commdata.appointmentdate }}</td>
                    <td v-if="commdata.activitystatus === 1" class="useractive">Open</td>
                    <td class="userinactive" v-else>Close</td>
                    <td>
                     <a href="/#/activity" title="Open Activity" data-id="commdata.id" @click="newModalWindow(commdata)">
                            <i class="fa fa-folder-open" aria-hidden="true"></i>
                        </a>
                          <a v-if="commdata.activitystatus === 2" href="/#/activity" title="Open" @click="openActivity(commdata.id)">
                        <i class="fa fa-openid" aria-hidden="true"></i>
                        </a>
                         <a v-if="commdata.activitystatus === 1" href="/#/activity" title="Close" @click="closeActivity(commdata.id)">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                        <!--a href="/#/activity" title="Edit Main Activity" data-id="commdata.id" @click="editModalWindow(commdata)">
                            <i class="fa fa-edit blue"></i>
                        </a-->
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
                            {{ selectedItem.customername }} [{{ selectedItem.telephone }}]
                            </div>

                            <div v-show="inputValue && apiLoaded" id="lsearch" class="dropdown-listsearch">
                            <div @click="selectSearchItem(item)" v-show="itemSearchVisible(item)" v-for="item in itemList" :key="item.id" class="dropdown-itemserach">
                            {{ item.customername }} [{{ item.telephone }}]
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
                        <label for="email">Next Follow Up Date & Time </label>
                        <datetime  v-model="form.appointmentdate" name="appointmentdate" id="appointmentdate"></datetime>
                        </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden"   name="systemid" v-model="form.systemid">
                            <input type="hidden"   name="companyid" v-model="form.companyid">
                            <input type="hidden"   name="branchid" v-model="form.branchid">
                            <!--button type="button" class="btn btn-danger" data-dismiss="modal">Close</button-->
                            <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>


         <div class="modal fade" id="addNewActivity" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-activity" role="document">
                <div class="modal-content">
                <div class="modal-header">

                    <h5 v-show="!editMode1" class="modal-title" id="addNewLabel">Activity</h5>
                    <h5 v-show="editMode1" class="modal-title" id="addNewLabel">Update Activity</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                <div class="activityform">
                    <form @submit.prevent="editMode1 ? updateActivitysub() : createActivitysub()" id="createinfo">
                        <div class="modal-body">
                          <div class="row">
                                <div class="form-group pst">
                                <label for="email">Partner Type <span class="required-sign">*</span></label><br/>
                                <label for="windows">
                                <input type="radio" id="partnertype" name="partnertype" value="2" v-model="formactivity.partnertype" v-on:change="onChange($event)" :disabled="shouldDisable">
                                Vendor
                                </label>
                                <label for="windows">
                                <input type="radio" id="partnertype" name="partnertype" value="1" v-model="formactivity.partnertype" v-on:change="onChange($event)" :disabled= "shouldDisable1">
                                Customer
                                </label>
                                </div>
                                <div class="form-group activityst">
                                <label for="email">Activity Status <span class="required-sign">*</span></label><br/>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="1" v-model="formactivity.activitystatus"  checked  >
                                Open
                                </label>
                                <label for="windows">
                                <input type="radio" id="activitystatus" name="activitystatus" value="2" v-model="formactivity.activitystatus"  >
                                Close
                                </label>
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="email">Vendor/Customer Name:<span class="required-sign">*</span></label>
                             <input type="hidden"   name="vendorid" v-model="formactivity.vendorid">
                             <input type="hidden"   name="activityid" v-model="formactivity.activityid">
                             <input type="hidden"   name="baseid" v-model="formactivity.baseid">
                            <input type="text"name="cname" v-model="formactivity.cname" class="form-control" readOnly>
                            </div>
                            <div class="form-group">
                                <label for="email">Communication Messsage <span class="required-sign">*</span></label>
                                <textarea v-model="formactivity.cmessage" name="cmessage" placeholder="Message" class="form-control" maxlength="300" required></textarea>
                                <span class="limiter">Max limit 300 characters and now shows ( {{charactersLeft1}} )</span>
                            </div>
                            <div class="form-group">
                            <label for="address">Communication Source <span class="required-sign">*</span></label>
                            <select name="csourse" v-model="formactivity.csourse" id="csourse" class="form-control" required>
                                    <option value="phone">Phone</option>
                                    <option value="sms">SMS</option>
                                    <option value="email">Email</option>
                                </select>
                        </div>
                        <div class="form-group">
                        <label for="email">Next Follow Up Date & Time </label>
                        <datetime  v-model="formactivity.appointmentdate" name="appointmentdate" id="appointmentdate"></datetime>
                         <input type="hidden"   name="systemid" v-model="formactivity.systemid">
                        </div>

                        </div>
                        <div class="modal-footer">
                            <a v-show="editMode1" class="btn btn-primary" href="/#/activity" title="Open Activity" data-id="currentactivity.id" @click="newModalWindow(currentactivity)">
                            Reset for New
                            </a>
                            <a v-if="currentactivity.activitystatus === 1" href="/#/activity" title="Close" @click="closeActivity(currentactivity.id)" class="btn btn-danger">
                            Close Activity
                            </a>
                            <a v-if="currentactivity.activitystatus === 2" href="/#/activity" title="Close" @click="openActivity(currentactivity.id)" class="btn btn-primary">
                            Open Activity
                            </a>
                            <button v-show="editMode1" type="submit" class="btn btn-primary">Update</button>
                            <button v-show="!editMode1" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                    </div>
                      <div class="card-body table-responsive p-0 activitylisttable">
                      <table class="table table-hover">
                      <tbody>
                      <tr>
                      <th>Last Activity List   </th>
                      </tr>
                      <tr >
                      <td>
                      <ul>
                      <li v-for="activitydata in activitiessub" :key="activitydata.id" class="activirylistli">
                           <div v-if="activitydata.appointmentdate != null">{{activitydata.appointmentdate}}</div>
                           <div v-else class="required-sign">Date not specified</div>
                         <div>{{activitydata.cmessage | truncate(50)}}
                          <a href="/#/activity" title="More Activity" data-id="activitydata.id" v-on:click="changeIsshow(activitydata.id)">
                          more..
                          </a>
                          <a href="/#/activity" title="Edit Activity" data-id="activitydata.id" @click="editSubModalWindow(activitydata)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                         <div style="display: none;"  class="moreinfo" :id="activitydata.id">
                              <div class="row">
                              <div class="form-group pst">
                              <label for="email" class="activitydetailheader">Partner Type </label>
                             <div v-if="activitydata.partnertype==1"> Customer</div>
                               <div v-else> Vendor</div>
                              </label>
                              </div>
                              <div class="form-group activityst">
                               <label for="email" class="activitydetailheader">Activity Status </label>
                               <div v-if="activitydata.activitystatus==1"> Open</div>
                               <div v-else> Close</div>
                              </div>
                              </div>
                              <div class="form-group">
                              <label for="email" class="activitydetailheader">Vendor/Customer Name</label>
                              <div> {{activitydata.cname}}</div>
                              </div>
                              <div class="form-group">
                              <label for="email" class="activitydetailheader">Communication Messsage </label>
                              <div>
                              {{activitydata.cmessage}}
                              </div>
                              </div>
                              <div class="form-group">
                              <label for="address" class="activitydetailheader">Communication Source </label>
                              <div>
                              {{activitydata.csourse}}
                              </div>
                              </div>
                              <div class="form-group">
                              <label for="email" class="activitydetailheader">Next Follow Up Date & Time </label>
                               <div>
                              {{activitydata.appointmentdate}}
                              </div>
                              </div>
                          </div>
                         </div>
                         </li>
                         </ul>
                      </td>
                      </tr>
                      </tbody>
                      </table>
                      </div>
                         
                    </div>
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
         props: ['userData'],
         components: {Datepicker,datetime},
        data() {
            return {
                editMode: false,
                editMode1: false,
                isHidden:false,
                activities: {},
                currentactivity: {},
                activitiessub: {},
                shouldDisable: false,
                shouldDisable1: false,
                readonlyfield: false,
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
                 formactivity: new Form({
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
                    activityid:'',
                    baseid:0
                }),
                formsearch: new Form({
                partnertype: '',
                searchvalue:'',
                activitystatus: 1,
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
        },
         charactersLeft1() {
        var char = this.formactivity.cmessage.length,
        limit = 300;

       // return (limit - char) + " / " + limit + "characters remaining";
       return char;
        }
        },
        methods: {
           isToday(date) {
           // return moment(date).isSame(moment().clone().startOf('day'), 'd');
           //console.log("dateformat=",date);
           if(date != null)
           {
            return moment.utc(date).local().format("D-MMM-Y hh:mm:ss");
           }
        },
        changeIsshow(aid){
          console.log("sssssss=",aid);
					//this.isHidden = !this.isHidden;
           var e = document.getElementById(aid);
           console.log("divid=",e);
           if ( e.style.display == 'block' )
            e.style.display = 'none';
        else
            e.style.display = 'block';
       //  $("#aid").show();
         //$('#'.aid).css('display', 'block');
				},
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
               console.log("swewew=",typeof(currentInput));
               /* if(typeof(currentInput) === 'string')
                {
                  console.log("string=",currentInput);
                return currentName.includes(currentInput)
                }
                 else
                {*/
                  //console.log("number=",currentInput);
                return currentName.includes(currentInput)
                //}
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
          console.log("category=",theItem)
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
            //this.shouldDisable =true
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
             // this.shouldDisable =true
              this.form.reset();
              $('#addNew').modal('hide');
              this.form.fill(comp)*/
          }
        },
        searchActivity(){
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
            this.formsearch.post('/searchActivity',{headers})
               .then((response)=>{
                   this.activities = response.data;
                    console.log("activities =>", this.activities);
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
         updateActivitysub(){
           this.formactivity.put('/communication/'+this.formactivity.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Activity updated successfully'
                    })

                    Fire.$emit('AfterCreatedUserLoadIt');

                     $('#addNewActivity').modal('show');
                    let headers = {
                    "Sessionkey": this.userData.remember_user,
                    "ActivityId":  this.formactivity.activityid,
                    }
                    console.log("act=",headers);
                    axios.get("/getsubactivity",{headers}).then( response => {
                    this.activitiessub = response.data
                    console.log("sub=", this.activitiessub);
                    })
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              this.appointmentdate='';
              this.inputValue='';
              this.vendorid='';
              this.cname='';
              this.form.clear();
              this.editMode = false
              //this.shouldDisable =false
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
         newModalWindow(comdata){
           if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              this.formactivity.clear();
              this.formactivity.reset();
              //this.formactivity.fill(comdata);
              console.log("comdata1111111=",comdata);
              this.formactivity.appointmentdate='';
              this.formactivity.partnertype=comdata.partnertype;
              this.formactivity.vendorid=comdata.vendorid;
              this.formactivity.cname=comdata.cname;
              this.formactivity.activityid=comdata.id;
              this.formactivity.activitystatus=comdata.activitystatus;
              this.editMode1 = false
              if(comdata.partnertype==2)
              {
              this.shouldDisable =false;
               console.log("vendorf=",this.shouldDisable);
              }
              else{
                this.shouldDisable =true;
                console.log("vendort=",this.shouldDisable);
              }
               if(comdata.partnertype==1)
              {
                console.log("customerf=",this.shouldDisable1);
              this.shouldDisable1 =false;
              }
              else{
                this.shouldDisable1 =true;
                 console.log("customert=",this.shouldDisable1);
              }
              this.readonlyfield=true
              //this.apiLoaded1 = false
              $('#addNewActivity').modal('show');



              let headers = {
              "Sessionkey": this.userData.remember_user,
              "ActivityId":  this.formactivity.activityid,
              }
              console.log("act=",headers);
              axios.get("/getsubactivity",{headers}).then( response => {
              this.activitiessub = response.data
              console.log("sub=", this.activitiessub);
              })
              // get current activity
              axios.get("/getcurrentactivity",{headers}).then( response => {
              this.currentactivity = response.data
              console.log("current=", this.currentactivity);
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
         editSubModalWindow(activitydata){
           if(this.currentdate<= this.subsdate && this.userData.subscriptionstatus === 1)
            {
              this.formactivity.clear();
              this.formactivity.reset();

              this.formactivity.fill(activitydata);
              this.formactivity.appointmentdate='';
              if(activitydata.appointmentdate==null)
              {
               this.formactivity.appointmentdate='';
                console.log("appointmentdate=",activitydata.appointmentdate);
              }
              else{
                 this.formactivity.appointmentdate=activitydata.appointmentdate;
              }
              console.log("comdata1111111=",activitydata);

              this.editMode1 = true
              //this.shouldDisable1 =true
              this.readonlyfield=true
              //this.apiLoaded1 = false
              $('#addNewActivity').modal('show');
               let headers = {
              "Sessionkey": this.userData.remember_user,
              "ActivityId":  this.formactivity.activityid,
              }

               // get current activity
              axios.get("/getcurrentactivity",{headers}).then( response => {
              this.currentactivity = response.data
              console.log("current=", this.currentactivity);
              })
            }
        },
         moredata(activitydata){
           
              console.log("comdata1111111=",activitydata);

            
              this.isHidden = true
               let headers = {
              "Sessionkey": this.userData.remember_user,
              "ActivityId":  this.formactivity.activityid,
              }

               // get current activity
              axios.get("/getcurrentactivity",{headers}).then( response => {
              this.currentactivity = response.data
              console.log("current=", this.currentactivity);
              })
            
        },

        loadActivity(page) {
            if (typeof page === 'undefined') {
            page = 1;
            }
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
            axios.get('/communication?page=' + page, {headers})
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
        loadPartnertype() {
        axios.get("/getpartnertype")
        .then( data =>{
        console.log("partner =>", data);
        this.partnertypedata = data.data
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
          },
           createActivitysub(){
            this.$Progress.start()
             var formContents = jQuery("#createinfo").serialize();
             axios.post('/createsubcommunication',formContents)
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Something wrong'
                        })
                    }
                    else{
                   // Fire.$emit('AfterCreatedUserLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Activity created successfully'
                        })

                          let headers = {
                          "Sessionkey": this.userData.remember_user,
                          "ActivityId": response.data,
                          }
                          console.log("act=",headers);
                          axios.get("/getsubactivity",{headers}).then( response => {
                          this.activitiessub = response.data
                          console.log("sub=", this.activitiessub);
                          })

                           this.$Progress.finish()
                            //this.formactivity.clear();
                            //this.formactivity.reset();
                            this.formactivity.cmessage="";
                            this.formactivity.appointmentdate="";
                            $('#addNewActivity').modal('show');

                }})
                .catch(() => {
                   console.log("Error......")
                })
          },
          closeActivity(id) {
            let headers = {
             "StatusKey": 'close',
            }
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to close this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Close it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('/communication/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Close!',
                              'Activity Close successfully',
                              'success'
                            )
                   this.loadActivity();
                    $('#addNewActivity').modal('hide');
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
          openActivity(id) {
                let headers = {
                "StatusKey": 'open',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to Open this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Open it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('/communication/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Open!',
                              'Activity Open successfully',
                              'success'
                            )
                    this.loadActivity();
                     $('#addNewActivity').modal('hide');
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
              text: "You won't be able to delete this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.form.delete('/communication/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Deleted successfully',
                              'success'
                            )
                   this.loadActivity();

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
            this.loadPartnertype();
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