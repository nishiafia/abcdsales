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
                
                <h3 class="card-title">Outgoing Payments</h3>
                <div>
                    <form  @submit.prevent="searchOutgoingOrder()">
                 <table class="table">
                   <tbody>
                     <tr>
                       <td>
                       
                        <label>Find Order: </label><br>
                         <input v-model="form.inputValue" name="inputValue" type="text" placeholder="Vendor Name/Phone/Ordernumbr" class="form-control" required />
                       
                        </td>
                        <td><label>Date from</label> <br><datepicker v-model="form.deliverydate" name="deliverydate" id="deliverydate"   required></datepicker> 
                        
                        </td>
                        <td><label>To</label> <br>
                        <datepicker v-model="form.duedeliverydate" name="duedeliverydate" id="duedeliverydate"></datepicker>
                        </td>
                        <td><br>
                     
                           
                          <button :disabled='isDisabled'  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button>
                        </td>
                     </tr>
                   </tbody>
                 </table>
                    </form>
                 </div>
              </div>
                <form @submit.prevent="createOutgoingPayment()" id="incomingpayment" >
              <div class="card-body table-responsive p-0">
           
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Order #</th>
                        <th>Vendor</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th class="">Total</th>
                        <th class="incomingtableheader"> Paid</th>
                        <th class="incomingtableheader">Current Payment </th>
                         <th class="incomingtableheader">Balance</th>
                  </tr> 

                  <tr v-for="(porder,k) in porders" :key="k">
                      <td  colspan="8" v-if="porder.totalamount !== 0">
                          <table class="incomingtable">
                                <tr class="incomingtabletr">
                                <td class="incomingtabletd" v-if="porder.paidamount !== porder.totalamount">
                                <input type="checkbox" :value="porder.id"  v-model="porder.checkedOrders"  @click="checkConfirmbutton(porder.id,$event,k)"> 
                                {{ porder.ordernumber }}
                              </td>
                              <td class="incomingtabletd" v-else>
                              <input type="checkbox" :value="porder.id"    :checked='true' :disabled='true' class="disablecheckedbox"> 
                              {{ porder.ordernumber }}
                              </td>
                            <td class="incomingtabletd">{{ porder.customername}} </td>
                            <td class="incomingtabletd">{{ porder.currentdate}} </td>
                            <td class="incomingtabletd deliverydata">{{ porder.deliverydate}} </td>
                            <td class="incomingtabletd incomingtableheader">{{ porder.totalamount | toCurrency}}</td>
                            <td class="incomingtabletd incomingtableheader"><input type="text" name="paidamount"  v-model="porder.paidamount" readonly class="transactioninput"  />+<input type="text" name="paidamountcurrent"  v-model="porder.paidamountcurrent" readonly class="transactioninput" />
                            </td>
                            <td class="incomingtabletd incomingtableheader" v-if="porder.paidamount !== porder.totalamount"> 
                              <input type="text"  name="payamount"  v-model="porder.payamount" @keypress="onlyNumber"   @change="amountvalidation(porder.totalamount,porder.paidamount,$event,k)" :readonly="currentpayment" class="transactioninput"/>

                            </td>
                            <td class="incomingtabletd incomingtableheader" v-else> 
                              <input type="text"  name="payamount"  v-model="porder.payamount" @keypress="onlyNumber"   @change="amountvalidation(porder.totalamount,porder.paidamount,$event,k)" class="transactioninput" :disabled='true'/>
                              
                            </td>
                            <td class="incomingtabletd incomingtableheader"><input type="text" name="dueamount"  v-model="porder.dueamount" readonly class="transactioninput"  />
                            </td>
                                </tr>
                                 
                          </table>
                      </td>  
                  </tr>
                </tbody></table>
              </div>
                            <div class="modal-content" v-show="paymentinfo">
                                <div class="modal-header">
                                <h5   class="modal-title" id="addNewPayment">Payment Method</h5>
                                 </div>
                                <table  class="paymenttable"> 
                                 <tbody>
                                <tr>
                                <td colspan="2">
                               <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="1" v-model="formaccount.paymentmethod" v-on:change="showCash" >
                                  Cash
                                  </label>
                                  <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="2" v-model="formaccount.paymentmethod" v-on:change="showBkash" >
                                  Bkash
                                  </label>
                                   <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="3" v-model="formaccount.paymentmethod" v-on:change="showBank" >
                                  Bank
                                  </label>
                                    
                                  </td>
                               </tr>
                              
                                <tr>
                                  <td>
                                      
                                       <table  class="paymenttable"> 
                                  <tbody>
                                    <tr>
                                      <td class="title">Date</td>
                                      <td><datepicker v-model="formaccount.paymentdate" name="paymentdate"  id="paymentdate" :class="{ 'is-invalid': form.errors.has('paymentdate') }"  required></datepicker></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                  </td>
                                </tr>
                                <tr>
                                <td colspan="2">
                                  <table  class="paymenttable" v-show="bkashform"> 
                                  <tbody>
                                    <tr>
                                      <td class="title">Bkash Number</td>
                                      <td> 
                                        <VuePhoneNumberInput 
                                        default-country-code="BD"
                                        name="bkashnumber" 
                                        v-model="formaccount.bkashnumber"
                                        :maxlength="max"
                                        />
                                        
                                        <input type="hidden" v-model="formaccount.dialcode" name="dialcode" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="title">Pin Number</td>
                                      <td><input type="text" v-model="formaccount.bkashpin" name="bkashpin" class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="cashform"> 
                                  <tbody>
                                    <tr>
                                      <td class="title">Cash Payee</td>
                                      <td><input type="text" v-model="formaccount.cashpay" name="cashpay"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="bankform"> 
                                  <tbody>
                                    <tr>
                                      <td class="title">Bank</td>
                                      <td>
                                        <select name="bankid" v-model="formaccount.bankid"  class="form-control" >
                                        <option value="0">Select Bank</option>
                                        <option v-for="bank in banksdata" v-bind:value="bank.id" :key="bank.id">
                                        {{ bank.bankname }}
                                        </option>
                                        </select>
                                      </td>
                                    </tr>
                                     <tr>
                                      <td class="title">Branch</td>
                                      <td><input type="text" v-model="formaccount.branchname" name="branchname"  class="form-control" /></td>
                                    </tr>
                                     <tr>
                                      <td class="title">Account Name</td>
                                      <td><input type="text" v-model="formaccount.accountholder" name="accountholder"  class="form-control" /></td>
                                    </tr>
                                     <tr >
                                      <td class="title">Account Number</td>
                                      <td><input type="text" v-model="formaccount.accountnumber" name="accountnumber"  class="form-control" /></td>
                                    </tr>
                                      <tr >
                                      <td class="title">Cheque Number</td>
                                      <td><input type="text" v-model="formaccount.chequenumber" name="chequenumber"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>

                                </td>
                              
                               </tr>

                            </tbody>
                          </table>
                                <div class="modal-footer">
                                   <input type="hidden" v-model="formaccount.systemid" name="systemid" />
                                   <input type="hidden" v-model="formaccount.entryid" name="entryid" />
                                   <input type="hidden" v-model="formaccount.companyid" name="companyid" />
                                 
                                 <button  :disabled='paymentbutton'  type="submit" class="btn btn-primary">Submit</button>
                                </div>
                             
                        
              </div>
                </form>
            </div>

          </div>
        </div>


            
    </div>

