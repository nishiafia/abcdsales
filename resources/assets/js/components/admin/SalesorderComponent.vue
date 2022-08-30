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
                
                <h3 class="card-title">Sales Order</h3>
                <div>
                    <form  @submit.prevent="searchOrder()">
                 <table class="table">
                   <tbody>
                     <tr>
                       <td>
                         <!--div>
                         Customer:  
                        <div class="dropdownsearch">
                        <input v-if="Object.keys(selectedItem).length === 0" ref="dropdowninput" v-model="form.inputValue" name="inputValue" class="dropdown-input" type="text" placeholder="Find Customer" required />
                        <div v-else @click="resetSearchItem" class="dropdown-selected">
                        <input type="hidden"   name="customerid" v-model="form.customerid">
                        
                        {{ selectedItem.customername }}
                        </div>
                        <div v-show="form.inputValue && apiLoaded" class="dropdown-listsearch">
                        <div @click="selectSearchItem(item)" v-show="itemSearchVisible(item)" v-for="item in itemList" :key="item.id" class="dropdown-itemserach">

                        {{ item.customername }}
                        </div>
                        </div>
                        </div> 
                        </div-->
                      
                        <label>Find Order: </label><br>
                         <input v-model="form.inputValue" name="inputValue" type="text" placeholder="Customername/Phone/Ordernumbr" class="form-control" required />
                       
                        </td>
                        <td><label>Date from</label> <br><datepicker v-model="form.deliverydate" name="deliverydate" id="deliverydate" bootstrap-styling
               class="dateinputstyle" ></datepicker> 
                        
                        </td>
                        <td><label>To</label> <br>
                        <datepicker v-model="form.duedeliverydate" name="duedeliverydate" id="duedeliverydate" bootstrap-styling 
               class="dateinputstyle" ></datepicker>
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
                     
                           
                          <button :disabled='isDisabled'  type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>Search </button>
                        </td>
                      
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
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Total</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Incoming</th>
                  </tr> 
                  <tr v-for="porder in porders.data" :key="porder.id">
                    <td>{{ porder.ordernumber }}</td>
                    <td>{{ porder.customername}} </td>
                    <td>{{ porder.totalamount | toCurrency}}</td>
                    <td>{{ porder.paidamount | toCurrency}}</td>
                    <td >{{porder.dueamount | toCurrency}}</td>
                   
                    <td v-if="porder.deliverydate !==null">{{porder.deliverydate | formatDate}}</td>
                     <td v-else></td> 
                     <td v-if="porder.orderdelivered === 1" class="deliverystatus" > Delivered</td>
                    <td v-else-if="porder.orderdelivered === 2" class="returnstatus"> Returned</td>
                    <td v-else-if="porder.orderdelivered === 3" class="returnstatus"> Canceled</td>
                     <td v-else-if="porder.orderdelivered === 4" class="partialstatus"> Partial Delivered</td>
                     <td  v-else-if="porder.orderdelivered === 0" > Open</td>
                    
                    <td>
                        <!--a href="/#/salesorder" title="Edit" data-id="porder.id" @click="editModalWindowOrder(porder)">
                            <i class="fa fa-edit blue"></i>
                        </a-->
                        
                       
                        <router-link :to="`/salesorder/${porder.id}`"  title="Order Detail">
                             <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </router-link>
                       
                         

                    </td>
                  </tr>
                </tbody></table>
               
                <pagination :data="porders" @pagination-change-page="loadOrder"></pagination>
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
                                  <td class="tabletitle">Customer :</td> 
                                  <td> <select name="customerid" v-model="formorder.customerid" id="customerid" class="form-control" v-on:change="getCustomerId"  required>
                                  <option value="0">Select Customer</option>
                                  <option v-for="customer in customers" v-bind:value="customer.id" :key="customer.id">
                                  {{ customer.customername }}
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
                                  <td class="tabletitle">Reference SO :</td> 
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
                customers: {},
                formorder: new Form({
                id: '',
                ordernumber: '',
                systemid: this.userData.systemid,
                customerid: 0,
                deliverydate: '',
                duedeliverydate: '',
                refso: '',
                notes: '',
                shipping: 0,
                }),
               
               form: new Form({
              
               orderdelivered:'',
                deliverydate: '',
                duedeliverydate: '',
                inputValue: '',
               }),
              
              itemList: [],
              apiLoaded: false,
              selectedItem: {},
              teamcompanyid:this.userData.companyid,
              teamcompanies:{}, 
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
            "Sessionkey": this.userData.remember_user,
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
        editModalWindowOrder(porder){   
            //console.log("Nishi====",paccount.paymentmethod);
            this.formorder.clear();
            this.editMode = true;
            this.formorder.reset();
            $('#editOrder').modal('show');
            this.formorder.fill(porder)
            this.loadRefPo(this.formorder.vendorid);
          },
        loadOrder(page) { 
          let headers = {
            "Sessionkey": this.userData.remember_user,
          }
          if (typeof page === 'undefined') {
            page = 1;
            }
          //console.log("token =", token);
          axios.get('/salesorder?page=' + page, {headers})
          .then( response =>{
              console.log("orders =>", response.data);
              this.porders = response.data
          }); 
        },
        
      getCustomerId(event)
         {
           //console.log('vendorid=' , this.vendorid);
            this.loadRefPo(this.customerid);

         },
          loadCustomer() {
             let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get('/getcustomer',{headers}).then( data => (this.customers = data.data));
            console.log("vendor=", this.customers);
          },
          loadRefSo(cid) {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/getrefso/"+cid,{headers})
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
            this.form.post('/searchSalesOrder',{headers})
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
        },
      
        created() { //Like Mounted this method
            this.loadOrder();
            this.loadCustomer();
             this.getSearchList();
             this.loadSwitchCompany();
           Fire.$on('AfterCreatedPurchaseorderLoadIt',()=>{ //custom events fire on
                this.loadOrder();
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