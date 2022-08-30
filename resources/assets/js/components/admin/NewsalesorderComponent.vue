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
                <h3 class="card-title">New Sales Order </h3>
              </div>
              <div class="card-body table-responsive p-0" v-if="this.currentdate1 <= this.subsdate && this.userData.subscriptionstatus === 1">
              <form @submit="createSalesorder" id="createSales">
                <table class="table table-hover">
                  <tbody >
                    <tr>
                        <td class="tabletitle">Customer : <button class="btn newbtn" data-toggle="modal" data-target="#addNew" title="New Customer" @click="openModalWindow"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></td>
                        <td class="row">
                        <div class="dropdownsearch">
                              <input type="hidden"   name="customerid" v-model="customerid">
                        <input v-if="Object.keys(selectedItem).length === 0" ref="dropdowninput" v-model="inputValue" class="dropdown-input" type="text" placeholder="Find Customer" required />
                        <div v-else @click="resetSearchItem" class="dropdown-selected">
                        {{ selectedItem.customername }}
                        </div>
                        <div v-show="inputValue && apiLoaded" class="dropdown-listsearch">
                        <div @click="selectSearchItem(item)" v-show="itemSearchVisible(item)" v-for="item in itemList" :key="item.id" class="dropdown-itemserach">
                        {{ item.customername }}
                        </div>
                        </div>
                        </div>
                        <span class="phnaddress" v-if="this.customerphone !==''">{{this.customerphone}},  {{this.customeraddress}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Date :</td>
                        <td> <datepicker v-model="currentdate" name="currentdate" id="currentdate" required></datepicker></td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Delivery Date :</td>
                        <td> <datepicker v-model="deliverydate" name="deliverydate" id="deliverydate"></datepicker></td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Due Delivery Date :</td>
                        <td> <datepicker v-model="duedeliverydate" name="duedeliverydate" id="duedeliverydate"></datepicker></td>
                    </tr>
                     <tr>
                        <td class="tabletitle">Reference SO :</td>
                        <td>
                          <select name="refso" v-model="refso" id="refso" class="form-control" >
                          <option value="0" >Select Reference SO</option>
                           <option v-for="ref in refpos" v-bind:value="ref.id" :key="ref.id">
                          {{ ref.ordernumber }}
                          </option>
                        </select></td>
                    </tr>
                     <tr>
                        <td class="tabletitle">Shipping Agent:
                          <button class="btn newbtn" data-toggle="modal" data-target="#addNew1" title="New Shipping Agent" @click="openModalWindow1"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                         </td>
                        <td>
                          <select name="agentid" v-model="agentid" id="agentid" class="form-control" v-on:change="getAgentInfo"  required>
                          <option value="" >Select Delivery Agent</option>
                           <option v-for="agent in agents" v-bind:value="agent.id" :key="agent.id">
                          {{ agent.agentname }}
                          </option>
                        </select>
                    </td>
                    </tr>
                     <tr>
                        <td class="tabletitle">Delivery Area:</td>
                        <td>
                          <select name="deliveryarea" v-model="deliveryarea" id="deliveryarea" class="form-control" v-on:change="getAgentInfobyarea" >
                          <option value="1" >Inside Dhaka</option>
                          <option value="2" >Outside Dhaka</option>
                        </select></td>
                    </tr>
                     <tr>
                        <td class="tabletitle">Notes :</td>
                        <td>
                           <table>
                             <tbody>
                               <tr>
                                 <td><textarea v-model="notes" name="notes" class="form-control" ></textarea></td>
                                  <td class="tabletitle">Comments: </td>
                                   <td><textarea v-model="comments" name="comments" class="form-control" ></textarea></td>
                               </tr>
                             </tbody>
                          </table>
                         <input type="hidden" v-model="codcharge" name="codcharge" />
                         <input type="hidden" v-model="systemid" name="systemid" />
                          <input type="hidden" v-model="entryid" name="entryid" />
                           <input type="hidden" v-model="entryid" name="entryid" />
                          <input type="hidden"   name="companyid" v-model="companyid">
                          <input type="hidden"   name="branchid" v-model="branchid">
                        </td>
                    </tr>
                    </tbody>
                </table>
                          <table class="table table-hover itemtable">
                            <tbody >
                            <tr>
                              <th class="multirow">P.Item</th>
                              <th class="multirow">Detail</th>
                              <th class="multirow" >Variation</th>
                              <th class="multirow">Qty</th>
                              <th class="multirow">Price</th>
                              <th class="multirow">Ex.Category</th>
                              <th class="multirow">Discount</th>
                              <th class="multirow">Tax</th>
                              <th class="multirow">Total</th>
                              <th class="multirow"></th>
                            </tr>
                            <tr v-for="(input,k) in inputs" :key="k">
                              <td>
                            <select name="pitem"  v-model="input.pitem" :id="k" class="form-control" v-on:change="checksalesItem($event, k)"   required>
                            <option value="0">Select Purchase Item</option>
                            <option v-for="item in items" v-bind:value="item.id" :key="item.id">
                            {{ item.groupname.gcode }}::{{ item.productname }}
                            </option>
                            </select>
                                <p v-if="errors.length">
                                    <ul class="list-group">
                                    <li v-for="error in errors" class="list-group-item list-group-item-danger" :key="error">{{ error }}</li>
                                    </ul>
                                    </p>
                              </td>
                              <td><input type="text" v-model="input.detail" name="detail" :id="k" class="form-control" /></td>
                              <td  class="dropdown-check-list">
                               <select  name="variationsid" v-model="input.variationsid"  class="form-control"  multiple  required>
                                  <option v-for="varl in variationlist[k]" v-bind:value="varl.variationid" :key="varl.id">
                                  {{ varl.labeldata.vlabel }}::{{ varl.valdata.variationname }}
                                  </option>
                                  </select>

                               <!--input type="checkbox" id="k" name="input.showhide" value="1" true-value="1" false-value="0"  v-on:change="varlistpopulate($event, k)" :checked=varlistchecked ><span class="itemlistshowhide">Show/Hide</span>
                              <ul class="items" v-show="varlist">
                              <li v-for="varl in variationlist[k]" :key="varl.id"><input v-model="input.variationsid" :value="varl.id" type="checkbox" /> <span class="variationtitle">{{ varl.labeldata.vlabel }}:: </span>{{ varl.valdata.variationname }} </li>
                              </ul-->
                               </td>
                              <td><input type="text" :id="k" class="form-control" v-model="input.qty" name="qty"  @keypress="onlyNumber"   @change="amountcalculation" required/></td>
                              <td>
                                <input type="text" :id="`price-${k}`" v-model="input.price" name="price" @keypress="onlyNumber"  @change="amountcalculation" class="priceiteminput" required />
                                </td>
                              <td>
                                <select name="excat" v-model="input.excat" :id="k" class="form-control" >
                                <option value="0">Select Ex Category</option>
                                  <option v-for="excata in categories" v-bind:value="excata.id" :key="excata.id">
                                  {{ excata.ecategoryname }}
                                  </option>
                                </select></td>
                              <td>
                                  <select name="discountid" v-model="input.discountid" :id="k" class="form-control" @change="amountcalculation"  required>
                                <option value="0,0">Select Discount</option>"
                                  <option v-for="discnt in discountrates" v-bind:value="`${discnt.id},${discnt.discountrate}`" v-bind:key="discnt.id">
                                  {{ discnt.discountrate }}%
                                  </option>
                                  </select>
                               </td>
                              <td> <select name="taxid" v-model="input.taxid" :id="k" class="form-control"  @change="amountcalculation" required>
                                <option value="0,0">Select Tax</option>
                                  <option v-for="txrate in taxrates" v-bind:value="`${txrate.id},${txrate.taxrate}`" :key="txrate.id">
                                  {{ txrate.taxrate }}%
                                  </option>
                                  </select>
                                  </td>
                              <td> <input type="text" :id="k" v-model="input.total" name="total" class="priceiteminput" readonly /></td>
                              <td>
                                <input type="hidden" v-model="input.delivered" :id="k" name="delivered" />
                                <input type="hidden" v-model="input.systemid" name="systemid" />
                                <input type="hidden" v-model="input.entryid" name="entryid" />
                                <input type="hidden" v-model="input.discountfigure" name="discountfigure" />
                                <input type="hidden" v-model="input.taxfigure" name="taxfigure" />
                                <span class="addmore">
                            <i class="fa fa-minus-circle" aria-hidden="true"
                            @click="remove(k)"
                            v-show="k || ( !k && inputs.length > 1)"
                            ></i>
                          <button
                          type="button"
                          class="btnadd"
                          v-on:click="add(k)"
                          v-show="k == inputs.length-1"
                          :disabled='isDisabled'>
                          <i class="show fa fa-plus-circle" aria-hidden="true"></i>
                          </button>
                            </span></td>
                            </tr>
                            </tbody>
                          </table>
                     <table class="table table-hover">
                        <tbody>
                     <tr>
                        <td  class="paymentcol">
                          <table  class="paymenttable payment">
                            <tbody>
                              <tr>
                                <td>Payment Method</td>
                               </tr>
                                <tr>
                                <td>
                               <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="1" v-model="paymentmethod" v-on:change="showCash" >
                                  Cash
                                  </label>
                                  <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="2" v-model="paymentmethod" v-on:change="showBkash">
                                  Bkash
                                  </label>
                                   <label for="windows">
                                  <input type="radio" id="paymentmethod" name="paymentmethod" value="3" v-model="paymentmethod" v-on:change="showBank">
                                  Bank
                                  </label>
                                  </td>
                               </tr>
                                <tr>
                                <td>
                                  <table  class="paymenttable bkash" v-show="bkash">
                                  <tbody>
                                    <tr>
                                      <td>Bkash Number</td>
                                      <td>
                                        <VuePhoneNumberInput
                                        default-country-code="BD"
                                        name="bkashnumber"
                                        v-model="bkashnumber"
                                        :maxlength="max"
                                         />
                                        <input type="hidden" v-model="dialcode" name="dialcode" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>Pin Number</td>
                                      <td><input type="text" v-model="bkashpin" name="bkashpin" class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="cash">
                                  <tbody>
                                    <tr>
                                      <td>Cash Payee</td>
                                      <td><input type="text" v-model="cashpay" name="cashpay"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                   <table  class="paymenttable bkash" v-show="bank">
                                  <tbody>
                                    <tr>
                                      <td>Bank</td>
                                      <td>
                                        <select name="bankid" v-model="bankid"  class="form-control" >
                                        <option value="0">Select Bank</option>
                                        <option v-for="bank in banksdata" v-bind:value="bank.id" :key="bank.id">
                                        {{ bank.bankname }}
                                        </option>
                                        </select>
                                      </td>
                                    </tr>
                                     <tr>
                                      <td>Branch</td>
                                      <td><input type="text" v-model="branchname" name="branchname"  class="form-control" /></td>
                                    </tr>
                                     <tr>
                                      <td>Account Name</td>
                                      <td><input type="text" v-model="accountholder" name="accountholder"  class="form-control" /></td>
                                    </tr>
                                     <tr>
                                      <td>Account Number</td>
                                      <td><input type="text" v-model="branchname" name="branchname"  class="form-control" /></td>
                                    </tr>
                                  </tbody>
                                  </table>
                                </td>
                               </tr>

                            </tbody>
                          </table>
                        </td>
                        <td  class="paymentcol1">
                          <table class="paymenttable1">
                            <tbody>
                               <tr>
                                <td class="paymentheader2">Subtotal:</td>
                                <td class="paymentheader3">
                                <input type="text" v-model="subtotal" name="subtotal" class="form-control" readonly />
                                </td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Discount:</td>
                                <td class="paymentheader3"><input type="text" v-model="totaldiscount" name="totaldiscount"  class="form-control" readonly /></td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Tax:</td>
                                <td class="paymentheader3"><input type="text" v-model="totaltax" name="totaltax"  class="form-control" readonly /></td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Shipping:</td>
                                <td class="paymentheader3"> <input type="text" v-model="shipping" name="shipping"  class="form-control" @keypress="onlyNumber" @change="amountcalculation" /></td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Total Payable:</td>
                                <td class="paymentheader3"> <input type="text" v-model="totalamount" name="totalamount"  class="form-control" readonly /></td>
                               </tr>
                                <tr>
                                <td class="paymentheader2">Advance:</td>
                                <td class="paymentheader3">
                                 <input type="text" v-model="paidamount" name="paidamount"  class="form-control" @keypress="onlyNumber"   @change="amountcalculation" />
                                  </td>
                               </tr>
                                <tr>
                                <td></td>
                                <td></td>
                               </tr>
                               <tr>
                                <td class="paymentheader2">Due Total:</td>
                                <td class="paymentheader3">
                                 <input type="text" v-model="dueamount" name="dueamount"  class="form-control" readonly /></td>
                               </tr>
                            </tbody>
                          </table>
                         </td>
                    </tr>
                     <tr>
                        <td></td>
                        <td><div class="modal-footer"><button :disabled='isDisabled'  type="submit" class="btn btn-primary">Save</button></div></td>
                    </tr>
                 </tbody>
                </table>
              </form>
               <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title" id="addNewLabel">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form @submit.prevent="createPartner()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="phone">Name</label>
                                <input v-model="form.customername" type="text" name="customername"
                                    placeholder="Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('customername') }" required>
                                <has-error :form="form" field="customername"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <VuePhoneNumberInput
                                default-country-code="BD"
                                name="telephone"
                                required
                                v-model="form.telephone"
                                :maxlength="max"
                                />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="form.email" type="email" name="email"
                                    placeholder="Email Address"
                                    class="form-control" >
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
                            <input type="hidden"   name="partnertype" v-model="form.partnertype">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button  type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


      <div class="modal fade" id="addNew1" tabindex="-2" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addNewLabel">Add New Delivery Agent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="createAgent()">
                  <div class="modal-body">
                    <div class="form-group">
                    <label for="">Agent Name</label>
                    <input v-model="formagent.agentname" type="text" name="agentname"
                    placeholder="Agent Name"
                    class="form-control"  required>
                    </div>
                    <div class="form-group">
                    <label for="">Rate Inside Dhaka </label><br />
                     <div class="formdiscount">
                    <input v-model="formagent.insiderate" type="text" name="insiderate"
                    placeholder="Rate Inside Dhaka"
                    class="form-control"  @keypress="onlyNumber" required><span class="percentage">TK</span>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Inside COD Charge</label><br />
                     <div class="formdiscount">
                    <input v-model="formagent.insidecodcharge" type="text" name="insidecodcharge"
                    placeholder="Inside COD Charge"
                    class="form-control"  @keypress="onlyNumber" required><span class="percentage">%</span>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Rate Outside Dhaka </label><br />
                     <div class="formdiscount">
                    <input v-model="formagent.outsiderate" type="text" name="outsiderate"
                    placeholder="Rate Outside Dhaka"
                    class="form-control"  @keypress="onlyNumber" required><span class="percentage">TK</span>
                     </div>
                    </div>
                     <div class="form-group">
                    <label for="">Outside COD Charge</label><br />
                     <div class="formdiscount">
                    <input v-model="formagent.outsidecodcharge" type="text" name="outsidecodcharge"
                    placeholder="Outside COD Charge"
                    class="form-control"  @keypress="onlyNumber" required><span class="percentage">%</span>
                     </div>
                    </div>
                 </div>
                  <div class="modal-footer">
                     <input type="hidden"   name="systemid" v-model="formagent.systemid">
                     <input type="hidden"   name="entryid" v-model="formagent.entryid">
                      <input type="hidden"   name="isactive" v-model="formagent.isactive">
                      <input type="hidden"   name="companyid" v-model="formagent.companyid">
                      <input type="hidden"   name="branchid" v-model="formagent.branchid">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button  type="submit" class="btn btn-primary">Create</button>
                  </div>
                </form>
        </div>
      </div>
     </div>
    </div>
     <div class="card-body table-responsive p-0 " v-else>
       <p class="unaccess">Sorry your membership expired !!</p>
       <p >Please contact our support <span class="supportcontact">09638010100</span>   to unlock your access!</p>
        </div>

  </div>
</div>
</div>
</div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import moment from 'moment';
    export default {
      //  props: ['id'],
        props: ['userData'],
        components: {  Datepicker,VuePhoneNumberInput},
        data() {
            return {
                customers: {},
                discountrates: {},
                agents:{},
                taxrates: {},
                variationlist: [],
                max: 12,
                errors: {},
                itemarray: [],
                items: {},
                pcolors: {},
                psizes: {},
                categories: {},
                refpos: {},
                banksdata:{},
                isDisabled: false,
                isDisabledadd: false,
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                cash: false,
                bkash: false,
                bank: false,
                format: "d MMMM yyyy",
                comments:'',
                systemid: this.userData.systemid,
                entryid: this.userData.id,
                companyid: this.userData.companyid,
                branchid: this.userData.branchid,
                customerid: '',
                deliverydate: '',
                currentdate:  new Date(),
                currentdate1:  moment().unix(),
                subsdate: moment(this.userData.subscriptiondate).unix(),
                duedeliverydate: '',
                refso: 0,
                agentid:'',
                deliveryarea:1,
                codcharge:0,
                notes: '',
                dueamount: 0,
                paymentmethod: '',
                paidamount: 0,
                dialcode: '+88',
                bkashnumber: '',
                bkashpin: '',
                cashpay: '',
                bankid: '',
                accountholder: '',
                accountnumber: '',
                branchname: '',
                shipping: 0,
                subtotal: 0,
                totaldiscount: 0,
                totaltax: 0,
                totalamount: 0,
                inputs: [
                  {
                    pitem: 0,
                    detail: '',
                    variationsid: [],
                    qty: 1,
                    price: 0,
                    excat: 0,
                    discountid: '0,0',
                    taxid: '0,0',
                    discountfigure: 0.0,
                    taxfigure: 0.0,
                    total: 0,
                    delivered: 0,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                  }
                ],
                inputValue: '',
                customerdial: '',
                customerphone: '',
                customeraddress: '',
                itemList: [],
                apiLoaded: false,
                selectedItem: {},
                selected: [],
                allSelected: false,
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
                    partnertype: 1,
                    country:'Bangladesh',
                    description: '',

                }),
                formagent: new Form({
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
           openModalWindow(){
          // this.editMode = false
         //  this.shouldDisable =false
          //  this.phoneDisable=false
           this.form.reset();
           $('#addNew').modal('show');
        },
         openModalWindow1(){
          // this.editMode = false
         //  this.shouldDisable =false
          //  this.phoneDisable=false
           this.form.reset();
           $('#addNew1').modal('show');
        },
        itemSearchVisible (item) {
          let currentName = item.customername.toLowerCase()
          let currentInput = this.inputValue//.toLowerCase()
          return currentName.includes(currentInput)
          },

          getSearchList () {
            let headers = {
            "Sessionkey": this.userData.remember_user,
            }
          axios.get("/getcustomer",{headers}).then( response => {
          console.log("customerlist=",response.data)
          this.itemList = response.data
          this.apiLoaded = true
          })
          },

          selectSearchItem (theItem) {
          console.log("category=",theItem)
          this.selectedItem = theItem
          this.inputValue = ''
          this.customerid=theItem.id
          this.$emit('on-item-selected', theItem)
          this.customerdial=theItem.dialcode;
          this.customerphone=theItem.telephone;
          this.customeraddress=theItem.address;
           this.loadRefSo(this.customerid);

          },
          resetSearchItem () {
          this.selectedItem = {}
          this.$nextTick( () => this.$refs.dropdowninput.focus() )
           this.customerdial='';
          this.customerphone='';
          this.customeraddress='';
          this.$emit('on-item-reset')
          },
          add (index) {
              this.inputs.push({
                    pitem: 0,
                    detail: '',
                    variationsid: [],
                    qty: 1,
                    price: 0,
                    excat: 0,
                    discountid: '0,0',
                    taxid: '0,0',
                    discountfigure: 0.0,
                    taxfigure: 0.0,
                    total: 0,
                    delivered: 0,
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
              })
              this.inputs.map((input,key) => {
                  if(input.pitem != 0 ){
                  if(this.itemarray.indexOf(input.pitem) == -1){
                    this.itemarray.push(input.pitem);
                  }
                }
              });
          },
          remove (index) {
              let totalPrice = 0.0;
              let totalDiscount = 0.0;
              let totalTax = 0.0;
              let pricemultiply = 0.0;
              let discountidval=0;
              let taxidval=0;
              let discountval=0;
              let taxval=0;
              let totalwithtaxdiscount=0.0;
               let totalpay=0.0;
              this.subtotal = (parseFloat(this.subtotal-this.inputs[index].total).toFixed(2));
             totalpay=(parseFloat(this.subtotal)+parseFloat(this.shipping));
              this.totalamount =(parseFloat(totalpay).toFixed(2));
              this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
              this.totaldiscount = (parseFloat(this.totaldiscount-this.inputs[index].discountfigure).toFixed(2));
              this.totaltax = (parseFloat(this.totaltax-this.inputs[index].taxfigure).toFixed(2));
              this.inputs.splice(index, 1)
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
            let totalpay=0.0;
            let shipp='';
           // let taxArr='';

                 this.inputs.map((input,key) => {
                     discountidval = input.discountid.split(',');
                      taxidval = input.taxid.split(',');
                      pricemultiply=parseFloat(input.price)*input.qty;
                      discountval=parseFloat((pricemultiply*discountidval[1]))/100;
                      totalwithtaxdiscount=parseFloat(pricemultiply)-parseFloat(discountval);
                      taxval=parseFloat(totalwithtaxdiscount*taxidval[1])/100;
                      console.log("totalwithtaxdiscount=", totalwithtaxdiscount);
                      input.total=(parseFloat(totalwithtaxdiscount+taxval).toFixed(2));
                      input.taxfigure=(parseFloat(taxval).toFixed(2));
                      input.discountfigure=(parseFloat(discountval).toFixed(2));
                       console.log("discountval=>", discountval);
                       console.log("taxval =>", taxval);
                       console.log("total=", input.total);
                       totalPrice +=parseFloat(input.total);
                       totalDiscount +=discountval;
                       totalTax +=taxval;
                       console.log("totalPrice=", totalPrice);
                 if(input.qty<1)
                    {
                        this.isDisabled= true;
                    }
                    else
                    {
                      this.isDisabled= false;
                    }

            });
             shipp=(parseFloat(this.shipping).toFixed(2))
             console.log("shipping1=", shipp);
            this.subtotal = (parseFloat(totalPrice).toFixed(2));
            this.totaldiscount = (parseFloat(totalDiscount).toFixed(2));
            this.totaltax = (parseFloat(totalTax).toFixed(2));
            totalpay=parseFloat(this.subtotal)+parseFloat(shipp);
            console.log("totalpay=", totalpay);
            this.totalamount =(parseFloat(totalpay).toFixed(2));
            this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
          },
          checksalesItem(event, addmoreKey){
            let pricemultiply=0.0;
            let totalPrice=0.0;
            let subtotalshipping=0.0;
            let target = parseInt(event.target.value);
              console.log("event=>", target);
          /*  if(this.itemarray.includes(target)){
              Toast.fire({
                icon: 'error',
                title: 'This Item Already Exists!!'
              });
              this.isDisabled = true;
            }
            else{
               this.isDisabled = false;
            }*/

            let headers = {
            "Sessionkey": this.userData.remember_user,
            }

            if(target !=0){
            axios.get("/getsalesprice/"+target,{headers})
            .then( response =>{
              //console.log("ddddd",response.data[0]);
              //this.notes=
             this.inputs[addmoreKey].price = (parseFloat(response.data[0].sellprice).toFixed(2));
             pricemultiply=parseFloat(this.inputs[addmoreKey].price)*this.inputs[addmoreKey].qty;
             this.inputs[addmoreKey].total=(parseFloat(pricemultiply).toFixed(2));
             for(let i=0; i<=addmoreKey; i++)
             {
               console.log("i=", this.inputs[i].total);
               totalPrice +=(parseFloat(this.inputs[i].total));
               console.log("total=", totalPrice);
             }
             console.log("subtotal=", totalPrice);
               this.subtotal = (parseFloat(totalPrice).toFixed(2));
                console.log("shipping=", this.shipping);
                subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
                 console.log("subtotalshipping=", subtotalshipping);
               this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
               this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));

            });

              axios.get("/getvariationlist/"+target,{headers})
                .then( response =>{
                  this.variationlist[addmoreKey]=response.data;
                  // this.varlist=true;
                 //this.varlistchecked=true;
                 console.log("swaew=",this.variationlist[addmoreKey]);
                });
            }
            else{
               this.inputs[addmoreKey].price=0;
            }
          },
          getAgentInfo(event){
              let subtotalshipping=0.0;
              let target = parseInt(event.target.value);
              let deliveryarea=this.deliveryarea;
              let headers = {
              "Sessionkey": this.userData.remember_user,
             // "DelArea": deliveryarea,
              }
              console.log("agent=>", target,deliveryarea);
               axios.get("/getdeliveryprice/"+target,{headers})
            .then( response =>{
              console.log("charge=",response.data);
              if(deliveryarea === 1)
              {
               this.shipping =(parseFloat(response.data[0].insiderate).toFixed(2));
               this.codcharge =(parseFloat(response.data[0].insidecodcharge).toFixed(2));
              subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
              console.log("subtotalshipping=", subtotalshipping);
              this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
              this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
              }
               if(deliveryarea === 2)
              {
               this.shipping =(parseFloat(response.data[0].outsiderate).toFixed(2));
               this.codcharge =(parseFloat(response.data[0].outsidecodcharge).toFixed(2));
              subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
              console.log("subtotalshipping=", subtotalshipping);
              this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
              this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
              }
            });
          },
          getAgentInfobyarea(event){
              let subtotalshipping=0.0;
              let deliveryarea = parseInt(event.target.value);
              let target=this.agentid;
              console.log("agent=>", target,deliveryarea);
              let headers = {
              "Sessionkey": this.userData.remember_user,
             // "DelArea": deliveryarea,
              }
              console.log("agent=>", target,deliveryarea);
               axios.get("/getdeliveryprice/"+target,{headers})
            .then( response =>{
              console.log("charge=",response.data);
              if(deliveryarea === 1)
              {
               this.shipping =(parseFloat(response.data[0].insiderate).toFixed(2));
               this.codcharge =(parseFloat(response.data[0].insidecodcharge).toFixed(2));
                subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
              console.log("subtotalshipping=", subtotalshipping);
              this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
              this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
              }
               if(deliveryarea === 2)
              {
               this.shipping =(parseFloat(response.data[0].outsiderate).toFixed(2));
               this.codcharge =(parseFloat(response.data[0].outsidecodcharge).toFixed(2));
                subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
              console.log("subtotalshipping=", subtotalshipping);
              this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
              this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
              }
            });
          },
          showBkash: function() {
            this.bkash = true;
            this.cash = false;
            this.bank = false;
          },
          showCash: function() {
            this.bkash =false;
            this.cash =true;
            this.bank =false;
          },
           showBank: function() {
            this.bkash =false;
            this.cash =false;
            this.bank =true;
          },
          loadDeliveryAgent() {
             let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get('/getagent',{headers}).then( response => (this.agents = response.data));
            //console.log("vendor=", this.vendors);
          },

           loadItem() {
             let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get('/getsalesitem',{headers}).then( data => (this.items = data.data));
            //console.log("vendor=", this.vendors);
          },
        loadExcategory() {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/getexcat",{headers})
            .then( data =>{
            this.categories = data.data;
          //this.productcode=1000;
          });
        },
        loadDiscount() {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/getdiscount",{headers})
            .then( data =>{
            this.discountrates = data.data;
          //this.productcode=1000;
          });
        },
        loadTax() {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/gettax",{headers})
            .then( data =>{
            this.taxrates = data.data;
          //this.productcode=1000;
          });
        },
        loadBank() {
            axios.get("/getbank")
            .then( data =>{
            this.banksdata = data.data;
          //this.productcode=1000;
          });
        },
        loadRefSo(cid) {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            axios.get("/getrefso/"+cid,{headers})
            .then( response =>{
            this.refpos = response.data;
          console.log('ref=', this.refpos);
          });
        },
         createPartner(){

            this.$Progress.start()

            this.form.post('/customer')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedVendorLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Customer created successfully'
                        })
                         this.customerid=response.data.id;
                         this.inputValue=response.data.customername//.toLowerCase();
                          this.customerdial=response.data.dialcode;
                          this.customerphone=response.data.telephone;
                          this.customeraddress=response.data.address;
                        this.$Progress.finish()

                        $('#addNew').modal('hide');

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
           createAgent(){
            this.$Progress.start()
            this.formagent.post('/deliveryagent')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Agent Already Exists!!'
                        })
                    }
                    else{
                      let subtotalshipping = 0.0;
                    Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Delivery Agent created successfully'
                        })
                         this.agentid=response.data.id;
                         this.shipping =(parseFloat(response.data.insiderate).toFixed(2));
                        subtotalshipping=parseFloat(this.subtotal)+parseFloat(this.shipping);
                        console.log("subtotalshipping=", subtotalshipping);
                        this.totalamount =(parseFloat(subtotalshipping).toFixed(2));
                        this.dueamount= (parseFloat(this.totalamount-this.paidamount).toFixed(2));
                        this.$Progress.finish()
                        $('#addNew1').modal('hide');
                         //location.reload();
                    }
                })
                .catch(() => {
                   console.log("Error......")
                })
            //this.loadUsers();
          },
          createSalesorder: function (e) {
            let headers = {
            "Sessionkey": this.userData.remember_user,
          }
            if(this.currentdate1 <= this.subsdate && this.userData.subscriptionstatus === 1) 
            {
               console.log("form=",JSON.stringify(this.inputs));
          // var navigate = this.$router;
            var formContents = jQuery("#createSales").serialize();
            console.log("formdata=",formContents);
           axios.post('/createsalesorder', formContents,{headers})
            .then(function(response) {
               if(response.data.message === 'not')
                {   // alert("not");
                  Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
                  Toast.fire({
                  icon: 'error',
                  title: 'Sorry you have no authorization!! \n Please contact our support 09638010100 \n to unlock your access.'
                })
                }
                else{
              axios.post('/saveSalesOrderItem', {orderId: response.data, inputs: this.inputs})
              .then((response) =>{
                console.log('response =', response);
                 this.$router.push({ path: '/salesorder/'+response.data });
              });
            //alert(response.data.user);
            console.log('rsponse => ',response.data);
            //location.reload();
           // navigate.push({ path: '//purchaseorderdetail/:'+response.data });
            }}.bind(this), function() {
            console.log('failed');
            });
            e.preventDefault();
            }
            else{
                Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
                })
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
                Fire.$emit('AfterCreatedVendorLoadIt'); //custom events
                });
            },
        },
        created() { //Like Mounted this method
          this.loadDeliveryAgent();
          this.loadItem();
          this.loadExcategory();
          this.loadBank();
          this.loadDiscount();
          this.loadTax();
          this.getSearchList();
          this.loadSwitchCompany();
           Fire.$on('AfterCreatedVendorLoadIt',()=>{ //custom events fire on
                 this.getSearchList();
                  this.loadDeliveryAgent();
            });
            /* this.id = this.userData.id;
             this.name = this.userData.name;
             this.telephone = this.userData.telephone;
             this.email = this.userData.email;
             this.usertype = this.userData.usertype;
             this.address = this.userData.address;*/
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}


</style>