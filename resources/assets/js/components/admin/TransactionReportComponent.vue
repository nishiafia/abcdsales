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
           <div v-if="this.userData.usertype != 'professional'">
            <p class="firstcompany"> This feature is for professional version.</p>
 
            <p class="firstcompany">Please contact administrator number 09638010100 for your upgrade and access!</p>
          </div>
            <div class="card" v-if="this.userData.usertype === 'professional'">
              <div class="card-header">
                <h3 class="card-title">Transaction Report</h3>
                <div>
                    <form  @submit.prevent="searchOrder()">
                 <table class="table">
                   <tbody>
                     <tr>
                       <td>
                         <div>
                         Vendor:
                        <div class="dropdownsearch">
                        <input v-if="Object.keys(selectedItem).length === 0" ref="dropdowninput" v-model="inputValue" class="dropdown-input" type="text" placeholder="Find Vendor" />
                        <div v-else @click="resetSearchItem" class="dropdown-selected">
                        <input type="hidden"   name="vendorid" v-model="form.vendorid">
                        {{ selectedItem.customername }}
                        </div>
                        <div v-show="inputValue && apiLoaded" class="dropdown-listsearch">
                        <div @click="selectSearchItem(item)" v-show="itemSearchVisible(item)" v-for="item in itemList" :key="item.id" class="dropdown-itemserach">

                        {{ item.customername }}
                        </div>
                        </div>
                        </div>
                        </div>
                        </td>
                        <td>
                         <div>
                         Order#:
                        <div class="dropdownsearch">
                        <input v-if="Object.keys(selectedOrderItem).length === 0" ref="dropdowninput1" v-model="inputValueOrder" class="dropdown-input" type="text" placeholder="Find Order#" />
                        <div v-else @click="resetSearchItemOrder" class="dropdown-selected">
                        <input type="hidden"   name="orderid" v-model="form.orderid">
                        {{ selectedOrderItem.ordernumber }}
                        </div>
                        <div v-show="inputValueOrder && apiLoaded1" class="dropdown-listsearch">
                        <div @click="selectSearchItemOrder(itemorder)" v-show="itemSearchVisibleOrder(itemorder)" v-for="itemorder in itemListOrder" :key="itemorder.id" class="dropdown-itemserach">

                        {{ itemorder.ordernumber }}
                        </div>
                        </div>
                        </div>
                        </div>
                        </td>
                        <td>Date from <datepicker v-model="form.deliverydate" name="deliverydate" id="deliverydate"  bootstrap-styling
                        class="dateinputstyle"  :typeable="true" required></datepicker>
                        </td>
                        <td>To
                        <datepicker v-model="form.duedeliverydate" name="duedeliverydate" id="duedeliverydate" bootstrap-styling
               class="dateinputstyle"  :typeable="true" required></datepicker>
                        </td>
                        <td>
                        <label>By Status: </label><br>
                        <select name="orderdelivered" v-model="form.orderdelivered" id="orderdelivered" class="form-control"  >
                           <option value="" >Select Status</option>
                          <option value="0" >Open</option>
                          <option value="1" >Delivered</option>
                          <option value="4" >Partial Delivered</option>
                          <option value="2" >Returned</option>
                          <option value="3" >Canceled</option>
                        </select>
                        </td>
                       <td><br>
                          <button :disabled='isDisabled'  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button></td>
                     </tr>
                   </tbody>
                 </table>
                    </form>
                 </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Order #</th>
                        <th>Vendor</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Total</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Outgoing</th>
                  </tr>
                  <tr v-for="porder in porders" :key="porder.id">
                    <td>{{ porder.ordernumber }}</td>
                    <td>{{ porder.vendordata.customername }} </td>
                    <td>{{ porder.totalamount | toCurrency}}</td>
                    <td>{{ porder.paidamount | toCurrency}}</td>
                    <td >{{porder.dueamount | toCurrency}}</td>
                    <td >{{porder.deliverydate | formatDate}}</td>
                     <td v-if="porder.orderdelivered === 1" class="deliverystatus" > Delivered</td>
                    <td v-else-if="porder.orderdelivered === 2" class="returnstatus"> Returned</td>
                    <td v-else-if="porder.orderdelivered === 3" class="returnstatus"> Canceled</td>
                    <td v-else-if="porder.orderdelivered === 4" class="partialstatus"> Partial Delivered</td>
                     <td  v-else-if="porder.orderdelivered === 0" > Open</td>
                    <td>
                        <!--a href="/#/purchaseorder" title="Edit" data-id="porder.id" @click="editModalWindowOrder(porder)">
                            <i class="fa fa-edit blue"></i>
                        </a-->
                        <router-link :to="`/purchaseorderreport/${porder.id}`"  title="Order Detail">
                             <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </router-link>
                    </td>
                  </tr>
                </tbody></table>
                <!--pagination :data="porders" @pagination-change-page="loadOrder"></pagination-->
              </div>

              <div class="card-footer">

              </div>
            </div>

          </div>
        </div>
          <div class="modal fade" id="editOrder" tabindex="3" role="dialog" aria-labelledby="editOrder" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content" >
                                <div class="modal-header">
                                  <h5 v-show="editMode" class="modal-title" id="addNewItemLabel">Update Order</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              </div>
                                <form  @submit.prevent="updateOrder()">
                                <table class="table table-hover">
                                  <tbody >
                                   <tr>
                                  <td class="tabletitle">Order # :</td>
                                  <td>  <input type="text" v-model="formorder.ordernumber" name="ordernumber" readonly />

                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="tabletitle">Vendor :</td>
                                  <td> <select name="vendorid" v-model="formorder.vendorid" id="vendorid" class="form-control" v-on:change="getVendorId"  required>
                                  <option value="0">Select Vendor</option>
                                  <option v-for="vendor in vendors" v-bind:value="vendor.id" :key="vendor.id">
                                  {{ vendor.vendorname }}
                                  </option>
                                  </select>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="tabletitle">Delivery Date :</td>
                                  <td> <datepicker v-model="formorder.deliverydate" name="deliverydate" id="deliverydate"  required></datepicker></td>
                                  </tr>
                                  <tr>
                                  <td class="tabletitle">Due Delivery Date :</td>
                                  <td> <datepicker v-model="formorder.duedeliverydate" name="duedeliverydate" id="duedeliverydate"></datepicker></td>
                                  </tr>
                                  <tr>
                                  <td class="tabletitle">Reference PO :</td>
                                  <td>
                                  <select name="refpo" v-model="formorder.refpo" id="refpo" class="form-control" >
                                  <option value="0" >Select Reference PO</option>
                                  <option v-for="ref in refpos" v-bind:value="ref.id" :key="ref.id">
                                  {{ ref.ordernumber }}
                                  </option>
                                  </select></td>
                                  </tr>
                                  <tr>
                                  <td class="tabletitle">Notes :</td>
                                  <td>  <textarea v-model="formorder.notes" name="notes" class="form-control"   required ></textarea>
                                  <input type="hidden" v-model="formorder.systemid" name="systemid" />
                                  </td>
                                  </tr>
                                   <tr>
                                  <td class="tabletitle">Shipping :</td>
                                  <td>  <input type="text" v-model="formorder.shipping" name="shipping" @keypress="onlyNumber" />

                                  </td>
                                  </tr>
                                  </tbody>
                                </table>
                                <div class="modal-footer">
                                  <input type="hidden" v-model="formorder.systemid" name="systemid" />
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  <button v-show="editMode" type="submit" class="btn btn-primary">Update Order</button>
                                 <button v-show="!editMode"  :disabled='isDisabled'  type="submit" class="btn btn-primary">Submit</button>
                                </div>
                               </form>
                              </div>
                            </div>
                          </div>
    </div>

