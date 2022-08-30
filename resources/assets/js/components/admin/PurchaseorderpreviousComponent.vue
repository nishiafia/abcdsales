<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                
                <h3 class="card-title">Purchase Order</h3>
                <div>
                  
                 <table class="table">
                   <tbody>
                     <tr>
                       <td>
                         <div>
                         Vendor:  
                         <select name="vendorid" v-model="vendorid" id="vendorid" class="form-control"  required>
                          <option value='0'>Select Vendor</option>
                          <option v-for="vendor in vendors" v-bind:value="vendor.vendorname" :key="vendor.id">
                          {{ vendor.vendorname }}
                          </option>
                        </select>
                        </div>
                        </td>
                        <td>Date Range <datepicker v-model="deliverydate" name="deliverydate" id="deliverydate"  required></datepicker>
                        <datepicker v-model="duedeliverydate" name="duedeliverydate" id="duedeliverydate"></datepicker>
                        </td>
                        <div class="modal-footer"><button :disabled='isDisabled'  type="button" class="btn btn-primary" @click="filter"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button></div>
                     </tr>
                   </tbody>
                 </table>
                 
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
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="porder in displayedPosts" :key="porder.id">
                    <td>{{ porder.ordernumber }}</td>
                    <td>{{ porder.vendordata.id }}{{ porder.vendordata.vendorname }} </td>
                    <td>{{ porder.totalamount | toCurrency}}</td>
                    <td>{{ porder.paidamount | toCurrency}}</td>
                    <td >{{porder.dueamount | toCurrency}}</td>
                    <td >{{porder.deliverydate | formatDate}}</td>
                    
                    <td>
                        <a href="/#/purchaseorder" title="Edit" data-id="porder.id" @click="editModalWindowOrder(porder)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                       
                        <router-link :to="`/purchaseorder/${porder.id}`"  title="Order Detail">
                             <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </router-link>
                       
                         

                    </td>
                  </tr>
                </tbody></table>

                 <nav aria-label="Page navigation example">
			<ul class="pagination">
				<li class="page-item">
					<button type="button" class="page-link" v-if="page != 1" @click="page--"> Previous </button>
				</li>
				<li class="page-item">
					<button type="button" class="page-link" v-for="pageNumber in pages.slice(page-1, page+5)" @click="page = pageNumber"> {{pageNumber}} </button>
				</li>
				<li class="page-item">
					<button type="button" @click="page++" v-if="page < pages.length" class="page-link"> Next </button>
				</li>
			</ul>
		</nav>	
               
                <!--pagination :data="porders" @pagination-change-page="loadOrder"></pagination -->
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
                porders: [''],
                page: 1,
                perPage: 10,
                pages: [],	
                refpos: {},
                vendors: {},
                formorder: new Form({
                id: '',
                ordernumber: '',
                systemid: this.userData.id,
                vendorid: 0,
                deliverydate: '',
                duedeliverydate: '',
                refpo: '',
                notes: '',
                shipping: 0,
                }),
               
              
                systemid: this.userData.id,
                vendorid: 0,
                deliverydate: new Date(),
                duedeliverydate: '',
               
                

               
            }
        },
        computed: {  
          displayedPosts () {
          if(this.vendorid === 0) {
          return this.paginate(this.porders);
          }
          else
          {
          //console.log("vendorid=" ,this.vendorid);
          return this.porders.filter((porder) => {
          console.log("porder=" ,porder);
          return porder.vendordata.vendorname.match(this.vendorid);
          return  (porder.deliverydate >= this.deliverydate) && (porder.deliverydate <= this.duedeliverydate);
        
        
          
          })
          }
          }
        },

        watch: {
        porders () {
        this.setPages();
        }
        },  
        methods: {
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
        loadOrder() { 
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
         /* if (typeof page === 'undefined') {
            page = 1;
            }*/
          //console.log("token =", token);
          axios.get('api/purchaseorder', {headers})
          .then( data =>{
              console.log("orders =>", data);
              this.porders = data.data
          }); 
        },

        setPages () {
        let numberOfPages = Math.ceil(this.porders.length / this.perPage);
        for (let index = 1; index <= numberOfPages; index++) {
        this.pages.push(index);
        }
        },
        paginate (porders) {
        let page = this.page;
        let perPage = this.perPage;
        let from = (page * perPage) - perPage;
        let to = (page * perPage);
        return  porders.slice(from, to);
        },

        
      getVendorId()
         {
           console.log('vendorid=' , this.vendorid);
            this.loadRefPo(this.vendorid);

         },
          loadVendor() {
             let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get('/getvendor',{headers}).then( data => (this.vendors = data.data));
            console.log("vendor=", this.vendors);
          },
          loadRefPo(vid) {
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getrefpo/"+vid,{headers})
            .then( data =>{
            this.refpos = data.data;
          console.log('ref=', this.refpos);
          });
             
        },
         filter() {
            console.log("sdsfdsfdsgv", this.vendorid);
             return this.porders.filter((porder) => {
               //console.log("data", porder);
              
               return porder.vendordata.vendorname.match(this.vendorid);
             console.log("data1", porder); 
             //return porder;
              
             
      })
     // console.log(filtered)
         },
         searchOrder(){
              this.$Progress.start()
              let formorder = this.formorder;
              let fpd = new Date(this.formorder.deliverydate);
              let m = parseInt(fpd.getMonth())+1;
              formorder.deliverydate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();

              let duefpd = new Date(this.formorder.duedeliverydate);
              let duem = parseInt(fpd.getMonth())+1;
              formorder.duedeliverydate = duefpd.getFullYear() + '-' + duem + '-' + duefpd.getDate();
              formorder.put('api/purchaseorder/'+this.formorder.id)
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
         updateOrder(){
              this.$Progress.start()
              let formorder = this.formorder;
              let fpd = new Date(this.formorder.deliverydate);
              let m = parseInt(fpd.getMonth())+1;
              formorder.deliverydate = fpd.getFullYear() + '-' + m + '-' + fpd.getDate();

              let duefpd = new Date(this.formorder.duedeliverydate);
              let duem = parseInt(fpd.getMonth())+1;
              formorder.duedeliverydate = duefpd.getFullYear() + '-' + duem + '-' + duefpd.getDate();
              formorder.put('api/purchaseorder/'+this.formorder.id)
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
                this.form.delete('api/product/'+id)
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
            this.loadOrder();
            this.loadVendor();
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
button.page-link {
	display: inline-block;
}
button.page-link {
    font-size: 20px;
    color: #29b3ed;
    font-weight: 500;
}
.offset{
  width: 500px !important;
  margin: 20px auto;  
}

</style>