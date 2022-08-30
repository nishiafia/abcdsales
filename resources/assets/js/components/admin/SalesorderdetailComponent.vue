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
          <div class="col-md-12"  v-for="porder in porders" :key="porder.id">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sales Order Detail</h3>

                  <div class="card-tools">
                    <router-link :to="`/salesorder/${porder.id}`" class="btn btn-success" data-id="porder.id"   title="Edit"  @click.native="editModalWindowOrder(porder)">
                    Edit Order <i class="fa fa-edit blue"></i>
                    </router-link>
                    
                  </div>
                 
               
              </div>
              <div class="card-body table-responsive p-0" >
             
                <table class="table table-hover">
                  <tbody  >
                     <tr>
                        <td class="tabletitle">Company :</td> 
                        <td colspan="2"> 
                        {{porder.companydata.companyname}}
                        </td>
                    </tr>
                     <tr>
                        <td class="tabletitle">Order # :</td> 
                        <td colspan="2"> 
                        {{porder.ordernumber}}
                        </td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Customer :</td> 
                        <td class="customerinfo"> {{porder.customerdata.customername }}  </td>
                        <td class="customerinfo"> {{porder.customerdata.dialcode}}{{porder.customerdata.telephone}} <br>{{porder.customerdata.address }}  </td>
                        
                    </tr>
                    
                    <tr>
                        <td class="tabletitle">Date :</td> 
                        <td colspan="2">  {{porder.currentdate | formatDate}}</td> 
                    </tr>
                    <tr>
                        <td class="tabletitle">Delivery Date :</td> 
                        <td colspan="2" v-if="porder.deliverydate !==null">  {{porder.deliverydate | formatDate}}</td> 
                         <td colspan="2" else ></td> 
                    </tr>
                    <tr v-if="porder.duedeliverydate !== ''">
                        <td class="tabletitle">Due Delivery Date :</td> 
                        <td colspan="2" v-if="porder.duedeliverydate !==null"> {{porder.duedeliverydate | formatDate}}</td> 
                        <td colspan="2" else ></td> 
                    </tr>
                     <tr v-if="porder.refso !== 0">
                        <td class="tabletitle">Reference PO :</td> 
                        <td colspan="2"> 
                           {{porder.refsorder.ordernumber}}</td> 
                    </tr>
                      <tr>
                        <td class="tabletitle ">Delivery Agent :</td> 
                        <td colspan="2" class="customerinfo">  {{porder.agentdata.agentname}}
                        </td> 
                    </tr>
                      <tr>
                        <td class="tabletitle">Delivery Area :</td> 
                        <td v-if="porder.deliveryarea === 1" colspan="2" class="customerinfo">  Inside Dhaka
                        </td>
                        <td v-else-if="porder.deliveryarea === 2" colspan="2" class="customerinfo">  Outside Dhaka
                        </td>  
                    </tr>
                     <tr>
                        <td class="tabletitle">Notes :</td> 
                        <td>{{porder.notes }}</td>
                        <td>  
                           
                                  <table class="table"> 
                                    <tbody>
                                      <tr>
                                        <th>Comments
                                        </th>
                                       
                                        <th colspan="2">
                                          <div class="card-tools">
                                          <button class="btn btn-success" data-toggle="modal" data-target="#addNewComment" title="New Comments" @click="openModalWindowComment">New Comment <i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                          </div>
                                        </th>
                                      </tr>
                                      <tr v-for="(scomment,index) in salescomments" :key="scomment.id">
                                        <td >
                                            {{ index+1}}: {{scomment.comments }}
                                        </td>
                                        <td class="rightalign" colspan="2">  
                                        <router-link :to="`/salesorder/${scomment.orderid}`" data-id="scomment.id"  title="Edit"  @click.native="editModalWindowComment(scomment)">
                                        <i class="fa fa-edit blue"></i>
                                        </router-link>
                                        |
                                        <router-link  :to="`/salesorder/${scomment.orderid}`"   data-id="scomment.id"   title="Delete"  @click.native="deleteComment(scomment.id)">
                                        <i class="fa fa-remove red" aria-hidden="true"></i>
                                        </router-link>
                                        </td>
                                       
                                      </tr>
                                    </tbody>
                                  </table>
                               
                        </td> 
                    </tr>
                   
                    </tbody>
                </table>
                       
                          <table class="table table-hover itemtable">
                            <tbody >
                               <tr>
                              <th ></th>
                              <th ></th>
                              <th></th>
                              <th></th>
                              <th ></th>
                              <th ></th>
                              <th ></th>
                              <th ></th>
                              <th ></th>
                            
                              <th colspan="3" > 
                              <div class="card-tools">
                              <button class="btn btn-success" data-toggle="modal"  data-target="#addNewitem" @click="openModalWindowItem()">New Item  <i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                              </div>
                                  </th>
                                
                            </tr>
                            <tr class="multirow">
                              <th>Delivered</th>
                              <th >P.Item</th>
                              <th >Detail</th>
                              <th>Variation</th>
                              
                              <th >Qty</th>
                              <th >Price</th>
                              <th >Ex.Category</th>
                              <th >Discount</th>
                              <th >Tax</th>
                              <th >Total</th>
                              <th >Action</th>
                            </tr>
                            <tr v-for="pitem in pitems" :key="pitem.id" class="rowfont">
                            <td v-if="pitem.delivered === 0">
                                
                              <input type="checkbox" :value="pitem.id" :id="pitem.id" v-model="checkedItems"  @click="checkConfirmbutton(pitem.id,$event)"> 
                          
                              </td>
                              <td v-else>
                              <input type="checkbox" :value="pitem.id" :id="pitem.id"   :checked='true' :disabled='true' class="disablecheckedbox"> 
                              </td>
                              <td v-for="pcode in pitem.product_code" :key="pcode.id" class="innerrowfont" >
                                <span class="inventory">{{ pitem.inventory.productcode }}</span>({{ pcode.gcode }}::{{ pcode.gtitle }})
                              </td>
                             
                              <td>{{ pitem.detail }}</td>
                               
                             
                               <td >
                               <div v-for="productval in pitem.variationdatadetail" :key="productval.id"><span> {{productval.labeldata.vlabel}}:: </span> {{productval.valdata.variationname}}</div>
                               </td>
                              <td>{{ pitem.qty }}</td>
                              <td>
                                {{ pitem.price | toCurrency}}
                                </td>
                              <td v-if="pitem.excategory !==null"> 
                                {{ pitem.excategory.ecategoryname }}
                                </td>
                                <td v-else> No data</td>
                                <td v-if="pitem.discountid.split(',').length > 0">
                                {{ pitem.discountid.split(',')[1] }}%  
                               </td>
                              <td v-if="pitem.taxid.split(',').length > 0" >{{ pitem.taxid.split(',')[1] }}%</td>
                              <td>{{ pitem.total | toCurrency }} </td>
                              <td> 
                              <router-link :to="`/salesorder/${pitem.orderid}`" data-id="pitem.id"  title="Edit"  @click.native="editModalWindow(pitem)">
                              <i class="fa fa-edit blue"></i>
                              </router-link>

                              
                                <router-link v-if="pitem.delivered === 1" :to="`/salesorder/${pitem.orderid}`" data-id="pitem.id"  title="Return"  @click.native="returnItem(pitem.id)">
                                <i class="fa fa-get-pocket" aria-hidden="true"></i>
                                </router-link>

                                <router-link v-if="pitem.delivered === 0 && porder.dueamount >= pitem.total" :to="`/salesorder/${pitem.orderid}`"   data-id="pitem.id"   title="Cancel"  @click.native="cancelItem(pitem.id)">
                               <i class="fa fa-ban red" aria-hidden="true"></i>
                                </router-link>
                               </td>
                            </tr> 
                            <tr>
                              <td colspan="3"> <button type="button" :disabled='confirmbutton' class="btn btn-success"  @click="checkItem($event)">Confirm Delivery</button></td>
                              
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            </tbody>
                          </table> 
                      
                     <table class="table table-hover">
                        <tbody>
                     <tr> 
                        <td  class="paymentcol" >
                          <table  class="paymenttable payment" > 
                            <tbody>
                               <tr v-if="porder.dueamount !== 0">
                                <td colspan="4" >
                                  <div class="card-tools">
                                  <button class="btn btn-success"  data-toggle="modal" data-target="#addNewpayment" @click="openModalWindow(porder.id,porder.dueamount)">New Payment <i class="fa fa-credit-card-alt" aria-hidden="true"></i></button>
                                  </div>
                                 </td>
                               </tr>
                              <tr class="multirow" v-if="porder.paidamount !== 0">
                                <td>Payment Method</td>
                               <td>Paid Amount</td>
                               <td>Date</td>
                               <td>Action</td>
                               </tr>
                                <tr v-for="paccount in paccounts" :key="paccount.id" class="paymentdata">
                                <td v-if="paccount.paymentmethod === 1">
                                  <a v-bind:href="'/#/salesorder/'+porder.id" title="show accounts details" data-id="paccount.id" class="methodlink" @click="showModalWindowpayment(paccount.id,paccount.paymentmethod)">
                                  Cash
                                  </a>
                                  </td>
                                  <td v-else-if="paccount.paymentmethod === 2">
                                  <a v-bind:href="'/#/salesorder/'+porder.id" title="show accounts details" data-id="paccount.id" class="methodlink" @click="showModalWindowpayment(paccount.id,paccount.paymentmethod)">
                                    Bkash
                                    </a>
                                  </td>
                                  <td v-else-if="paccount.paymentmethod === 3">
                                    <a v-bind:href="'/#/salesorder/'+porder.id" title="show accounts details" data-id="paccount.id" class="methodlink" @click="showModalWindowpayment(paccount.id,paccount.paymentmethod)">
                                    Bank
                                    </a>
                                  </td>
                                  <td v-else >
                                    No Method
                                  </td>
                                  <td>{{ paccount.paidamount | toCurrency}} </td>
                                  <td>{{ paccount.paymentdate | formatDate}}</td>
                                  <td> 
                                    <router-link :to="`/salesorder/${paccount.orderid}`" data-id="paccount.id"  title="Edit"  @click.native="editModalWindowPayment(paccount)">
                                    <i class="fa fa-edit blue"></i>
                                    </router-link>
                                     |
                                    <router-link  :to="`/salesorder/${paccount.orderid}`" data-id="paccount.id"  title="Delete"  @click.native="deletePayment(paccount.id)">
                                     <i class="fa fa-trash red"></i>
                                    </router-link>
                                  </td>
                               </tr>
                              
                            </tbody>
                          </table>
                          <div class="modal fade" id="paymentinfo" tabindex="1" role="dialog" aria-labelledby="addNewLabel1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content" v-for="accountinfo in methodsdata" :key="accountinfo.id" >
                                <div class="modal-header">
                                <h5 v-show="bkashtable" class="modal-title" id="addNewLabel1">Bkash Information</h5>
                                <h5 v-show="cashtable" class="modal-title" id="addNewLabel1">Cash Information</h5>
                                <h5 v-show="banktable" class="modal-title" id="addNewLabel1">Cash Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              </div>
                                <table  class="paymenttable bkash" v-show="bkash"> 
                                <tbody>
                                <tr>
                                <td>Bkash Number</td>
                                <td> 
                                {{ accountinfo.dialcode}}{{ accountinfo.bkashnumber}}

                                </td>
                                </tr>
                                <tr>
                                <td>Pin Number</td>
                                <td>{{ accountinfo.bkashpin}}</td>
                                </tr>
                                </tbody>
                                </table>
                                <table  class="paymenttable bkash" v-show="cash"> 
                                <tbody>
                                <tr>
                                <td>Cash Payee</td>
                                <td>{{ accountinfo.cashpay}}</td>
                                </tr>
                                </tbody>
                                </table>
                                <table  class="paymenttable bkash" v-show="bank"> 
                                <tbody>
                                <tr>
                                <td>Bank</td>
                                <td  v-if="accountinfo.getbankname !== null"> 
                                {{ accountinfo.getbankname.bankname}}
                                </td>
                                </tr>
                                <tr>
                                <td>Branch</td>
                                <td>{{ accountinfo.branchname}}</td>
                                </tr>
                                <tr>
                                <td>Account Name</td>
                                <td>{{ accountinfo.accountholder}}</td>
                                </tr>
                                <tr>
                                <td>Account Number</td>
                                <td>{{ accountinfo.accountnumber}}</td>
                                </tr>
                                </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                         
                           <div class="modal fade" id="addNewpayment" tabindex="-1" role="dialog" aria-labelledby="addNewPayment" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content" >
                              
                                <div class="modal-header">
                                <h5 v-show="!editMode1"  class="modal-title" id="addNewPayment">Make Payment</h5>
                                <h5 v-show="editMode1"  class="modal-title" id="addNewPayment">Update Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              </div>
                                <form @submit.prevent="editMode1 ? updateSalesaccount() : createSalesaccount()">
                                <table  class="paymenttable"> 
                                 <tbody>
                                  <tr>
                                <td colspan="2">Payment Method</td>
                              
                               </tr>
                                <tr>
                                <td colspan="2">
                               <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="1" v-model="form.paymentmethod" v-on:change="showCash" >
                                  Cash
                                  </label>
                                  <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="2" v-model="form.paymentmethod" v-on:change="showBkash" >
                                  Bkash
                                  </label>
                                   <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="3" v-model="form.paymentmethod" v-on:change="showBank" >
                                  Bank
                                  </label>
                                     <has-error :form="form" field="paymentmethod"></has-error>
                                  </td>
                               </tr>
                               <tr>
                                 <td>Pay Amount</td>
                                 <td>
                                  
                                    <input type="text" v-model="form.paidamount" name="paidamount" id="paidamount" :class="{ 'is-invalid': form.errors.has('paidamount') }"  @keypress="onlyNumber" @change="amountvalidation(porder.dueamount)" required />
                                    <has-error :form="form" field="paidamount"></has-error>
                                    <p v-if="errors.length">
                                  
                                    <ul class="list-group">
                                    <li v-for="error in errors" class="list-group-item list-group-item-danger" :key="error">{{ error }}</li>
                                    </ul>
                                    </p>
                                 
                                 </td>
                               </tr>
                                <tr>
                                  <td>Date</td>
                                <td>
                                 
                                   <datepicker v-model="form.paymentdate" name="paymentdate"  id="paymentdate" :class="{ 'is-invalid': form.errors.has('paymentdate') }"  required></datepicker></td> 
                                <has-error :form="form" field="paymentdate"></has-error>
                                </tr>
                                <tr>
                                <td colspan="2">
                                  <table  class="paymenttable" v-show="bkashform"> 
                                  <tbody>
                                    <tr>
                                      <td>Bkash Number</td>
                                      <td> 
                                        <VuePhoneNumberInput 
                                        default-country-code="BD"
                                        name="bkashnumber" 
                                        v-model="form.bkashnumber"
                                        :maxlength="max"
                                        />
                                        
                                        <input type="hidden" v-model="form.dialcode" name="dialcode" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>Pin Number</td>
                                      <td><input type="text" v-model="form.bkashpin" name="bkashpin" class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="cashform"> 
                                  <tbody>
                                    <tr>
                                      <td>Cash Payee</td>
                                      <td><input type="text" v-model="form.cashpay" name="cashpay"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="bankform"> 
                                  <tbody>
                                    <tr>
                                      <td>Bank</td>
                                      <td>
                                        <select name="bankid" v-model="form.bankid"  class="form-control" >
                                        <option value="0">Select Bank</option>
                                        <option v-for="bank in banksdata" v-bind:value="bank.id" :key="bank.id">
                                        {{ bank.bankname }}
                                        </option>
                                        </select>
                                      </td>
                                    </tr>
                                     <tr>
                                      <td>Branch</td>
                                      <td><input type="text" v-model="form.branchname" name="branchname"  class="form-control" /></td>
                                    </tr>
                                     <tr>
                                      <td>Account Name</td>
                                      <td><input type="text" v-model="form.accountholder" name="accountholder"  class="form-control" /></td>
                                    </tr>
                                     <tr>
                                      <td>Account Number</td>
                                      <td><input type="text" v-model="form.accountnumber" name="accountnumber"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>

                                </td>
                              
                               </tr>

                            </tbody>
                          </table>
                                <div class="modal-footer">
                                   <input type="hidden" v-model="form.systemid" name="systemid" />
                                   <input type="hidden" v-model="form.entryid" name="entryid" />
                                     <input type="hidden" v-model="form.orderid" name="orderid" />
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                
                                 <button v-show="editMode1" type="submit" class="btn btn-primary">Update</button>
                              
                                 <button v-show="!editMode1"  :disabled='paymentbutton'  type="submit" class="btn btn-primary">Submit</button>
                                </div>
                               </form>
                              </div>
                            </div>
                          </div>



                           <div class="modal fade" id="addNewitem" tabindex="2" role="dialog" aria-labelledby="addNewItem" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content" >
                              
                                <div class="modal-header">
                             
                                  <h5 v-show="!editMode" class="modal-title" id="addNewItemLabel">Add New Item</h5>
                                  <h5 v-show="editMode" class="modal-title" id="addNewItemLabel">Update Item</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              </div>
                                <form  @submit.prevent="editMode ? updateItem() : createItem()">
                                <table  class="paymenttable"> 
                                 <tbody>
                                 
                               <tr>
                                 <td>Purchase Item</td>
                                 <td>
                                  <select name="pitem" v-model="formitem.pitem" class="form-control" :class="{ 'is-invalid': form.errors.has('pitem') }" v-on:change="checkpurchaseItem($event)"  required>
                                  <option value="0">Select Purchase Item</option>
                                  <option v-for="item in items" v-bind:value="item.id" :key="item.id">
                                  {{ item.groupname.gcode }}::{{ item.productname }}
                                  </option>
                                  </select>
                                  <has-error :form="formitem" field="pitem"></has-error>
                                 </td>
                               </tr>
                                <tr>
                                  <td>Details</td>
                                <td>
                                   <textarea v-model="formitem.detail" name="detail" class="form-control" ></textarea></td> 
                              
                                </tr>
                                <tr>
                                <td>Variation
                                </td>
                                <td>
                                  <div class="row" >
                                    <div v-for="varl in variationlist" :key="varl.id" class="variationrow">
                                    <input type="checkbox" v-model="formitem.variationsid"  :value="varl.variationid">
                                    <span class="variationtitle">{{ varl.labeldata.vlabel }}:: </span>{{ varl.valdata.variationname }}
                                    </div>
                                    </div>
                                </td>
                               </tr>
                               
                                <tr>
                                <td>Quantity
                                </td>
                                <td>
                                  <input type="text"  class="form-control" v-model="formitem.qty" name="qty" @keypress="onlyNumber"  @change="amountcalculation" :class="{ 'is-invalid': form.errors.has('qty') }" required/>
                                 <has-error :form="form" field="qty"></has-error>
                                </td>
                               </tr>
                                <tr>
                                <td>Price
                                </td>
                                <td>
                                   <input type="text"  v-model="formitem.price" name="price" @keypress="onlyNumber"  @change="amountcalculation" class="priceiteminput" :class="{ 'is-invalid': form.errors.has('price') }" required />
                                 <has-error :form="form" field="price"></has-error>
                                </td>
                               </tr>
                                <tr>
                                <td>Expense Category
                                </td>
                                <td>
                                  <select name="excat" v-model="formitem.excat"  class="form-control" >
                                    <option value="0">Select Ex Category</option>
                                    <option v-for="excata in categories" v-bind:value="excata.id" :key="excata.id">
                                    {{ excata.ecategoryname }}
                                    </option>
                                  </select>
                                </td>
                               </tr>
                                <tr>
                                <td>Discount
                                </td>
                                <td>
                                   <select name="discountid" v-model="formitem.discountid"  class="form-control" @change="amountcalculation"  required>
                                <option value="0,0">Select Discount</option>"
                                  <option v-for="discnt in discountrates" v-bind:value="`${discnt.id},${discnt.discountrate}`" v-bind:key="discnt.id">
                                  {{ discnt.discountrate }}%
                                  </option>
                                  </select>
                                </td>
                               </tr>
                                <tr>
                                <td>Tax
                                </td>
                                <td>
                                  <select name="taxid" v-model="formitem.taxid"  class="form-control"  @change="amountcalculation" required>
                                <option value="0,0">Select Tax</option>
                                  <option v-for="txrate in taxrates" v-bind:value="`${txrate.id},${txrate.taxrate}`" :key="txrate.id">
                                  {{ txrate.taxrate }}%
                                  </option>
                                  </select>
                                </td>
                               </tr>
                              
                                <tr>
                                <td>Total 
                                </td>
                                <td>
                                  <input type="text"  v-model="formitem.total" name="total" class="priceiteminput" readonly />
                                </td>
                               </tr>
                            </tbody>
                          </table>
                                <div class="modal-footer">
                                 <input type="hidden" v-model="formitem.delivered"  name="delivered" />
                               
                                   <input type="hidden" v-model="formitem.discountfigure" name="discountfigure" />
                                <input type="hidden" v-model="formitem.taxfigure" name="taxfigure" />
                                  <input type="hidden" v-model="formitem.systemid" name="systemid" />
                                  <input type="hidden" v-model="formitem.entryid" name="entryid" />
                                  <input type="hidden" v-model="formitem.orderid" name="orderid" />
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                              
                                 <button v-show="!editMode"  :disabled='isDisabled'  type="submit" class="btn btn-primary">Submit</button>
                                </div>
                               </form>
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
                                  <select name="refso" v-model="formorder.refso" id="refso" class="form-control" >
                                  <option value="0" >Select Reference SO</option>
                                  <option v-for="ref in refsos" v-bind:value="ref.id" :key="ref.id">
                                  {{ ref.ordernumber }}
                                  </option>
                                  </select></td> 
                                  </tr>
                                    <tr>
                                    <td class="tabletitle">Shipping Agent:</td> 
                                    <td> 
                                    <select name="agentid" v-model="formorder.agentid" id="agentid" class="form-control" v-on:change="getAgentInfo"  required>
                                    <option value="0" >Select Delivery Agent</option>
                                    <option v-for="agent in agents" v-bind:value="agent.id" :key="agent.id">
                                    {{ agent.agentname }}
                                    </option>
                                    </select></td> 
                                    </tr>
                                    <tr>
                                    <td class="tabletitle">Delivery Area:</td> 
                                    <td> 
                                    <select name="deliveryarea" v-model="formorder.deliveryarea" id="deliveryarea" class="form-control" v-on:change="getAgentInfobyarea" >
                                    <option value="1" >Inside Dhaka</option>
                                    <option value="2" >Outside Dhaka</option>
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
                                   <tr>
                                  <td class="tabletitle"></td> 
                                  <td>   <input type="checkbox"  name="orderdelivered" value="1"  true-value="1" false-value="0" v-model="formorder.orderdelivered" :disabled="deliverybutton"    >
                                  Confirm Delivered

                                  </td>
                                  </tr>
                                  
                                  </tbody>
                                </table>
                                <div class="modal-footer">
                                 
                                  <input type="hidden" v-model="formorder.systemid" name="systemid" />
                                   <input type="hidden" v-model="formorder.companyid" name="companyid" />
                                    <input type="hidden" v-model="formorder.branchid" name="branchid" />
                                   <input type="hidden" v-model="formorder.codcharge" name="codcharge" />
                                 
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  <button v-show="editMode" type="submit" class="btn btn-primary">Update Order</button>
                              
                                 <button v-show="!editMode"  :disabled='isDisabled'  type="submit" class="btn btn-primary">Submit</button>
                                </div>
                               </form>
                              </div>
                            </div>
                          </div>



                      <div class="modal fade" id="addNewComment" tabindex="-7" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                      <div class="modal-header">

                      <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Comment</h5>
                      <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Comment</h5>

                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                      </div>

                      <form @submit.prevent="editMode ? updateComment() : createComment()">
                      <div class="modal-body">
                      <div class="form-group">
                      <label for="name">Comment:</label>
                      <textarea v-model="formcomment.comments" name="comments" class="form-control"   required ></textarea>
                      </div>
                     
                      </div>
                      <div class="modal-footer">
                    
                      <input type="hidden"   name="systemid" v-model="formcomment.systemid">
                      <input  v-show="!editMode" type="hidden"   name="entryid" v-model="formcomment.entryid">
                      <input type="hidden"   name="companyid" v-model="formcomment.companyid">
                      <input type="hidden"   name="branchid" v-model="formcomment.branchid">
                      <input type="hidden"   name="orderid" v-model="formcomment.orderid">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                      <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                      </div>

                      </form>

                      </div>
                      </div>
                      </div>

                           
                        </td>
                        <td  class="paymentcol1">
                          <table class="paymenttable1 table">
                            <tbody>
                                <tr>
                                <td class="paymentheader2">Discount:</td>
                                <td class="paymentheader3 currencyfont"> {{porder.totaldiscount | toCurrency}}</td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Tax:</td>
                                <td class="paymentheader3 currencyfont"> {{porder.totaltax | toCurrency}}</td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Shipping:</td>
                                <td class="paymentheader3 currencyfont"> {{porder.shipping | toCurrency}}</td>
                               </tr>
                                <tr>
                               
                                <td class="paymentheader2">Subtotal:</td>
                                <td class="paymentheader3 currencyfont"> 
                                {{porder.totalamount | toCurrency}}
                                </td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Paid Amount:</td>
                                <td class="paymentheader3 currencyfont">
                                 {{porder.paidamount | toCurrency}}
                                  </td>
                               </tr>
                                <tr>
                                
                                <td></td>
                                <td></td>
                               </tr>
                               <tr>
                               
                                <td class="paymentheader2">Due Total:</td>
                                <td class="paymentheader3 currencyfont">
                                  
                                  {{porder.dueamount | toCurrency}}</td>
                               </tr>
                              
                            </tbody>
                          </table> 
                         </td> 
                        
                    </tr>
                     <tr>
                        
                        <td colspan="2" class="detailbottom"><label for="windows" class="btn btn-success">
                                  <input type="radio"  name="orderdelivered" value="1"  v-model="orderdelivered" :disabled="deliverybutton"   v-on:change="deliveryOrder(porder.id)" >
                                  Confirm Delivered
                                  </label>
                                  <label for="windows" class="btn btn-danger">
                                  <input type="radio"  name="orderdelivered" value="2" v-model="orderdelivered" :disabled="returnbutton"  v-on:change="returnOrder(porder.id)" >
                                  Return Order 
                                  </label>
                                  <label for="windows" class="btn btn-danger" v-if="porder.paidamount === 0.00">
                                  <input type="radio"  name="orderdelivered" value="3" v-model="orderdelivered" :disabled="returnbutton"  v-on:change="cancelOrder(porder.id)" >
                                  Cancel Order 
                                  </label>
                                  <!-- a @click="$router.go(-1)" class="btn btn-danger detail">Back</a--></td>  
                    </tr>
                 </tbody>
                
                </table>
              
              </div>

              <div class="card-footer">

              </div>
            </div>

          </div>
        </div>
    
    </div>