</template>

<script>
import Datepicker from 'vuejs-datepicker';
    export default {
        props: ['userData'],
        components: {  Datepicker},
        data() {
            return {
                editMode: false,
                isDisabled: false,
                porders: {},
                refpos: {},
                vendors: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                formorder: new Form({
                id: '',
                ordernumber: '',
                systemid: this.userData.systemid,
                vendorid: 0,
                deliverydate: '',
                duedeliverydate: '',
                refpo: '',
                notes: '',
                shipping: 0,
                }),
               form: new Form({
                vendorid: '',
                orderid: '',
                deliverydate: '',
                duedeliverydate: '',
               }),
              inputValue: '',
              itemList: [],
              apiLoaded: false,
              selectedItem: {},
              inputValueOrder: '',
              itemListOrder: [],
              apiLoaded1: false,
              selectedOrderItem: {},
            }
        },
         computed: {
         },
        methods: {
           itemSearchVisible (item) {
                let currentName = item.customername.toLowerCase()
                let currentInput = this.inputValue.toLowerCase()
                return currentName.includes(currentInput)
                },

          getSearchList () {
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
          axios.get("/getvendor",{headers}).then( response => {
             console.log("vendorlist=",response.data)
          this.itemList = response.data
          this.apiLoaded = true
          })
          },

          selectSearchItem (theItem) {
          console.log("category=",theItem.id)
          this.selectedItem = theItem
          this.inputValue = ''
          this.form.vendorid=theItem.id
          this.$emit('on-item-selected', theItem)
          },
          resetSearchItem () {
          this.selectedItem = {}
          this.$nextTick( () => this.$refs.dropdowninput.focus() )
          this.$emit('on-item-reset')
          },

           itemSearchVisibleOrder (itemorder) {
                let currentName1 = itemorder.ordernumber.toLowerCase()
                let currentInput1 = this.inputValueOrder.toLowerCase()
                return currentName1.includes(currentInput1)
                },

          getSearchListOrder () {
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
          axios.get("/getordernumber",{headers}).then( response => {
             console.log("orderlist=",response.data)
          this.itemListOrder = response.data
          this.apiLoaded1 = true
          })
          },

          selectSearchItemOrder (theItem) {
          console.log("category1=",theItem.id)
          this.selectedOrderItem = theItem
          this.inputValueOrder = ''
          this.form.orderid=theItem.id
          this.$emit('on-item-selected', theItem)
          },
          resetSearchItemOrder() {
          this.selectedOrderItem = {}
          this.$nextTick( () => this.$refs.dropdowninput1.focus() )
          this.$emit('on-item-reset')
          },
          onlyNumber ($event) {
          let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
          if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
          }
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
        editModalWindowOrder(porder){
            //console.log("Nishi====",paccount.paymentmethod);
            this.formorder.clear();
            this.editMode = true;
            this.formorder.reset();
            $('#editOrder').modal('show');
            this.formorder.fill(porder)
            this.loadRefPo(this.formorder.vendorid);
          },
       /* loadOrder(page) {
          let headers = {
            "Sessionkey": this.userData.remember_user,
          }
          if (typeof page === 'undefined') {
            page = 1;
            }
          //console.log("token =", token);
          axios.get('/purchaseorder?page=' + page, {headers})
          .then( response =>{
              console.log("orders =>", response.data);
              this.porders = response.data
          });
        },*/
      getVendorId()
         {
           console.log('vendorid=' , this.vendorid);
            this.loadRefPo(this.vendorid);

         },
          loadVendor() {
             let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get('/getvendor',{headers}).then( data => (this.vendors = data.data));
            console.log("vendor=", this.vendors);
          },
          loadRefPo(vid) {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/getrefpo/"+vid,{headers})
            .then( data =>{
            this.refpos = data.data;
          console.log('ref=', this.refpos);
          });
        },
         searchOrder(){
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
            this.form.post('/purchaseOrderReport',{headers})
               .then((response)=>{
                   this.porders = response.data;
                    console.log("orders =>", this.porders);
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
         updateOrder(){
              this.$Progress.start()
              let formorder = this.formorder;
              let fpd = new Date(this.formorder.deliverydate);
              let m = parseInt(fpd.getMonth())+1;
              formorder.deliverydate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();

              let duefpd = new Date(this.formorder.duedeliverydate);
              let duem = parseInt(fpd.getMonth())+1;
              formorder.duedeliverydate = duefpd.getFullYear() + '-' + duem + '-' + duefpd.getDate();
              formorder.put('/purchaseorder/'+this.formorder.id)
               .then(()=>{
                   Toast.fire({
                      icon: 'success',
                      title: 'Order updated successfully'
                    })

                    Fire.$emit('AfterCreatedPurchaseorderLoadIt');

                    $('#editOrder').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
          deleteProduct(id) {
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
                this.form.delete('/product/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Product deleted successfully',
                              'success'
                            )
                     this.loadOrder();

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
           // this.loadOrder();
            this.loadVendor();
            this.getSearchList();
            this.getSearchListOrder();
            this.loadSwitchCompany();
            Fire.$on('AfterCreatedPurchaseorderLoadIt',()=>{ //custom events fire on
            //this.loadOrder();
            //this.getSearchList()
            });

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


.dateinputstyle {
  width:120px;
}
</style>