</template>

<script>
import Datepicker from 'vuejs-datepicker';
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
    export default {
        props: ['userData'],
        components: {  Datepicker,VuePhoneNumberInput},
        data() {
            return {
                editMode: false,
                isDisabled: false,
                paymentinfo: false,
                allPaid: false,
                porders: {},
                max:12,
                methodsdata: {},
                banksdata: {},
                bkash: false,
                cash: false,
                bank: false,
                bkashform: false,
                cashform: true,
                bankform: false,
                bkashtable: false,
                cashtable: false,
                banktable: false,
                paymentbutton:false,
                currentpayment: true,
                currentdate: new Date(), 
                subsdate: new Date(this.userData.subscriptiondate),
               // checkedOrders:[],
                errors: [],
             //   payamount: [],
               
                porders: [
                  {
                totalamount: 0,
                paidamountcurrent: 0,
                dueamount: 0,
                paidamount: 0,
                payamount:0,
                checkedOrders: false,
               }],
              formaccount: new Form({
                paymentmethod: 1,
                dialcode: '+88',
                bkashnumber: '',
                bkashpin: '',
                cashpay: '',
                bankid: '',
                accountholder: '',
                accountnumber: '',
                branchname: '',
                chequenumber: '',
                systemid: this.userData.systemid,
                entryid: this.userData.id,
                companyid: this.userData.companyid,
                paymentdate: new Date(),
              }),
               
               form: new Form({
                deliverydate: '',
                duedeliverydate: '',
                inputValue: '',
               }),
              
              itemList: [],
              apiLoaded: false,
              selectedItem: {},
              teamcompanyid:this.userData.companyid,
              teamcompanies:{},
              checkedItems: [], 
            }
        },
         computed: {  
       
         },  
        methods: {
           itemSearchVisible (item) {
          let currentName = item.customername.toLowerCase()
          let currentInput = this.form.inputValue.toLowerCase()
          return currentName.includes(currentInput)
          },

          getSearchList () {
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
          axios.get("/getcustomer",{headers}).then( response => {
             console.log("vendorlist=",response.data)
          this.itemList = response.data
          this.apiLoaded = true
          })
          },

          selectSearchItem (theItem) {
          console.log("category=",theItem)
          this.selectedItem = theItem
          // this.form.customername=this.inputValue
          this.form.inputValue = ''
          this.form.customerid=theItem.id
          
          this.$emit('on-item-selected', theItem)
          },
          resetSearchItem () {
          this.selectedItem = {}
          this.$nextTick( () => this.$refs.dropdowninput.focus() )
          this.$emit('on-item-reset')
          },
          onlyNumber ($event) {
          let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
          if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
          }
          },
          checkConfirmbutton(id,event,k){
            //this.checkedItems.push( {id: id, key: k});
                console.log("key=",id, k);
              if (event.target.checked) {
                 this.currentpayment=false;
                }
                else{
                this.currentpayment=true;
                }
           
             /* this.porders.forEach((val) => {
                this.checkedItems.forEach(v =>{
                  console.log("===> ",this.porders[v.key], v);
                    if(val.id == v.id){
                      console.log("Matched => ",this.porders[v.key]);
                      let p = this.porders[v.key].payamount;
                      document.getElementById(p +"-"+v.key).setAttribute("readOnly", true); 
                      //readOnly = true;
                    }else{
                      console.log("Unmatched => ", this.porders[v.key]);
                      this.porders[v.key].payamount = false;
                    }
                });
            });
            console.log("this.checkedItems=", this.checkedItems);
            //this.confirmbutton=false;*/
           },
            amountvalidation(totalamount,paid,event,k){
                console.log("forminput=", paid);
                let paidtotal=0.00;
                let duetotal=0.00;
                let due=0.00;
                paidtotal=(parseFloat(paid)+parseFloat(this.porders[k].payamount));
                duetotal=(parseFloat(totalamount)-parseFloat(paidtotal));
                due=(parseFloat(totalamount)-parseFloat(this.porders[k].paidamount));
                if(this.porders[k].payamount === '')
                {
                  console.log("due=", due);
                  this.porders[k].payamount=0.00;
                  this.porders[k].paidamountcurrent=0.00;
                  this.porders[k].dueamount=parseFloat(due);
                  this.paymentbutton=true;
                }
                else if(this.porders[k].payamount>due)
                {
                  this.paymentbutton=true;
                  Toast.fire({
                  icon: 'error',
                  title: 'Input Amount more than due amount!!'
                  })
                  console.log("due=", due);
                  this.porders[k].payamount=0.00;
                  this.porders[k].paidamountcurrent=0.00;
                  this.porders[k].dueamount=parseFloat(due);
                }
                else{
                  this.paymentbutton=false;
                  this.porders[k].paidamountcurrent=parseFloat(this.porders[k].payamount);
                  this.porders[k].dueamount=parseFloat(duetotal);
                }


                //paidtotal=(parseFloat(paid)+parseFloat(this.porders[k].payamount));
            

           },
          showBkash: function() {
            this.bkashform =true;
            this.cashform =false;
            this.bankform =false;
          },
          showCash: function() {
            this.bkashform =false;
            this.cashform =true;
            this.bankform =false;
          },
           showBank: function() {
            this.bkashform =false;
            this.cashform =false;
            this.bankform =true;
          },
       /* loadOrder(page) { 
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          if (typeof page === 'undefined') {
            page = 1;
            }
          //console.log("token =", token);
          axios.get('api/salesorder?page=' + page, {headers})
          .then( response =>{
              console.log("orders =>", response.data);
              this.porders = response.data
          }); 
        },*/
        
      getCustomerId(event)
         {
           //console.log('vendorid=' , this.vendorid);
            this.loadRefPo(this.customerid);

         },
          loadCustomer() {
             let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get('/getcustomer',{headers}).then( data => (this.customers = data.data));
            console.log("vendor=", this.customers);
          },
          loadBank() {
            axios.get("/getbank")
            .then( data =>{
            this.banksdata = data.data;
          });
             
        },
          createOutgoingPayment: function (e) {
             if(this.currentdate.getTime() <= this.subsdate.getTime() && this.userData.subscriptionstatus === 1) 
            {
            let orders = this.porders.filter( (order) => order.checkedOrders)
            // var navigate = this.$router;
            console.log("orders=",orders);
            let allCheckedOrders = [];
            orders.forEach((ord) => allCheckedOrders.push({id: ord.id, payable: ord.payamount}))
            var formContents = jQuery("#incomingpayment").serialize();//this.formaccount;
            console.log("allCheckedOrders=",allCheckedOrders);

            let accountDetails = {};

            for (const [key, value] of Object.entries(this.formaccount)) {
              console.log(`${key}: ${value}`);
              accountDetails[key] = value;
            }

            console.log("accountDetails=",accountDetails);
            axios.post('/createOutgoingPayment', {accountInfo: accountDetails, ordersList: allCheckedOrders})
            .then((response) => {
              console.log('rsponse => ',response.data);
              if(response.data === 'success')
                    {    // alert("Wrong");
                         // Fire.$emit('AfterCreatedGroupcodeLoadIt'); //custom events
                         Toast.fire({
                          icon: 'success',
                          title: 'Payment Sent Successfully'
                        })
                    }
            }, () =>{
              console.log('failed');
            });

          
            //e.preventDefault();
            }
            else{
               Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !! \n Please contact to admin for access.'
                })
            }
          },
         
         searchOutgoingOrder(){
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
            this.form.post('/searchOutgoingPayment',{headers})
               .then((response)=>{
                   
                   this.porders = response.data;
                    console.log("orders =>", this.porders);
                    this.paymentinfo=true;
               })
               .catch(()=>{
                  console.log("Error.....")
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
           // this.loadOrder();
           // this.loadCustomer();
             this.getSearchList();
             this.loadSwitchCompany();
             this.loadBank();
         //  Fire.$on('AfterCreatedPurchaseorderLoadIt',()=>{ //custom events fire on
              //  this.loadOrder();
              //  });	

        }
    }
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}
.row{

    margin-left: 0px;
}
.rowlabel{
    color:white;
}
.unittype{
    margin-left: 20px;
}
.pagination{
  margin:15px;
}
.modal-content{
    padding: 10px;
}

</style>