</template>

<script>

import Datepicker from 'vuejs-datepicker';
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
//import moment from 'moment' ;
    export default {
        props: ['userData','soid'],
        components: {  Datepicker,VuePhoneNumberInput},
        data() {
            return {
                deliverycheck: true,
                discountrates: {},
                taxrates: {},
                agents:{},
                variationlist:[],
                max:12,
                editMode: false,
                editMode1: false,
                checkedItems: [],
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
               // format: moment(new Date()).format("yyyy-MM-d H:mm:ss"),
                errors: [],
                porders: {},
                customers: {},
                salescomments: {},
                refsos: {},
                items: {},
                pcolors: {},
                psizes: {},
                categories: {},
                paymentbutton: false,
                confirmbutton: true,
                deliverybutton: false,
                returnbutton: false,
                cancelbutton: false,
                isDisabled: false,
                allDisabled: false,
                pitems:{},
                paccounts: {},
                methodsdata: {},
                banksdata: {},
                bkash: false,
                cash: false,
                bank: false,
                bkashform: false,
                cashform: false,
                bankform: false,
                bkashtable: false,
                cashtable: false,
                banktable: false,
                orderdelivered:0,
                form: new Form({
                id:'',
                paymentmethod: 1,
                paidamount: 0,
                dialcode: '+88',
                bkashnumber: '',
                bkashpin: '',
                cashpay: '',
                bankid: '',
                accountholder: '',
                accountnumber: '',
                branchname: '',
                orderid: this.soid,
                systemid: this.userData.systemid,
                entryid: this.userData.id,
                paymentdate: new Date(),
                }),
                formitem: new Form({
                id: '',
                pitem: 0,
                detail: '',
                qty: 1,
                price: 0,
                excat: 0,
                discountid: '0,0',
                taxid: '0,0',
                total: 0,
                delivered: 0,
                orderid:this.soid, 
                systemid: this.userData.systemid,
                entryid: this.userData.id,
                discountfigure: 0.0,
                taxfigure: 0.0,
                variationsid:[],
                  }),
                formorder: new Form({
                id: '',
                ordernumber: '',
                systemid: this.userData.systemid,
                companyid: this.userData.companyid,
                branchid: this.userData.branchid, 
                customerid: 0,
                deliverydate: '',
               // currentdate: new Date(),
                duedeliverydate: '',
                refso: '',
                notes: '',
                shipping: 0,
                 agentid:0,
                deliveryarea:1,
                codcharge:0,
                orderdelivered:0,
                }),
                 formcomment: new Form({
                id: '',
                comments: '',
                systemid: this.userData.systemid,
                companyid: this.userData.companyid,
                branchid: this.userData.branchid, 
                entryid: this.userData.id,
                orderid: this.soid,
                
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
        

        showModalWindowpayment(accountid,methodid){
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          
           if(methodid == 2){
            this.bkashtable = true
            this.bkash=true;
            this.banktable = false
            this.bank=false;
            this.cashtable = false
            this.cash=false;
           }
          if(methodid == 1){
            this.cashtable = true
            this.cash=true;
            this.bkashtable = false
            this.bkash=false;
            this.banktable = false
            this.bank=false;
           }
          if(methodid == 3){
            this.banktable = true
            this.bank=true;
            this.cashtable = false
            this.cash=false;
            this.bkashtable = false
            this.bkash=false;
           }
            $('#addNewpayment').modal('hide');
           $('#paymentinfo').modal('show'); 
           axios.get("/getsalespaymentmethodinformation/"+accountid, {headers})
          .then( data =>{
              console.log("methodinfo =>", data);
              this.methodsdata = data.data
          });   
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
          amountvalidation(dueamt){
            console.log("forminput=", this.form.paidamount);
             console.log("due=", dueamt);
              this.errors = [];
             if(this.form.paidamount>dueamt)
             {
               this.paymentbutton=true;
               this.errors.push('Input Amount more than due amount');
             }
             else{
               this.paymentbutton=false;
             }

           },
          amountcalculation($event)
          {
            let totalPrice = 0.0;
           let totalDiscount = 0.0;
            let totalTax = 0.0;
            let pricemultiply = 0.0;
            let discountidval=0;
            let taxidval=0;
            let discountval=0;
            let taxval=0;
            let totalwithtaxdiscount=0.0;
           // let shipping=0.0;
            
             /*console.log("inputshipping=", this.formitem.shipping);
             
            
             if(typeof this.formitem.shipping === 'undefined')
             {
                console.log("shipping=", shipping);
               shipping=0.0;
             }
             else{
               shipping= parseFloat(this.formitem.shipping);
               console.log("shipping=", shipping);
             }*/

             
              //console.log("key=", shipping);
                      discountidval = this.formitem.discountid.split(',');
                      taxidval = this.formitem.taxid.split(',');
                 
                     
                    
                      pricemultiply=parseFloat(this.formitem.price)*this.formitem.qty;
                      discountval=parseFloat((pricemultiply*discountidval[1]))/100;
                     
                      totalwithtaxdiscount=parseFloat(pricemultiply)-parseFloat(discountval);
                      taxval=parseFloat(totalwithtaxdiscount*taxidval[1])/100;
    
                      console.log("totalwithtaxdiscount=", totalwithtaxdiscount);
                  
                      totalPrice=parseFloat(totalwithtaxdiscount)+parseFloat(taxval);
                      this.formitem.total=(parseFloat(totalPrice).toFixed(2));
                      console.log("discountval=>", discountval);
                       console.log("taxval =>", taxval);
                       console.log("total=", this.formitem.total);
                        this.formitem.taxfigure=(parseFloat(taxval).toFixed(2));
                       this.formitem.discountfigure=(parseFloat(discountval).toFixed(2));
                       //totalPrice +=this.formitem.total;
                       //totalDiscount +=discountval;
                       //totalTax +=taxval;
              
                if(this.formitem.qty<1)
               {
                  this.isDisabled= true;
               }
               else
               {
                 this.isDisabled= false;
               }

           
          },
          checkpurchaseItem(event){
            let pricemultiply=0.0;
            let totalPrice=0.0;
            
            let target = parseInt(event.target.value);
            console.log("event, =>", target);
           
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }

           

           
            axios.get("/getsalesprice/"+target,{headers})
            .then( response =>{
             this.formitem.price = (parseFloat(response.data[0].sellprice).toFixed(2));
             pricemultiply=parseFloat(this.formitem.price)*this.formitem.qty;
             this.formitem.total=(parseFloat(pricemultiply).toFixed(2));
             });

             axios.get("/getvariationlist/"+target,{headers}).then( response =>{
              this.variationlist=response.data;
              });
              console.log("pitemid=", target,this.formitem.pitem);
                this.formitem.variationsid=[];
          },
          checkConfirmbutton(ids,event){
              if (event.target.checked) {
                 this.confirmbutton=false;
                }
                else{
                this.confirmbutton=true;
                }
           // console.log("event=", $event);
            //this.confirmbutton=false;
           },
          checkItem(){
            console.log("checkedid=", this.checkedItems);
            this.$Progress.start()
            axios.post('/updateSalesItemDelivery',{itemids: this.checkedItems,sorderid:this.soid})
             .then((response) => {
                console.log("response:",response.data);
              if(response.data === '')
              {  
              Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Not Done!!'
              })
              }
              else{
              Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events
               
              Toast.fire({
              icon: 'success',
              title: 'Delivery successfully'
              })
               this.confirmbutton=true;
              this.$Progress.finish()
              //$('#addNewpayment').modal('hide');
              }})
            .catch(() => {
            console.log("Error......")
            })
          },
          openModalWindow(id,dueamt){
            console.log("dueamount=",dueamt, id);
            this.form.paidamount=parseFloat(dueamt);
            $('#paymentinfo').modal('hide');
            this.form.clear();
            // this.form.reset();
            $('#addNewpayment').modal('show');
             this.cashform =true;
              this.editMode1 = false;
           
          }, 
          openModalWindowItem(){
           // this.form.paidamount=parseFloat(dueamt);
            this.editMode = false;
            this.formitem.clear();
             this.formitem.reset();
            $('#paymentinfo').modal('hide');
            $('#addNewpayment').modal('hide');
             $('#addNewitem').modal('show');
          }, 
           openModalWindowComment(){
           // this.form.paidamount=parseFloat(dueamt);
            this.editMode = false;
            this.formcomment.clear();
             this.formcomment.reset();
            $('#paymentinfo').modal('hide');
            $('#addNewpayment').modal('hide');
             $('#addNewitem').modal('hide');
              $('#addNewComment').modal('show');
          },
          editModalWindowComment(scomment){
            console.log("Nishi1111====");
        
          this.formcomment.clear();
          this.editMode = true;
          //this.formitem.shipping = 0,
          this.formcomment.reset();
          $('#addNewComment').modal('show');
          this.formcomment.fill(scomment)
          },
          editModalWindow(pitem){
            console.log("Nishi====");
        
          this.formitem.clear();
          this.editMode = true;
          //this.formitem.shipping = 0,
          this.formitem.reset();
          $('#addNewitem').modal('show');
          this.formitem.fill(pitem);
           let headers = {
            "Sessionkey": this.userData.remember_token,
          }
             this.formitem.variationsid=[];
          let target=pitem.pitem;
              axios.get("/getvariationlist/"+target,{headers}).then( response =>{
              this.variationlist=response.data;
              });
            // let variationsid=[];
           
            for (let i = 0; i < pitem.variationdatadetail.length; i++) {  
               console.log("vId=",pitem.variationdatadetail[i].variationid); 
            let vId=parseInt(pitem.variationdatadetail[i].variationid,10);
           
            console.log("vidid=",vId);
            this.formitem.variationsid.push(vId);
             
            }

          },
          editModalWindowPayment(paccount){
            console.log("Nishi====",paccount.paymentmethod);
          this.form.clear();
          this.editMode1 = true;
          this.form.reset();
          $('#addNewpayment').modal('show');
          this.form.fill(paccount)

          if(paccount.paymentmethod == 2){
            this.bkashform =true;
            this.cashform =false;
            this.bankform =false;
           }
          if(paccount.paymentmethod == 1){
            this.bkashform =false;
            this.cashform =true;
            this.bankform =false;
           }
          if(paccount.paymentmethod == 3){
            this.bkashform =false;
            this.cashform =false;
            this.bankform =true;
           }

          },
          editModalWindowOrder(porder){
            //console.log("Nishi====",paccount.paymentmethod);
            this.formorder.clear();
            this.editMode = true;
            this.formorder.reset();
            $('#editOrder').modal('show');
            this.formorder.fill(porder)
            this.loadRefSo(this.formorder.customerid);
          },

       
         loadSalesorder() { 
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get("api/salesorder/"+this.soid, {headers})
          .then( response =>{
             
              this.porders = response.data.order;
              this.pitems = response.data.items;
              this.paccounts = response.data.accounts;
               this.salescomments = response.data.comments;
              this.orderdelivered=this.porders[0].orderdelivered;

              console.log("ddd=",this.porders);
              if(this.orderdelivered === 1)
              {
              
                this.deliverybutton=true;
                this.allDisabled=true;
              }
              if(this.orderdelivered == 2)
              {
              
                this.returnbutton=true;
                this.allDisabled=true;
              }
              if(this.orderdelivered == 3)
              {
                this.cancelbutton=true;
                this.allDisabled=true;
              }
               if(this.orderdelivered == 0)
              {
                this.deliverybutton=false;
                 this.returnbutton=false;
                 this.allDisabled=false;
                  this.cancelbutton=false;
              }
            
              console.log("orders =>", this.porders[0].orderdelivered);
          });
        },
       loadBank() {
            axios.get("/getbank")
            .then( data =>{
            this.banksdata = data.data;
          });
             
        },
        loadItem() {
            let headers = {
          "Sessionkey": this.userData.remember_token,
        }
          axios.get('/getsalesitem',{headers}).then( response => (this.items = response.data));
          //console.log("vendor=", this.vendors);
        },
        loadProductcolor() {
           let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getproductcolor",{headers})
          .then( data =>{
          this.pcolors = data.data;
          //this.productcode=1000;
          });
             
        },
        loadProductsize() {
           let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getproductsize",{headers})
            .then( data =>{
            this.psizes = data.data;
          //this.productcode=1000;
          });
             
        },
         loadExcategory() {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getexcat",{headers})
            .then( data =>{
            this.categories = data.data;
          //this.productcode=1000;
          });
             
        },
         getCustomerId()
         {
           console.log('vendorid=' , this.customerid);
            this.loadRefSo(this.customerid);

         },
          loadCustomer() {
             let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get('/getcustomer',{headers}).then( data => (this.customers = data.data));
            console.log("customer=", this.customers);
          },
          loadRefSo(cid) {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getrefso/"+cid,{headers})
            .then( data =>{
            this.refsos = data.data;
          console.log('ref=', this.refsos);
          });
             
        },
      loadDiscount() {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getdiscount",{headers})
            .then( data =>{
            this.discountrates = data.data;
          //this.productcode=1000;
          });  
        },
        loadTax() {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/gettax",{headers})
            .then( data =>{
            this.taxrates = data.data;
          //this.productcode=1000;
          });  
        },
         loadDeliveryAgent() {
             let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get('/getagent',{headers}).then( response => (this.agents = response.data));
            //console.log("vendor=", this.vendors);
          },
          getAgentInfo(event){
              let subtotalshipping=0.0;
              let target = parseInt(event.target.value);
              let deliveryarea=this.formorder.deliveryarea;
              let headers = {
              "Sessionkey": this.userData.remember_token,
             // "DelArea": deliveryarea,
              }
            
               axios.get("/getdeliveryprice/"+target,{headers})
            .then( response =>{
              console.log("charge=",response.data);
              if(deliveryarea === 1)
              {
                  console.log("agent=>", target,deliveryarea);
               this.formorder.shipping =(parseFloat(response.data[0].insiderate).toFixed(2));
               this.formorder.codcharge =(parseFloat(response.data[0].insidecodcharge).toFixed(2));
           
              }
               if(deliveryarea === 2)
              {
                this.formorder.shipping =(parseFloat(response.data[0].outsiderate).toFixed(2));
                this.formorder.codcharge =(parseFloat(response.data[0].outsidecodcharge).toFixed(2));
            
              }
            });

            
          },
          getAgentInfobyarea(event){
              let subtotalshipping=0.0;
              let deliveryarea = parseInt(event.target.value);
              let target=this.formorder.agentid;
              console.log("agent=>", target,deliveryarea);
              let headers = {
              "Sessionkey": this.userData.remember_token,
             // "DelArea": deliveryarea,
              }
              console.log("agent=>", target,deliveryarea);
               axios.get("/getdeliveryprice/"+target,{headers})
            .then( response =>{
              console.log("charge=",response.data);
              if(deliveryarea === 1)
              {
               this.formorder.shipping =(parseFloat(response.data[0].insiderate).toFixed(2));
               this.formorder.codcharge =(parseFloat(response.data[0].insidecodcharge).toFixed(2));
                
              }
               if(deliveryarea === 2)
              {
               this.formorder.shipping =(parseFloat(response.data[0].outsiderate).toFixed(2));
               this.formorder.codcharge =(parseFloat(response.data[0].outsidecodcharge).toFixed(2));
               
              }
            });
          },
        createSalesaccount(){
            this.$Progress.start()
             console.log("enter:");
            this.form.post('api/salesaccount')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {  
                        Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Payment Not Done!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Payment successfully'
                        })
                        this.$Progress.finish()
                        $('#addNewpayment').modal('hide');
                }})
                .catch(() => {
                   console.log("Error......")
                })
          },
           updateSalesaccount(){
              console.log("This.form =>", this.form);
              let form = this.form;
              let fpd = new Date(this.form.paymentdate);
              let m = parseInt(fpd.getMonth())+1;
              form.paymentdate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();
              form.put('api/salesaccount/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Payment updated successfully'
                    })

                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt');

                    $('#addNewpayment').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
            createItem(){
            this.$Progress.start()
            this.formitem.post('api/salesitem')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === 'Exist')
                    {  
                        Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Item Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Item Insert successfully'
                        })
                        this.$Progress.finish()
                        $('#addNewitem').modal('hide');
                }})
                .catch(() => {
                   console.log("Error......")
                })
          },
           updateItem(){
            this.$Progress.start()
            this.formitem.put('api/salesitem/'+this.formitem.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Item updated successfully'
                    })

                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt');

                    $('#addNewitem').modal('hide');
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
              formorder.put('api/salesorder/'+this.formorder.id)
               .then(()=>{
                   Toast.fire({
                      icon: 'success',
                      title: 'Order updated successfully'
                    })

                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt');

                    $('#editOrder').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
         createComment(){
            this.$Progress.start()
             console.log("enter:");
            this.formcomment.post('api/salesordercomment')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {  
                        Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Comment Not Done!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Comment Done'
                        })
                        this.$Progress.finish()
                        $('#addNewComment').modal('hide');
                }})
                .catch(() => {
                   console.log("Error......")
                })
          },
           updateComment(){
            this.$Progress.start()
            this.formcomment.put('api/salesordercomment/'+this.formcomment.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Comment updated successfully'
                    })

                    Fire.$emit('AfterCreatedSalesorderdetailLoadIt');

                    $('#addNewComment').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
         deleteComment(id) {
              let headers = {
                "StatusKey": 'inactive',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "Want to Delete It !",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formcomment.delete('api/salesordercomment/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Confirmed!',
                              'Delete successfully',
                              'success'
                            )
                          this.loadSalesorder();

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

          cancelItem(id) {
             let headers = {
                "StatusKey": 'cancel',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formitem.delete('api/salesitem/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Cancel!',
                              'Cancel  successfully',
                              'success'
                            )
                          this.loadSalesorder();

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
          returnItem(id) {
             let headers = {
                "StatusKey": 'return',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to return this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, return it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formitem.delete('api/salesitem/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Return!',
                              'Return successfully',
                              'success'
                            )
                          this.loadSalesorder();

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

           deliveryOrder(id) {
              let headers = {
                "StatusKey": 'delivery',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "Confirm Delivery !",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delivery it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formorder.delete('api/salesorder/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Confirmed!',
                              'Delivery successfully',
                              'success'
                            )
                          this.loadSalesorder();

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
          returnOrder(id) {
              let headers = {
                "StatusKey": 'return',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "Want to return It !",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, return it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formorder.delete('api/salesorder/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Confirmed!',
                              'Return successfully',
                              'success'
                            )
                          this.loadSalesorder();

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
          cancelOrder(id) {
              let headers = {
                "StatusKey": 'cancel',
                }
            Swal.fire({
              title: 'Are you sure?',
              text: "Want to cancel It !",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.formorder.delete('api/salesorder/'+id,{headers})
                    .then((response)=> {
                            Swal.fire(
                              'Confirmed!',
                              'Cancel successfully',
                              'success'
                            )
                          this.loadSalesorder();

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
          deletePayment(id) {
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
                this.form.delete('api/salesaccount/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Payment deleted successfully',
                              'success'
                            )
                          this.loadSalesorder();

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
                this.loadSwitchCompany();
               this.loadSalesorder();
                this.loadBank();
                this.loadItem();
                this.loadProductcolor();
                this.loadProductsize();
                this.loadExcategory();
                this.loadCustomer();
                this.loadDiscount();
                this.loadTax();
                this.loadDeliveryAgent();
               
                Fire.$on('AfterCreatedSalesorderdetailLoadIt',()=>{ //custom events fire on
                this.loadSalesorder();
                
                });	
        },
         
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
.tabletitle{
    width: 20%;
}

</style>